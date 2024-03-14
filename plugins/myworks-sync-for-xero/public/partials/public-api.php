<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://myworks.software
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/public/partials
 */

global $MWXS_L;
global $wpdb;

$t = $MWXS_L->var_g('t');

# Select2 ajax customer and products
if($t == 'get_json_item_list'){
	$is_valid_user = false;
	
	if(is_user_logged_in() && current_user_can('manage_woocommerce')){
		$is_valid_user = true;
	}
	
	if($is_valid_user){
		$item = $MWXS_L->var_g('item');
		
		$search = $MWXS_L->var_g('q');
		
		$limit = ' LIMIT 0,50';
		
		if($item=='xero_product'){
			$tbl = $MWXS_L->gdtn('products');
			
			$query = "SELECT `ItemID` as `id`, `Name` as `text` FROM `{$tbl}` WHERE `Name` LIKE '%%%s%%'  OR `Code` LIKE '%%%s%%' ORDER BY `Name` ASC {$limit} ";
			
			$query = $wpdb->prepare($query,$search,$search);
			
			$q_data = $MWXS_L->get_data($query);
			$q_data = $MWXS_L->stripslash_get_data($q_data,array('text'));
			
			header('Content-Type: application/json');
			echo json_encode($q_data);
		}
		
		if($item=='xero_customer'){
			$tbl = $MWXS_L->gdtn('customers');
			
			$query = "SELECT `ContactID` as `id`, `Name` as `text` FROM `{$tbl}` WHERE `Name` LIKE '%%%s%%'  OR `EmailAddress` LIKE '%%%s%%'  OR `FirstName` LIKE '%%%s%%'  OR `LastName` LIKE '%%%s%%'  OR `CompanyNumber` LIKE '%%%s%%' ORDER BY `Name` ASC {$limit} ";
			
			$query = $wpdb->prepare($query,$search,$search,$search,$search,$search);
			
			header('Content-Type: application/json');
			echo json_encode($MWXS_L->get_data($query));
		}
	}
}