<?php
/**
 * Class for return organizers data
 */

defined( 'ABSPATH' ) || exit;

class EventM_Organizer_Controller_List {
    /**
     * Term Type.
     * 
     * @var string
     */
    private $term_type = EM_EVENT_ORGANIZER_TAX;

    /**
     * Get organizers data
     */

    public function get_organizers_data( $args = array() ) {
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
        $args       = wp_parse_args( $args, $defaults );
        $terms      = get_terms( $this->term_type, $args );
        $organizers = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $organizers;
        }
        foreach( $terms as $term ){
            $organizer = $this->get_single_organizer( $term->term_id, $term );
            if( ! empty( $organizer ) ) {
                $organizers[] = $organizer;
            }
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $organizers;
        return $wp_query;
    }

    /**
     * Count the total organizers
     */
    public function get_organizers_count( $args = array(), $ep_search = '', $featured = 0 , $popular = 0 ) {
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

        if( $popular == 1 ) return 1;
        
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
     * Get Popular Event Organizers
     */
    public function get_popular_event_organizers( $count = 5, $featured = 0 ){
        $args = array( 
            'hide_empty' => true ,
            'number' => $count,
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

        $terms = get_terms( $this->term_type, $args );

        // check no of events in event organizers
        $events_controller = new EventM_Event_Controller_List();
        $events = $events_controller->get_events_post_data();
        $event_count= array();
        if( isset( $events->posts ) && ! empty( $events->posts ) ){
            foreach( $events->posts as $event ){
                if( isset( $event->em_organizer ) && ! empty( $event->em_organizer ) ){
                    foreach( $event->em_organizer as $organizer_id ){
                        if( isset( $event_count[$organizer_id] ) ){
                            $event_count[$organizer_id] += 1;
                        }
                        else{
                            $event_count[$organizer_id]= 1;
                        }
                    }
                }
            }
        }

        $organizers = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $organizers;
        }

        foreach( $terms as $term ){
            if( isset( $event_count[$term->term_id] ) ){
                $organizer = $this->get_single_organizer( $term->term_id, $term );
                $organizer->events = $event_count[$term->term_id];
                if( isset( $organizer ) && ! empty( $organizer ) ) {
                    $organizers[] = $organizer;
                }
            }   
        }
        
        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $organizers;
        return $wp_query;
    }
    
    /*
     * Get Featured Event Types
     */
    public function get_featured_event_organizers($count = 5){
        $args = array( 
            'hide_empty' => false ,
            'number'     => $count,
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
        $terms      = get_terms( $this->term_type, $args );
        $organizers = array();
        if( empty( $terms ) || is_wp_error( $terms ) ){
           return $organizers;
        }
        foreach( $terms as $term ){
            $organizer = $this->get_single_organizer( $term->term_id, $term );
            if( ! empty( $organizer ) ) {
                $organizers[] = $organizer;
            }
        }

        $wp_query        = new WP_Term_Query( $args );
        $wp_query->terms = $organizers;
        return $wp_query;
    }

    /**
     * Get single organizer data.
     * 
     * @param int $post_id Post ID.
     * 
     * @return objact $organizer Organizer Data.
     */
    public function get_single_organizer( $term_id, $term = null ) {
        if( empty( $term_id ) ) return;

        $organizer = new stdClass();
        $meta      = get_term_meta( $term_id );
        if( ! empty( $meta ) ) {
            foreach ( $meta as $key => $val ) {
                $organizer->{$key} = maybe_unserialize( $val[0] );
            }
        }
        
        if( empty( $term ) ) {
            $term = get_term( $term_id );
        }

        if( empty( $term ) ) return;
        $organizer->id            = $term->term_id;
        $organizer->name          = htmlspecialchars_decode($term->name);
        $organizer->slug          = $term->slug;
        $organizer->description   = $term->description;
        $organizer->count         = $term->count;
        $organizer->organizer_url = ep_get_custom_page_url( 'event_organizers', $term->term_id, 'organizer', 'term' );
        $organizer->image_url     = $this->get_event_organizer_image_url($term->term_id);
        
        return $organizer;
    }

    /**
     * Get upcoming events for single organizer
     * 
     * @param int $organizer_id organizer Id.
     * 
     * @param array $args Post Arguments.
     * 
     * @return object Post.
     */
    public function get_upcoming_events_for_organizer( $organizer_id, $args = array() ) {
        $hide_past_events = ep_get_global_settings( 'single_organizer_hide_past_events' );
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
                        'key'     => 'em_organizer',
                        'value'   =>  serialize( strval ( $organizer_id ) ),
                        'compare' => 'LIKE'
                    ),
                    $past_events_meta_qry,
                )
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );

        $args = wp_parse_args($args, $filter);
        $wp_query = new WP_Query( $args );
        $wp_query->organizer_id = $organizer_id;
        return $wp_query;
    }

    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        wp_enqueue_script(
            'ep-organizer-views-js',
            EP_BASE_URL . '/includes/organizers/assets/js/em-organizer-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-organizer-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        $organizers_data = array();
        $settings                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $organizers_settings                = $settings->ep_get_settings( 'organizers' );

        $organizers_data['display_style']   = isset( $atts['display_style'] ) ? $atts["display_style"] : $organizers_settings->organizer_display_view;
        $organizers_data['limit']           = isset( $atts['limit'] ) ? (empty($atts["limit"]) ? EP_PAGINATION_LIMIT : $atts["limit"]) : (empty($organizers_settings->organizer_limit) ? EP_PAGINATION_LIMIT : $organizers_settings->organizer_limit );
        $organizers_data['column']            = isset( $atts['cols'] ) ? $atts['cols']  : $organizers_settings->organizer_no_of_columns;
        $organizers_data['cols']            = isset( $atts['cols'] ) ? ep_check_column_size( $atts['cols'] ) : ep_check_column_size( $organizers_settings->organizer_no_of_columns );
        $organizers_data['load_more']       = isset( $atts['load_more'] ) ? $atts['load_more'] : $organizers_settings->organizer_load_more;
        $organizers_data['enable_search']   = isset( $atts['search'] ) ? $atts['search'] : $organizers_settings->organizer_search;
        $organizers_data['featured']        = isset( $atts["featured"] ) ? $atts["featured"] : 0;
        $organizers_data['popular']         = isset( $atts["popular"] ) ? $atts["popular"] : 0;
        $organizers_data['box_color'] = '';
        if( $organizers_data['display_style'] == 'box' || $organizers_data['display_style'] == 'colored_grid' ) {
            $organizers_data['box_color'] = ( isset( $atts["organizer_box_color"] ) && ! empty( $atts["organizer_box_color"] ) ) ? $atts["organizer_box_color"] : $organizers_settings->organizer_box_color;
        }

        // Set query arguments
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $organizers_data['paged'] = $paged;
        $ep_search = isset( $_GET['ep_search'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
        $pargs = array(
            'orderby'    => 'date',
            'name__like' => $ep_search,
        );

        $limit_args = array(
            'number'     => $organizers_data['limit'],
            'offset'     => (int)($paged-1) * (int)$organizers_data['limit'],
            'paged'      => $paged,
        );
        // get featured event organizers
        if( $organizers_data['featured'] == 1 && ( $organizers_data['popular'] == 0 || $organizers_data['popular'] == '' ) ){ 
            $pargs['meta_query'] = array(
                // 'relation' => 'OR',
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
        // Get featured and popular organizers
        if( $organizers_data['popular'] == 1 && ( $organizers_data['featured'] == 0 || $organizers_data['featured'] == '' ) ){
            $event_organizers = $this->get_popular_event_organizers( $organizers_data['limit'] );
            $organizer_ids = array_column( wp_list_sort( $event_organizers->terms , $orderby = 'events', $order = 'DESC',  $organizers_data['limit'] ) , 'id' );
            $organizer_ids = array_slice( (array)$organizer_ids, 0, $organizers_data['limit'], true );
            if( ! empty( $organizer_ids ) ){
                $pargs['orderby'] = 'include';
                $pargs['include'] = $organizer_ids;
            } 
        }
        // Get featured and popular organizers
        if( $organizers_data['popular'] == 1 && $organizers_data['featured'] == 1 ){
            $event_organizers = $this->get_popular_event_organizers( $organizers_data['limit'], $organizers_data['featured'] );
            $organizer_ids = array_column( wp_list_sort( $event_organizers->terms , $orderby = 'events', $order = 'DESC',  $organizers_data['limit'] ) , 'id' );
            $organizer_ids = array_slice( (array)$organizer_ids, 0, $organizers_data['limit'], true );
            if( ! empty( $organizer_ids ) ){
                $pargs['orderby'] = 'include';
                $pargs['include'] = $organizer_ids;
            } 
        }
        $organizers_data['organizers_count'] = $this->get_organizers_count( $pargs, $ep_search, $organizers_data['featured'], $organizers_data['popular'] );
        $pargs = wp_parse_args( $pargs, $limit_args );
        $organizers_data['organizers'] = $this->get_organizers_data( $pargs );

        ob_start();
        wp_enqueue_style(
            'ep-organizer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
		ep_get_template_part( 'organizers/list', null, (object)$organizers_data );
		return ob_get_clean();
    }

    /**
     * Render detail page
     */
    public function render_detail_template( $atts = array() ) {
        wp_enqueue_script(
            'ep-organizer-views-js',
            EP_BASE_URL . '/includes/organizers/assets/js/em-organizer-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-organizer-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        $atts                    = array_change_key_case( (array) $atts, CASE_LOWER );
        $organizer_id            = absint( $atts['id'] );
        $term                    = get_term( $organizer_id );
        $organizers_data         = array();
        if( ! empty( $term ) && ! empty( $term->term_id ) ) {
            $organizers_data['term'] = $term;
            $organizers_data['organizer'] = $this->get_single_organizer( $term->term_id );
            // upcoming events
            $organizers_data['hide_upcoming_events'] = ep_get_global_settings( 'shortcode_hide_upcoming_events' );
            if ( isset( $atts['upcoming'] ) ) {
                $organizers_data['hide_upcoming_events'] = 1;
                if ( 1 === $atts['upcoming'] ) {
                    $organizers_data['hide_upcoming_events'] = 0;
                }
            }
            // check event limit
            if( isset( $atts['event_limit'] ) ){
                $single_organizer_event_limit = ( $atts["event_limit"] == 0 || $atts["event_limit"] == '' ) ? EP_PAGINATION_LIMIT : $atts["event_limit"];
            } else{
                $single_organizer_event_limit = ( ep_get_global_settings( 'single_organizer_event_limit' ) == 0 ) ? EP_PAGINATION_LIMIT : ep_get_global_settings( 'single_organizer_event_limit');
            }
            // check hide past events
            if( isset( $atts['hide_past_events'] ) ){
                $hide_past_events = $atts['hide_past_events'];
            } else{
                $hide_past_events = ep_get_global_settings( 'single_organizer_hide_past_events' );
            }

            // get upcoming events for organizer
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $args = array(
                'orderby'        => 'em_start_date',
                'posts_per_page' => $single_organizer_event_limit,
                'offset'         => (int)( $paged - 1 ) * (int)$single_organizer_event_limit,
                'paged'          => $paged,
            );
            $args['post_status'] = !empty( $hide_past_events ) == 1 ? 'publish' : 'any';

            $organizers_data['events'] = $this->get_upcoming_events_for_organizer( $organizer_id,$args );
            
            $event_args  = array();
            $event_args['show_events']      = ( isset( $atts['show_events'] ) ? $atts['show_events'] : ep_get_global_settings( 'single_organizer_show_events' ) );
            $event_args['event_style']      = ( isset( $atts['event_style'] ) ? $atts['event_style'] : ep_get_global_settings( 'single_organizer_event_display_view' ) );
            $event_args['event_limit']      = $single_organizer_event_limit;
            $event_args['event_cols']       = ( isset( $atts['event_cols'] ) ? ep_check_column_size( $atts['event_cols'] ) : ep_check_column_size( ep_get_global_settings( 'single_organizer_event_column' ) ) );
            $event_args['load_more']        = ( isset( $atts['load_more'] ) ? $atts['load_more'] : ep_get_global_settings( 'single_organizer_event_load_more' ) );
            $event_args['hide_past_events'] = $hide_past_events;
            $event_args['paged']            = $paged;
            $organizers_data['event_args']  = $event_args;
            $organizers_data['organizer_id']= $organizer_id;
        }

        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'organizers/single-organizer', null, (object)$organizers_data );
		return ob_get_clean();
    }
    
    /*
     * Load More 
     */
    function get_event_organizer_loadmore(){
        $organizers_data = array();
        $settings                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $organizers_settings                = $settings->ep_get_settings( 'organizers' );
        
        $organizers_data['display_style']   = isset( $_POST['display_style'] ) ? $_POST["display_style"] : $organizers_settings->organizer_display_view;
        $organizers_data['limit']           = isset( $_POST['limit'] ) ? (empty($_POST["limit"]) ? EP_PAGINATION_LIMIT : $_POST["limit"]) : (empty($organizers_settings->organizer_limit) ? EP_PAGINATION_LIMIT : $organizers_settings->organizer_limit );
        $organizers_data['cols']            = isset( $_POST['cols'] ) ? ep_check_column_size( $_POST['cols'] ) : ep_check_column_size( $organizers_settings->organizer_no_of_columns );
        $organizers_data['load_more']       = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $organizers_settings->organizer_load_more;
        $organizers_data['enable_search']   = isset( $_POST['search'] ) ? $_POST['search'] : $organizers_settings->organizer_search;
        $organizers_data['featured']        = isset( $_POST["featured"] ) ? $_POST["featured"] : 0;
        $organizers_data['popular']         = isset( $_POST["popular"] ) ? $_POST["popular"] : 0;
        $organizers_data['box_color'] = '';
        if( $organizers_data['display_style'] == 'box' || $organizers_data['display_style'] == 'colored_grid' ) {
            $organizers_data['box_color'] = ( isset( $_POST["box_color"] ) && ! empty( $_POST["box_color"] ) ) ? explode( ',', $_POST["box_color"] ) : $organizers_settings->organizer_box_color;
        }

        // Set query arguments
        $paged = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged++;
        $organizers_data['paged'] = $paged;
        $ep_search = isset( $_POST['ep_search'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        $pargs = array(
            'orderby'    => 'date',
            'name__like' => $ep_search,
        );
      
        $limit_args = array(
            'number'     => $organizers_data['limit'],
            'offset'     => (int)($paged-1) * (int)$organizers_data['limit'],
            'paged'      => $paged,
        );

        // get featured event organizers
        if( $organizers_data['featured'] == 1 && ( $organizers_data['popular'] == 0 || $organizers_data['popular'] == '' ) ){ 
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
        $organizers_data['organizers_count'] = $this->get_organizers_count( $pargs, $ep_search, $organizers_data['featured'], $organizers_data['popular'] );
        $pargs = wp_parse_args( $pargs, $limit_args );
        $organizers_data['organizers'] = $this->get_organizers_data( $pargs );

        ob_start();
        
		ep_get_template_part( 'organizers/list-load', null, (object)$organizers_data );
        $data['html'] = ob_get_clean();
        $data['paged'] = $paged;
        return $data;
    }
    
    public function get_eventupcoming_organizer_loadmore(){
        $settings                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $organizers_settings                = $settings->ep_get_settings( 'organizers' );
        
        $event_args  = array();
        $organizer_data                  = array();
        $event_args['event_style']   = isset( $_POST['event_style'] ) ? $_POST["event_style"] : $organizers_settings->single_organizer_event_display_view;
        $event_args['event_limit']   = isset( $_POST['event_limit'] ) ? (empty($_POST["event_limit"]) ? EP_PAGINATION_LIMIT : $_POST["event_limit"]) : (empty($organizers_settings->single_organizer_event_limit) ? EP_PAGINATION_LIMIT : $organizers_settings->single_organizer_event_limit );
        $event_args['event_cols']    = isset( $_POST['event_cols'] ) ? $_POST['event_cols']  : ep_check_column_size( $organizers_settings->single_organizer_event_column );
        $event_args['load_more']     = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $organizers_settings->single_organizer_event_load_more;
        $event_args['hide_past_events'] = isset( $_POST['hide_past_events'] ) ? $_POST['hide_past_events'] : $organizers_settings->single_organizer_hide_past_events;
        $event_args['post_id'] = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;
        
        // set query arguments
        //$paged = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged = ( ! empty( $_POST['paged'] ) ? $_POST['paged'] : 1 );
        $paged++;
        $pargs = array(
            'orderby'        => 'date',
            'posts_per_page' => $event_args['event_limit'],
            'offset'         => (int)( $paged - 1 ) * (int)$event_args['event_limit'],
            'paged'          => $paged,
        );
        $organizer_data['event_args']  = $event_args;
        
        $pargs['post_status'] = !empty( $event_args['hide_past_events'] ) == 1 ? 'publish' : 'any';
        $organizer_data['events'] = $this->get_upcoming_events_for_organizer( $event_args['post_id'] , $pargs);
        
        ob_start();
        wp_enqueue_style(
            'ep-organizer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'organizers/single-organizer/event-list-load', null, (object)$organizer_data );
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
    public function get_event_organizer_field_data( $fields = array() ) {
        $response = array();
        $terms = $this->get_organizers_data();
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
                }
                if( ! empty( $term_data ) ) {
                    $response[] = $term_data;
                }
            }
        }
        return $response;
    }
    
    public function create_organizer($data = array()){
        $term_id = 0;
        if(!empty($data)){
            $organizer_name = isset($data['name']) ? sanitize_text_field($data['name']) : '';
            $description = isset($data['description']) ? sanitize_text_field($data['description']) : '';
            $org = wp_insert_term(
                    $organizer_name,
                    'em_event_organizer',
                    array('description'=>$description)
                    );
            $term_id = isset($org['term_id']) ? $org['term_id'] : 0;
            
            $em_organizer_phones = isset($data['em_organizer_phones']) && !empty($data['em_organizer_phones']) ? $data['em_organizer_phones']: array();
            $em_organizer_emails = isset($data['em_organizer_emails']) && !empty($data['em_organizer_emails']) ? $data['em_organizer_emails'] : array();
            $em_organizer_websites = isset($data['em_organizer_websites']) && !empty($data['em_organizer_websites']) ? $data['em_organizer_websites']: array();
            $em_image_id = isset($data['em_image_id']) ? $data['em_image_id']: '';
            $em_is_featured = isset($data['em_is_featured']) ? sanitize_text_field($data['em_is_featured']): 0;
            $em_social_links = isset($data['em_social_links']) ? $data['em_social_links'] : array('facebook','instagram','linkedin','twitter');
            
            update_term_meta( $term_id, 'em_organizer_phones', $em_organizer_phones );
            update_term_meta( $term_id, 'em_organizer_emails', $em_organizer_emails );
            update_term_meta( $term_id, 'em_organizer_websites', $em_organizer_websites );
            update_term_meta( $term_id, 'em_image_id', $em_image_id );
            update_term_meta( $term_id, 'em_is_featured', $em_is_featured );
            update_term_meta( $term_id, 'em_social_links', $em_social_links );
            
            if ( isset($data['em_status']) && !empty($data['em_status'])) {
                update_term_meta( $term_id, 'em_status', 1 );
            }
        }
        return $term_id;
    }
    
    public function get_event_organizer_image_url($term_id){
        $image_url = EP_BASE_URL . 'includes/assets/images/dummy-user.png';
        $thumb_id = get_term_meta( $term_id, 'em_image_id', true );
        if($thumb_id){
            $image_url = wp_get_attachment_url(  $thumb_id );
        }
        return $image_url;
    }
}