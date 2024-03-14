<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFields{

	protected static $instance;

	protected $config;

	protected function __construct(){
		$this->config = new apeGalleryFieldsConfig();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(){
		add_action('init', 					array($this, 'addMetaBoxes'));
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_filter('admin_body_class', 		array($this, 'adminBodyClass'));
	}

	public function addMetaBoxes(){
		foreach ((array)$this->config->get('metabox') as $name => $metaBoxConfig) {
			new apeGalleryFieldsMetaBoxClass($metaBoxConfig);
		}
	}

	public function enqueueScripts(){

		$screen = get_current_screen();
		if ('post' !== $screen->base) {
			return;
		}
		
		/* CSS */
		wp_enqueue_style( WPAPE_GALLERY_ASSETS_PREFIX.'app-style', 			WPAPE_GALLERY_FIELDS_URL . 'asset/core/css/app-style.css', array(), 1);
		
		wp_enqueue_style( WPAPE_GALLERY_ASSETS_PREFIX.'color-pick', 	WPAPE_GALLERY_FIELDS_URL . 'asset/vanilla-picker-master/src/picker.css', array(), 1);

		wp_enqueue_style( WPAPE_GALLERY_ASSETS_PREFIX.'help', 			WPAPE_GALLERY_FIELDS_URL . 'asset/help/help.css', array(), 1);
		
		/* JS */
		wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'foundation', 	WPAPE_GALLERY_FIELDS_URL . 'asset/foundation/foundation.min.js', array('jquery'), false, true);		

		wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'tinycolor', 	WPAPE_GALLERY_FIELDS_URL . 'asset/tinycolor/dist/tinycolor-min.js', array(), false, false);
		wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'color-pick', 	WPAPE_GALLERY_FIELDS_URL . 'asset/vanilla-picker-master/dist/vanilla-picker.min.js', array( WPAPE_GALLERY_ASSETS_PREFIX.'tinycolor' ), false, false);
		
		wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'app', 			WPAPE_GALLERY_FIELDS_URL . 'asset/core/js/app.js', array(WPAPE_GALLERY_ASSETS_PREFIX.'foundation'), false, true);
		
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		
		wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'help', 			WPAPE_GALLERY_FIELDS_URL . 'asset/help/help.js', array('jquery', 'jquery-ui-dialog'), false, true);
		$translation_array = array(
		    'close' => __( 'Close', 'plugin-domain' ),
		    'title' => __( 'Ape Gallery :: Help', 'plugin-domain' ),
		);
		wp_localize_script( WPAPE_GALLERY_ASSETS_PREFIX.'help', WPAPE_GALLERY_NAMESPACE.'fields_help_i18', $translation_array );
	}


	public function adminBodyClass($classes){
		return $classes . ' ' . WPAPE_GALLERY_FIELDS_BODY_CLASS;
	}

	public function getConfig(){
		return $this->config;
	}
}
