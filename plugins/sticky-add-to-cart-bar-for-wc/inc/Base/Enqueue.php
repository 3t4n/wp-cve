<?php 
/**
 * @package  WooCart
 */
namespace WscInc\Base;

use WscInc\Base\BaseController;

/**
* 
*/
class Enqueue extends BaseController
{
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );	
	}

	function enqueue($hook) {
		wp_enqueue_script('wsc_notice_script', $this->plugin_url.'assets/woocart-admin.js');
		if('toplevel_page_woo_cart' != $hook){
			return;
		}
		
		// Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 

        // Add wordpress default media upload files
		wp_enqueue_media();

		wp_enqueue_style( 'wscpluginstyle', $this->plugin_url . 'assets/woocart-admin.css' );

		wp_enqueue_style('jquery-ui-theme-smoothness',$this->plugin_url .'assets/scripts/jquery-ui.min.css');

		wp_enqueue_script( 'wscpluginscript', $this->plugin_url . 'assets/woocart-main.js', array( 'jquery', 'wp-color-picker' ,'jquery-ui-datepicker' ) );

	}
}