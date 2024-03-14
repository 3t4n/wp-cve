<?php
/*
Plugin Name: Ni WooCommerce Cost Of Goods
Description: Ni WooCommerce Cost Of Goods provides a seamless solution for adding cost or purchase prices to both simple and variation products in WooCommerce. It also offers comprehensive product profit information, empowering you to optimize pricing strategies and drive profitability in your online store.
Author: anzia
Version: 3.2.4
Author URI: http://naziinfotech.com/
Plugin URI: https://wordpress.org/plugins/ni-woocommerce-cost-of-goods/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/agpl-3.0.html
Text Domain: wooreportcog
Domain Path: /languages/
Requires at least: 4.7
Tested up to: 6.4.3
WC requires at least: 3.0.0
WC tested up to: 8.6.1
Last Updated Date: 12-March-2024
Requires PHP: 7.0
*/
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_WooCommerce_Cost_Of_Goods' ) ) {
	class Ni_WooCommerce_Cost_Of_Goods{
		var $ni_constant = array();  
		 public function __construct(){
			 $this->ni_constant = array(
				 "prefix" 		  => "ni-",
				 "manage_options" => "manage_options",
				 "menu"   		  => "ni-cost-of-goods",
				 "file_path"   	  => __FILE__,
				);
			include("include/ni-woocommerce-cost-of-goods-init.php");
			$obj_init =  new Ni_WooCommerce_Cost_Of_Goods_Init($this->ni_constant);
			add_action( 'plugins_loaded',  array(&$this,'plugins_loaded') );
				add_filter( 'plugin_action_links', array( $this, 'plugin_action_links_ni_cost_of_goods' ), 10, 2);
		 }
		 function plugin_action_links_ni_cost_of_goods($actions, $plugin_file){
		 	static $plugin;

			if (!isset($plugin))
				$plugin = plugin_basename(__FILE__);
				
			if ($plugin == $plugin_file) {
					$buy_pro = array('buypro' => '<a href="http://naziinfotech.com/product/ni-woocommerce-cost-of-good-pro/" target="_blank">' . __('Buy Pro', 'wooreportcog') . '</a>');
					$site_link = array('support' => '<a href="http://naziinfotech.com" target="_blank">' . __('Support', 'wooreportcog') . '</a>');
					$email_link = array('email' => '<a href="mailto:support@naziinfotech.com" target="_top">' . __('Email', 'wooreportcog') . '</a>');
					
					$actions = array_merge($site_link, $actions);
					$actions = array_merge($email_link, $actions);
					$actions = array_merge($buy_pro, $actions);
			}
				
			return $actions;
		 }
		 function plugins_loaded(){
			//load_plugin_textdomain('wooreportcog', WP_PLUGIN_DIR.'/ni-woocommerce-cost-of-goods/languages','ni-woocommerce-cost-of-goods/languages');
			load_plugin_textdomain('wooreportcog', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		 }	
	}
	$obj = new Ni_WooCommerce_Cost_Of_Goods();
}
?>