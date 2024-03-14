<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
  if( !class_exists( 'ni_custom_order_status_setting' ) ) {
	class ni_custom_order_status_setting {
		public function __construct(){
			//add_action('woocommerce_order_status_changed', array($this, 'ni_custom_order_status_woocommerce_order_status_changed'));
		}
		function page_init(){
			//echo "dsa";
		}	
	}
  }
?>