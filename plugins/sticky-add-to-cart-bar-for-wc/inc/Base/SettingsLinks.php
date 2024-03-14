<?php
/**
 * @package  WooCart
 */
namespace WscInc\Base;

use WscInc\Base\BaseController;

class SettingsLinks extends BaseController
{
	public function register() 
	{
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		add_filter( "plugin_row_meta", array( $this, 'plugin_meta_links' ), 10, 4 );
	}

	public function settings_link( $links ) 
	{
		$settings_link[0] = '<a href="admin.php?page=woo_cart">Settings</a>';
		$settings_link[1] = '<b><a target="_blank" href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" style="color: #39b54a; font-weight: bold;">Get Pro Plugin</a></b>';
		array_push( $links, $settings_link[0],$settings_link[1]);
		return $links;
	}

	public function plugin_meta_links( $links, $plugin_file_name, $plugin_data, $status ) {
		if ( $plugin_file_name === $this->plugin ) {
			// $links[] = '<b><a target="_blank" href="https://addonsplus.com/"><span class="dashicons  dashicons-search"></span>Documentation</a></b>';
			$links[] = '<b><a target="_blank" href="https://demo.addonsplus.com/woo-sticky-cart-bar/product/athletic-shirt/"><span class="dashicons  dashicons-laptop"></span>Demo</a></b>';
			$links[] = '<b><a target="_blank" href="https://wordpress.org/support/plugin/sticky-add-to-cart-bar-for-wc/reviews/?filter=5#new-post"><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span> Rate Us</a></b>';
		}
		return $links;
	}
}