<?php 
/*
Plugin Name: Ni WooCommerce Product Enquiry 
Description: Ni WooCommerce Product Enquiry plug-inallows customers to make the enquiry about a product before purchasing via email or directly on whatsapp.
Version: 4.1.8
Author: anzia
Author URI: http://naziinfotech.com/
Plugin URI: https://wordpress.org/plugins/ni-woocommerce-product-enquiry/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/agpl-3.0.html
Text Domain: niwoope
Domain Path: /languages/
Requires at least: 4.7
Tested up to: 6.4.3
WC requires at least: 3.0.0
WC tested up to: 8.6.0 
Last Updated Date: 18-February-2024
Requires PHP: 7.0

*/
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_woocommerce_product_enquiry' ) ) :
	class ni_woocommerce_product_enquiry{
		function __construct(){
			
				add_action( 'activated_plugin',  array(&$this,'niwoope_activation_redirect' ));
			
			add_filter( 'plugin_action_links',  array( &$this, 'niwoope_plugin_action_links'), 10, 5 );
			 add_action('plugins_loaded', array($this, 'plugins_loaded'));
			include_once("include/ni-enquiry-init.php");
			$obj =new  ni_enquiry_init();
		}
		function plugins_loaded(){
			//load_plugin_textdomain('niwoope', WP_PLUGIN_DIR.'/ni-woocommerce-product-enquiry/languages','ni-woocommerce-product-enquiry/languages');
			
			 // load_plugin_textdomain( 'niwoope', false, get_plugin_data( __FILE__ ) . '/languages/' );
			
			 load_plugin_textdomain('niwoope', false, dirname(plugin_basename( __FILE__ )).'/languages');
			
		}
		static   function niwoope_activation_redirect($plugin){
			 if( $plugin == plugin_basename( __FILE__ ) ) {
				 
				 
				exit( wp_redirect( admin_url( 'admin.php?page=ni-enquiry-setting' ) ) );
			}
		}
		function niwoope_plugin_action_links($actions, $plugin_file){
			static $plugin;

			if (!isset($plugin))
				$plugin = plugin_basename(__FILE__);
				if ($plugin == $plugin_file) {
						  $settings_url = admin_url() . 'admin.php?page=ni-enquiry-setting';
							$settings = array('settings' => '<a href='. $settings_url.'>' . __('Settings', 'niwoope') . '</a>');
							$site_link = array('support' => '<a href="http://naziinfotech.com" target="_blank">' . __('Support', 'niwoope') . '</a>');
							$email_link = array('email' => '<a href="mailto:support@naziinfotech.com" target="_top">' . __('Email', 'niwoope') . ' </a>');
					
							$actions = array_merge($settings, $actions);
							$actions = array_merge($site_link, $actions);
							$actions = array_merge($email_link, $actions);
						
					}
					
					return $actions;
				}
	}
	$obj = new ni_woocommerce_product_enquiry(); 
endif;
?>