<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class Gallery_Images_Ape_Init {

	function __construct(){
		$this->init();
	}

	function init(){
		$this->defineConstants();
		$this->initPluginActivate();
		$this->load();
		$this->hooks();
	}

	private function defineConstants(){
		//define("WPAPE_GALLERY_FRONTEND_PATH", WPAPE_GALLERY_INCLUDES_PATH.'render/');
		define("WPAPE_GALLERY_URL_ADDONS", 		'https://wpape.net/open.php?type=gallery&action=premium');
		define("WPAPE_GALLERY_URL_UPDATEKEY", 	'https://wpape.net/open.php?type=gallery&action=updateKey');

		define("WPAPE_GALLERY_OFFER", 			0); 
		define("WPAPE_GALLERY_BUTTON_PREMIUM",  __('Premium Version', 'gallery-images-ape'));
		define("WPAPE_GALLERY_EDIT_POST_URL", 	'edit.php?post_type='.WPAPE_GALLERY_POST);
		define("WPAPE_GALLERY_ASSET", 			'gallery-images-ape-file-');
		
	}

	private function initPluginActivate(){
		register_activation_hook( 	WPAPE_GALLERY_FILE, 	array( $this, 'pluginActivate') 	);
		register_deactivation_hook( WPAPE_GALLERY_FILE, 	array( $this, 'pluginDeactivate') 	);
	}

	public function pluginActivate(){
		apeGalleryHelper::load('classActivator.php');
		ApeGalleryActivator::activate();
	}

	public function pluginDeactivate() {
		apeGalleryHelper::load('classActivator.php');
		ApeGalleryActivator::deactivate();
	}

	function load(){
		apeGalleryHelper::load( array( 'editor.button.php', 'gallery.copy.php', 'widget.php') );
		apeGalleryHelper::load('static.php');
		apeGalleryHelper::load('modules/setup/init.php');
	}

	function hooks(){
		add_action( 'init', 			array( $this, 'registerPostType') );
		
		add_action( 'plugins_loaded', 	array( $this, 'loadFiles') );
		add_action( 'admin_init', 		array( $this, 'optionsInit') );

		/*if( $this->getCurrentScreen()==WPAPE_GALLERY_POST ){
			if( !WPAPE_GALLERY_PREMIUM && apeGalleryHelper::check_new_edit_page('new')) add_action( 'load-post-new.php', array($this, 'redirect') );
		}*/

		if ( apeGalleryHelper::isAdminArea() === true ) {
			add_action( 'wp_ajax_ape_gallery_save_hide_wizard', array( $this, 'hideWizard') );
			add_filter( 'plugin_action_links', 					array( $this, 'plugin_actions_links'), 10, 2 );
		}
	}
	
	public function plugin_actions_links( $links, $file ) {
		static $plugin;

		if( 
			( $file == 'gallery-images-ape/index.php' || $file == 'ape-gallery/index.php' ) && 
			current_user_can('manage_options') 
		){
			array_unshift(
				$links,
				sprintf( '<a href="%s">%s</a>', $this->settings_page_url(), __( 'Settings' ) )
			);
		}
		return $links;
	}

	private function settings_page_url() {
		return esc_attr( admin_url('edit.php?post_type=wpape_gallery_type&page=wpape-gallery-settings') );
	}

	function hideWizard(){

		update_option( WPAPE_GALLERY_NAMESPACE.'hideWizard', 1 );

		$ajaxReturn = array(
    		'message'	=> null,
    		'action'	=> null,
    		'code'		=> null,
    		'ID'		=> null
		);
		echo 1;		
		//wp_send_json($return);
		die();
	}


	function registerPostType(){
		
		apeGalleryHelper::load('classUpdate.php');
		$update = new ApeGalleryUpdate();
		
		register_post_type( WPAPE_GALLERY_POST, array(
	        'labels' => array(
				'name' => 'Gallery Ape',
				'singular_name' => __( 'Gallery Ape', 		'gallery-images-ape' ),
				'all_items'     => __( 'Galleries', 		'gallery-images-ape' ),
				'add_new'       => __( 'New Gallery', 		'gallery-images-ape' ),
				'add_new_item'  => __( 'New Gallery', 		'gallery-images-ape' ),
				'edit_item'     => __( 'Edit Gallery', 		'gallery-images-ape' ),
	        ),
			'rewrite'         => array( 
				'slug' => 'ape_gallery', 
				'with_front' => true 
			),
			'public'=>true, 
			'has_archive'=>false, 
			'hierarchical'=>true,
			'supports'=>array('title','comments','page-attributes'),
			'menu_icon'=>'dashicons-palmtree',
	    ));

	    if( 
	    	apeGalleryHelper::isUserAdmin() && 
	    	get_option('ApeGalleryInstall', '')=='now' 
	    ){
			apeGalleryHelper::writeLog("run  initAfterInstall");
			add_action( 'wp_loaded', 			array( $this, 'initAfterInstall') );
		}
	}

	function initAfterInstall(){
		
		apeGalleryHelper::writeLog("run Flush");
		
		global $wp_rewrite;
		$wp_rewrite->flush_rules();

		delete_option( 'ApeGalleryInstall' );

		/*if( 
			!( 
				isset($_GET['post_type']) && 
				$_GET['post_type']== WPAPE_GALLERY_POST
			)
		){*/
	 		wp_redirect( admin_url('edit.php?post_type='.WPAPE_GALLERY_POST) );
	 		exit();
	 	/*}*/
	}


	function getCurrentScreen() {
        global $post, $typenow, $current_screen;
        if($post&&$post->post_type) return $post->post_type;
          elseif($typenow) return $typenow;
          elseif($current_screen && $current_screen->post_type ) return $current_screen->post_type;
          elseif(isset($_REQUEST['post_type'])) return sanitize_key( $_REQUEST['post_type'] );
          elseif(isset($_REQUEST['post']) && get_post_type($_REQUEST['post'])) return get_post_type($_REQUEST['post']);
        return null;
    }

    function redirect(){
		$page=1;$wpape_gallery=new WP_Query();++$page;
		$all_wp_pages=$wpape_gallery->query( array('post_type'=>WPAPE_GALLERY_POST, 'post_status' => array('any','trash')) );
		if(count($all_wp_pages)>=++$page){
			delete_option( 'gallery-images-ape-dialog' );
			add_option( 'gallery-images-ape-dialog', 1 );
			wp_redirect("edit.php?post_type=".WPAPE_GALLERY_POST."&dialogpremium=1");
		}
    }

	function loadFiles(){
		if($this->getCurrentScreen()==WPAPE_GALLERY_POST){
			if( apeGalleryHelper::check_new_edit_page('list') ) apeGalleryHelper::load('gallery.list.php');
			
			//if(!WPAPE_GALLERY_PREMIUM) apeGalleryHelper::load('gallery.banner.php');
			
			if(  
				apeGalleryHelper::check_new_edit_page('new') || 
				apeGalleryHelper::check_new_edit_page('edit') 
			){
				apeGalleryHelper::load('gallery.edit.php');
			}
		}



		if( apeGalleryHelper::isAdminArea( $allowAjax = 1 ) ){
			apeGalleryHelper::load(array('gallery.images.library.php', 'admin.menu.php' ));
		}
		
	}

	function optionsInit() {
		register_setting( 'wpape_gallery_settings', WPAPE_GALLERY_NAMESPACE.'jqueryVersion' );
		register_setting( 'wpape_gallery_settings', WPAPE_GALLERY_NAMESPACE.'delay' );	

		register_setting( 'wpape_gallery_settings_clone', WPAPE_GALLERY_NAMESPACE.'copyPrefix' );	
		register_setting( 'wpape_gallery_settings_clone', WPAPE_GALLERY_NAMESPACE.'copySuffix' );
		register_setting( 'wpape_gallery_settings_clone', WPAPE_GALLERY_NAMESPACE.'copyDate' );
		register_setting( 'wpape_gallery_settings_clone', WPAPE_GALLERY_NAMESPACE.'emptySlug' );

		register_setting( 'wpape_gallery_settings_source', WPAPE_GALLERY_NAMESPACE.'sourceGalleryEnable' );	
		register_setting( 'wpape_gallery_settings_source', WPAPE_GALLERY_NAMESPACE.'sourceGallery' );	
		register_setting( 'wpape_gallery_settings_source', WPAPE_GALLERY_NAMESPACE.'shortcode' );	
	}
}

