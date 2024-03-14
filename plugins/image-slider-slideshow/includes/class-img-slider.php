<?php
/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks, internationalization, and
 * public-facing site hooks.
 *
 * 
 */
class IMG_Slider {
    

    private function load_dependencies() {

        require_once IMG_SLIDER_INCLUDES . 'libraries/class-img-slider-template-loader.php';
        require_once IMG_SLIDER_INCLUDES . 'helper/class-img-slider-helper.php';
        
        require_once IMG_SLIDER_INCLUDES . 'admin/class-img-slider-image.php';
       
        require_once IMG_SLIDER_INCLUDES . 'admin/class-img-slider-cpt.php';
       
        require_once IMG_SLIDER_INCLUDES . 'admin/class-img-slider-admin.php';

        require_once IMG_SLIDER_INCLUDES . 'public/class-img-slider-shortcode.php';

        require_once IMG_SLIDER_INCLUDES . 'scripts.php';


    }

    private function define_admin_hooks() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
        new Img_Slider_CPT();
    }

    private function define_public_hooks() {}
    
       
	/* Enqueue Admin Scripts */
	public function admin_scripts( $hook ) {

		global $id, $post;

        // Get current screen.
        $screen = get_current_screen();

        // Check if is Image Slider custom post type
        if ( 'img_slider' !== $screen->post_type ) {
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

	        $img_slider_helper = array(
	        	'items' => array(),
	        	'settings' => array(),
	        	'strings' => array(
	        		'limitExceeded' => sprintf( __( 'You excedeed the limit of 30 photos. You can remove an image or %supgrade to pro%s', 'img-slider' ), '<a href="#" target="_blank">', '</a>' ),
	        	),
	        	'id' => $post_id,
	        	'_wpnonce' => wp_create_nonce( 'img-slider-ajax-save' ),
	        	'ajax_url' => admin_url( 'admin-ajax.php' ),
	        );

	        // Get all items from current gallery.
	        $images = get_post_meta( $post_id, 'slider-images', true );
	        
	        if ( is_array( $images ) && ! empty( $images ) ) {
	        	foreach ( $images as $image ) {
	        		if ( ! is_numeric( $image['id'] ) ) {
	        			continue;
	        		}

	        		$attachment = wp_prepare_attachment_for_js( $image['id'] );
	        		$image_url  = wp_get_attachment_image_src( $image['id'], 'large' );
					$image_full = wp_get_attachment_image_src( $image['id'], 'full' );

					$image['full']        = $image_full[0];
					$image['thumbnail']   = $image_url[0];
					$image['orientation'] = $attachment['orientation'];

					$img_slider_helper['items'][] = $image;

	        	}
	        } 
	        else 
	        {   
	        	/*default image*/
	        	$img_slider_helper['items'] =  get_option('rpg-slider-images-default');
	        }

	        // Get current gallery settings.
	        $settings = get_post_meta( $post_id, 'img-slider-settings', true );
	        if ( is_array( $settings ) ) {
	        	$img_slider_helper['settings'] = wp_parse_args( $settings, Img_Slider_WP_CPT_Fields_Helper::get_defaults() );
	        }else{
	        	$img_slider_helper['settings'] = Img_Slider_WP_CPT_Fields_Helper::get_defaults();
	        }

			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_style( 'img-slider-cpt-',           IMG_SLIDER_ASSETS . 'css/portfolio-wp-cpt.css', null, IMG_SLIDER_CURRENT_VERSION );
			wp_enqueue_style( 'bootstrap-css', IMG_SLIDER_ASSETS . 'css/bootstrap.css', null, IMG_SLIDER_CURRENT_VERSION );
			
			/*fontawesome*/
			wp_enqueue_style('rpg-font-awesome-5.0.8', IMG_SLIDER_ASSETS.'css/font-awesome-latest/css/fontawesome-all.min.css');

			wp_enqueue_script( 'img-slider-resize-senzor', IMG_SLIDER_ASSETS . 'js/resizesensor.js', array( 'jquery' ), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-packery',       IMG_SLIDER_ASSETS . 'js/packery.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-draggable' ), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-settings',      IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-settings.js', array( 'jquery', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-sortable' ), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-save',          IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-save.js', array(), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-items',         IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-items.js', array(), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-modal',         IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-modal.js', array(), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-upload-media',        IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-upload.js', array(), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-gallery',       IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-gallery.js', array(), IMG_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'img-slider-conditions',    IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp-conditions.js', array(), IMG_SLIDER_CURRENT_VERSION, true );

			/*admin-script*/
			//wp_enqueue_script( 'admin-script-js',    IMG_SLIDER_ASSETS . 'js/admin_script.js', array(), IMG_SLIDER_CURRENT_VERSION, true );

			do_action( 'img_slider_scripts_before_img_slider' );

			wp_enqueue_script( 'img-slider', IMG_SLIDER_ASSETS . 'js/wp-portfolio-wp.js', array(), IMG_SLIDER_CURRENT_VERSION, true );
			wp_localize_script( 'img-slider', 'ImgSliderHelper', $img_slider_helper );

			do_action( 'img_slider_scripts_after_img_slider' );

		}
	}


    // loading language files
    public function img_slider_load_plugin_textdomain() {
        $rs = load_plugin_textdomain('img-slider', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }

    
    public function __construct() {
        
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

        
        //loading plugin translation files
        add_action('plugins_loaded', array($this, 'img_slider_load_plugin_textdomain'));

        if ( is_admin() ) {
            $plugin = plugin_basename(__FILE__);
            
        }
    }

}
