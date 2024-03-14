<?php
/*
	Decimal Product Quantity for WooCommerce
	uninstall.php
*/

	// if uninstall.php is not called by WordPress, die
	if (!defined('WP_UNINSTALL_PLUGIN')) {
		die;
	}

	if (!function_exists ('WooDecimalProduct_Check_Plugin_Installed')) {
		include_once(__DIR__ .'/includes/functions.php');
	}	
	
	$WooDecimalProduct_Uninstall_Del_MetaData = get_option ('woodecimalproduct_uninstall_del_metadata', 0);
	
	if ($WooDecimalProduct_Uninstall_Del_MetaData) {
		if (WooDecimalProduct_Check_Plugin_Installed ('decimal-product-quantity-for-woocommerce-pro')) {
			// Нельзя удалять некоторые Общие Настройки, т.к. имеется Плагин "Decimal Product Quantity for WooCommerce PRO"
		} else {	
			// Remove Plugin Options
			delete_option ('woodecimalproduct_min_qnt_default');
			delete_option ('woodecimalproduct_step_qnt_default');
			delete_option ('woodecimalproduct_item_qnt_default');		
			delete_option ('woodecimalproduct_max_qnt_default');
			
			delete_option ('woodecimalproduct_auto_correction_qnt');
			delete_option ('woodecimalproduct_ajax_cart_update');
			delete_option ('woodecimalproduct_price_unit_label');
			
			delete_option ('woodecimalproduct_debug_log');
			delete_option ('woodecimalproduct_uninstall_del_metadata');
			
			// Remove post meta
			global $wpdb;
			$PostMeta_Table = $wpdb->prefix .'postmeta';
			
			$Query = "DELETE FROM $PostMeta_Table WHERE meta_key LIKE 'woodecimalproduct_%'";			
			$wpdb->query($Query);	
		}		
	}