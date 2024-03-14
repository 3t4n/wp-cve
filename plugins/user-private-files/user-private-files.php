<?php
/**
* Plugin Name: User Private Files
* Description: This plugin allows users to manage their uploaded files and access to them.
* Version: 2.0.8
* Author: User Private Files
* Author URI: https://userprivatefiles.com/
* License: GPL+2
* Text Domain: user-private-files
* Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

include_once dirname( __FILE__ ) . '/upvf_actdeact.php';
register_activation_hook( __FILE__, array( 'Upvf_Actdeact', 'upvf_plugin_activate' ) );
register_deactivation_hook( __FILE__, array( 'Upvf_Actdeact', 'upvf_plugin_deactivate') );

add_action( 'admin_init', 'upvf_admin_init_plugin', 1 );
if (!function_exists('upvf_admin_init_plugin')) {
	function upvf_admin_init_plugin(){
		// check if htaccess is writable
		$htaccess = ABSPATH.".htaccess";
		if ( ! is_writeable( $htaccess ) ) {
			add_action( 'admin_notices', function(){
				$upload_dir = wp_upload_dir();
				$manual_htcode = "RewriteRule ^".basename(content_url()) . "/" . wp_basename( $upload_dir['baseurl'] )."/upf-docs/(.*)$ ".home_url()."?file=$1 [QSA,L]";
				echo '<div class="notice notice-warning is-dismissible">
						<p>Your htaccess file is not writable. Please change permission and reactivate the plugin OR edit the htaccess file manually and enter this code at the bottom. Please ignore if already added!</p>
						<p><code>'.$manual_htcode.'</code></p>
					</div>';
			} );
			add_action('admin_head', function(){ ?>
				<script>
					let hta_access = 0;
					jQuery(document).ready(function(){
						jQuery('#deactivate-user-private-files').on('click', function(){
							alert('Your htaccess file is not writable. Please check and remove the code from htaccess (if manually added)');
						});
					})
				</script>
		<?php });
		}
		// check if plugin created uploads folder or not
		$upload_dir = wp_upload_dir();
		$upf_dir_path = $upload_dir['basedir'] . "/upf-docs";
		$created_dir = wp_mkdir_p($upf_dir_path);
		if(!$created_dir){
			add_action('admin_head', function(){
				echo '<div class="notice notice-warning is-dismissible"><p>Plugin was unable to create directory in uploads. Please create a "upf-docs" directory/folder under your uploads directory</p></div>';
			});
		}
	}
}

add_action( 'init', 'upvf_init_plugin', 1 );
if (!function_exists('upvf_init_plugin')) {
	function upvf_init_plugin(){
		define ( 'UPVF_PLUGIN_DIR', plugin_dir_path(__FILE__ ) );
		global $upf_plugin_url;
		$upf_plugin_url = plugin_dir_url( __FILE__ );
		add_action( 'wp_enqueue_scripts', 'upf_styles_scripts' );
		add_action( 'admin_enqueue_scripts', 'upfp_admin_script' );

		// Including other functions
		include(plugin_dir_path(__FILE__ ) . 'inc/classic-post-new.php');
		include(plugin_dir_path(__FILE__ ) . 'inc/classic-render.php');
		include(plugin_dir_path(__FILE__ ) . 'inc/classic-user-functions.php');
		
		// PRO Design
		include(plugin_dir_path(__FILE__ ) . 'admin/settings.php');
		include(plugin_dir_path(__FILE__ ) . 'inc/class-upf-template-loader.php');
		global $upvf_template_loader;
		$upvf_template_loader = new UPF_Template_Loader();
		include(plugin_dir_path(__FILE__ ) . 'inc/shortcodes.php');
		include(plugin_dir_path(__FILE__ ) . 'inc/functions-file.php');
		include(plugin_dir_path(__FILE__ ) . 'inc/functions-folder.php');
		include(plugin_dir_path(__FILE__ ) . 'filters.php');
		include(plugin_dir_path(__FILE__ ) . 'actions.php');
		
		load_plugin_textdomain( 'user-private-files', false, 'user-private-files' );
	}
}

// Include file permission functions
add_filter( 'template_include', 'upvf_check_perm' );
if (!function_exists('upvf_check_perm')) {
	function upvf_check_perm( $original_template ) {
		if(isset($_GET[ 'file' ])){
            $template = dirname( __FILE__ ) . '/dl-file.php';
            if ( file_exists( $template ) ) {
            	include $template;
            }
            return $template;
		} else {
			return $original_template;
		}
	}
}

// Back-end assets
if (!function_exists('upfp_admin_script')) {
	function upfp_admin_script(){
		wp_enqueue_style(
			'upf-admin-style',
			plugin_dir_url( __FILE__ ) . 'css/admin/admin_free.css'
		);
		wp_enqueue_script(
			'upf-admin-script',
			plugins_url('js/admin/admin-upf_free.js',__FILE__ ),
			array('jquery')
		);
		wp_enqueue_style(
			'upf-multiple_choose-style',
			"https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css"
		);
		wp_enqueue_script(
			'upf-multiple_choose-script2',
			"https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js",
			array('jquery')
		);
	}
}

if (!function_exists('upf_styles_scripts')) {
	function upf_styles_scripts(){
		
		wp_register_style(
			'upf-classic-style',
			plugin_dir_url( __FILE__ ) . 'css/classic-style.css'
		);
		wp_register_script(
			'upf-classic-script',
			plugins_url('js/classic-main.js',__FILE__ ),
			array('jquery')
		);
		wp_localize_script(
			'upf-classic-script',
			'ajax_upf_classic_obj',
			array( 
				'ajaxurl' 			=> admin_url( 'admin-ajax.php' ), 
				'upvf_plugin_url' 	=> plugin_dir_url( __FILE__ ),
				'nonce'				=> wp_create_nonce('upf_classic_ajax_nonce')
			)
		);
		
		wp_register_style(
			'upf-style',
			plugin_dir_url( __FILE__ ) . 'css/style.css'
		);
		wp_register_style(
			'upf-google-font',
			'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap',
			array(),
			'null'
		);

		wp_register_script(
			'upf-fa-script',
			'https://kit.fontawesome.com/ab50bef255.js',
			array('jquery')
		);
		wp_register_script(
			'upf-waitforimages-script',
			plugins_url('js/waitforimages.min.js',__FILE__ ),
			array('jquery')
		);
		wp_register_script(
			'upf-script',
			plugins_url('js/file.js',__FILE__ ),
			array('jquery')
		);
		wp_register_script(
			'upvf-frnt-script',
			plugins_url('js/folder.js',__FILE__ ),
			array('jquery')
		);
		wp_register_script(
			'upvf-bulk-script',
			plugins_url('js/bulk-action.js',__FILE__ ),
			array('jquery')
		);
		
		wp_localize_script(
			'upf-script',
			'ajax_upf_obj',
			array( 'ajaxurl' 			=> admin_url( 'admin-ajax.php' ), 
					'upvf_plugin_url' 	=> plugin_dir_url( __FILE__ ), 
					'nonce'				=> wp_create_nonce('upfp_ajax_nonce'),
					'max_upload_size'	=> wp_max_upload_size(), 
					'max_err' 			=> __("Uploaded file exceeds the maximum upload size for this site", "user-private-files") 
				 )
		);
		wp_localize_script(
			'upvf-frnt-script',
			'ajax_upvf_frnt_obj',
			array( 'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
					'upvf_plugin_url' 	=> plugin_dir_url( __FILE__ ),
					'nonce'				=> wp_create_nonce('upfp_ajax_nonce')
				 )
		);
		wp_localize_script(
			'upvf-bulk-script',
			'ajax_upvf_bulk_obj',
			array( 'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
					'nonce'				=> wp_create_nonce('upfp_ajax_nonce')
				 )		 
		);

		

		
		
	}
}

if(is_admin()){
	// Plugin Configuration Page
	add_action( 'plugins_loaded', 'upvf_set_admin_menu' );
	if (!function_exists('upvf_set_admin_menu')) {
		function upvf_set_admin_menu(){
			add_action('admin_menu', 'upvf_admin_config', 999);
		}
	}
	
	if (!function_exists('upvf_admin_config')) {
		function upvf_admin_config() {
			add_menu_page('User Private Files', 'User Private Files', 'manage_options', 'upvf-free', 'upvf_config_callback', 'dashicons-superhero');
			add_submenu_page('upvf-free', 'Settings', 'Settings', 'manage_options', 'upvf-free', 'upvf_config_callback', 1);
		}
	}
}
