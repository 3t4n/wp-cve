<?php
namespace GSLOGO;
use function GSLOGOPRO\is_plugin_loaded;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

final class Builder {

    private $option_name = 'gs_logo_slider_shortcode_prefs';

    public function __construct() {
        
        add_action( 'admin_menu', array( $this, 'register_sub_menu') );
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts') );
        add_action( 'wp_enqueue_scripts', array( $this, 'preview_scripts') );

        add_action( 'wp_ajax_gslogo_create_shortcode', array($this, 'create_shortcode') );
        add_action( 'wp_ajax_gslogo_clone_shortcode', array($this, 'clone_shortcode') );
        add_action( 'wp_ajax_gslogo_get_shortcode', array($this, 'get_shortcode') );
        add_action( 'wp_ajax_gslogo_update_shortcode', array($this, 'update_shortcode') );
        add_action( 'wp_ajax_gslogo_delete_shortcodes', array($this, 'delete_shortcodes') );
        add_action( 'wp_ajax_gslogo_temp_save_shortcode_settings', array($this, 'temp_save_shortcode_settings') );
        add_action( 'wp_ajax_gslogo_get_shortcodes', array($this, 'get_shortcodes') );

        add_action( 'wp_ajax_gslogo_get_shortcode_pref', array($this, 'get_shortcode_pref') );
        add_action( 'wp_ajax_gslogo_save_shortcode_pref', array($this, 'save_shortcode_pref') );

        add_action( 'template_include', array($this, 'populate_shortcode_preview') );
        add_action( 'show_admin_bar', array($this, 'hide_admin_bar_from_preview') );

        return $this;

    }

    public static function is_gslogo_shortcode_preview() {

        return isset( $_REQUEST['gslogo_shortcode_preview'] ) && !empty($_REQUEST['gslogo_shortcode_preview']);

    }

    public function hide_admin_bar_from_preview( $visibility ) {

        if ( $this->is_gslogo_shortcode_preview() ) return false;

        return $visibility;

    }

    public function add_shortcode_body_class( $classes ) {

        if ( $this->is_gslogo_shortcode_preview() ) return array_merge( $classes, array( 'gslogo-shortcode-preview--page' ) );

        return $classes;

    }

    public function populate_shortcode_preview( $template ) {

        global $wp, $wp_query;
        
        if ( $this->is_gslogo_shortcode_preview() ) {

            // Create our fake post
            $post_id = rand( 1, 99999 ) - 9999999;
            $post = new \stdClass();
            $post->ID = $post_id;
            $post->post_author = 1;
            $post->post_date = current_time( 'mysql' );
            $post->post_date_gmt = current_time( 'mysql', 1 );
            $post->post_title = __('Shortcode Preview', 'gslogo');
            $post->post_content = '[gslogo preview="yes" id="'. esc_attr( sanitize_text_field( $_REQUEST['gslogo_shortcode_preview'] ) ) .'"]';
            $post->post_status = 'publish';
            $post->comment_status = 'closed';
            $post->ping_status = 'closed';
            $post->post_name = 'fake-page-' . rand( 1, 99999 ); // append random number to avoid clash
            $post->post_type = 'page';
            $post->filter = 'raw'; // important!

            // Convert to WP_Post object
            $wp_post = new \WP_Post( $post );

            // Add the fake post to the cache
            wp_cache_add( $post_id, $wp_post, 'posts' );

            // Update the main query
            $wp_query->post = $wp_post;
            $wp_query->posts = array( $wp_post );
            $wp_query->queried_object = $wp_post;
            $wp_query->queried_object_id = $post_id;
            $wp_query->found_posts = 1;
            $wp_query->post_count = 1;
            $wp_query->max_num_pages = 1; 
            $wp_query->is_page = true;
            $wp_query->is_singular = true; 
            $wp_query->is_single = false; 
            $wp_query->is_attachment = false;
            $wp_query->is_archive = false; 
            $wp_query->is_category = false;
            $wp_query->is_tag = false; 
            $wp_query->is_tax = false;
            $wp_query->is_author = false;
            $wp_query->is_date = false;
            $wp_query->is_year = false;
            $wp_query->is_month = false;
            $wp_query->is_day = false;
            $wp_query->is_time = false;
            $wp_query->is_search = false;
            $wp_query->is_feed = false;
            $wp_query->is_comment_feed = false;
            $wp_query->is_trackback = false;
            $wp_query->is_home = false;
            $wp_query->is_embed = false;
            $wp_query->is_404 = false; 
            $wp_query->is_paged = false;
            $wp_query->is_admin = false; 
            $wp_query->is_preview = false; 
            $wp_query->is_robots = false; 
            $wp_query->is_posts_page = false;
            $wp_query->is_post_type_archive = false;


            // Update globals
            $GLOBALS['wp_query'] = $wp_query;
            $wp->register_globals();


            include GSL_PLUGIN_DIR . 'includes/shortcode-builder/preview.php';

            return;

        }

        return $template;

    }

    public function register_sub_menu() {

        add_submenu_page( 
            'edit.php?post_type=gs-logo-slider', 'Logo Shortcode', 'Logo Shortcode', 'manage_options', 'gs-logo-shortcode', array( $this, 'view' )
        );

        add_submenu_page( 
            'edit.php?post_type=gs-logo-slider', 'Install Demo', 'Install Demo', 'manage_options', 'gs-logo-shortcode#/demo-data', array( $this, 'view' )
        );

    }

    public function view() {

        include_once GSL_PLUGIN_DIR . 'includes/shortcode-builder/page.php';
    }

    public function get_logo_categories() {

        $_terms = get_terms( 'logo-category' );

        $terms = [];

        foreach ( $_terms as $term ) {
            $terms[] = [
                'label' => $term->name,
                'value' => $term->slug
            ];
        }

        return $terms;
    }

    public function scripts( $hook ) {

        if ( 'gs-logo-slider_page_gs-logo-shortcode' != $hook ) return;

        wp_register_style( 'gs-zmdi-fonts', GSL_PLUGIN_URI . 'assets/libs/material-design-iconic-font/css/material-design-iconic-font.min.css', '', GSL_VERSION, 'all' );

        if ( ! is_pro_active() || ! is_plugin_loaded() ) {
            wp_register_style( 'gs-logo-shortcode', GSL_PLUGIN_URI . 'assets/admin/css/gs-logo-shortcode.min.css', array('gs-zmdi-fonts'), GSL_VERSION, 'all' );
            wp_register_script( 'gs-logo-shortcode', GSL_PLUGIN_URI . 'assets/admin/js/gs-logo-shortcode.min.js', array('jquery'), GSL_VERSION, true );
        }

        do_action( 'gs_logo_register_scripts' );

        wp_localize_script( 'gs-logo-shortcode', '_gslogo_data', $this->get_localized_data() );

        wp_enqueue_style( 'gs-logo-shortcode' );
        wp_enqueue_script( 'gs-logo-shortcode' );
        
    }

    public function get_localized_data() {

        $data = array(
            "nonce" => array(
                "create_shortcode" 		        => wp_create_nonce( "_gslogo_create_shortcode_gs_" ),
                "clone_shortcode" 		        => wp_create_nonce( "_gslogo_clone_shortcode_gs_" ),
                "update_shortcode" 	            => wp_create_nonce( "_gslogo_update_shortcode_gs_" ),
                "delete_shortcodes" 	        => wp_create_nonce( "_gslogo_delete_shortcodes_gs_" ),
                "temp_save_shortcode_settings" 	=> wp_create_nonce( "_gslogo_temp_save_shortcode_settings_gs_" ),
                "save_shortcode_pref" 	        => wp_create_nonce( "_gslogo_save_shortcode_pref_gs_" ),
                "import_gslogo_demo" 	        => wp_create_nonce( "_gslogo_simport_gslogo_demo_gs_" ),
            ),
            "ajaxurl" => admin_url( "admin-ajax.php" ),
            "adminurl" => admin_url(),
            "siteurl" => home_url()
        );

        $data['shortcode_settings'] = $this->get_shortcode_default_settings();
        $data['shortcode_options']  = $this->get_shortcode_default_options();
        $data['translations']       = $this->get_translation_srtings();
        $data['preference']         = $this->get_shortcode_default_prefs();
        $data['preference_options'] = $this->get_shortcode_prefs_options();

        $data['demo_data'] = [
            'logo_data'      => wp_validate_boolean( get_option('gslogo_dummy_logo_data_created') ),
            'shortcode_data' => wp_validate_boolean( get_option('gslogo_dummy_shortcode_data_created') )
        ];

        return $data;
    }

    public function preview_scripts() {
        
        if ( ! $this->is_gslogo_shortcode_preview() ) return;

        wp_enqueue_style( 'gs-logo-shortcode-preview', GSL_PLUGIN_URI . 'assets/css/gs-logo-shortcode-preview.min.css', '', GSL_VERSION, 'all' );
        
    }

    public function get_wpdb() {

        global $wpdb;
        
        if ( wp_doing_ajax() ) $wpdb->show_errors = false;

        return $wpdb;

    }

    public function check_db_error() {

        $wpdb = $this->get_wpdb();

        if ( $wpdb->last_error === '') return false;

        return true;

    }

    public function validate_shortcode_settings( $shortcode_settings ) {
        $shortcode_settings = shortcode_atts( $this->get_shortcode_default_settings(), $shortcode_settings );
        return array_map( 'sanitize_text_field', $shortcode_settings );
    }

    protected function get_shortcode_db_columns() {

        return array(
            'shortcode_name' => '%s',
            'shortcode_settings' => '%s',
            'created_at' => '%s',
            'updated_at' => '%s',
        );

    }

    public function _get_shortcode( $shortcode_id, $is_ajax = false ) {

        if ( empty($shortcode_id) ) {
            if ( $is_ajax ) wp_send_json_error( __('Shortcode ID missing', 'gslogo'), 400 );
            return false;
        }

        $wpdb = $this->get_wpdb();

        $shortcode = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}gs_logo_slider WHERE id = %d LIMIT 1", absint($shortcode_id) ), ARRAY_A );

        if ( $shortcode ) {
            $shortcode["shortcode_settings"] = json_decode( $shortcode["shortcode_settings"], true );
            $shortcode["shortcode_settings"] = $this->validate_shortcode_settings( $shortcode["shortcode_settings"] );
            if ( $is_ajax ) wp_send_json_success( $shortcode );
            return $shortcode;
        }

        if ( $is_ajax ) wp_send_json_error( __('No shortcode found', 'gslogo'), 404 );

        return false;

    }

    public function _update_shortcode( $shortcode_id, $nonce, $fields, $is_ajax ) {

        if ( ! wp_verify_nonce( $nonce, '_gslogo_update_shortcode_gs_') ) {

            if ( $is_ajax ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );
            return false;

        }

        if ( empty($shortcode_id) ) {

            if ( $is_ajax ) wp_send_json_error( __('Shortcode ID missing', 'gslogo'), 400 );
            return false;

        }
    
        $_shortcode = $this->_get_shortcode( $shortcode_id, false );
    
        if ( empty($_shortcode) ) {
            if ( $is_ajax ) wp_send_json_error( __('No shortcode found to update', 'gslogo'), 404 );
            return false;
        }
    
        $shortcode_name = !empty( $fields['shortcode_name'] ) ? sanitize_text_field( $fields['shortcode_name'] ) : $_shortcode['shortcode_name'];
        $shortcode_settings  = !empty( $fields['shortcode_settings']) ? $fields['shortcode_settings'] : $_shortcode['shortcode_settings'];

        // Remove dummy indicator on update
        if ( isset($shortcode_settings['gslogo-demo_data']) ) unset($shortcode_settings['gslogo-demo_data']);
    
        $shortcode_settings = $this->validate_shortcode_settings( $shortcode_settings );
    
        $wpdb = $this->get_wpdb();
    
        $data = array(
            "shortcode_name" 	    => $shortcode_name,
            "shortcode_settings" 	=> json_encode($shortcode_settings),
            "updated_at" 		    => current_time( 'mysql')
        );
    
        $num_row_updated = $wpdb->update( "{$wpdb->prefix}gs_logo_slider" , $data, array( 'id' => absint( $shortcode_id ) ),  $this->get_shortcode_db_columns() );

        if ( $this->check_db_error() ) {
            if ( $is_ajax ) wp_send_json_error( sprintf( __( 'Database Error: %1$s', 'gslogo'), $wpdb->last_error), 500 );
            return false;
        }

        // Delete the shortcode cache
        wp_cache_delete( 'gs_logo_shortcodes', 'gs_logo_slider' );

        do_action( 'gs_logo_shortcode_updated', $num_row_updated );
        do_action( 'gsp_shortcode_updated', $num_row_updated );
    
        if ($is_ajax) wp_send_json_success( array(
            'message' => __('Shortcode updated', 'gslogo'),
            'shortcode_id' => $num_row_updated
        ));
    
        return $num_row_updated;

    }
    
    public function _get_shortcodes( $shortcode_ids = [], $is_ajax = false, $minimal = false ) {

        $wpdb = $this->get_wpdb();
        $fields = $minimal ? 'id, shortcode_name' : '*';

        if ( !empty($shortcode_ids) ) {

            $how_many = count($shortcode_ids);
            $placeholders = array_fill(0, $how_many, '%d');
            $format = implode(', ', $placeholders);
            $query = "SELECT {$fields} FROM {$wpdb->prefix}gs_logo_slider WHERE id IN($format)";
            
            $shortcodes = $wpdb->get_results( $wpdb->prepare($query, $shortcode_ids), ARRAY_A );

        } else {

            $shortcodes = wp_cache_get( 'gs_logo_shortcodes', 'gs_logo_slider' );

            if ( !empty($shortcodes) ) {
                if ( $is_ajax ) wp_send_json_success( $shortcodes );
                return $shortcodes;
            }

            $shortcodes = $wpdb->get_results( "SELECT {$fields} FROM {$wpdb->prefix}gs_logo_slider ORDER BY id DESC", ARRAY_A );

        }

        // check for database error
        if ( $this->check_db_error() ) wp_send_json_error(sprintf(__('Database Error: %s'), $wpdb->last_error));

        if ( empty($shortcode_ids) ) wp_cache_set( 'gs_logo_shortcodes', $shortcodes, 'gs_logo_slider', DAY_IN_SECONDS );

        if ( $is_ajax ) wp_send_json_success( $shortcodes );

        return $shortcodes;

    }

    public function create_shortcode() {

        // validate nonce && check permission
        if ( !check_admin_referer('_gslogo_create_shortcode_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

        $shortcode_settings  = !empty( $_POST['shortcode_settings'] ) ? $this->validate_shortcode_settings( $_POST['shortcode_settings'] ) : '';
        $shortcode_name  = !empty( $_POST['shortcode_name']) ? sanitize_text_field( $_POST['shortcode_name'] ) : __('Undefined', 'gslogo');

        if ( empty($shortcode_settings) || !is_array($shortcode_settings) ) {
            wp_send_json_error( __('Please configure the settings properly', 'gslogo'), 206 );
        }

        $wpdb = $this->get_wpdb();

        $data = array(
            "shortcode_name" => $shortcode_name,
            "shortcode_settings" => json_encode($shortcode_settings),
            "created_at" => current_time( 'mysql'),
            "updated_at" => current_time( 'mysql'),
        );

        $wpdb->insert( "{$wpdb->prefix}gs_logo_slider", $data, $this->get_shortcode_db_columns() );

        // check for database error
        if ( $this->check_db_error() ) wp_send_json_error( sprintf(__('Database Error: %s'), $wpdb->last_error), 500 );

        // Delete the shortcode cache
        wp_cache_delete( 'gs_logo_shortcodes', 'gs_logo_slider' );

        do_action( 'gs_logo_shortcode_created', $wpdb->insert_id );
        do_action( 'gsp_shortcode_created', $wpdb->insert_id );

        // send success response with inserted id
        wp_send_json_success( array(
            'message' => __('Shortcode created successfully', 'gslogo'),
            'shortcode_id' => $wpdb->insert_id
        ));
    }

    public function clone_shortcode() {

        // validate nonce && check permission
        if ( !check_admin_referer('_gslogo_clone_shortcode_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

        $clone_id  = !empty( $_POST['clone_id']) ? (int) $_POST['clone_id'] : '';

        if ( empty($clone_id) ) wp_send_json_error( __('Clone Id not provided', 'gslogo'), 400 );

        $clone_shortcode = $this->_get_shortcode( $clone_id, false );

        if ( empty($clone_shortcode) ) wp_send_json_error( __('Clone shortcode not found', 'gslogo'), 404 );


        $shortcode_settings  = $clone_shortcode['shortcode_settings'];
        $shortcode_name  = $clone_shortcode['shortcode_name'] .' '. __('- Cloned', 'gslogo');

        $shortcode_settings = $this->validate_shortcode_settings( $shortcode_settings );

        $wpdb = $this->get_wpdb();

        $data = array(
            "shortcode_name" => $shortcode_name,
            "shortcode_settings" => json_encode($shortcode_settings),
            "created_at" => current_time( 'mysql'),
            "updated_at" => current_time( 'mysql'),
        );

        $wpdb->insert( "{$wpdb->prefix}gs_logo_slider", $data, $this->get_shortcode_db_columns() );

        // check for database error
        if ( $this->check_db_error() ) wp_send_json_error( sprintf(__('Database Error: %s'), $wpdb->last_error), 500 );

        // Delete the shortcode cache
        wp_cache_delete( 'gs_logo_shortcodes', 'gs_logo_slider' );

        do_action( 'gs_logo_shortcode_created', $wpdb->insert_id );
        do_action( 'gsp_shortcode_created', $wpdb->insert_id );

        // Get the cloned shortcode
        $shotcode = $this->_get_shortcode( $wpdb->insert_id, false );

        // send success response with inserted id
        wp_send_json_success( array(
            'message' => __('Shortcode cloned successfully', 'gslogo'),
            'shortcode' => $shotcode,
        ));
    }

    public function get_shortcode() {

        $shortcode_id = !empty( $_GET['id']) ? absint( $_GET['id'] ) : null;

        $this->_get_shortcode( $shortcode_id, wp_doing_ajax() );

    }

    public function update_shortcode( $shortcode_id = null, $nonce = null ) {

        if ( ! $shortcode_id ) {
            $shortcode_id = !empty( $_POST['id']) ? (int) $_POST['id'] : null;
        }
        
        if ( ! $nonce ) {
            $nonce = $_POST['_wpnonce'] ?: null;
        }

        $this->_update_shortcode( $shortcode_id, $nonce, $_POST, true );

    }

    public function delete_shortcodes() {

        if ( !check_admin_referer('_gslogo_delete_shortcodes_gs_') || !current_user_can('manage_options') )
            wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

        $ids = isset( $_POST['ids'] ) ? array_map( 'absint', $_POST['ids'] ) : null;

        if ( empty( $ids ) ) {
            wp_send_json_error( __('No shortcode ids provided', 'gslogo'), 400 );
        }

        $wpdb = $this->get_wpdb();

        $count = count( $ids );
        $ids_format = implode( ', ', array_fill( 0, $count, '%d' ) );

        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}gs_logo_slider WHERE ID IN($ids_format)", $ids ) );

        // Delete the shortcode cache
        wp_cache_delete( 'gs_logo_shortcodes', 'gs_logo_slider' );

        do_action( 'gs_logo_shortcode_deleted' );
        do_action( 'gsp_shortcode_deleted' );

        if ( $this->check_db_error() ) wp_send_json_error( sprintf(__('Database Error: %s'), $wpdb->last_error), 500 );

        $m = _n( "Shortcode has been deleted", "Shortcodes have been deleted", $count, 'gslogo' ) ;

        wp_send_json_success( ['message' => $m] );

    }

    public function get_shortcodes() {

        $this->_get_shortcodes( null, wp_doing_ajax() );

    }

    public function temp_save_shortcode_settings() {

        if ( !check_admin_referer('_gslogo_temp_save_shortcode_settings_gs_') || !current_user_can('manage_options') )
            wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );
        
        $temp_key = isset( $_POST['temp_key'] ) ? sanitize_text_field( $_POST['temp_key'] ) : null;
        $shortcode_settings = isset( $_POST['shortcode_settings'] ) ? $this->validate_shortcode_settings( $_POST['shortcode_settings'] ) : null;

        if ( empty($temp_key) ) wp_send_json_error( __('No temp key provided', 'gslogo'), 400 );
        if ( empty($shortcode_settings) ) wp_send_json_error( __('No temp settings provided', 'gslogo'), 400 );

        delete_transient( $temp_key );
        set_transient( $temp_key, $shortcode_settings, DAY_IN_SECONDS ); // save the transient for 1 day

        wp_send_json_success([
            'message' => __('Temp data saved', 'gslogo'),
        ]);

    }

    public function get_translation_srtings() {
        return [

            'gs-l-theme' => __('Style & Theming', 'gslogo'),
            'gs-l-theme--placeholder' => __('Select Theme', 'gslogo'),
            'gs-l-theme--help' => __('Select preferred Style & Theme', 'gslogo'),
            
            'disable_lazy_load' => __('Disable Lazy Load', 'gslogo'),
            'disable_lazy_load-details' => __('Disable Lazy Load for logos', 'gslogo'),
            
            'lazy_load_class' => __('Lazy Load Class', 'gslogo'),
            'lazy_load_class-details' => __('Add class to disable lazy loading, multiple classes should be separated by space', 'gslogo'),

            'anchor_tag_rel' => __( 'Anchor Tag rel', 'gslogo' ),
            'anchor_tag_rel--details' => __( 'Select Anchor Tag rel attribute\'s value, to improve security and SEO, by default the value is dofollow.', 'gslogo' ),

            'image-size' => __('Image Size', 'gslogo'),
            'image-size--placeholder' => __('Select Size', 'gslogo'),
            'image-size--help' => __('Select the attachment size from the registered sources', 'gslogo'),

            'custom-image-size' => __('Custom Image Size', 'gslogo'),
            'custom-image-size-width--placeholder' => __('Width', 'gslogo'),
            'custom-image-size-height--placeholder' => __('Height', 'gslogo'),
            'custom-image-size--help' => __('Set width and height of the logo image.', 'gslogo'),

            'gs-l-slide-speed' => __('Sliding Speed', 'gslogo'),
            'gs-l-slide-speed--help' => __('Set the speed in millisecond. Default 500 ms. To disable autoplay just set the speed 0', 'gslogo'),
            
            'gs-l-is-autop' => __('Autoplay', 'gslogo'),
            'gs-l-play-pause--help' => __('Enable/Disable Auto play to change the slides automatically after certain time. Default On', 'gslogo'),

            'gs-l-autop-pause' => __('Autoplay Delay', 'gslogo'),
            'gs-l-autop-pause--help' => __('You can adjust the time (in ms) between each slide. Default 4000 ms', 'gslogo'),

            'gs-l-inf-loop' => __('Infinite Loop', 'gslogo'),
            'gs-l-inf-loop--help' => __('If ON, clicking on "Next" while on the last slide will start over from first slide and vice-versa', 'gslogo'),

            'gs-l-slider-stop' => __('Pause on hover', 'gslogo'),
            'gs-l-slider-stop--help' => __('Autoplay will pause when mouse hovers over Logo. Default On', 'gslogo'),

            'gs-reverse-direction' => __('Reverse Direction', 'gslogo'),
            'gs-reverse-direction--help' => __('Reverse the direction of movement. Default Off', 'gslogo'),

            'gs-l-stp-tkr' => __('Pause on Hover', 'gslogo'),
            'gs-l-stp-tkr--help' => __('Ticker will pause when mouse hovers over slider. Note: this functionality does NOT work if using CSS transitions!. Default Off', 'gslogo'),
            
            'gs-l-ctrl' => __('Slider Navs', 'gslogo'),
            'gs-l-ctrl--help' => __('Next / Previous control for Logo slider. Default On Controls are not available when Ticker Mode is enabled', 'gslogo'),
            
            'gs-l-ctrl-pos' => __('Navs Position', 'gslogo'),
            'gs-l-ctrl-pos--placeholder' => __('Navs Position', 'gslogo'),
            'gs-l-ctrl-pos--help' => __('Position of Next / Previous control for Logo slider. Default Bottom', 'gslogo'),

            'gs-l-pagi' => __('Slider Dots', 'gslogo'),
            'gs-l-pagi--help' => __('Dots control for logo slider below the widget. Default Off', 'gslogo'),

            'gs-l-pagi-dynamic' => __('Dynamic Dots', 'gslogo'),
            'gs-l-pagi-dynamic--help' => __('Good to enable if you use for many slides. So it will keep only few dots visible at the same time. Default On', 'gslogo'),

            'gs-l-play-pause' => __('Pause on Hover', 'gslogo'),
            'gs-l-play-pause--help' => __('Pause on mouse hover over the logo slider. Default Off', 'gslogo'),

            'gs-l-title' => __('Logo Title', 'gslogo'),
            'gs-l-title--help' => __('Display Logo including / excluding Title. Default Off', 'gslogo'),

            'show-cat' => __('Logo Category', 'gslogo'),
            'show-cat--help' => __('Display Logo including / excluding Category. Default Off', 'gslogo'),

            'gs-l-tooltip' => __('Tooltip', 'gslogo'),
            'gs-l-tooltip--help' => __('Enable / disable Tooltip option.', 'gslogo'),


            'gs-l-gray' => __('Logo style', 'gslogo'),
            'gs-l-gray--help' => __('Logo grayscale feature works only in modern browsers.. like Chrome, Firefox and Safari', 'gslogo'),

            'gs-l-align' => __( 'Alignment', 'gslogo' ),
            'gs-l-align--help' => __( 'Horizonal Alignment of Grid Items', 'gslogo' ),

            'gs-l-margin' => __('Logo Space (px)', 'gslogo'),
            'gs-l-margin--help' => __('Increase / decrease Margin between each Logo. Default 10, max 50.', 'gslogo'),

            'gs-l-min-logo' => __('Desktop Logos', 'gslogo'),
            'gs-l-min-logo--help' => __('The minimum number of logos to be shown. Default 5, max 10. (Theme : Slider1,Fullwith slider,Center Mode, Ticker)', 'gslogo'),

            'gs-l-tab-logo' => __('Tablet Logos', 'gslogo'),
            'gs-l-tab-logo--help' => __('The minimum number of logos to be shown. Default 3, max 10. (Theme : Slider1,Fullwith slider,Center Mode,2 Rows Slider, Ticker)', 'gslogo'),

            'gs-l-mob-logo' => __('Mobile Logos', 'gslogo'),
            'gs-l-mob-logo--help' => __('The minimum number of logos to be shown. Default 2, max 10. (Theme : Slider1,Fullwith slider,Center Mode,2 Rows Slider, Ticker)', 'gslogo'),

            'gs-l-move-logo' => __('Move Logos', 'gslogo'),
            'gs-l-move-logo--help' => __('The number of logos to move on transition. Default 1, max 10.', 'gslogo'),

            'gs-logo-filter-name' => __('All Filter Name', 'gslogo'),
            'gs-logo-filter-name--placeholder' => __('All', 'gslogo'),
            'gs-logo-filter-name--help' => __('Replace preferred text instead of "All" for Filter Theme.', 'gslogo'),

            'gs-logo-filter-align' => __('Filter Name Align', 'gslogo'),
            'gs-logo-filter-align--placeholder' => __('Filters Align', 'gslogo'),
            'gs-logo-filter-align--help' => __('Filter Categories alignment for Filter Theme.', 'gslogo'),

            'gs-l-clkable' => __('Clickable Logos', 'gslogo'),
            'gs-l-clkable--help' => __('Specify target to open the Links, Default New Tab', 'gslogo'),

            'row_heading_image' => __( 'Image Heading', 'gslogo' ),
            'row_heading_image--placeholder' => __( 'Image', 'gslogo' ),

            'row_heading_name' => __( 'Name Heading', 'gslogo' ),
            'row_heading_name--placeholder' => __( 'Name', 'gslogo' ),

            'row_heading_desc' => __( 'Description Heading', 'gslogo' ),
            'row_heading_desc--placeholder' => __( 'Description', 'gslogo' ),

            'posts' => __('Logos', 'gslogo'),
            'posts--placeholder' => __('Logos', 'gslogo'),
            'posts--help' => __('Set max logo numbers you want to show, set -1 for all logos', 'gslogo'),

            'order' => __('Order', 'gslogo'),
            'order--placeholder' => __('Order', 'gslogo'),

            'order-by' => __('Order By', 'gslogo'),
            'order-by--placeholder' => __('Order By', 'gslogo'),

            'logo-cat' => __('Categories', 'gslogo'),
            'logo-cat--placeholder' => __('Categories', 'gslogo'),
            'logo-cat--help' => __('Select specific logo category to show that specific category logos', 'gslogo'),

            'install-demo-data' => __('Install Demo Data', 'gslogo'),
            'install-demo-data-description' => __('Quick start with GS Plugins by installing the demo data', 'gslogo'),

            'preference' => __('Preference', 'gslogo'),
            'save-preference' => __('Save Preference', 'gslogo'),
            
            'custom-css' => __('Custom CSS', 'gslogo'),

            'shortcodes' => __('Shortcodes', 'gslogo'),
            'global-settings-for-gs-logo-slider' => __('Global Settings for GS Logo Slider', 'gslogo'),
            'all-shortcodes-for-gs-logo-slider' => __('All shortcodes for GS Logo Slider', 'gslogo'),
            'create-shortcode' => __('Create Shortcode', 'gslogo'),
            'create-new-shortcode' => __('Create New Shortcode', 'gslogo'),
            'shortcode' => __('Shortcode', 'gslogo'),
            'name' => __('Name', 'gslogo'),
            'action' => __('Action', 'gslogo'),
            'actions' => __('Actions', 'gslogo'),
            'edit' => __('Edit', 'gslogo'),
            'clone' => __('Clone', 'gslogo'),
            'delete' => __('Delete', 'gslogo'),
            'delete-all' => __('Delete All', 'gslogo'),
            'create-a-new-shortcode-and' => __('Create a new shortcode & save it to use globally in anywhere', 'gslogo'),
            'edit-shortcode' => __('Edit Shortcode', 'gslogo'),
            'general-settings' => __('General Settings', 'gslogo'),
            'style-settings' => __('Style Settings', 'gslogo'),
            'query-settings' => __('Query Settings', 'gslogo'),
            'shortcode-name' => __('Shortcode Name', 'gslogo'),
            'name-of-the-shortcode' => __('Name of the Shortcode', 'gslogo'),
            'save-shortcode' => __('Save Shortcode', 'gslogo'),
            'preview-shortcode' => __('Preview Shortcode', 'gslogo')

        ];
        
    }

    public function get_shortcode_options_themes() {

        $free_themes = [
            [
                'label' => __( 'Slider 1', 'gslogo' ),
                'value' => 'slider1'
            ],
            [
                'label' => __( 'Grid - 1', 'gslogo' ),
                'value' => 'grid1'
            ],
            [
                'label' => __( 'List - 1', 'gslogo' ),
                'value' => 'list1'
            ],
            [
                'label' => __( 'Table - 1', 'gslogo' ),
                'value' => 'table1'
            ],
        ];

        $pro_themes = [
            [
                'label' => __( 'Ticker 1', 'gslogo' ),
                'value' => 'ticker1'
            ],
            [
                'label' => __( 'Grid - 2', 'gslogo' ),
                'value' => 'grid2'
            ],
            [
                'label' => __( 'Grid - 3', 'gslogo' ),
                'value' => 'grid3'
            ],
            [
                'label' => __( 'List - 2', 'gslogo' ),
                'value' => 'list2'
            ],
            [
                'label' => __( 'List - 3', 'gslogo' ),
                'value' => 'list3'
            ],
            [
                'label' => __( 'List - 4', 'gslogo' ),
                'value' => 'list4'
            ],
            [
                'label' => __( 'Table - 2', 'gslogo' ),
                'value' => 'table2'
            ],
            [
                'label' => __( 'Table - 3', 'gslogo' ),
                'value' => 'table3'
            ],
            [
                'label' => __( 'Vertical Slider', 'gslogo' ),
                'value' => 'vslider1'
            ],
            [
                'label' => __( 'Filter - 1', 'gslogo' ),
                'value' => 'filter1'
            ],
            [
                'label' => __( 'Filter - 2', 'gslogo' ),
                'value' => 'filter2'
            ],
            [
                'label' => __( 'Filter - 3', 'gslogo' ),
                'value' => 'filter3'
            ],
            [
                'label' => __( 'Live Filter - 1', 'gslogo' ),
                'value' => 'filterlive1'
            ],
            [
                'label' => __( 'Live Filter - 2', 'gslogo' ),
                'value' => 'filterlive2'
            ],
            [
                'label' => __( 'Live Filter - 3', 'gslogo' ),
                'value' => 'filterlive3'
            ],
            [
                'label' => __( 'Filter - Selected Cats', 'gslogo' ),
                'value' => 'filter-select'
            ],
            [
                'label' => __( 'Full Width Slider', 'gslogo' ),
                'value' => 'slider_fullwidth'
            ],
            [
                'label' => __( 'Center Mode', 'gslogo' ),
                'value' => 'center'
            ],
            [
                'label' => __( 'Variable Width', 'gslogo' ),
                'value' => 'vwidth'
            ],
            [
                'label' => __( 'Vertical Center', 'gslogo' ),
                'value' => 'verticalcenter'
            ],
            [
                'label' => __( 'Vertical Ticker Up', 'gslogo' ),
                'value' => 'verticalticker'
            ],
            [
                'label' => __( 'Vertical Ticker Down', 'gslogo' ),
                'value' => 'verticaltickerdown'
            ],
            [
                'label' => __( '2 Rows Slider', 'gslogo' ),
                'value' => 'slider-2rows'
            ],
        ];

        if ( ! is_pro_active() || ! is_plugin_loaded() || ! gs_logo_pro_is_valid() ) {
            $pro_themes = array_map( function( $item ) {
                $item['pro'] = true;
                return $item;
            }, $pro_themes);
        }

        return array_merge( $free_themes, $pro_themes );

    }

    public function get_shortcode_options_image_sizes() {

        $sizes = get_intermediate_image_sizes();

        if ( empty($sizes) ) return [];

        $_sizes = array_map( function($size) {
            $label = preg_replace('/_|-/', ' ', $size);
            return [
                'label' => ucwords($label),
                'value' => $size
            ];
        }, $sizes );

        $_sizes[] = [
            'label' => __('Set Custom Size', 'gslogo'),
            'value' => 'custom'
        ];

        return $_sizes;

    }

    public function get_shortcode_default_options() {

        return [

            'gs_l_clkable' => [
                [
                    'label' => __( 'New Tab', 'gslogo' ),
                    'value' => '_blank'
                ],
                [
                    'label' => __( 'Same Window', 'gslogo' ),
                    'value' => '_self'
                ],
            ],

            'custom_image_size_crop' => [
                [
                    'label' => __( 'Hard Crop', 'gslogo' ),
                    'value' => 'hard-crop'
                ],
                [
                    'label' => __( 'Soft Crop', 'gslogo' ),
                    'value' => 'soft-crop'
                ],
            ],

            'gs_l_ctrl_pos' => [
                [
                    'label' => __( 'Bottom', 'gslogo' ),
                    'value' => 'bottom'
                ],
                [
                    'label' => __( 'Left Right', 'gslogo' ),
                    'value' => 'left-right'
                ],
                [
                    'label' => __( 'Left Right Outside', 'gslogo' ),
                    'value' => 'left-right-out'
                ],
            ],

            'gs_l_theme' => $this->get_shortcode_options_themes(),

            'image_size' => $this->get_shortcode_options_image_sizes(),

            'gs_l_gray' => [
                [
                    'label' => __( 'Default', 'gslogo' ),
                    'value' => 'default',
                ],
                [
                    'label' => __( 'Grayscale', 'gslogo' ),
                    'value' => 'gray',
                ],
                [
                    'label' => __( 'Gray to Default', 'gslogo' ),
                    'value' => 'gray_to_def',
                ],
                [
                    'label' => __( 'Default to Gray', 'gslogo' ),
                    'value' => 'def_to_gray',
                ]
            ],

            'gs_l_align' => [
                [
                    'label' => __( 'Left', 'gslogo' ),
                    'value' => 'flex-start',
                ],
                [
                    'label' => __( 'Center', 'gslogo' ),
                    'value' => 'center',
                ],
                [
                    'label' => __( 'Right', 'gslogo' ),
                    'value' => 'flex-end',
                ]
            ],

            'gs_logo_filter_align' => [
                [
                    'label' => __( 'Center', 'gslogo' ),
                    'value' => 'center',
                ],
                [
                    'label' => __( 'Left', 'gslogo' ),
                    'value' => 'left',
                ],
                [
                    'label' => __( 'Right', 'gslogo' ),
                    'value' => 'right',
                ]
            ],

            'logo_cat' => $this->get_logo_categories(),

            'orderby' => [
                [
                    'label' => __( 'Custom Order', 'gslogo' ),
                    'value' => 'menu_order'
                ],
                [
                    'label' => __( 'Logo ID', 'gslogo' ),
                    'value' => 'ID'
                ],
                [
                    'label' => __( 'Logo Name', 'gslogo' ),
                    'value' => 'title'
                ],
                [
                    'label' => __( 'Date', 'gslogo' ),
                    'value' => 'date'
                ],
                [
                    'label' => __( 'Random', 'gslogo' ),
                    'value' => 'rand'
                ],
            ],

            'order' => [
                [
                    'label' => __( 'DESC', 'gslogo' ),
                    'value' => 'DESC'
                ],
                [
                    'label' => __( 'ASC', 'gslogo' ),
                    'value' => 'ASC'
                ],
            ],

        ];
        
    }

    public function get_shortcode_default_prefs() {
        return [
            'gs_logo_slider_custom_css' => '',
            'disable_lazy_load' => 'off',
            'lazy_load_class' => 'skip-lazy',
            'anchor_tag_rel' => 'noopener'
        ];
    }

    public function get_shortcode_prefs_options() {
        return [
            'anchor_tag_rel' => [
                [
                    'label' => __( 'nofollow', 'gslogo' ),
                    'value' => 'nofollow'
                ],
                [
                    'label' => __( 'noopener', 'gslogo' ),
                    'value' => 'noopener'
                ],
                [
                    'label' => __( 'noreferrer', 'gslogo' ),
                    'value' => 'noreferrer'
                ],
                [
                    'label' => __( 'nofollow noopener', 'gslogo' ),
                    'value' => 'nofollow noopener'
                ],
                [
                    'label' => __( 'nofollow noreferrer', 'gslogo' ),
                    'value' => 'nofollow noreferrer'
                ],
                [
                    'label' => __( 'noopener noreferrer', 'gslogo' ),
                    'value' => 'noopener noreferrer'
                ],
                [
                    'label' => __( 'nofollow noopener noreferrer', 'gslogo' ),
                    'value' => 'nofollow noopener noreferrer'
                ],
            ]
        ];
    }

    function get_shortcode_default_settings() {
        return [
            'posts' 	               => -1,
            'order'		               => 'DESC',
            'orderby'                  => 'date',
            'gs_l_title'               => 'on',
            'logo_cat'	               => '',
            'gs_l_ctrl'                => 'on',
            'gs_l_ctrl_pos'            => 'bottom',
            'gs_l_pagi'                => 'off',
            'gs_l_pagi_dynamic'        => 'on',
            'gs_l_play_pause'          => 'off',
            'gs_l_inf_loop'	           => 'on',
            'gs_l_slider_stop'         => 'on',
            'gs_l_tooltip' 	           => 'off',
            'gs_l_slide_speed'		   => 500,
            'gs_l_autop_pause'         => 2000,
            'gs_l_theme'		       => 'slider1',
            'image_size'               => 'medium',
            'custom_image_size_width'  => '',
            'custom_image_size_height' => '',
            'custom_image_size_crop'   => 'hard-crop',
            'gs_l_clkable'             => '_blank',
            'gs_l_is_autop'            => 'on',
            'gs_reverse_direction'     => 'off',
            'gs_l_gray'                => 'default',
            'gs_l_align'               => 'center',
            'gs_l_margin'              => 10,
            'gs_l_min_logo'            => 5,
            'gs_l_tab_logo'            => 3,
            'gs_l_mob_logo'            => 2,
            'gs_l_move_logo'           => 1,
            'gs_logo_filter_name'      => 'All',
            'gs_logo_filter_align'     => 'center',
            'show_cat'                 => 'on',
            'row_heading_image'        => 'Image',
            'row_heading_name'         => 'Name',
            'row_heading_desc'         => 'Description',
        ];
    }

    public function _save_shortcode_pref( $nonce, $settings, $is_ajax ) {

        if ( ! wp_verify_nonce( $nonce, '_gslogo_save_shortcode_pref_gs_') ) {
            if ( $is_ajax ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );
            return false;
        }

        // Maybe add validation?
        update_option( $this->option_name, $settings, 'yes' );
        
        // Clean permalink flush
        delete_option( 'GS_Logo_Slider_plugin_permalinks_flushed' );

        do_action( 'gs_logo_preference_update' );

        do_action( 'gsp_preference_update' );
    
        if ( $is_ajax ) wp_send_json_success( __('Preference saved', 'gslogo') );

    }

    public function save_shortcode_pref( $nonce = null ) {

        if ( ! $nonce ) {
            $nonce = wp_create_nonce('_gslogo_save_shortcode_pref_gs_');
        }

        if ( empty($_POST['prefs']) ) {
            wp_send_json_error( __('No preference provided', 'gslogo'), 400 );
        }

        $prefs = $this->validate_preference( $_POST['prefs'] );

        $this->_save_shortcode_pref( $nonce, $prefs, true );

    }

    public function validate_preference( $settings ) {
        $settings['gs_logo_slider_custom_css'] = wp_strip_all_tags( $settings['gs_logo_slider_custom_css'] );
        $settings['disable_lazy_load']         = sanitize_text_field( $settings['disable_lazy_load'] );
        $settings['lazy_load_class']           = sanitize_text_field( $settings['lazy_load_class'] );
        return $settings;
    }

    public function _get_shortcode_pref( $is_ajax ) {

        $pref = get_option( $this->option_name );

        if ( empty($pref) ) {
            $pref = $this->get_shortcode_default_prefs();
            $this->_save_shortcode_pref( wp_create_nonce('_gslogo_save_shortcode_pref_gs_'), $pref, false );
        }

        if ( $is_ajax ) {
            wp_send_json_success( $pref );
        }

        return $pref;

    }

    public function get_shortcode_pref() {

        return $this->_get_shortcode_pref( wp_doing_ajax() );

    }

    static function maybe_create_shortcodes_table() {

        global $wpdb;

        $gs_logo_slider_db_version = '1.0';

        if ( get_option("{$wpdb->prefix}gs_logo_slider_db_version") == $gs_logo_slider_db_version ) return; // vail early
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gs_logo_slider (
            id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
            shortcode_name TEXT NOT NULL,
            shortcode_settings LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (id)
        )".$wpdb->get_charset_collate().";";
            
        if ( get_option("{$wpdb->prefix}gs_logo_slider_db_version") < $gs_logo_slider_db_version ) {
            dbDelta( $sql );
        }

        update_option( "{$wpdb->prefix}gs_logo_slider_db_version", $gs_logo_slider_db_version );
        
    }

    public function create_dummy_shortcodes() {

        $request = wp_remote_get( GSL_PLUGIN_URI . 'includes/demo-data/shortcodes.json', array('sslverify' => false) );

        if ( is_wp_error($request) ) return false;

        $shortcodes = wp_remote_retrieve_body( $request );

        $shortcodes = json_decode( $shortcodes, true );

        $wpdb = $this->get_wpdb();

        if ( ! $shortcodes || ! count($shortcodes) ) return;

        foreach ( $shortcodes as $shortcode ) {

            $shortcode['shortcode_settings'] = json_decode( $shortcode['shortcode_settings'], true );
            $shortcode['shortcode_settings']['gslogo-demo_data'] = true;

            $data = array(
                "shortcode_name" => $shortcode['shortcode_name'],
                "shortcode_settings" => json_encode($shortcode['shortcode_settings']),
                "created_at" => current_time( 'mysql'),
                "updated_at" => current_time( 'mysql'),
            );

            $wpdb->insert( "{$wpdb->prefix}gs_logo_slider", $data, $this->get_shortcode_db_columns() );

        }

        wp_cache_delete( 'gs_logo_shortcodes' );

    }

    public function delete_dummy_shortcodes() {

        $wpdb = $this->get_wpdb();

        $string = 'gslogo-demo_data';

        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}gs_logo_slider WHERE shortcode_settings like '%%s%'", $string ) );

        // Delete the shortcode cache
        wp_cache_delete( 'gs_logo_shortcodes', 'gs_logo_slider' );

    }
}