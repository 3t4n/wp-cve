<?php
/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks, internationalization, and
 * public-facing site hooks.
 *
 * 
 */

class Photo_Gallery {
    

    private function load_dependencies() {

        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'libraries/class-photo-gallery-template-loader.php';
        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'helper/class-photo-gallery-helper.php';
        
        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'admin/class-photo-gallery-image.php';
        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'public/photo-gallery-helper-functions.php';

        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'admin/class-photo-gallery-helper-cpt.php';
       
        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'admin/class-photo-gallery-admin.php';

        require_once PHOTO_GALLERY_BUILDER_INCLUDES . 'public/class-photo-gallery-shortcode.php';


    }
    private function define_admin_hooks() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
        new Photo_Gallery_CPT();
    }
    private function define_public_hooks() {}
    
       
	/* Enqueue Admin Scripts */
	public function admin_scripts( $hook ) {

		global $id, $post;

        // Get current screen.
        $screen = get_current_screen();

        // Check if is Photo Gallery custom post type
        if ( 'pg_builder' !== $screen->post_type ) {
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

	        $photo_gallery_helper = array(
	        	'items' => array(),
	        	'settings' => array(),
	        	'strings' => array(
	        		'limitExceeded' => sprintf( __( 'You excedeed the limit of 30 photos. You can remove an image or %supgrade to pro%s', 'photo-gallery-builder' ), '<a href="#" target="_blank">', '</a>' ),
	        	),
	        	'id' => $post_id,
	        	'_wpnonce' => wp_create_nonce( 'photo-gallery-ajax-save' ),
	        	'ajax_url' => admin_url( 'admin-ajax.php' ),
	        );

	        // Get all items from current gallery.
	        $images = get_post_meta( $post_id, 'photo-gallery-images', true );
	        
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

					$photo_gallery_helper['items'][] = $image;

	        	}
	        } 
	        else 
	        {   
	        	/*default image*/
	        	$photo_gallery_helper['items'] =  get_option('pgb-photo-gallery-images-default');
	        }

	        // Get current gallery settings.
	        $settings = get_post_meta( $post_id, 'photo-gallery-settings', true );
	        if ( is_array( $settings ) ) {
	        	$photo_gallery_helper['settings'] = wp_parse_args( $settings, Photo_Gallery_CPT_Fields_Helper::get_defaults() );
	        }else{
	        	$photo_gallery_helper['settings'] = Photo_Gallery_CPT_Fields_Helper::get_defaults();
	        }

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'jquery-ui',                  PHOTO_GALLERY_BUILDER_ASSETS . 'css/jquery-ui.min.css', null, PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
			wp_enqueue_style( 'photo-gallery-cpt-css',           PHOTO_GALLERY_BUILDER_ASSETS . 'css/photo-gallery-cpt.css', null, PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
			wp_enqueue_style( 'bootstrap-back-css', PHOTO_GALLERY_BUILDER_ASSETS . 'css/bootstrap-back.css', null, PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
			
			/*fontawesome*/
			wp_enqueue_style('pgb-font-awesome-5.0.8', PHOTO_GALLERY_BUILDER_ASSETS.'css/font-awesome-latest/css/fontawesome-all.min.css');

			wp_enqueue_script( 'photo-gallery-resize-senzor', PHOTO_GALLERY_BUILDER_ASSETS . 'js/resizesensor.js', array( 'jquery' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-packery',       PHOTO_GALLERY_BUILDER_ASSETS . 'js/packery.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-draggable' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-settings',      PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-settings.js', array( 'jquery', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-sortable' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-save',          PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-save.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-items',         PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-items.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-modal',         PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-modal.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-upload-media',        PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-upload.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-gallery',       PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-gallery.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_enqueue_script( 'photo-gallery-conditions',    PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery-conditions.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );

			do_action( 'photo-gallery_scripts_before_wp_photo-gallery' );

			wp_enqueue_script( 'photo-gallery', PHOTO_GALLERY_BUILDER_ASSETS . 'js/wp-photo-gallery.js', array(), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
			wp_localize_script( 'photo-gallery', 'PhotoGalleryHelper', $photo_gallery_helper );

			do_action( 'photo-gallery_scripts_after_wp_photo-gallery' );

		}
	}


    // loading language files
    public function photo_gallery_load_plugin_textdomain() {
        $rs = load_plugin_textdomain('photo-gallery-builder', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }

    
    public function __construct() {
        
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

        
        //loading plugin translation files
        add_action('plugins_loaded', array($this, 'photo_gallery_load_plugin_textdomain'));

        if ( is_admin() ) {
            $plugin = plugin_basename(__FILE__);
            
        }
    }

}
