<?php
/**
 * Class for return performers data
 */

defined( 'ABSPATH' ) || exit;

class EventM_Performer_Controller_List {
    /**
     * Post Type.
     * 
     * @var string
     */
    private $post_type = EM_PERFORMER_POST_TYPE;

    /**
     * Return all performers posts
     */
    public function get_performer_all_data( $args = array() ) {
        $default = array(
            'orderby'          => 'title',
            'numberposts'      => -1,
            'offset'           => 0,     
            'order'            => 'ASC',
            'post_type'        => $this->post_type,
            'post_status'      => 'publish',
            'meta_query'       => array(    
                'relation'     => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key'      => 'em_status',
                        'compare'  => 'NOT EXISTS'
                    ),
                    array(
                        'key'      => 'em_status',
                        'value'    => 0,
                        'compare'  => '!='
                    ),
                ),
                
                array(
                    'relation'     => 'OR',
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 1,
                        'compare'  => '='
                    ),
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 'true',
                        'compare'  => '='
                    ),
                )
            )
        );
        $default = apply_filters( 'ep_performers_render_argument', $default, $args );
        $args = wp_parse_args( $args, $default );
        $posts = get_posts( $args );
        return $posts;
    }

    /**
     * Get specific data from posts
     */
    public function get_performer_field_data( $fields = array() ) {
        $response = array();
        $posts = $this->get_performer_all_data();
        if( !empty($posts) && count($posts) > 0 ) {
            foreach( $posts as $post ) {
                $post_data = array();
                if( !empty( $fields ) ) {
                    if( in_array( 'id', $fields, true ) ) {
                        $post_data['id'] = $post->ID;
                    }
                    if( in_array( 'image_url', $fields, true ) ) {
                        $featured_img_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                        $post_data['image_url'] = ( ! empty( $featured_img_url ) ) ? $featured_img_url : '';
                    }
                    if( in_array( 'name', $fields, true ) ) {
                        $post_data['name'] = $post->post_title;
                    }
                }
                if( ! empty( $post_data ) ) {
                    $response[] = $post_data;
                }
            }
        }
        return $response;
    }

    /**
     * Get single performer data.
     * 
     * @param int $post_id Post ID.
     * 
     * @return objact $performer Performer Data.
     */
    public function get_single_performer( $post_id, $post = null ) {
        if( empty( $post_id ) ) return;

        $performer = new stdClass();
        $meta = get_post_meta( $post_id );
        foreach ( $meta as $key => $val ) {
            $performer->{$key} = maybe_unserialize( $val[0] );
        }
        if( empty( $post ) ) {
            $post = get_post( $post_id );
        }
        if( ! empty( $post ) ) {
            $performer->id            = $post->ID;
            $performer->name          = $post->post_title;
            $performer->slug          = $post->post_name;
            $performer->description   = $post->post_content;
            //$performer->performer_url = ep_get_custom_page_url( 'performers_page', $performer->id, 'performer' );
            $performer->performer_url = $this->get_performer_single_url($performer->id);
            $performer->image_url     = $this->get_performer_image_url( $performer->id );
        }
        
        return $performer;
    }
    
    /**
     * Get Featured Performers
     */
    public function get_featured_event_performers( $args = array(), $number = -1 ) {
        $default = array(
            'post_type'        => $this->post_type,
            'post_status'      => 'publish',
            'numberposts'      => $number,
            'meta_query'       => array(    
                'relation'     => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 1,
                        'compare'  => '='
                    ),
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 'true',
                        'compare'  => '='
                    )
                ),
                array(
                    'key'      => 'em_is_featured',
                    'value'    => 1,
                    'compare'  => '='
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'      => 'em_status',
                        'compare'  => 'NOT EXISTS'
                    ),
                    array(
                        'key'      => 'em_status',
                        'value'    => 0,
                        'compare'  => '!='
                    ),
                ),
            )
        );
        $default = apply_filters( 'ep_performers_render_argument', $default, $args );
        $args = wp_parse_args( $args, $default );
        $posts = get_posts( $args );
        if( empty( $posts ) )
           return array();
       
        $performers = array();
        foreach( $posts as $post ) {
            $performer = $this->get_single_performer( $post->ID, $post );
            if( ! empty( $performer ) ) {
                $performers[] = $performer;
            }
        }

        $wp_query = new WP_Query( $args );
        $wp_query->posts = $performers;

        return $wp_query;
    }
    
    public function get_popular_event_performers($posts_per_page = 5, $featured = 0) {
        $args = array();
        if( $featured == 1 ) {
            $args = array(
                'meta_query'       => array(    
                    'relation'     => 'AND',
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 1,
                        'compare'  => '='
                    ),
                    array(
                        'key'   => 'em_is_featured',
                        'value' => 1
                    ),
                    array(
                    'relation' => 'OR',
                    array(
                        'key'      => 'em_status',
                        'compare'  => 'NOT EXISTS'
                    ),
                    array(
                        'key'      => 'em_status',
                        'value'    => 0,
                        'compare'  => '!='
                    ),
                ),
                    
                )
            );
        }
        $args = apply_filters( 'ep_performers_render_argument', $args, array() );
        $performers = $this->get_performers_post_data($args);
        $events_controller = new EventM_Event_Controller_List();
        $events = $events_controller->get_events_post_data();
        $event_count = array();
        if(isset($events->posts) && !empty($events->posts)){
            foreach($events->posts as $event){
                if(isset($event->em_performer) && !empty($event->em_performer)){
                    foreach($event->em_performer as $performer_id){
                        if(isset($event_count[$performer_id])){
                            $event_count[$performer_id] +=1;
                        }
                        else{
                            $event_count[$performer_id]= 1;
                        }
                    }
                }
            }
        }
        
        $p_performers = array();
        foreach($performers->posts as $performer){
            if(isset($event_count[$performer->id])){
                $performer->events= $event_count[$performer->id];
                $p_performers[] = $performer;
            } else{
                $performer->events=0;
            }
            
        }
        $p_performers = wp_list_sort( $p_performers , 'events', 'DESC',  false );
        if(count($p_performers) > $posts_per_page){
            $p_performers = array_slice($p_performers, 0, $posts_per_page);
        }
        $pp = new stdClass();
        $pp->posts = $p_performers;
   
        return $pp;
    }
    
    /**
     * Get post data
     */
    public function get_performers_post_data( $args = array() ) {
        $default = array(
            'orderby'          => 'title',
            'numberposts'      => -1,
            'offset'           => 0,     
            'order'            => 'ASC',
            'post_type'        => $this->post_type,
            'post_status'      => 'publish',
            'meta_query'       => array(    
                'relation'     => 'AND',
                array(
                  'relation'     => 'OR',
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 1,
                        'compare'  => '='
                    ),
                    array(
                        'key'      => 'em_display_front',
                        'value'    => 'true',
                        'compare'  => '='
                    ),  
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'      => 'em_status',
                        'compare'  => 'NOT EXISTS'
                    ),
                    array(
                        'key'      => 'em_status',
                        'value'    => 0,
                        'compare'  => '!='
                    ),
                ),
            )
        );
        $default = apply_filters( 'ep_performers_render_argument', $default, $args );
        $args = wp_parse_args( $args, $default );
        $posts = get_posts( $args );
        if( empty( $posts ) )
           return array();
       
        $performers = array();
        foreach( $posts as $post ) {
            $performer = $this->get_single_performer( $post->ID, $post );
            if( ! empty( $performer ) ) {
                $performers[] = $performer;
            }
        }

        $wp_query = new WP_Query( $args );
        $wp_query->posts = $performers;

        return $wp_query;
    }

    /**
     * Get upcoming events for single performer
     * 
     * @param int $performer_id Performer Id.
     * 
     * @param array $args Post Arguments.
     * 
     * @return object Post.
     */
    public function get_upcoming_events_for_performer( $performer_id, $args = array() ) {
        $hide_past_events = ep_get_global_settings( 'single_performer_hide_past_events' );
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
                        'key'     => 'em_performer',
                        'value'   =>  serialize( strval ( $performer_id ) ),
                        'compare' => 'LIKE'
                    ),
                    $past_events_meta_qry,
                    array(
                        'relation' => 'OR',
                        array(
                            'key'      => 'em_status',
                            'compare'  => 'NOT EXISTS'
                        ),
                        array(
                            'key'      => 'em_status',
                            'value'    => 0,
                            'compare'  => '!='
                        ),
                    ),
                )
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $filter = apply_filters( 'ep_performers_render_argument', $filter, $args );
        $args = wp_parse_args($args, $filter);
        $wp_query = new WP_Query( $args );
        $wp_query->performer_id = $performer_id;
        return $wp_query;
    }

    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        wp_enqueue_script(
            'ep-performer-views-js',
            EP_BASE_URL . '/includes/performers/assets/js/em-performer-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-performer-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        $settings                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $performers_settings                = $settings->ep_get_settings( 'performers' );
        $performers_data                    = array();
        $performers_data['display_style']   = isset( $atts['display_style'] ) ? $atts["display_style"] : $performers_settings->performer_display_view;
        $performers_data['limit']           = isset( $atts['limit'] ) ? ( empty( $atts['limit'] ) ? EP_PAGINATION_LIMIT : $atts['limit'] ) : ( empty( $performers_settings->performer_limit ) ? EP_PAGINATION_LIMIT : $performers_settings->performer_limit );
        $performers_data['column']          = isset( $atts['cols'] ) ? $atts['cols'] : $performers_settings->performer_no_of_columns;
        $performers_data['cols']            = isset( $atts['cols'] ) ? ep_check_column_size( $atts['cols'] ) : ep_check_column_size( $performers_settings->performer_no_of_columns );
        $performers_data['load_more']       = isset( $atts['load_more'] ) ? $atts['load_more'] : $performers_settings->performer_load_more;
        $performers_data['enable_search']   = isset( $atts['search'] ) ? $atts['search'] : $performers_settings->performer_search;
        $performers_data['featured']        = isset( $atts["featured"] ) ? $atts["featured"] : 0;
        $performers_data['popular']         = isset( $atts["popular"] ) ? $atts["popular"] : 0;
        $performers_data['orderby']         = isset( $atts["orderby"] ) ? $atts["orderby"] : 'date';
        if($performers_data['orderby'] == 'rand'){
            
            $performers_data['orderby'] = 'RAND('.rand().')';
        }
        $performers_data['box_color'] = '';
        $performers_data['box_color'] = '';
        if( $performers_data['display_style'] == 'box' || $performers_data['display_style'] == 'colored_grid' ) {
            $performers_data['box_color'] = ( isset( $atts["performer_box_color"] ) && ! empty( $atts["performer_box_color"] ) ) ? $atts["performer_box_color"] : $performers_settings->performer_box_color;
        }
        // set query arguments
        $paged     = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $performers_data['paged'] = $paged;
        $ep_search = isset( $_GET['ep_search'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
        $pargs     = array(
            'orderby'        => $performers_data['orderby'],
            'posts_per_page' => $performers_data['limit'],
            'offset'         => (int)( $paged - 1 ) * (int)$performers_data['limit'],
            'paged'          => $paged,
            's'              => $ep_search,
        );
        // if featured enabled then get featured performers
        if( $performers_data['featured'] == 1 && $performers_data['popular'] == 0) {
            $pargs['meta_query'] = array(
                'relation'     => 'AND',
                array(
                    'key'      => 'em_display_front',
                    'value'    => 1,
                    'compare'  => '='
                    ),
                array(
                    'key'   => 'em_is_featured',
                    'value' => 1
                )
            );
        }
        $performers_data['performers'] = $this->get_performers_post_data( $pargs );
        if( $performers_data['popular'] == 1 && $performers_data['featured'] == 0) {
            $performers_data['performers'] = $this->get_popular_event_performers($performers_data['limit']);
        }
        if( $performers_data['popular'] == 1 && $performers_data['featured'] == 1) {
            $performers_data['performers'] = $this->get_popular_event_performers($performers_data['limit'], $performers_data['featured']);
        }
        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'performers/list', null, (object)$performers_data );
        return ob_get_clean();
    }

    /**
     * Render detail page
     */
    public function render_detail_template( $atts = array() ) {
        wp_enqueue_style( 'ep-responsive-slides-css' );
        wp_enqueue_script( 'ep-responsive-slides-js' );
        wp_enqueue_script(
            'ep-performer-views-js',
            EP_BASE_URL . '/includes/performers/assets/js/em-performer-frontend-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-performer-views-js', 
            'ep_frontend', 
            array(
                '_nonce' => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        $atts                    = array_change_key_case( (array) $atts, CASE_LOWER );
        $performer_id            = absint( $atts['id'] );
        $post                    = get_post( $performer_id );
        $performers_data         = array();
        if( ! empty( $post ) ) {
            $performers_data['post'] = $post;
            $performers_data['performer'] = $this->get_single_performer( $post->ID );
            // upcoming events
            $performers_data['hide_upcoming_events'] = ep_get_global_settings( 'shortcode_hide_upcoming_events' );
            if ( isset( $atts['upcoming'] ) ) {
                $performers_data['hide_upcoming_events'] = 1;
                if ( 1 === $atts['upcoming'] ) {
                    $performers_data['hide_upcoming_events'] = 0;
                }
            }
            // check event limit
            if( isset( $atts['event_limit'] ) ){
                $single_performer_event_limit = ( $atts["event_limit"] == 0 || $atts["event_limit"] == '' ) ? EP_PAGINATION_LIMIT : $atts["event_limit"];
            } else{
                $single_performer_event_limit = ( ep_get_global_settings( 'single_performer_event_limit' ) == 0 ) ? EP_PAGINATION_LIMIT : ep_get_global_settings( 'single_performer_event_limit');
            }
            // check hide past events
            if( isset( $atts['hide_past_events'] ) ){
                $hide_past_events = $atts['hide_past_events'];
            } else{
                $hide_past_events = ep_get_global_settings( 'single_performer_hide_past_events' );
            }
            // get events
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            // get upcoming events for performer
            $args = array(
                'orderby'        => 'em_start_date_time',
                'posts_per_page' => $single_performer_event_limit,
                'offset'         => (int) ( $paged - 1 ) * (int)$single_performer_event_limit,
                'paged'          => $paged,
            );
            
            $args['post_status'] = !empty( $hide_past_events ) == 1 ? 'publish' : 'any';
            $performers_data['events'] = $this->get_upcoming_events_for_performer( $post->ID , $args);
            
            $event_args  = array();
            $event_args['show_events']      = ( isset( $atts['show_events'] ) ? $atts['show_events'] : ep_get_global_settings( 'single_performer_show_events' ) );
            $event_args['event_style']      = ( isset( $atts['event_style'] ) ? $atts['event_style'] : ep_get_global_settings( 'single_performer_event_display_view' ) );
            $event_args['event_limit']      = $single_performer_event_limit;
            $event_args['event_cols']       = ( isset( $atts['event_cols'] ) ? ep_check_column_size( $atts['event_cols'] ) : ep_check_column_size( ep_get_global_settings( 'single_performer_event_column' ) ) );
            $event_args['load_more']        = ( isset( $atts['load_more'] ) ? $atts['load_more'] : ep_get_global_settings( 'single_performer_event_load_more' ) );
            $event_args['hide_past_events'] = $hide_past_events;
            $event_args['paged']            = $paged;
            $performers_data['event_args']  = $event_args;
        }
        
        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'performers/single-performer', null, (object)$performers_data );
        return ob_get_clean();
    }
    
    /*
     * load more
     */
    public function get_event_performer_loadmore(){
        $settings                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $performers_settings                = $settings->ep_get_settings( 'performers' );
        $performers_data                    = array();
        $performers_data['display_style']   = isset( $_POST['display_style'] ) ? $_POST["display_style"] : $performers_settings->performer_display_view;
        $performers_data['limit']           = isset( $_POST['limit'] ) ? (empty($_POST["limit"]) ? EP_PAGINATION_LIMIT : $_POST["limit"]) : (empty($performers_settings->performer_limit) ? EP_PAGINATION_LIMIT : $performers_settings->performer_limit );
        $performers_data['cols']            = isset( $_POST['cols'] ) ? ep_check_column_size( $_POST['cols'] ) : ep_check_column_size( $performers_settings->performer_no_of_columns );
        $performers_data['load_more']       = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $performers_settings->performer_load_more;
        $performers_data['enable_search']   = isset( $_POST['search'] ) ? $_POST['search'] : $performers_settings->performer_search;
        $performers_data['featured']        = isset( $_POST["featured"] ) ? $_POST["featured"] : 0;
        $performers_data['popular']         = isset( $_POST["popular"] ) ? $_POST["popular"] : 0;
        $performers_data['orderby']         = isset( $_POST["orderby"] ) ? $_POST["orderby"] : 'date';
        $performers_data['box_color'] = '';
        if( $performers_data['display_style'] == 'box' || $performers_data['display_style'] == 'colored_grid' ) {
            $performers_data['box_color'] = ( isset( $_POST["box_color"] ) && ! empty( $_POST["box_color"] ) ) ? explode( ',', $_POST["box_color"] ) : $performers_settings->performer_box_color;
        }
        // set query arguments
        $paged     = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged++;
        $ep_search = isset( $_POST['ep_search'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        $pargs     = array(
            'orderby'        => $performers_data['orderby'],
            'posts_per_page' => $performers_data['limit'],
            'offset'         => (int)( $paged-1 ) * (int)$performers_data['limit'],
            'paged'          => $paged,
            's'              => $ep_search,
        );
        // if featured enabled then get featured performers
        if( $performers_data['featured'] == 1 && $performers_data['popular'] == 0) {
            $pargs['meta_query'] = array(
                'relation'     => 'AND',
                array(
                    'key'      => 'em_display_front',
                    'value'    => 1,
                    'compare'  => '='
                    ),
                array(
                    'key'   => 'em_is_featured',
                    'value' => 1,
                    'compare'=> '='
                )
            );
        }
        $performers_data['performers'] = $this->get_performers_post_data( $pargs );

        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'performers/list-load', null, (object)$performers_data );
        $data['html'] = ob_get_clean();
        $data['paged'] = $paged;
        return $data;
    }
    
    public function get_eventupcoming_performer_loadmore(){
        $settings            = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $performers_settings = $settings->ep_get_settings( 'performers' );
        $event_args  = $performers_data = array();
        $event_args['event_style']   = isset( $_POST['event_style'] ) ? $_POST["event_style"] : $performers_settings->single_performer_event_display_view;
        $event_args['event_limit']   = isset( $_POST['event_limit'] ) ? (empty($_POST["event_limit"]) ? EP_PAGINATION_LIMIT : $_POST["event_limit"]) : (empty($performers_settings->single_performer_event_limit) ? EP_PAGINATION_LIMIT : $performers_settings->single_performer_event_limit );
        $event_args['event_cols']    = isset( $_POST['event_cols'] ) ? $_POST['event_cols']  : ep_check_column_size( $performers_settings->single_performer_event_column );
        $event_args['load_more']     = isset( $_POST['load_more'] ) ? $_POST['load_more'] : $performers_settings->single_performer_event_load_more;
        $event_args['hide_past_events'] = isset( $_POST['hide_past_events'] ) ? $_POST['hide_past_events'] : $performers_settings->single_performer_hide_past_events;
        $event_args['post_id'] = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;
        
        // set query arguments
        $paged     = ( $_POST['paged'] ) ? $_POST['paged'] : 1;
        $paged++;
        $pargs     = array(
            'orderby'        => 'em_start_date_time',
            'posts_per_page' => $event_args['event_limit'],
            'offset'         => (int)( $paged-1 ) * (int)$event_args['event_limit'],
            'paged'          => $paged,
        );
        $performers_data['event_args']  = $event_args;
        
        $pargs['post_status'] = !empty( $event_args['hide_past_events'] ) == 1 ? 'publish' : 'any';
        $performers_data['events'] = $this->get_upcoming_events_for_performer( $event_args['post_id'] , $pargs);
        
        ob_start();
        wp_enqueue_style(
            'ep-performer-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
        ep_get_template_part( 'performers/single-performer/event-list-load', null, (object)$performers_data );
        $data['html'] = ob_get_clean();
        $data['paged'] = $paged;
        return $data;
    }
    /**
     * Render single post content
     */
    public function render_post_content() {
        wp_enqueue_style( 'ep-material-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), EVENTPRIME_VERSION );
        
        $atts['id'] = get_the_ID();
        return $this->render_detail_template( $atts );
    }
    
    public function insert_performer_post_data($post_data){
        $post_id = 0;
        if(!empty($post_data)){
            $title = isset($post_data['name']) ? sanitize_text_field($post_data['name']) : '';
            $description = isset($post_data['description']) ? $post_data['description'] : '';
            $status = isset($post_data['status']) ? $post_data['status'] : 'publish';
            $post_id = wp_insert_post(array (
                'post_type' => EM_PERFORMER_POST_TYPE,
                'post_title' => $title,
                'post_content' => $description,
                'post_status' => $status
             ));
            
        }
        
        if($post_id){
            $em_type = isset($post_data['em_type']) ? $post_data['em_type'] : '';
            $em_role = isset($post_data['em_role']) ? $post_data['em_role'] : '';
            $em_display_front = isset($post_data['em_display_front']) && !empty($post_data['em_display_front']) ? 1 : 0;
            $em_is_featured = isset($post_data['em_is_featured']) && !empty($post_data['em_is_featured']) ? 1 : 0;
            $em_social_links = isset($post_data['em_social_links']) ? $post_data['em_social_links'] : array();
            $em_performer_phones = isset($post_data['em_performer_phones']) ? $post_data['em_performer_phones'] : array();
            $em_performer_emails = isset($post_data['em_performer_emails']) ? $post_data['em_performer_emails'] : array();
            $em_performer_websites = isset($post_data['em_performer_websites']) ? $post_data['em_performer_websites'] : array();
            $em_performer_gallery = isset($post_data['em_performer_gallery']) ? $post_data['em_performer_gallery'] : array();
            
            $thumbnail_id = isset( $post_data['thumbnail'] ) ? $post_data['thumbnail'] : 0;
            set_post_thumbnail($post_id, $thumbnail_id);
            
            update_post_meta( $post_id, 'em_type', $em_type );
            update_post_meta( $post_id, 'em_role', $em_role );
            update_post_meta( $post_id, 'em_display_front', $em_display_front );
            update_post_meta( $post_id, 'em_is_featured', $em_is_featured );
            update_post_meta( $post_id, 'em_social_links', $em_social_links );
            update_post_meta( $post_id, 'em_performer_phones', $em_performer_phones );
            update_post_meta( $post_id, 'em_performer_emails', $em_performer_emails );
            update_post_meta( $post_id, 'em_performer_websites', $em_performer_websites );
            update_post_meta( $post_id, 'em_performer_gallery', $em_performer_gallery );
            if ( isset($post_data['em_status']) && !empty($post_data['em_type']) ) {
                update_post_meta( $post_id, 'em_status', 1 );
            }
        }
        return $post_id;
    }
    
    public function get_performer_image_url($performer_id){
        $image_url = EP_BASE_URL . 'includes/assets/images/dummy-user.png';
        if ( has_post_thumbnail( $performer_id ) ) {
            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $performer_id ), 'large' )[0];
        }
        return $image_url;
    }
    
    public function get_performer_single_url($performer_id){
        $performer_url = ep_get_custom_page_url( 'performers_page', $performer_id, 'performer' );
        $performer_url = apply_filters('ep_perfomers_url_modify', $performer_url, $performer_id);
        return $performer_url;
    }
}