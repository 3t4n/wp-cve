<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class Timeline_WP {
    

    private function load_dependencies() {
        require_once TIMELINE_WP_INCLUDES . 'libraries/class-timeline-wp-template-loader.php';
        require_once TIMELINE_WP_INCLUDES . 'helper/class-timeline-wp-helper.php';
        require_once TIMELINE_WP_INCLUDES . 'admin/class-timeline-wp-cpt.php';
        require_once TIMELINE_WP_INCLUDES . 'public/class-timeline-wp-shortcode.php';
        require TIMELINE_WP_INCLUDES . 'gutenberg/gutenberg.php';
	}
    private function define_admin_hooks() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
        new Timeline_WP_CPT();
    }
    private function define_public_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 20 );
	}
    
    /*
     * Including required files
     */
    public function timeline_include_files() {

    }
    
	/* Enqueue Admin Scripts */
	public function admin_scripts( $hook ) {

		global $id, $post;

        // Get current screen.
		$screen = get_current_screen();

        // Check if is timeline_wp custom post type
        if ( 'timeline_wp' !== $screen->post_type ) {
            return;
        }

        // Set the post_id
        $post_id = isset( $post->ID ) ? $post->ID : (int) $id;

		if ( 'post-new.php' == $hook || 'post.php' == $hook ) {

			/* CPT Styles & Scripts */
			// Media Scripts
			wp_enqueue_media( array(
	            'post' => $post_id,
	        ) );

	        $timeline_wp_helper = array(
	        	'items' => array(),
	        	'settings' => array(),
	        	'strings' => array(
	        		'limitExceeded' => sprintf( __( 'You excedeed the limit of 20 photos. You can remove an image or %supgrade to pro%s', timeline_wp ), '<a href="#" target="_blank">', '</a>' ),
	        	),
	        	'id' => $post_id,
	        	'_wpnonce' => wp_create_nonce( 'timeline_wp-ajax-save' ),
	        	'ajax_url' => admin_url( 'admin-ajax.php' ),
	        );

	        // Get current gallery settings.
	        $settings = get_post_meta( $post_id, 'timeline-wp-settings', true );
	        if ( is_array( $settings ) ) {
	        	$timeline_wp_helper['settings'] = wp_parse_args( $settings, Timline_WP_CPT_Fields_Helper::get_defaults() );
	        }else{
	        	$timeline_wp_helper['settings'] = Timline_WP_CPT_Fields_Helper::get_defaults();
			}


			wp_enqueue_style('wp-editor');
			wp_enqueue_script('wp-tinymce');
			wp_enqueue_style('tinymce');
			wp_enqueue_script('word-count'); //if you want it
	
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( timeline_wp. '-bootstrap-main', 	TIMELINE_WP_RESOURCES	 . 'bootstrap/bootstrap.css', null, '4.3.1' );
			wp_enqueue_style( timeline_wp. '-bs-fontawesome', 	TIMELINE_WP_RESOURCES	 . 'fontawesome/css/fontawesome.min.css', null, '5.11.2' );
			wp_enqueue_style( timeline_wp. '-bs-iconpicker', 	TIMELINE_WP_RESOURCES	 . 'bootstrap-iconpicker/css/bootstrap-iconpicker.min.css', null, '1.10.0' );
			wp_enqueue_style( 'jquery-ui',                  	TIMELINE_WP_ASSETS		 . 'css/jquery-ui.min.css', null, TIMELINE_WP_CURRENT_VERSION );
			wp_enqueue_style( 'timeline-wp-cpt-',           	TIMELINE_WP_ASSETS		 . 'css/timeline-wp-cpt.css', null, TIMELINE_WP_CURRENT_VERSION );

			wp_enqueue_script( timeline_wp. '-popper', 			TIMELINE_WP_RESOURCES	 . 'bootstrap/js/popper.min.js', array( 'jquery' ), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( timeline_wp. '-bootstrap', 		TIMELINE_WP_RESOURCES	 . 'bootstrap/js/bootstrap.min.js', array( 'jquery' ), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( timeline_wp. '-bs-iconpicker', 	TIMELINE_WP_RESOURCES	 . 'bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js', array( 'jquery' ), '1.10.0', true );

			wp_enqueue_script( 'timeline-wp-resize-senzor', 	TIMELINE_WP_ASSETS		 . 'js/resizesensor.js', array( 'jquery' ), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-packery',       	TIMELINE_WP_ASSETS		 . 'js/packery.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-draggable' ), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-settings',      	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-settings.js', array( 'jquery', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-sortable' ), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-save',          	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-save.js', array(), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-items',         	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-items.js', array(), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-modal',         	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-modal.js', array(), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-upload',        	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-upload.js', array(), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-timeline',       	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-timeline.js', array(), TIMELINE_WP_CURRENT_VERSION, true );
			wp_enqueue_script( 'timeline-wp-conditions',    	TIMELINE_WP_ASSETS		 . 'js/wp-timeline-wp-conditions.js', array(), TIMELINE_WP_CURRENT_VERSION, true );

			do_action( 'timeline_wp_scripts_before_wp_timeline_wp' );

			// wp_enqueue_script( 'tinymce', 		TIMELINE_WP_ASSETS . 'js/tinymce.min.js', array( 'jquery' ), '5.1.3', true );
			wp_enqueue_script( 'timeline-wp', 	TIMELINE_WP_ASSETS . 'js/wp-timeline-wp.js', array( 'jquery' ), TIMELINE_WP_CURRENT_VERSION, true );
			wp_localize_script( 'timeline-wp', 'TimelineWPHelper', $timeline_wp_helper );

			do_action( 'timeline_wp_scripts_after_wp_timeline-wp' );

		}elseif ( 'timeline-wp-gallery_page_timeline-wp' == $hook ) {
			wp_enqueue_style( 'timeline-wp-welcome-style', 	TIMELINE_WP_ASSETS . 'css/welcome.css', null, TIMELINE_WP_CURRENT_VERSION );
		}elseif ( 'timeline-wp-gallery_page_timeline-wp-addons' == $hook ) {
			wp_enqueue_style( 'timeline-wp-welcome-style', 	TIMELINE_WP_ASSETS . 'css/addons.css', null, TIMELINE_WP_CURRENT_VERSION );
			wp_enqueue_script( 'timeline-wp-addon', 	TIMELINE_WP_ASSETS . 'js/timeline-wp-addon.js', array( 'jquery' ), TIMELINE_WP_CURRENT_VERSION, true );
		}elseif ( 'edit.php' == $hook  ) {
			wp_enqueue_script( 'timeline-wp-edit-screen', 	TIMELINE_WP_ASSETS . 'js/timeline-wp-edit.js', array(), TIMELINE_WP_CURRENT_VERSION, true );
		}

	}

	/* Enqueue Admin Scripts */
	public function front_scripts( $hook ) {
		wp_enqueue_style( 'timeline-fontawosome', TIMELINE_WP_RESOURCES . 'fontawesome/css/fontawesome.min.css', '', TIMELINE_WP_CURRENT_VERSION );
	}


    // loading language files
    public function timeline_load_plugin_textdomain() {
        $rs = load_plugin_textdomain( 'timeline', FALSE, basename( dirname(__FILE__) ) . '/languages/');
    }
    
    public function __construct() {
        
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

        
        // Including required files
        add_action('plugins_loaded', array($this, 'timeline_include_files'));      

        //loading plugin translation files
        add_action('plugins_loaded', array($this, 'timeline_load_plugin_textdomain'));

        if ( is_admin() ) {
            $plugin = plugin_basename(__FILE__);
        }
    }

}
