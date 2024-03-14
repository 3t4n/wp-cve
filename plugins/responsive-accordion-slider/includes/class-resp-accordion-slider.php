<?php
/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks, internationalization, and
 * public-facing site hooks.
 *
 * 
 */
class Resp_Accordion_Slider{

	public function __construct() {
        
		$this->resp_accordion_slider_load_dependencies();
		$this->resp_accordion_slider_define_admin_hooks();
		$this->resp_accordion_slider_define_public_hooks();

        
        //loading plugin translation files
        add_action('plugins_loaded', array($this, 'resp_accordion_slider_load_plugin_textdomain'));

        if ( is_admin() ) {
            $plugin = plugin_basename(__FILE__);
            
        }
    }    

    private function resp_accordion_slider_load_dependencies() {

        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'libraries/class-ras-template-loader.php';
        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'helper/class-ras-helper.php';
        
        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'admin/class-ras-image.php';
       
        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'admin/class-ras-cpt.php';
       
        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'admin/class-ras-admin.php';

        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'public/class-ras-shortcode.php';

        require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'ras-scripts.php';


    }

    private function resp_accordion_slider_define_admin_hooks() {
        add_action( 'activated_plugin',  array( $this, 'ras_redirect_after_activation' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'resp_accordion_slider_admin_scripts' ), 20 );
        new Resp_Accordion_Slider_CPT();
    }

    private function resp_accordion_slider_define_public_hooks() {}
    
    public function ras_redirect_after_activation( $file ) {
		if ( RESP_ACCORDION_SLIDER_BASENAME === $file ) {
			exit( esc_url( wp_safe_redirect( admin_url( 'edit.php?post_type=ras-accordion-slider&page=ras-premium' ) ) ) );
		}
	} 

	/* Enqueue Admin Scripts */
	public function resp_accordion_slider_admin_scripts( $hook ) {

		global $id, $post;

        // Get current screen.
        $screen = get_current_screen();

        // Check if is Image Slider custom post type
        if ( 'ras-accordion-slider' !== $screen->post_type ) {
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

	        $resp_accordion_slider_helper = array(
	        	'items' => array(),
	        	'settings' => array(),
	        	'strings' => array(
	        		'limitExceeded' => sprintf( __( 'You excedeed the limit of 25 photos. You can remove an image or %supgrade to pro%s', 'responsive-accordion-slider' ), '<a href="#" target="_blank">', '</a>' ),
	        	),
	        	'id' => $post_id,
	        	'_wpnonce' => wp_create_nonce( 'resp-accordion-slider-ajax-save' ),
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

					$resp_accordion_slider_helper['items'][] = $image;

	        	}
	        } 
	        else 
	        {   
	        	/*default image*/
	        	$resp_accordion_slider_helper['items'] =  get_option('ras-slider-images-default');
	        }

	        // Get current gallery settings.
	        $settings = get_post_meta( $post_id, 'ras-accordion-slider-settings', true );
	        if ( is_array( $settings ) ) {
	        	$resp_accordion_slider_helper['settings'] = wp_parse_args( $settings, RESP_ACCORDION_SLIDER_CPT_Fields_Helper::resp_accordion_slider_get_defaults() );
	        }else{
	        	$resp_accordion_slider_helper['settings'] = RESP_ACCORDION_SLIDER_CPT_Fields_Helper::resp_accordion_slider_get_defaults();
	        }

			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_style( 'ras-cpt-css',  RESP_ACCORDION_SLIDER_ASSETS_PATH . 'css/ras-cpt.css', null, RESP_ACCORDION_SLIDER_CURRENT_VERSION );
			wp_enqueue_style( 'ras-custom-css',  RESP_ACCORDION_SLIDER_ASSETS_PATH . 'css/ras-custom.css', null, RESP_ACCORDION_SLIDER_CURRENT_VERSION );
			wp_enqueue_style( 'bootstrap-css', RESP_ACCORDION_SLIDER_ASSETS_PATH . 'css/bootstrap.css', null, RESP_ACCORDION_SLIDER_CURRENT_VERSION );
			
			/*fontawesome*/
			wp_enqueue_style('rpg-font-awesome-5.0.8', RESP_ACCORDION_SLIDER_ASSETS_PATH.'css/font-awesome-latest/css/fontawesome-all.min.css');

			wp_enqueue_script( 'ras-resize-senzor-js', RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/resizesensor.js', array( 'jquery' ), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-packery-js',       RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/packery.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-draggable' ), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-settings-js',      RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-settings.js', array( 'jquery', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-sortable' ), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-save-js',          RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-save.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-items-js',         RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-items.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-modal-js',         RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-modal.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-upload-media-js',        RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-upload.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-gallery-js',       RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-gallery.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-conditions-js',    RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/ras-conditions.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'ras-custom-js', RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/custom.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true ); 

			do_action( 'ras_scripts_before_accordion_slider' );

			wp_enqueue_script( 'resp-accordion-slider', RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/accordion-slider.js', array(), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
			wp_localize_script( 'resp-accordion-slider', 'RespAccordionSliderHelper', $resp_accordion_slider_helper );

			do_action( 'ras_scripts_after_accordion_slider' );

		}
	}


    // loading language files
    public function resp_accordion_slider_load_plugin_textdomain() {
        $rs = load_plugin_textdomain('responsive-accordion-slider', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }

}