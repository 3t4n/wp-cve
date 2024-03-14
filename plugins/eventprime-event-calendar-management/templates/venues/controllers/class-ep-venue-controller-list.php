<?php
/**
 * Class for return event venue data
 */

defined( 'ABSPATH' ) || exit;

class EventM_Venue_Controller_List {
    /**
     * Term Type.
     * 
     * @var string
     */
    private $term_type = EM_VENUE_TYPE_TAX;

    /**
     * Get venues data
     */

    public function get_venues_data( $args = array() ) {
        $defaults = array( 
            'hide_empty' => false ,
            'meta_query' => array(
                'relation'=>'OR',
                array(
                  'key'     => 'em_status',
                  'value'   => 0,
                  'compare' => '!='
                ),
                array(
                  'key'     => 'em_status',
                  'compare' => 'NOT EXISTS'
                )
            )
        );
        $args        = wp_parse_args( $args, $defaults );
        $terms       = get_terms( $this->term_type, $args );
        $venues = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $venues;
        }
        foreach( $terms as $term ){
            $venue = $this->get_single_venue( $term->term_id, $term );
            if( ! empty( $venue ) ) {
                $venues[] = $venue;
            }
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $venues;
        return $wp_query;
    }

    /**
     * Count the total venues
     */
    public function get_venues_count( $args = array() ) {
        $defaults = array( 
            'hide_empty' => false ,
            'meta_query' => array(
                'relation'=>'OR',
                array(
                  'key'     => 'em_status',
                  'value'   => 0,
                  'compare' => '!='
                ),
                array(
                  'key'     => 'em_status',
                  'compare' => 'NOT EXISTS'
                )
            )
        );
        $args  = wp_parse_args( $args, $defaults );
        $terms = get_terms( $this->term_type, $args );
        if( ! empty( $terms ) && is_array( $terms )){
            return count($terms);
        }else{
            return 1;
        }
    }
    
    
    /*
     * Get Popular Event Venues
     */
    public function get_popular_event_venues( $count = 5, $featured = 0 ){
        $args = array( 
            'hide_empty' => true ,
            'number'=> $count,
            'orderby' => 'count', 
            'order' => 'DESC',
            'meta_query' => array(
                'relation'=>'OR',
                array(
                  'key'     => 'em_status',
                  'value'   => 0,
                  'compare' => '!='
                ),
                array(
                  'key'     => 'em_status',
                  'compare' => 'NOT EXISTS'
                )
            )
        );
        if( $featured == 1 ){
            $args['post_status'] = 'publish';
            $args['meta_query'] = array(
                'relation'=>'AND',
                array(
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
                ),
                array(
                    'relation'=>'OR',
                    array(
                      'key'     => 'em_status',
                      'value'   => 0,
                      'compare' => '!='
                    ),
                    array(
                      'key'     => 'em_status',
                      'compare' => 'NOT EXISTS'
                    )
                )
            );           
        }
        
        $terms       = get_terms( $this->term_type, $args );

        // check no of events in event venues
        $events_controller = new EventM_Event_Controller_List();
        $events = $events_controller->get_events_post_data();
        $event_count= array();
        if( isset( $events->posts ) && ! empty( $events->posts ) ){
            foreach( $events->posts as $event ){
                if( isset( $event->em_venue ) && ! empty( $event->em_venue ) ){
                    if( isset( $event_count[$event->em_venue] ) ){
                        $event_count[$event->em_venue] += 1;
                    }else{
                        $event_count[$event->em_venue] = 1;
                    }
                }
            }
        }

        $venues = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $venues;
        }
        foreach( $terms as $term ){
            if( isset( $event_count[$term->term_id] ) ){
                $venue = $this->get_single_venue( $term->term_id, $term );
                $venue->events = $event_count[$term->term_id];
                if( isset( $venue ) && ! empty( $venue ) ) {
                    $venues[] = $venue;
                }
            }   
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $venues;
        return $wp_query;
    }
    
    /*
     * Get Featured Event Venues
     */
    public function get_featured_event_venues($count = 5){
        $args = array( 
            'hide_empty' => false ,
            'number'=>$count,
            'meta_query' => array(
                'relation' => 'AND',
                array(
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
                ),
                array(
                    'relation'=>'OR',
                    array(
                      'key'     => 'em_status',
                      'value'   => 0,
                      'compare' => '!='
                    ),
                    array(
                      'key'     => 'em_status',
                      'compare' => 'NOT EXISTS'
                    )
                )
            )
        );
        $terms       = get_terms( $this->term_type, $args );
        $venues = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $venues;
        }
        foreach( $terms as $term ){
            $venue = $this->get_single_venue( $term->term_id, $term );
            if( ! empty( $venue ) ) {
                $venues[] = $venue;
            }
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $venues;
        return $wp_query;
    }
    
    /**
     * Get single venue data.
     * 
     * @param int $post_id Post ID.
     * 
     * @return objact $venue Venue Data.
     */
    public function get_single_venue( $term_id, $term = null ) {
        if( empty( $term_id ) ) return;

        $venue = new stdClass();
        $meta  = get_term_meta( $term_id );
        foreach ($meta as $key => $val) {
            $venue->{$key} = maybe_unserialize( $val[0] );
        }
        if( empty( $term ) ) {
            $term = get_term( $term_id );
        }
        if( empty( $term ) ) return;
        $venue->id              = $term->term_id;
        $venue->name            = htmlspecialchars_decode( $term->name );
        $venue->slug            = $term->slug;
        $venue->description     = $term->description;
        $venue->count           = $term->count;
        $venue->venue_url       = ep_get_custom_page_url( 'venues_page', $term->term_id, 'venue', 'term' );
        $venue->image_url       = EP_BASE_URL . 'includes/assets/images/dummy_image.png';
        $venue->other_image_url = array();
        if( ! empty( $venue->em_gallery_images ) ) {
            if( count( $venue->em_gallery_images ) > 0 ) {
                $img_url = wp_get_attachment_image_src( $venue->em_gallery_images[0], 'large' );
                if( ! empty( $img_url ) && isset( $img_url[0] ) ) {
                    $venue->image_url = $img_url[0];
                }
            }
            // other images
            if( count( $venue->em_gallery_images ) > 1 ) {
                for( $i = 1; $i < count( $venue->em_gallery_images ); $i++ ) {
                    $venue->other_image_url[] = wp_get_attachment_image_src( $venue->em_gallery_images[$i], 'large' )[0];
                }
            }
        }
        
        return $venue;
    }

    /**
     * Get upcoming events for single venue
     * 
     * @param int $venue_id venue Id.
     * 
     * @param array $args Post Arguments.
     * 
     * @return object Post.
     */
    public function get_upcoming_events_for_venue( $venue_id, $args = array() ) {
        $hide_past_events = ep_get_global_settings( 'single_venue_hide_past_events' );
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
                        'key'     => 'em_venue',
                        'value'   =>  $venue_id,
                        'compare' => '='
                    ),
                    $past_events_meta_qry,
                )
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );

        $args = wp_parse_args( $args, $filter );
        $wp_query = new WP_Query( $args );
        $wp_query->venue_id = $venue_id;
        return $wp_query;
    }

    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        wp_enqueue_script(
            'ep-venues-views-js',
            EP_BASE_URL . '/includes/venues/assets/js/em-venue-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-venues-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        $venues_data = array();
        $settings                     = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $venues_settings              = $settings->ep_get_settings( 'venues' );
       
        $venues_data['display_style'] = isset( $atts['display_style'] ) ? $atts["display_style"] : $venues_settings->venue_display_view;
        $venues_data['limit']         = isset( $atts['limit'] ) ? ( empty($atts["limit"] ) ? EP_PAGINATION_LIMIT : $atts["limit"]) : ( empty( $venues_settings->venue_limit ) ? EP_PAGINATION_LIMIT : $venues_settings->venue_limit );
        $venues_data['column']        = isset( $atts['cols'] ) ? $atts['cols'] : $venues_settings->venue_no_of_columns;
        $venues_data['cols']          = isset( $atts['cols'] ) ? ep_check_column_size( $atts['cols'] ) : ep_check_column_size( $venues_settings->venue_no_of_columns );
        $venues_data['load_more']     = isset( $atts['load_more'] ) ? $atts['load_more'] : $venues_settings->venue_load_more;
        $venues_data['enable_search'] = isset( $atts['search'] ) ? $atts['search'] : $venues_settings->venue_search;
        $venues_data['featured']      = isset( $atts["featured"] ) ? $atts["featured"] : 0;
        $venues_data['popular']       = isset( $atts["popular"] ) ? $atts["popular"] : 0;
        $venues_data['box_color'] = '';
        if( $venues_data['display_style'] == 'box' || $venues_data['display_style'] == 'colored_grid' ) {
            $venues_data['box_color'] = ( isset( $atts["venue_box_color"] ) && ! empty( $atts["venue_box_color"] ) ) ? $atts["venue_box_color"] : $venues_settings->venue_box_color;
        }

        // Set query arguments
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $venues_data['paged'] = $paged;
        $ep_search = isset( $_GET['ep_search'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
        $pargs = array(
            'orderby'    => 'date',
            'name__like' => $ep_search,
        );

        $limit_args = array(
            'number'     => $venues_data['limit'],
            'offset'     => (int)($paged-1) * (int)$venues_data['limit'],
            'paged'      => $paged,
        );
        // Get featured event venues
        if( $venues_data['featured'] == 1 && ( $venues_data['popular'] == 0 || $venues_data['popular'] == '' ) ){ 
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
        // Get popular event venues
        if( $venues_data['popular'] == 1 && ( $venues_data['featured'] == 0 || $venues_data['featured'] == '' ) ){
            $event_venues = $this->get_popular_event_venues( $venues_data['limit'] );
            $venue_ids = array_column( wp_list_sort( $event_venues->terms , $orderby = 'events', $order = 'DESC') , 'id' );
            $venue_ids = array_slice( (array)$venue_ids, 0, $venues_data['limit'], true );
            if( ! empty( $venue_ids ) ){
                $pargs['orderby'] = 'include';
                $pargs['include'] = $venue_ids;
            } 
        }
        // Get featured and popular event venues
        if( $venues_data['popular'] == 1 && $venues_data['featured'] == 1 ){
            $event_venues = $this->get_popular_event_venues( $venues_data['limit'], $venues_data['featured'] );
            $venue_ids = array_column( wp_list_sort( $event_venues->terms , $orderby = 'events', $order = 'DESC') , 'id' );
            $venue_ids = array_slice( (array)$venue_ids, 0, $venues_data['limit'], true );
            if( ! empty( $venue_ids ) ){
                $pargs['orderby'] = 'include';
                $pargs['include'] = $venue_ids;
            } 
        }
        $venues_data['venue_count'] = $this->get_venues_count( $pargs, $ep_search, $venues_data['featured'], $venues_data['popular'] );
        $pargs = wp_parse_args( $pargs, $limit_args );
        $venues_data['venues'] = $this->get_venues_data( $pargs );

        ob_start();
        wp_enqueue_style(
            'ep-venue-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
		ep_get_template_part( 'venues/list', null, (object)$venues_data );
		return ob_get_clean();
    }

    /**
     * Render detail page
     */
    public function render_detail_template( $atts = array() ) {
        wp_enqueue_style( 'ep-responsive-slides-css' );
        wp_enqueue_script( 'ep-responsive-slides-js' );

        wp_register_script( 'em-google-map', EP_BASE_URL . '/includes/assets/js/em-map.js', array( 'jquery' ), EVENTPRIME_VERSION );
        $gmap_api_key = ep_get_global_settings( 'gmap_api_key' );
        if( $gmap_api_key ) {
            wp_enqueue_script(
                'google_map_key', 
                'https://maps.googleapis.com/maps/api/js?key='.$gmap_api_key.'&libraries=places&callback=Function.prototype', 
                array(), EVENTPRIME_VERSION
            );
        }

        wp_enqueue_script(
            'ep-venues-views-js',
            EP_BASE_URL . '/includes/venues/assets/js/em-venue-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-venues-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        $atts                 = array_change_key_case( (array) $atts, CASE_LOWER );
        $venue_id             = absint( $atts['id'] );
        $term                 = get_term( $venue_id );
        $venues_data          = array();
        if( ! empty( $term ) ) {
            $venues_data['term']  = $term;
            $venues_data['venue'] = $this->get_single_venue( $term->term_id );
            // upcoming events
            $venues_data['hide_upcoming_events'] = ep_get_global_settings( 'shortcode_hide_upcoming_events' );
            if ( isset( $atts['upcoming'] ) ) {
                $venues_data['hide_upcoming_events'] = 1;
                if ( 1 === $atts['upcoming'] ) {
                    $venues_data['hide_upcoming_events'] = 0;
                }
            }
            // check event limit
            if( isset( $atts['event_limit'] ) ){
                $single_venue_event_limit = ( $atts["event_limit"] == 0 || $atts["event_limit"] == '' ) ? EP_PAGINATION_LIMIT : $atts["event_limit"];
            } else{
                $single_venue_event_limit = ( ep_get_global_settings( 'single_venue_event_limit' ) == 0 ) ? EP_PAGINATION_LIMIT : ep_get_global_settings( 'single_venue_event_limit');
            }
            // check hide past events
            if( isset( $atts['hide_past_events'] ) ){
                $hide_past_events = $atts['hide_past_events'];
            } else{
                $hide_past_events = ep_get_global_settings( 'single_venue_hide_past_events' );
            }
            // get upcoming events for venue
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $args = array(
                'orderby'        => 'em_start_date',
                'posts_per_page' => $single_venue_event_limit,
                'offset'         => (int)( $paged - 1 ) * (int)$single_venue_event_limit,
                'paged'          => $paged,
            );
            $args['post_status'] = !empty( $hide_past_events ) == 1 ? 'publish' : 'any';

            $venues_data['events'] = $this->get_upcoming_events_for_venue( $venue_id, $args );

            $event_args  = array();
            $event_args['show_events']      = ( isset( $atts['show_events'] ) ? $atts['show_events'] : ep_get_global_settings( 'single_venue_show_events' ) );
            $event_args['event_style']      = ( isset( $atts['event_style'] ) ? $atts['event_style'] : ep_get_global_settings( 'single_venue_event_display_view' ) );
            $event_args['event_limit']      = $single_venue_event_limit;
            $event_args['event_cols']       = ( isset( $atts['event_cols'] ) ? ep_check_column_size( $atts['event_cols'] ) : ep_check_column_size( ep_get_global_settings( 'single_venue_event_column' ) ) );
            $event_args['load_more']        = ( isset( $atts['load_more'] ) ? $atts['load_more'] : ep_get_global_settings( 'single_venue_event_load_more' ) );
            $event_args['hide_past_events'] = $hide_past_events;
            $event_args['paged']            = $paged;
            $venues_data['event_args']      = $event_args;
            $venues_data['venue_id']        = $venue_id;
        }
        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'venues/single-venue', null, (object)$venues_data );
        
	return ob_get_clean();
    }
    
    /*
     * Load more 
     */
    public function get_event_venue_loadmore(){
        $venues_data = array();
        $settings                     = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $venues_settings              = $settings->ep_get_settings( 'venues' );
        
        $venues_data['display_style'] = isset( $_POST['display_style'] ) ? $_POST["display_style"] : $venues_settings->venue_display_view;
        $venues_data['limit']         = isset( $_POST['limit'] ) ? ( empty($_POST["limit"] ) ? EP_PAGINATION_LIMIT : $_POST["limit"]) : ( empty( $venues_settings->venue_limit ) ? EP_PAGINATION_LIMIT : $venues_settings->venue_limit );
        $venues_data['cols']          = isset( $_POST['cols'] ) ? ep_check_column_size( $_POST['cols'] ) : ep_check_column_size( $venues_settings->venue_no_of_columns );
        $venues_data['load_more']     = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $venues_settings->venue_load_more;
        $venues_data['enable_search'] = isset( $_POST['search'] ) ? $_POST['search'] : $venues_settings->venue_search;
        $venues_data['featured']      = isset( $_POST["featured"] ) ? $_POST["featured"] : 0;
        $venues_data['popular']       = isset( $_POST["popular"] ) ? $_POST["popular"] : 0;
        $venues_data['box_color'] = '';
        if( $venues_data['display_style'] == 'box' || $venues_data['display_style'] == 'colored_grid' ) {
            $venues_data['box_color'] = ( isset( $_POST["box_color"] ) && ! empty( $_POST["box_color"] ) ) ? explode( ',', $_POST["box_color"] ) : $venues_settings->venue_box_color;
        }

        // Set query arguments
        $paged = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged++;
        $venues_data['paged'] = $paged;
        $ep_search = isset( $_POST['ep_search'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        $pargs = array(
            'orderby'    => 'date',
            'name__like' => $ep_search,
        );

        $limit_args = array(
            'number'     => $venues_data['limit'],
            'offset'     => (int)($paged-1) * (int)$venues_data['limit'],
            'paged'      => $paged,
        );

        // Get featured event venues
        if( $venues_data['featured'] == 1 && ( $venues_data['popular'] == 0 || $venues_data['popular'] == '' ) ){ 
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
        $venues_data['venue_count'] = $this->get_venues_count( $pargs, $ep_search, $venues_data['featured'], $venues_data['popular'] );
        $pargs = wp_parse_args( $pargs, $limit_args );
        $venues_data['venues'] = $this->get_venues_data( $pargs );

        ob_start();
        wp_enqueue_style(
            'ep-venue-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
		ep_get_template_part( 'venues/list-load', null, (object)$venues_data );
	    $data['html'] = ob_get_clean();
        $data['paged'] = $paged;
        return $data;
    }
    
    public function get_eventupcoming_venue_loadmore(){
        $settings        = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $venues_settings = $settings->ep_get_settings( 'venues' );
        
        $event_args  = array();
        $venues_data                 = array();
        $event_args['event_style']   = isset( $_POST['event_style'] ) ? $_POST["event_style"] : $venues_settings->single_venue_event_display_view;
        $event_args['event_limit']   = isset( $_POST['event_limit'] ) ? (empty($_POST["event_limit"]) ? EP_PAGINATION_LIMIT : $_POST["event_limit"]) : (empty($venues_settings->single_venue_event_limit) ? EP_PAGINATION_LIMIT : $venues_settings->single_venue_event_limit );
        $event_args['event_cols']    = isset( $_POST['event_cols'] ) ? $_POST['event_cols']  : ep_check_column_size( $venues_settings->single_venue_event_column );
        $event_args['load_more']     = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $venues_settings->single_venue_event_load_more;
        $event_args['hide_past_events'] = isset( $_POST['hide_past_events'] ) ? $_POST['hide_past_events'] : $venues_settings->single_venue_hide_past_events;
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

        $venues_data['event_args']  = $event_args;
        $pargs['post_status'] = !empty( $event_args['hide_past_events'] ) == 1 ? 'publish' : 'any';
        $venues_data['events'] = $this->get_upcoming_events_for_venue( $event_args['post_id'] , $pargs);
        
        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'venues/single-venue/event-list-load', null, (object)$venues_data );
	    
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
    
    /**
     * Get specific data
     */
    public function get_event_venues_field_data( $fields = array(), $with_id = 0 ) {
        $response = array();
        $terms = $this->get_venues_data();
        if( !empty( $terms->terms ) && count( $terms->terms ) > 0 ) {
            foreach( $terms->terms as $term ) {
                $term_data = array();
                if( !empty( $fields ) ) {
                    if( in_array( 'id', $fields, true ) ) {
                        $term_data['id'] = $term->id;
                    }
                    if( in_array( 'name', $fields, true ) ) {
                        $term_data['name'] = $term->name;
                    }
                    if( in_array( 'address', $fields, true ) ) {
                        $term_data['address'] = !empty( $term->em_address ) ? $term->em_address : '';
                    }
                    if( in_array( 'image', $fields, true ) ) {
                        $image_url = EP_BASE_URL . 'includes/assets/images/dummy_image.png';
                        if( ! empty( $term->em_gallery_images ) ) {
                            if( count( $term->em_gallery_images ) > 0 ) {
                                $img_url = wp_get_attachment_image_src( $term->em_gallery_images[0], 'large' );
                                if( ! empty( $img_url ) && isset( $img_url[0] ) ) {
                                    $image_url = $img_url[0];
                                }
                            }
                        }
                        $term_data['image'] = $image_url;
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
     * Create new venue
     * @param (array) $data 
     * return $id
     */
    public function create_venue($data = array()){
        //epd($data);
        $term_id = 0;
        if(!empty($data)){
            $location_name = isset($data['name']) ? sanitize_text_field($data['name']) : '';
            $venue = wp_insert_term(
                $location_name,
                'em_venue',
                array()
            );
            $term_id = isset($venue['term_id']) ? $venue['term_id'] : 0;
            
            $em_type = isset($data['em_type']) ? sanitize_text_field($data['em_type']): '';
            $em_address = isset($data['em_address']) ? sanitize_text_field($data['em_address']) : '';
            $em_lat = isset($data['em_lat']) ? sanitize_text_field($data['em_lat']): '';
            $em_lng = isset($data['em_lng']) ? sanitize_text_field($data['em_lng']): '';
            $em_locality = isset($data['em_locality']) ? sanitize_text_field($data['em_locality']): '';
            $em_state = isset($data['em_state']) ? sanitize_text_field($data['em_state']): '';
            $em_country = isset($data['em_country']) ? sanitize_text_field($data['em_country']): '';
            $em_postal_code = isset($data['em_postal_code']) ? sanitize_text_field($data['em_postal_code']): '';
            $em_zoom_level = isset($data['em_zoom_level']) ? sanitize_text_field($data['em_zoom_level']): '';
            $em_display_address_on_frontend = isset($data['em_display_address_on_frontend']) & !empty($data['em_display_address_on_frontend']) ? 1: 0;
            
            $em_established = isset($data['em_established']) ? sanitize_text_field($data['em_established']): '';
            $em_seating_organizer = isset($data['em_seating_organizer']) ? sanitize_text_field($data['em_seating_organizer']): '';
            $em_facebook_page = isset($data['em_facebook_page']) ? sanitize_text_field($data['em_facebook_page']): '';
            $em_instagram_page = isset($data['em_instagram_page']) ? sanitize_text_field($data['em_instagram_page']): '';
            
            $em_image_ids = isset($data['em_image_id']) ? $data['em_image_id']: '';
            $em_is_featured = isset($data['em_is_featured']) ? sanitize_text_field($data['em_is_featured']): '';
            
            update_term_meta( $term_id, 'em_address', $em_address );
            update_term_meta( $term_id, 'em_lat', $em_lat );
            update_term_meta( $term_id, 'em_lng', $em_lng );
            update_term_meta( $term_id, 'em_locality', $em_locality );
            update_term_meta( $term_id, 'em_state', $em_state );
            update_term_meta( $term_id, 'em_country', $em_country );
            update_term_meta( $term_id, 'em_postal_code', $em_postal_code );
            update_term_meta( $term_id, 'em_zoom_level', $em_zoom_level );
            update_term_meta( $term_id, 'em_display_address_on_frontend', $em_display_address_on_frontend );
            update_term_meta( $term_id, 'em_established', $em_established );
            update_term_meta( $term_id, 'em_type', $em_type );
            update_term_meta( $term_id, 'em_seating_organizer', $em_seating_organizer );
            update_term_meta( $term_id, 'em_facebook_page', $em_facebook_page );
            update_term_meta( $term_id, 'em_instagram_page', $em_instagram_page );
            update_term_meta( $term_id, 'em_gallery_images', array($em_image_ids) );
            update_term_meta( $term_id, 'em_is_featured', $em_is_featured );
            
            if ( isset($data['em_status']) && !empty($data['em_status'])) {
                update_term_meta( $term_id, 'em_status', 1 );
            }
        }
        return $term_id;
    }
}