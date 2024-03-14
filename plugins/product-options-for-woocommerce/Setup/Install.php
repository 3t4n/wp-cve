<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Setup_Install {
	

	public static function install(){
	
		if ( !class_exists( 'WooCommerce' ) ) { 
		  deactivate_plugins('product-options-for-woocommerce');
		  wp_die( __('The Product Options plugin requires WooCommerce to run. Please install WooCommerce and activate.', 'product-options-for-woocommerce'));
	  }

    if ( version_compare( WC()->version, '3.0', "<" ) ) {
      wp_die(sprintf(__('WooCommerce %s or higher is required (You are running %s)', 'product-options-for-woocommerce'), '3.0', WC()->version));
    }
    	
		self::create_tables();			
	}


	private static function create_tables(){
		global $wpdb;

		$wpdb->hide_errors();
		 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta(self::get_schema());
	}


	private static function get_schema(){
		global $wpdb;

		$collate = '';

		if ($wpdb->has_cap( 'collation')){
			if (!empty( $wpdb->charset)){
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if (!empty( $wpdb->collate)){
				$collate .= " COLLATE $wpdb->collate";
			}
		}
		
		return "
CREATE TABLE {$wpdb->base_prefix}pofw_product_option (
  option_id int(11) unsigned NOT NULL auto_increment,
  product_id int(11) unsigned NOT NULL,   
  title varchar(255) DEFAULT NULL,
  price decimal (12,2) DEFAULT '0.00',   
  type varchar(60) DEFAULT NULL,
  required tinyint(1) DEFAULT '1',
  sort_order int(11) unsigned,    
  PRIMARY KEY (option_id)   
) $collate;
CREATE TABLE {$wpdb->base_prefix}pofw_product_option_value (
  value_id int(11) NOT NULL auto_increment,
  option_id int(11) unsigned NOT NULL,
  product_id int(11) unsigned NOT NULL,     
  title varchar(255) DEFAULT NULL,
  price decimal (12,2) DEFAULT '0.00',
  sort_order int(11) unsigned,      
  PRIMARY KEY (value_id)  
) $collate;
		";
		
	}

}
