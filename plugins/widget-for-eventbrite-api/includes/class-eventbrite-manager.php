<?php

/** @noinspection ALL */
namespace WidgetForEventbriteAPI\Includes;

use  WP_Error ;
class Eventbrite_Manager
{
    const  API_BASE = 'https://www.eventbriteapi.com/v3/' ;
    /**
     * Class instance used by themes and plugins.
     *
     * @var object
     */
    public static  $instance ;
    protected  $token = false ;
    /**
     * The class constructor.
     *
     * @access public
     */
    public function __construct()
    {
        // Assign our instance.
        self::$instance = $this;
    }
    
    /**
     * Flush all transients.
     *
     */
    public function flush_transients( $service, $request = null )
    {
        // Bail if it wasn't an Eventbrite connection that got deleted.
        if ( 'eventbrite' != $service ) {
            return;
        }
        // Get the list of registered transients.
        $transients = $this->get_wfea_transients();
        // Bail if we have no transients.
        if ( !$transients ) {
            return;
        }
        // Loop through all registered transients, deleting each one.
        foreach ( $transients as $transient ) {
            delete_transient( $transient );
            delete_transient( $transient . ' _bak' );
        }
    }
    
    function get_wfea_transients()
    {
        // need to cache this as it's a db call but persispent cache is not available yet so using transient
        $transients = get_transient( '_x_wfea_transients' );
        
        if ( false === $transients ) {
            global  $wpdb ;
            $prefix = $wpdb->prefix;
            $transients = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", '_transient_wfea%' ) );
            // Remove the "_transient_" part from the names
            $transients = str_replace( '_transient_', '', $transients );
            set_transient( '_x_wfea_transients', $transients, 120 );
        }
        
        global  $wpdb ;
        $prefix = $wpdb->prefix;
        $transients = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", '_transient_wfea%' ) );
        // Remove the "_transient_" part from the names
        $transients = str_replace( '_transient_', '', $transients );
        return $transients;
    }
    
    public function get_single_event( $params )
    {
        $event = $this->request(
            'events',
            $params,
            false,
            false
        );
        if ( is_wp_error( $event ) ) {
            return $event;
        }
        $events = array();
        
        if ( !empty($event) ) {
            $events[0] = $event;
            $events = array_map( array( $this, 'map_event_keys' ), $events );
        }
        
        $api_results = new \stdClass();
        $api_results->events = $events;
        return $api_results;
    }
    
    /**
     * Get user-owned private and public events.
     *
     * @access public
     *
     * @param array $params Parameters to be passed during the API call.
     * @param bool $force Force a fresh API call, ignoring any existing transient.
     *
     * @return object Eventbrite_Manager
     */
    public function get_organizations_events( $params = array(), $force = false )
    {
        
        if ( isset( $params['organization_id'] ) ) {
            $organizations = (object) array(
                'organizations' => array( (object) array(
                'id' => $params['organization_id'],
            ) ),
            );
            unset( $params['organization_id'] );
        } else {
            $organizations = $this->request(
                'organizations',
                $params,
                false,
                $force
            );
            if ( is_wp_error( $organizations ) ) {
                return $organizations;
            }
        }
        
        $merged_results = (object) array(
            'events' => array(),
        );
        if ( !empty($organizations) && property_exists( $organizations, 'organizations' ) ) {
            foreach ( $organizations->organizations as $organization ) {
                $org_id = $organization->id;
                // Get the raw results.
                $results = $this->request(
                    'user_owned_events',
                    $params,
                    $org_id,
                    $force
                );
                if ( is_wp_error( $results ) ) {
                    return $results;
                }
                // If we have events, map them to the format expected by Eventbrite_Event
                
                if ( !empty($results) && property_exists( $results, 'events' ) ) {
                    if ( !empty($results->events) ) {
                        $results->events = array_map( array( $this, 'map_event_keys' ), $results->events );
                    }
                    $merged_results->events = array_merge( $merged_results->events, $results->events );
                }
            
            }
        }
        return $merged_results;
    }
    
    /**
     * Increase the timeout for Eventbrite API calls from the default 5 seconds to 30.
     *
     * @access public
     */
    public function increase_timeout()
    {
        return 30;
    }
    
    /**
     * Make a call to the Eventbrite v3 REST API, or return an existing transient.
     *
     * @access public
     *
     * @param string $endpoint Valid Eventbrite v3 API endpoint.
     * @param array $params Parameters passed to the API during a call.
     * @param int|string|bool $id A specific event ID used for calls to the event_details endpoint.
     * @param bool $force Force a fresh API call, ignoring any existing transient.
     *
     * @return object Request results
     *
     *
     * async all API calls using action scheduler
     *
     * Check if we have it cached
     * If not check if we have an action scheduled
     * If not schedule an action to get the cache
     *
     */
    public function request(
        $endpoint,
        $params = array(),
        $id = false,
        $force = false
    )
    {
        if ( isset( $params['page'] ) ) {
            unset( $params['page'] );
        }
        // Return a cached result if we have one.
        // $force = true;
        $repeat = (int) apply_filters( 'wfea_eventbrite_cache_expiry', DAY_IN_SECONDS );
        if ( $repeat <= 60 || $force ) {
            return $this->process_request( $endpoint, $params, $id );
        }
        
        if ( !$force ) {
            $cached = $this->get_cache( $endpoint, $params, $id );
            
            if ( !empty($cached) ) {
                if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
                    error_log( print_r( array(
                        'msg'  => 'Widget for Eventbite: Debug:  Cache hit',
                        'call' => array(
                        'endpoint' => $endpoint,
                        'params'   => $params,
                        'id'       => $id,
                    ),
                    ), true ) );
                }
                if ( in_array( $endpoint, array( 'organizations', 'user_owned_events' ) ) ) {
                    $cached->cached = true;
                }
                return $cached;
            } else {
                if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
                    error_log( print_r( array(
                        'msg'  => 'Widget for Eventbite: Debug:  No Cache so will try refresh',
                        'call' => array(
                        'endpoint' => $endpoint,
                        'params'   => $params,
                        'id'       => $id,
                    ),
                    ), true ) );
                }
                /**
                 * @var \Freemius $wfea_fs Object for freemius.
                 */
                global  $wfea_fs ;
            }
            
            return $this->process_request( $endpoint, $params, $id );
        }
    
    }
    
    private function process_request( $endpoint, $params, $id )
    {
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        if ( false === $this->token ) {
            if ( isset( $params['token'] ) && !empty($params['token']) || isset( $options['key'] ) && !empty($options['key']) ) {
                
                if ( isset( $params['token'] ) && !empty($params['token']) ) {
                    $this->token = $params['token'];
                } else {
                    $this->token = $options['key'];
                }
            
            }
        }
        // Extend the HTTP timeout to account for Eventbrite API calls taking longer than ~5 seconds.
        add_filter( 'http_request_timeout', array( $this, 'increase_timeout' ) );
        // Make a fresh request.
        
        if ( false !== $this->token ) {
            $request = $this->call( $endpoint, $params, $id );
        } else {
            $request = new WP_error( 'wfea-no-api-key-set', esc_html__( 'No Eventbrite API key set, please set in settings', 'widget-for-eventbrite-api' ) );
        }
        
        // Remove the timeout extension for any non-Eventbrite calls.
        // Remove the timeout extension for any non-Eventbrite calls.
        remove_filter( 'http_request_timeout', array( $this, 'increase_timeout' ) );
        // If we get back a proper response, cache it.
        
        if ( !is_wp_error( $request ) ) {
            $transient_name = $this->get_transient_name( $endpoint, $params, $id );
            // if there are backup transient and is is different to the request if so we need to flush cache
            $transient_name_bak = $transient_name . '_bak';
            // need a copy as we will remove pagination
            $current_request = $request;
            $transient_bak = get_transient( $transient_name_bak );
            // check if the request is the same as the backup transient if not then we will clear cache
            
            if ( false !== $transient_bak ) {
                // pagination is not part of the request so remove it
                if ( property_exists( $current_request, 'pagination' ) ) {
                    unset( $current_request->pagination );
                }
                if ( property_exists( $transient_bak, 'pagination' ) ) {
                    unset( $transient_bak->pagination );
                }
                $serialized_request = serialize( $current_request );
                $serialize_transient_bak = serialize( $transient_bak );
                if ( $serialized_request == $serialize_transient_bak ) {
                    Utilities::get_instance()->clear_all_caches_for_posts_with_shortcode();
                }
            } else {
                // if no backup then clear cache
                Utilities::get_instance()->clear_all_caches_for_posts_with_shortcode();
            }
            
            set_transient( $transient_name, $request, apply_filters( 'wfea_eventbrite_cache_expiry', DAY_IN_SECONDS ) );
            // save a copy for a month incase EB is unavailable and useful to tell if data changes for cache clearing
            set_transient( $transient_name . '_bak', $request, MONTH_IN_SECONDS );
        } else {
            if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
                error_log( print_r( array(
                    'msg'   => 'Widget for Eventbite: Debug: Call issue',
                    'call'  => array(
                    'endpoint' => $endpoint,
                    'params'   => $params,
                    'id'       => $id,
                ),
                    'error' => Utilities::get_instance()->get_api_error_string( $request ),
                ), true ) );
            }
            //  extend the transient on failure of API call
            $transient_name = $this->get_transient_name( $endpoint, $params, $id );
            $transient_value = get_transient( $transient_name );
            
            if ( $transient_value !== false ) {
                set_transient( $transient_name, $transient_value, 2 * MINUTE_IN_SECONDS );
                // we have a good cache even though API call failed so lets use that and extend the bakup
                set_transient( $transient_name . '_bak', $transient_value, MONTH_IN_SECONDS );
                return $transient_value;
            } else {
                $transient_value = get_transient( $transient_name . '_bak' );
                
                if ( $transient_value !== false ) {
                    // we have a good backup cache even though API call failed so lets use that and extend the bakup
                    set_transient( $transient_name, $transient_value, 2 * MINUTE_IN_SECONDS );
                    // resave backup copy to extend the month incase EB continues to be unavailable
                    set_transient( $transient_name . '_bak', $transient_value, MONTH_IN_SECONDS );
                    $error_string = Utilities::get_instance()->get_api_error_string( $request );
                    
                    if ( preg_match( '/Operation timed out after/i', $error_string ) ) {
                        error_log( '[' . date( "F j, Y, g:i a e O" ) . '] ' . print_r( $error_string, true ) . ' attempt: using back up ' );
                        // we only return  the backup IF  Eventbrite is down otherwise fall through to error of we will never find Token errors
                        return $transient_value;
                    }
                
                }
            
            }
        
        }
        
        return $request;
    }
    
    /**
     * build up the call to make to EB and handle EB pagination merge into results array
     *
     * @param $endpoint
     * @param $query_params
     * @param $object_id
     *
     * @return mixed|null
     */
    private function call( $endpoint, $query_params = array(), $object_id = null )
    {
        global  $wfea_fs ;
        $endpoint_map = array(
            'user_owned_events' => 'organizations/' . $object_id . '/events',
            'organizations'     => 'users/me/organizations',
            'description'       => 'events/' . $object_id . '/description',
            'organizers'        => 'organizers/' . $object_id,
            'events'            => 'events/',
        );
        $endpoint_base = trailingslashit( self::API_BASE . $endpoint_map[$endpoint] );
        $endpoint_url = $endpoint_base;
        if ( !isset( $query_params['token'] ) && false !== $this->token ) {
            $query_params['token'] = $this->token;
        }
        
        if ( 'user_owned_events' == $endpoint ) {
            // Query for 'live' events by default (rather than 'all', which includes events in the past).
            if ( !isset( $query_params['status'] ) ) {
                $query_params['status'] = 'live';
            }
            $query_params['expand'] = apply_filters(
                'eventbrite_api_expansions',
                'event_sales_status,ticket_availability,external_ticketing,music_properties,logo,organizer,venue,ticket_classes,format,category,subcategory',
                $endpoint,
                $query_params,
                $object_id
            );
            $endpoint_url = add_query_arg( $query_params, $endpoint_url );
        } elseif ( 'organizations' == $endpoint ) {
            $url = explode( '?', esc_url_raw( $endpoint_base ) );
            $endpoint_url = $url[0];
        }
        
        $response = $this->request_api( $endpoint_url, $query_params );
        if ( !is_wp_error( $response ) ) {
            if ( isset( $response->pagination->has_more_items ) ) {
                while ( $response->pagination->has_more_items ) {
                    $next_response = $this->request_api( $endpoint_url . '&continuation=' . $response->pagination->continuation, array() );
                    
                    if ( !is_wp_error( $next_response ) ) {
                        $response->events = array_merge( $response->events, $next_response->events );
                        $response->pagination = $next_response->pagination;
                    } else {
                        break;
                    }
                
                }
            }
        }
        return apply_filters(
            'eventbrite_api_call_response',
            $response,
            $endpoint,
            $query_params,
            $object_id
        );
    }
    
    /**
     * Call to Eventbrite API
     *
     * @param $url
     * @param array $query_params
     *
     * @return mixed|WP_Error
     */
    private function request_api( $url, array $query_params = array() )
    {
        $params = array(
            'method' => 'GET',
        );
        
        if ( !isset( $query_params['token'] ) || empty($query_params['token']) ) {
            $params['headers']['Authorization'] = 'Bearer' . ' ' . (string) $this->token;
        } else {
            $params['headers']['Authorization'] = 'Bearer' . ' ' . (string) $query_params['token'];
        }
        
        $res = wp_remote_get( $url, $params );
        if ( in_array( wp_remote_retrieve_response_code( $res ), array( 200, 201, 202 ) ) ) {
            return json_decode( wp_remote_retrieve_body( $res ) );
        }
        return new WP_Error( 'eventbrite-api-request-error', $res );
    }
    
    /**
     * Determine a transient's name based on endpoint and parameters.
     *
     * @access protected
     *
     * @param string $endpoint Endpoint being called.
     * @param array $params Parameters to be passed during the API call.
     * @param integer $id specifuc id of organisation or event
     *
     * @return string
     */
    protected function get_transient_name( $endpoint, $params, $id )
    {
        // Results in 62 characters for the timeout option name (maximum is 64).
        $transient_name = 'wfea_' . md5( $endpoint . implode( $params ) . $id );
        return $transient_name;
    }
    
    /**
     * Get the transient for a certain endpoint and combination of parameters and id.
     * get_transient() returns false if not found.
     *
     * @access protected
     *
     * @param string $endpoint Endpoint being called.
     * @param array $params Parameters to be passed during the API call.
     * @param integer $id specifuc id of organisation or event
     *
     * @return mixed Transient if found, false if not.
     */
    protected function get_cache( $endpoint, $params, $id )
    {
        $transient_name = $this->get_transient_name( $endpoint, $params, $id );
        $transient_value = $this->get_cache_transient( $endpoint, $params, $id );
        return $transient_value;
    }
    
    protected function get_cache_transient( $endpoint, $params, $id )
    {
        return get_transient( $this->get_transient_name( $endpoint, $params, $id ) );
    }
    
    /**
     * Return an array of valid request parameters by endpoint.
     *
     * @access protected
     *
     * @return array All valid request parameters for supported endpoints.
     */
    protected function get_endpoint_params()
    {
        $params = array(
            'description'       => array(
            'id' => array(),
        ),
            'user_owned_events' => array(
            'status'   => array(
            'all',
            'cancelled',
            'draft',
            'ended',
            'live',
            'started'
        ),
            'order_by' => array(
            'start_asc',
            'start_desc',
            'created_asc',
            'created_desc',
            'published_asc',
            'published_desc'
        ),
        ),
            'organizations'     => array(
            'token'    => array(),
            'status'   => array(
            'all',
            'cancelled',
            'draft',
            'ended',
            'live',
            'started'
        ),
            'order_by' => array(
            'start_asc',
            'start_desc',
            'created_asc',
            'created_desc',
            'published_asc',
            'published_desc'
        ),
        ),
        );
        return $params;
    }
    
    /**
     * Convert the Eventbrite API properties into properties used by Eventbrite_Event.
     *
     * @access protected
     *
     * @param object $api_event A single event from the API results.
     *
     * @return object Event with Eventbrite_Event keys.
     */
    protected function map_event_keys( $api_event )
    {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        $event = array();
        $event['ID'] = ( isset( $api_event->id ) ? $api_event->id : '' );
        $event['post_title'] = ( isset( $api_event->name->text ) ? $api_event->name->text : '' );
        $event['post_content'] = ( isset( $api_event->summary ) ? $api_event->summary : '' );
        $event['summary'] = ( isset( $api_event->summary ) ? $api_event->summary : '' );
        $event['post_excerpt'] = ( isset( $api_event->summary ) ? $api_event->summary : '' );
        $event['post_date'] = ( isset( $api_event->start->local ) ? str_replace( 'T', ' ', $api_event->start->local ) : '' );
        $event['created'] = ( isset( $api_event->created ) ? $api_event->created : '' );
        $event['post_date_gmt'] = ( isset( $api_event->start->utc ) ? str_replace( 'T', ' ', $api_event->start->utc ) : '' );
        $event['logo_url'] = ( isset( $api_event->logo->url ) ? $api_event->logo->url : '' );
        $event['logo'] = ( isset( $api_event->logo ) ? $api_event->logo : '' );
        $event['start'] = ( isset( $api_event->start ) ? $api_event->start : '' );
        $event['end'] = ( isset( $api_event->end ) ? $api_event->end : '' );
        $event['eb_url'] = ( isset( $api_event->url ) ? $api_event->url : '' );
        $event['url'] = $event['eb_url'];
        $event['status'] = ( isset( $api_event->status ) ? $api_event->status : '' );
        $event['public'] = ( isset( $api_event->listed ) ? $api_event->listed : '' );
        return (object) $event;
    }

}
new Eventbrite_Manager();