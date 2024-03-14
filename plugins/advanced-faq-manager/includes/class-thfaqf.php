<?php
if(!defined('WPINC')){ die; }

if(!class_exists('THFAQF')):

class THFAQF{
	public function __construct() {
		$this->define_constants();
		$this->load_dependencies();
		$this->admin_hooks_settings();
		$this->public_hooks_settings();
	}

    private function define_constants(){
		!defined('THFAQF_ASSETS_URL_ADMIN') && define('THFAQF_ASSETS_URL_ADMIN', THFAQF_ASSETS_URL. 'admin/');
		!defined('THFAQF_ASSETS_URL_PUBLIC') && define('THFAQF_ASSETS_URL_PUBLIC', THFAQF_ASSETS_URL. 'public/');
	}

	private function load_dependencies(){
		if(!function_exists('is_plugin_active')){
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		require_once THFAQF_PATH . 'includes/class-thfaqf-utils.php';
		require_once THFAQF_PATH . 'includes/admin/class-thfaqf-admin-settings.php';
		require_once THFAQF_PATH . 'includes/admin/class-thfaqf-admin-settings-faq.php';
		require_once THFAQF_PATH . 'includes/admin/class-thfaqf-admin-settings-general.php';
		require_once THFAQF_PATH . 'includes/admin/class-thfaqf-admin.php';
		require_once THFAQF_PATH . 'includes/public/class-thfaqf-public.php';
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function admin_hooks_settings(){
		$status = THFAQF_Utils::check_premium_plugn_actve();
		if($status == 'deactivate'){
			$this->define_admin_hooks();
		}
	}
	
	private function define_admin_hooks(){
		$plugin_admin = new THFAQF_Admin();
		add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_styles_and_scripts'));
		add_action('admin_menu', array($plugin_admin, 'admin_menu'));
		add_filter('plugin_action_links_'.THFAQF_BASE_NAME, array($plugin_admin, 'plugin_action_links'));

		$post_faq = new THFAQF_Admin_Settings_FAQ();
		add_action('init', array($post_faq, 'init'));
		add_action('add_meta_boxes', array($post_faq, 'metabox_section')); 
		add_action('save_post', array($post_faq, 'save_faq_postdata'), 10, 3);
        add_filter('manage_posts_columns', array($post_faq,'add_custom_column'));
        add_action('manage_posts_custom_column', array($post_faq, 'add_custom_column_data'), 10, 2);
        add_filter('dynamic_sidebar_params', array($post_faq,'faq_widget_settings'));
	}

	private function public_hooks_settings(){
		$status = THFAQF_Utils::check_premium_plugn_actve();
		if($status == 'deactivate'){
			$this->define_public_hooks();
		}
	}

	private function define_public_hooks(){
		$plugin_public = new THFAQF_Public();
		add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_styles_and_scripts'));
		add_action( 'wp_ajax_like_dislike_option',array($plugin_public,'like_dislike_option'));	
		add_action( 'wp_ajax_thfaqf_comment',array($plugin_public,'thfaqf_comment'));
		add_action( 'wp_ajax_nopriv_thfaqf_comment',array($plugin_public, 'thfaqf_comment'));
		add_filter( 'body_class',array($plugin_public,'thfaq_add_body_class'),10);
	}

}// end of class
endif;


    

