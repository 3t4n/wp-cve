<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;

$tab = $MWXS_L->var_g('tab');
# Path Prefix
$PP = plugin_dir_path( __FILE__ ) . 'map-pages/';
# URL Prefix
$UP = admin_url('admin.php?page=myworks-wc-xero-sync-map&tab=');

switch($tab){
	case "customer":
		require_once $PP . 'customer-map.php';
		break;
	case "product":
		require_once $PP . 'product-map.php';
		break;
	case "variation":
		require_once $PP . 'variation-map.php';
		break;
	case "category":
		require_once $PP . 'category-map.php';
		break;
	case "payment-method":
		require_once $PP . 'payment-method-map.php';
		break;
	case "tax-class":
		require_once $PP . 'tax-class-map.php';
		break;
	default:
		require_once $PP . 'map-dashboard.php';
}

# Mapping save msg
if($MWXS_L->isset_session('map_page_update_message')){
	$mp_ssm = $MWXS_L->get_session_val('map_page_update_message','',true);
	$save_status = (!empty($mp_ssm))?$mp_ssm:'error';
	myworks_woo_sync_for_xero_set_admin_sweet_alert($save_status);	
}