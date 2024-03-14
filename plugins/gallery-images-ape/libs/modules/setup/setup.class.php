<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

/*namespace apeGallerySetup;*/

if ( ! defined( 'WPINC' ) )  die;

class apeGallerySetup{

	public $api	= 'https://wpape.net/setup/update.php';
	public $slug = 'gallery-images-ape';

	function __construct(){
		add_action("plugins_loaded", array($this, 'init'));
	}

	public function init(){
		if( !current_user_can('manage_options') ) return ;		
		if( !current_user_can('administrator') ) return ;	

		if( isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-admin/plugins.php') !== false ){
			add_action('admin_footer',			array($this, 'popup') );
			add_action('admin_enqueue_scripts', array($this, 'includes') );
		}

		add_action('wp_ajax_ape_gallery_setup',	array($this, 'ape_gallery_setup') );
	}
	
	public function includes(){
		
		wp_enqueue_script('ape_gallery_setup-js', plugin_dir_url( __FILE__ ) . 'js/setup.js', array('jquery'), false, true );		
		wp_enqueue_style('ape_gallery_setup-css', plugin_dir_url( __FILE__ ) . 'css/setup.css', false, '1.0', 'all');
		
		wp_localize_script('ape_gallery_setup-js', 'ape_gallery_setup',  array(
				'slug'		=> $this->slug,				
				'skip'		=> __('Skip & Deactivate','gallery-images-ape'),
				'submit'	=> __('Submit & Deactivate','gallery-images-ape'),
				'ajax_nonce' =>  wp_create_nonce( 'wpape_setup_ajax_nonce' ),
		));
		
	}
	
	private function deactivateApeGalery(){		
		$pluginName = 'gallery-images-ape/index.php';		
		if( is_plugin_active($pluginName) ){
			deactivate_plugins( $pluginName );
			return ;
		}

		$pluginName = 'ape-gallery/index.php';
		if( is_plugin_active($pluginName) ){
			deactivate_plugins( $pluginName );
			return ;	
		}
	}


	public function ape_gallery_setup(){

		if( !current_user_can('manage_options') ) return ;		
		if( !current_user_can('administrator') ) return ;

		check_ajax_referer('wpape_setup_ajax_nonce');

		if( isset( $_POST['plugin'] ) ){
			$this->deactivateApeGalery();	
		}
		
		if( isset( $_POST['check'] ) ){
			$message = '';
			if( isset($_POST['ape_gallery_setup-msg-better-plugin']) && $_POST['ape_gallery_setup-msg-better-plugin'] )  $message .= 'Plugin:'.$_POST['ape_gallery_setup-msg-better-plugin'].'|';
			if( isset($_POST['ape_gallery_setup-msg-other']) && $_POST['ape_gallery_setup-msg-other'] )  $message .= 'Other:'.$_POST['ape_gallery_setup-msg-other'].'|';
			$this->remoteGet( $_POST['check'], $message );
		}
		
		wp_die();
	}
	
	private function remoteGet( $check, $msg = '' ){
		if(!is_callable('wp_remote_get')) return ;
		
		$args = array(
			'body' => array( 'check'=> $check, 'msg' => $msg )
		);

		$response = wp_remote_get( $this->api, $args );
		/*var_dump($response);*/
		die();				
	}
	
	public function popup(){
		include_once dirname(__FILE__) . '/tpl/popup.php';
	}

}