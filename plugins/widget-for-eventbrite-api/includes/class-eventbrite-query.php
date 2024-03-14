<?php

namespace WidgetForEventbriteAPI\Includes;

use  WP_Query ;
class Eventbrite_Query extends WP_Query
{
    /**
     * Results from the API call. Includes up to 50 objects plus pagination info.
     *
     * @var object
     */
    public  $api_results ;
    /**
     * Constructor.
     *
     * Sets up the Eventbrite query.
     *
     * @access public
     *
     * @param string $query URL query string.
     */
    public function __construct( $query = '' )
    {
        // Process any query args from the URL.
        $query = $this->process_query_args( $query );
        // Assign hooks.
        remove_filter( 'the_content', 'wpautop' );
        // Put our query in motion.
        $this->query( $query );
    }
    
    public function get_merge_event_tags( $event_ids, $token )
    {
        $results = Eventbrite_Manager::$instance->get_destination_events__premium_only( $event_ids, $token );
        if ( !is_wp_error( $results ) ) {
            // match ID in $this->api_results->events to ID in $results->events
            foreach ( $results->events as $destination_event ) {
                foreach ( $this->api_results->events as $key => $event ) {
                    if ( $event->ID == $destination_event->id ) {
                        $this->api_results->events[$key]->tags = $destination_event->tags;
                    }
                }
            }
        }
    }
    
    /**
     * Retrieve the posts based on query variables.
     *
     * @access public
     *
     * @return array List of posts.
     */
    public function get_posts()
    {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        // Set up query variables.
        $this->parse_query();
        // Restore `paged` if changed to `page` (in the case of index pagination).
        
        if ( !empty($this->query_vars['page']) ) {
            $this->query_vars['paged'] = $this->query_vars['page'];
            unset( $this->query_vars['page'] );
        }
        
        // remove paged if a search query
        // Set any required parameters for the API request based on the query vars.
        $params = $this->set_api_params();
        for ( $i = 1 ;  $i <= 5 ;  $i++ ) {
            $this->api_results = Eventbrite_Manager::$instance->get_organizations_events( $params );
            if ( !is_wp_error( $this->api_results ) ) {
                break;
            }
            if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && is_wp_error( $this->api_results ) ) {
                error_log( '[' . date( "F j, Y, g:i a e O" ) . '] ' . print_r( $this->api_results->errors, true ) . ' attempt: ' . $i );
            }
        }
        if ( is_wp_error( $this->api_results ) ) {
            return array();
        }
        // Do any post-API query processing.
        $this->post_api_filters();
        // Set properties based on the results.
        $this->set_properties();
        // Return what we have for posts.
        // examine if we have any paid events
        // @TODO paid events only processing
        /*
        foreach ( $this->api_results->events as $event) {
        	if ( false == $event->is_free ) {
        		$version_history = get_option( 'widget-for-eventbrite-api-version' );
        		if ( $version_history ) {
        			$version_history['paid'] = true;
        			update_option( 'widget-for-eventbrite-api-version', $version_history );
        			break;
        		}
        	}
        }
        */
        return $this->posts;
    }
    
    /**
     * Handle any query args that come from the requested URL.
     *
     * @access protected
     *
     * @param mixed $query Query string.
     *
     * @return array Query arguments
     */
    protected function process_query_args( $query )
    {
        // Handle requests for paged events.
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        return $query;
    }
    
    /**
     * Determine parameters for an API call.
     *
     * @access protected
     *
     * @return array API call parameters
     */
    protected function set_api_params()
    {
        $params = array();
        // Add 'page' parameter if we need events above the first 50.
        // Adjust status for private events if necessary.
        if ( !empty($this->query_vars['status']) ) {
            $params['status'] = $this->query_vars['status'];
        }
        if ( !empty($this->query_vars['start_date.range_start']) ) {
            $params['start_date.range_start'] = $this->query_vars['start_date.range_start'];
        }
        if ( !empty($this->query_vars['start_date.range_end']) ) {
            $params['start_date.range_end'] = $this->query_vars['start_date.range_end'];
        }
        if ( isset( $this->query_vars['token'] ) && !empty($this->query_vars['token']) ) {
            $params['token'] = $this->query_vars['token'];
        }
        if ( isset( $this->query_vars['organization_id'] ) && !empty($this->query_vars['organization_id']) ) {
            $params['organization_id'] = $this->query_vars['organization_id'];
        }
        return $params;
    }
    
    /**
     * Process any remaining internal query parameters. These are parameters that are specific to Eventbrite_Query, not the API calls.
     *
     * @access protected
     */
    protected function post_api_filters()
    {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        $this->api_results->events = apply_filters( 'wfea_api_results', $this->api_results->events, $this->query_vars );
        $this->query_vars['display_private'] = false;
        $this->api_results->events = array_filter( $this->api_results->events, array( $this, 'filter_by_display_private' ) );
        // Do nothing if API results were empty, false, or an error.
        if ( empty($this->api_results) || is_wp_error( $this->api_results ) ) {
            return false;
        }
        // Filter out specified IDs: 'post__not_in'
        if ( isset( $this->query_vars['post__not_in'] ) && is_array( $this->query_vars['post__not_in'] ) ) {
            $this->api_results->events = array_filter( $this->api_results->events, array( $this, 'filter_by_post_not_in' ) );
        }
        //   use original logo url
        if ( isset( $this->query_vars['thumb_original'] ) && true === $this->query_vars['thumb_original'] ) {
            foreach ( $this->api_results->events as $key => $event ) {
                if ( isset( $event->logo->original->url ) && !empty(isset( $event->logo->original->url )) ) {
                    $this->api_results->events[$key]->logo_url = $event->logo->original->url;
                }
            }
        }
        
        if ( isset( $this->query_vars['ID'] ) ) {
            // find a specific ID
            $ids = explode( ',', $this->query_vars['ID'] );
            $this->api_results->events = array_filter( $this->api_results->events, function ( $e ) use( $ids ) {
                return in_array( $e->ID, $ids );
            } );
        }
        
        // filter out live events that have actually past their end time
        if ( isset( $this->query_vars['status'] ) && 'live' == $this->query_vars['status'] ) {
            $this->api_results->events = array_filter( $this->api_results->events, function ( $e ) {
                $epoch_end = strtotime( $e->end->utc );
                $result = $epoch_end > time();
                return $result;
            } );
        }
        // now sort  as last step
        $this->sort_results();
        // Limit the number of results: 'limit' - must be last after sort
        if ( isset( $this->query_vars['limit'] ) && is_integer( $this->query_vars['limit'] ) ) {
            $this->api_results->events = array_slice( $this->api_results->events, 0, absint( $this->query_vars['limit'] ) );
        }
    }
    
    private function sort_results()
    {
        
        if ( !isset( $this->query_vars['order_by_attrs'] ) ) {
            
            if ( !isset( $this->query_vars['order_by'] ) ) {
                $order_func = 'start_asc';
            } else {
                $order_func = $this->query_vars['order_by'];
            }
            
            usort( $this->api_results->events, array( $this, $order_func ) );
        }
        
        //
        global  $wfea_fs ;
    }
    
    /**
     * Set properties based on the fully processed results.
     *
     * @access protected
     */
    protected function set_properties()
    {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        
        if ( empty($this->api_results->events) ) {
            $this->posts = array();
        } else {
            // Set found_posts based on all posts returned after Eventbrite_Query filtering.
            $this->found_posts = count( $this->api_results->events );
            // Return all posts if `nopaging` is true.
            
            if ( isset( $this->query_vars['nopaging'] ) && true === $this->query_vars['nopaging'] ) {
                $this->posts = array_slice( $this->api_results->events, 0, $this->found_posts );
                $posts_per_page = count( $this->posts );
            } else {
            }
            
            // Turn the posts into Eventbrite_Event objects.
            $this->posts = array_map( array( $this, 'create_eventbrite_event' ), $this->posts );
            // Determine the maximum number of pages.
            $this->post_count = count( $this->posts );
            $this->max_num_pages = ceil( $this->found_posts / $posts_per_page );
            // Set the first post.
            $this->post = reset( $this->posts );
        }
        
        // Adjust some WP_Query parsing.
        
        if ( !empty($this->query_vars['p']) ) {
            $this->is_single = true;
        } else {
            $this->is_category = true;
            $this->is_archive = true;
            $this->is_page = false;
        }
        
        $this->is_home = false;
    }
    
    /**
     * Turn a given event into a proper Eventbrite_Event object.
     *
     * @access protected
     *
     * @param null|object $event An event object from the API results.
     *
     * @return object Eventbrite_Event object.
     */
    protected function create_eventbrite_event( $event = null )
    {
        // Bail if nothing is passed in.
        if ( empty($event) ) {
            return null;
        }
        
        if ( is_a( $event, 'Eventbrite_Event' ) ) {
            // We already have an Eventbrite_Event object. Nothing to do here.
            $_event = $event;
        } elseif ( is_object( $event ) ) {
            // Looks like we have an object already, so make it an Eventbrite_Event object.
            $_event = new Eventbrite_Event( $event );
        } else {
            return null;
            // used to be ID
        }
        
        // That was a bust. We've got nothing.
        if ( !$_event ) {
            return null;
        }
        // Return our Eventbrite_Event object.
        return $_event;
    }
    
    /**
     * Determine by ID if an event is to be filtered out.
     *
     * @access protected
     *
     * @param object $event A single event from the API call results.
     *
     * @return bool True with no ID match, false if the ID is in the array of events to be removed.
     */
    protected function filter_by_post_not_in( $event )
    {
        // Allow events not found in the array.
        return !in_array( $event->ID, $this->query_vars['post__not_in'] );
    }
    
    /**
     * Determine if an event is private.
     *
     * @access protected
     *
     * @param object $event A single event from the API call results.
     *
     * @return bool True if properties match, false otherwise.
     */
    protected function filter_by_display_private( $event )
    {
        return ( isset( $event->public ) ? $event->public != $this->query_vars['display_private'] || $this->query_vars['display_private'] : false );
    }
    
    private function start_asc( $a, $b )
    {
        return strcmp( $a->post_date_gmt, $b->post_date_gmt );
    }
    
    private function start_desc( $a, $b )
    {
        return strcmp( $b->post_date_gmt, $a->post_date_gmt );
    }
    
    private function created_asc( $a, $b )
    {
        return strcmp( $a->created, $b->created );
    }
    
    private function created_desc( $a, $b )
    {
        return strcmp( $b->created, $a->created );
    }
    
    private function published_asc( $a, $b )
    {
        return strcmp( $a->eb_published, $b->eb_published );
    }
    
    private function published_desc( $a, $b )
    {
        return strcmp( $b->eb_published, $a->eb_published );
    }
    
    private function get_property( $class, $property_path )
    {
        $property_path = explode( '.', $property_path );
        $property = $class;
        foreach ( $property_path as $path ) {
            if ( !isset( $property->{$path} ) ) {
                return false;
            }
            $property = $property->{$path};
        }
        return $property;
    }

}