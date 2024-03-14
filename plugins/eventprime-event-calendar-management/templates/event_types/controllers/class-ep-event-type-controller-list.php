<?php
/**
 * Class for return event type data
 */

defined( 'ABSPATH' ) || exit;

class EventM_Event_Type_Controller_List {
    /**
     * Term Type.
     * 
     * @var string
     */
    private $term_type = EM_EVENT_TYPE_TAX;

    /**
     * Get event_types data
     */

    public function get_event_types_data( $args = array() ) {
        $defaults = array( 
            'hide_empty' => false ,
        );
        $args        = wp_parse_args( $args, $defaults );
        $terms       = get_terms( $this->term_type, $args );
        $event_types = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $event_types;
        }
        foreach( $terms as $term ){
            $event_type = $this->get_single_event_type( $term->term_id, $term );
            if( ! empty( $event_type ) ) {
                $event_types[] = $event_type;
            }
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $event_types;
        return $wp_query;
    }

    /**
     * Count the total event_types
     */
    public function get_event_types_count( $args = array(), $ep_search = '', $featured = 0 , $popular = 0 ) {
        $defaults = array( 
            'hide_empty' => false ,
        );

        if( $featured == 1 ){
            $args['post_status'] = 'publish';
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                   'key'       => 'em_is_featured',
                   'value'     => 1,
                   'compare'   => '='
                ),
                array(
                   'key'       => 'em_is_featured',
                   'value'     => true,
                   'compare'   => '='
                )
            );           
        }  

        if( $popular == 1 )
        return 1;
        
        $ep_search = ( $ep_search != 'false' ) ? $ep_search : '';
        $args['name__like'] = $ep_search;

        $args  = wp_parse_args( $args, $defaults );
        $terms = get_terms( $this->term_type, $args );
        if( ! empty( $terms ) && is_array( $terms )){
            return count($terms);
        }else{
            return 1;
        }
    }
    
    
    /*
     * Get Popular Event Types
     */
    public function get_popular_event_types( $count = 5, $featured = 0 ){
        $args = array( 
            'hide_empty' => false ,
            'number'=> $count,
            'orderby' => 'count', 
            'order' => 'DESC'
        );
        if( $featured == 1 ){
            $args['post_status'] = 'publish';
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                   'key'       => 'em_is_featured',
                   'value'     => 1,
                   'compare'   => '='
                ),
                array(
                   'key'       => 'em_is_featured',
                   'value'     => true,
                   'compare'   => '='
                )
            );           
        }  

        $terms = get_terms( $this->term_type, $args );
        // check no of events in event types
        $events_controller = new EventM_Event_Controller_List();
        $events = $events_controller->get_events_post_data();
        $event_count= array();
        if( isset( $events->posts ) && ! empty( $events->posts ) ){
            foreach( $events->posts as $event ){
                if( isset( $event->em_event_type ) && ! empty( $event->em_event_type ) ){
                    if( isset( $event_count[$event->em_event_type] ) ){
                        $event_count[$event->em_event_type] += 1;
                    }else{
                        $event_count[$event->em_event_type] = 1;
                    }
                }
            }
        }
       
        $event_types = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $event_types;
        }
        foreach( $terms as $term ){
            if( isset( $event_count[$term->term_id] ) ){
                $event_type = $this->get_single_event_type( $term->term_id, $term );
                $event_type->events = $event_count[$term->term_id];
                if( isset( $event_type ) && ! empty( $event_type ) ) {
                    $event_types[] = $event_type;
                }
            }   
        }

        $wp_query = new WP_Term_Query( $args );
        $wp_query->terms = $event_types;
        return $wp_query;
    }
    
    /*
     * Get Featured Event Types
     */
    public function get_featured_event_types( $count = 5 ){
        $args = array( 
            'hide_empty' => false ,
            'number'=>$count,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                   'key'       => 'em_is_featured',
                   'value'     => 1,
                   'compare'   => '='
                ),
                array(
                   'key'       => 'em_is_featured',
                   'value'     => true,
                   'compare'   => '='
                )
            )
        );
        $terms       = get_terms( $this->term_type, $args );
        $event_types = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $event_types;
        }
        foreach( $terms as $term ){
            $event_type = $this->get_single_event_type( $term->term_id, $term );
            if( ! empty( $event_type ) ) {
                $event_types[] = $event_type;
            }
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $event_types;
        return $wp_query;
    }

    /**
     * Get single event_type data.
     * 
     * @param int $post_id Post ID.
     * 
     * @return objact $event_type Event Type Data.
     */
    public function get_single_event_type( $term_id, $term = null ) {
        if( empty( $term_id ) ) return;

        $event_type                 = new stdClass();
        $meta                       = get_term_meta( $term_id );
        foreach ($meta as $key => $val) {
            $event_type->{$key} = maybe_unserialize( $val[0] );
        }
        if( empty( $term ) ) {
            $term = get_term( $term_id );
        }
        if( empty( $term ) ) return;

        $event_type->id             = $term->term_id;
        $event_type->name           = htmlspecialchars_decode( $term->name );
        $event_type->slug           = $term->slug;
        $event_type->description    = $term->description;
        $event_type->count          = $term->count;
        $event_type->event_type_url = ep_get_custom_page_url( 'event_types', $term->term_id, 'event_type', 'term' );
        $event_type->image_url      = $this->get_event_type_image_url( $term->term_id );
        return $event_type;
    }

    /**
     * Get upcoming events for single event_type
     * 
     * @param int $event_type_id event_type Id.
     * 
     * @param array $args Post Arguments.
     * 
     * @return object Post.
     */
    public function get_upcoming_events_for_event_type( $event_type_id, $args = array() ) {
        $hide_past_events = ep_get_global_settings( 'single_type_hide_past_events' );
        $past_events_meta_qry = '';
        if( ! empty( $hide_past_events ) ) {
            $past_events_meta_qry = array(
                'relation' => 'OR',
                array(
                    'key'     => 'em_start_date_time',
                    'value'   => current_time( 'timestamp' ),
                    'compare' => '>=',
                ),
            );
        }
        $filter = array(
            'meta_key'    => 'em_start_date_time',
            'orderby'     => 'meta_value',
            'numberposts' => -1,
            'order'       => 'ASC',
            'meta_query'  => array( 'relation' => 'AND',
                array(
                    array(
                        'key'     => 'em_event_type',
                        'value'   =>  $event_type_id,
                        'compare' => '='
                    ),
                    $past_events_meta_qry,
                )
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );

        $args = wp_parse_args( $args, $filter );
        $wp_query = new WP_Query( $args );
        $wp_query->event_type_id = $event_type_id;
        return $wp_query;
    }

    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        wp_enqueue_script(
            'ep-type-views-js',
            EP_BASE_URL . '/includes/event_types/assets/js/em-type-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-type-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        
        $event_types_data = array();
        $settings                            = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $event_types_settings                = $settings->ep_get_settings( 'event_types' );

        $event_types_data['display_style']   = isset( $atts['display_style'] ) ? $atts["display_style"] : $event_types_settings->type_display_view;
        $event_types_data['limit']           = isset( $atts['limit'] ) ? ( empty($atts["limit"] ) ? EP_PAGINATION_LIMIT : $atts["limit"]) : ( empty( $event_types_settings->type_limit ) ? EP_PAGINATION_LIMIT : $event_types_settings->type_limit );
        $event_types_data['column']            = isset( $atts['cols'] ) ? $atts['cols'] : $event_types_settings->type_no_of_columns;
        $event_types_data['cols']            = isset( $atts['cols'] ) ? ep_check_column_size( $atts['cols'] ) : ep_check_column_size( $event_types_settings->type_no_of_columns );
        $event_types_data['load_more']       = isset( $atts['load_more'] ) ? $atts['load_more'] : $event_types_settings->type_load_more;
        $event_types_data['enable_search']   = isset( $atts['search'] ) ? $atts['search'] : $event_types_settings->type_search;
        $event_types_data['featured']        = isset( $atts["featured"] ) ? $atts["featured"] : 0;
        $event_types_data['popular']         = isset( $atts["popular"] ) ? $atts["popular"] : 0;
        $event_types_data['box_color'] = '';
        if( $event_types_data['display_style'] == 'box' || $event_types_data['display_style'] == 'colored_grid' ) {
            $event_types_data['box_color'] = ( isset( $atts["type_box_color"] ) && ! empty( $atts["type_box_color"] ) ) ? $atts["type_box_color"] : $event_types_settings->type_box_color;
        }

        // Set query arguments
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $event_types_data['paged'] = $paged;
        $ep_search = isset( $_GET['ep_search'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
        $pargs = array(
            'orderby'    => 'date',
            'name__like' => $ep_search,
        );
        // $event_types_data['event_types_count'] = $this->get_event_types_count( $pargs );

        $limit_args = array(
            'number'     => $event_types_data['limit'],
            'offset'     => (int)($paged-1) * (int)$event_types_data['limit'],
            'paged'      => $paged,
        );
        
        if( $event_types_data['featured'] == 1 && ( $event_types_data['popular'] == 0 || $event_types_data['popular'] == '' ) ){ 
            $pargs['meta_query'] = array(
                'relation' => 'OR',
                array(
                   'key'       => 'em_is_featured',
                   'value'     => 1,
                   'compare'   => '='
                ),
                array(
                   'key'       => 'em_is_featured',
                   'value'     => true,
                   'compare'   => '='
                )
            );
        }
        // Get popular event types
        if( $event_types_data['popular'] == 1 && ( $event_types_data['featured'] == 0 || $event_types_data['featured'] == '' ) ){
            $event_types = $this->get_popular_event_types( $event_types_data['limit'] );
            if( ! empty( $event_types ) && ! empty( $event_types->terms ) ) {
                $type_ids = array_column( wp_list_sort( $event_types->terms , $orderby = 'events', $order = 'DESC', $event_types_data['limit'] = 5 ) , 'id' );
                $type_ids = array_slice( (array)$type_ids, 0, $event_types_data['limit'], true );
                if( ! empty( $type_ids ) ){
                    $pargs['orderby'] = 'include';
                    $pargs['include'] = $type_ids;
                }
            }
        }
        // Get featured and popular event types
        if( $event_types_data['popular'] == 1 && $event_types_data['featured'] == 1 ){
            $event_types = $this->get_popular_event_types( $event_types_data['limit'], $event_types_data['featured'] );
            if( ! empty( $event_types ) && ! empty( $event_types->terms ) ) {
                $type_ids = array_column( wp_list_sort( $event_types->terms , $orderby = 'events', $order = 'DESC', $event_types_data['limit'] ) , 'id' );
                $type_ids = array_slice( (array)$type_ids, 0, $event_types_data['limit'], true );
                if( ! empty( $type_ids ) ){
                    $pargs['orderby'] = 'include';
                    $pargs['include'] = $type_ids;
                }
            }
        }
        $event_types_data['event_types_count'] = $this->get_event_types_count( $pargs , $ep_search, $event_types_data['featured'], $event_types_data['popular'] );
        $pargs = wp_parse_args( $pargs, $limit_args );
        $event_types_data['event_types'] = $this->get_event_types_data( $pargs );

        ob_start();
        wp_enqueue_style(
            'ep-event-type-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
		ep_get_template_part( 'event_types/list', null, (object)$event_types_data );
		return ob_get_clean();
    }

    /**
     * Render detail page
     */
    public function render_detail_template( $atts = array() ) {
        $atts                     = array_change_key_case( (array) $atts, CASE_LOWER );
        $event_type_id            = absint( $atts['id'] );
        $term                     = get_term( $event_type_id );
        if( ! empty( $term ) ) {
            wp_enqueue_script(
                'ep-eventtypes-details',
                EP_BASE_URL . '/includes/event_types/assets/js/em-type-frontend-custom.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            wp_localize_script(
                'ep-eventtypes-details', 
                'ep_frontend', 
                array(
                    '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                    'ajaxurl'   => admin_url( 'admin-ajax.php' )
                )
            );
            
            $event_types_data         = array();
            $event_types_data['term'] = $term;
            $event_types_data['event_type'] = $this->get_single_event_type( $term->term_id );
            // upcoming events
            $event_types_data['hide_upcoming_events'] = ep_get_global_settings( 'shortcode_hide_upcoming_events' );
            if ( isset( $atts['upcoming'] ) ) {
                $event_types_data['hide_upcoming_events'] = 1;
                if ( 1 === $atts['upcoming'] ) {
                    $event_types_data['hide_upcoming_events'] = 0;
                }
            }
            // check event limit
            if( isset( $atts['event_limit'] ) ){
                $single_type_event_limit = ( $atts["event_limit"] == 0 || $atts["event_limit"] == '' ) ? EP_PAGINATION_LIMIT : $atts["event_limit"];
            } else{
                $single_type_event_limit = ( ep_get_global_settings( 'single_type_event_limit' ) == 0 ) ? EP_PAGINATION_LIMIT : ep_get_global_settings( 'single_type_event_limit');
            }
            // check hide past events
            if( isset( $atts['hide_past_events'] ) ){
                $hide_past_events = $atts['hide_past_events'];
            } else{
                $hide_past_events = ep_get_global_settings( 'single_type_hide_past_events' );
            }
            // get upcoming events for event_type
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $args = array(
                'orderby'        => 'em_start_date',
                'posts_per_page' => $single_type_event_limit,
                'offset'         => (int)( $paged - 1 ) * (int)$single_type_event_limit,
                'paged'          => $paged,
            );
            $args['post_status'] = !empty( $hide_past_events ) == 1 ? 'publish' : 'any';

            $event_types_data['events'] = $this->get_upcoming_events_for_event_type( $event_type_id, $args );

            $event_args  = array();
            $event_args['show_events']      = ( isset( $atts['show_events'] ) ? $atts['show_events'] : ep_get_global_settings( 'single_type_show_events' ) );
            $event_args['event_style']      = ( isset( $atts['event_style'] ) ? $atts['event_style'] : ep_get_global_settings( 'single_type_event_display_view' ) );
            $event_args['event_limit']      = $single_type_event_limit;
            $event_args['event_cols']       = ( isset( $atts['event_cols'] ) ? ep_check_column_size( $atts['event_cols'] ) : ep_check_column_size( ep_get_global_settings( 'single_type_event_column' ) ) );
            $event_args['load_more']        = ( isset( $atts['load_more'] ) ? $atts['load_more'] : ep_get_global_settings( 'single_type_event_load_more' ) );
            $event_args['hide_past_events'] = $hide_past_events;
            $event_args['paged']            = $paged;
            $event_types_data['event_args'] = $event_args;
            $event_types_data['eventtype_id']= $event_type_id;
        }
        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'event_types/single-event-type', null, (object)$event_types_data );
		return ob_get_clean();
    }

    /**
     * Get specific data
     */
    public function get_event_type_field_data( $fields = array(), $with_id = 0 ) {
        $response = array();
        $terms = $this->get_event_types_data();
        if( !empty( $terms->terms ) && count( $terms->terms ) > 0 ) {
            foreach( $terms->terms as $term ) {
                $term_data = array();
                if( ! empty( $fields ) ) {
                    if( in_array( 'id', $fields, true ) ) {
                        $term_data['id'] = $term->id;
                    }
                    if( in_array( 'name', $fields, true ) ) {
                        $term_data['name'] = $term->name;
                    }
                    if( in_array( 'em_color', $fields, true ) ) {
                        $term_data['em_color'] = !empty( $term->em_color ) ? $term->em_color : '';
                    }
                    if( in_array( 'em_type_text_color', $fields, true ) ) {
                        $term_data['em_type_text_color'] = !empty( $term->em_type_text_color ) ? $term->em_type_text_color : '';
                    }
                }
                if( ! empty( $term_data ) ) {
                    if( ! empty( $with_id ) ) {
                        $response[$term_data['id']] = $term_data;
                    } else{
                        $response[] = $term_data;
                    }
                }
            }
        }
        return $response;
    }
    
    /*
     * Ajax loadmore
     */
    public function get_event_types_loadmore(){
        $event_types_data = array();
        $settings                            = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $event_types_settings                = $settings->ep_get_settings( 'event_types' );

        $event_types_data['display_style']   = isset( $_POST['display_style'] ) ? $_POST["display_style"] : $event_types_settings->type_display_view;
        $event_types_data['limit']           = isset( $_POST['limit'] ) ? ( empty($_POST["limit"] ) ? EP_PAGINATION_LIMIT : $_POST["limit"]) : ( empty( $event_types_settings->type_limit ) ? EP_PAGINATION_LIMIT : $event_types_settings->type_limit );
        $event_types_data['cols']            = isset( $_POST['cols'] ) ? ep_check_column_size( $_POST['cols'] ) : ep_check_column_size( $event_types_settings->type_no_of_columns );
        $event_types_data['load_more']       = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $event_types_settings->type_load_more;
        $event_types_data['enable_search']   = isset( $_POST['search'] ) ? $_POST['search'] : $event_types_settings->type_search;
        $event_types_data['featured']        = isset( $_POST["featured"] ) ? $_POST["featured"] : 0;
        $event_types_data['popular']         = isset( $_POST["popular"] ) ? $_POST["popular"] : 0;
        $event_types_data['box_color'] = '';
        if( $event_types_data['display_style'] == 'box' || $event_types_data['display_style'] == 'colored_grid' ) {
            $event_types_data['box_color'] = ( isset( $_POST["box_color"] ) && ! empty( $_POST["box_color"] ) ) ? explode( ',', $_POST["box_color"] ) : $event_types_settings->type_box_color;
        }

        // Set query arguments
        $paged = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged++;
        $event_types_data['paged'] = $paged;
        $ep_search = isset( $_POST['ep_search'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        $pargs = array(
            'orderby'    => 'date',
            'name__like' => $ep_search,
        );

        $limit_args = array(
            'number'     => $event_types_data['limit'],
            'offset'     => (int)($paged-1) * (int)$event_types_data['limit'],
            'paged'      => $paged,
        );

        if( $event_types_data['featured'] == 1 && ( $event_types_data['popular'] == 0 || $event_types_data['popular'] == '' ) ){ 
            $pargs['meta_query'] = array(
                'relation' => 'OR',
                array(
                   'key'       => 'em_is_featured',
                   'value'     => 1,
                   'compare'   => '='
                ),
                array(
                   'key'       => 'em_is_featured',
                   'value'     => true,
                   'compare'   => '='
                )
            );
        }
        $event_types_data['event_types_count'] = $this->get_event_types_count( $pargs , $ep_search, $event_types_data['featured'], $event_types_data['popular'] );
        $pargs = wp_parse_args( $pargs, $limit_args );
        $event_types_data['event_types'] = $this->get_event_types_data( $pargs );

        ob_start();
        
        ep_get_template_part( 'event_types/list-load', null, (object)$event_types_data );
        $data['html'] = ob_get_clean();
        $data['paged'] = $paged;
        return $data;
    }
    
    /*
     * Load more Event on Single Page
     */
    public function get_eventupcoming_eventtype_loadmore(){
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $event_type_settings = $settings->ep_get_settings( 'event_types' );
        
        $event_args  = array();
        $organizer_data                  = array();
        $event_args['event_style']   = isset( $_POST['event_style'] ) ? $_POST["event_style"] : $event_type_settings->single_type_event_display_view;
        $event_args['event_limit']   = isset( $_POST['event_limit'] ) ? (empty($_POST["event_limit"]) ? EP_PAGINATION_LIMIT : $_POST["event_limit"]) : (empty($event_type_settings->single_type_event_limit) ? EP_PAGINATION_LIMIT : $event_type_settings->single_type_event_limit );
        $event_args['event_cols']    = isset( $_POST['event_cols'] ) ? $_POST['event_cols']  : ep_check_column_size( $event_type_settings->single_type_event_column );
        $event_args['load_more']     = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $event_type_settings->single_type_event_load_more;
        $event_args['hide_past_events'] = isset( $_POST['hide_past_events'] ) ? $_POST['hide_past_events'] : $event_type_settings->single_type_hide_past_events;
        $event_args['post_id'] = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;
        
        // set query arguments
        $paged     = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged++;
        $pargs     = array(
            'orderby'        => 'date',
            'posts_per_page' => $event_args['event_limit'],
            'offset'         => (int)( $paged-1 ) * (int)$event_args['event_limit'],
            'paged'          => $paged,
        );
        $organizer_data['event_args']  = $event_args;
        
        $pargs['post_status'] = !empty( $event_args['hide_past_events'] ) == 1 ? 'publish' : 'any';
        $organizer_data['events'] = $this->get_upcoming_events_for_event_type( $event_args['post_id'] , $pargs);
        
        ob_start();
        wp_enqueue_style(
            'ep-organizer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'event_types/single-event-type/event-list-load', null, (object)$organizer_data );
	    
        $data['html'] = ob_get_clean();
        $data['paged'] = $paged;
        return $data;
    }
    
    
    /**
     * Render single term content
     */
    public function render_term_content() {
        wp_enqueue_style( 'ep-material-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), EVENTPRIME_VERSION );
        $term_obj = get_queried_object();
        $atts['id'] = $term_obj->term_id;
        return $this->render_detail_template( $atts );
    }
    
    public function create_event_types($data = array()){
        $term_id = 0;
        if(!empty($data)){
            $name = isset($data['name']) ? sanitize_text_field($data['name']) : '';
            $description = isset($data['description']) ? $data['description'] : '';
            $types = wp_insert_term(
                    $name,
                    'em_event_type',
                    array('description'=>$description)
                    );
            $term_id = isset($types['term_id']) ? $types['term_id'] : 0;
            
            $em_color = isset($data['em_color']) && !empty($data['em_color']) ? sanitize_text_field($data['em_color']) : 'FF5599';
            $em_type_text_color = isset($data['em_type_text_color']) && !empty($data['em_type_text_color']) ? sanitize_text_field($data['em_type_text_color']) : '#43CDFF';
            $em_image_id = isset($data['em_image_id']) ? $data['em_image_id']: '';
            $em_is_featured = isset($data['em_is_featured']) ? sanitize_text_field($data['em_is_featured']): 0;
            $em_age_group = isset($data['em_age_group']) ? $data['em_age_group'] : 'all';
            
            update_term_meta( $term_id, 'em_color', $em_color );
	    update_term_meta( $term_id, 'em_type_text_color', $em_type_text_color );
	    update_term_meta( $term_id, 'em_image_id', $em_image_id );
	    update_term_meta( $term_id, 'em_is_featured', $em_is_featured );
            update_term_meta( $term_id, 'age_group', $em_age_group);
            if ( isset($data['em_status']) && !empty($data['em_status'])) {
                update_term_meta( $term_id, 'em_status', 1 );
            }
        }
        return $term_id;
    }
    
    public function get_event_type_image_url($term_id){
        $image_url = EP_BASE_URL . 'includes/assets/images/dummy_image.png';
        $thumb_id = get_term_meta( $term_id, 'em_image_id', true );
        if($thumb_id){
            $image_url = wp_get_attachment_url(  $thumb_id );
        }
        return $image_url;
    }
}