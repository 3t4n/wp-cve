<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;

$tab = $MWXS_L->var_g('tab');
# Path Prefix
$PP = plugin_dir_path( __FILE__ ) . 'push-pages/';
# URL Prefix
$UP = admin_url('admin.php?page=myworks-wc-xero-sync-push&tab=');

$sync_window_url = $MWXS_L->get_sync_window_url();

switch($tab){
	case "customer":
		require_once $PP . 'customer-push.php';
		break;
	case "order":
		require_once $PP . 'order-push.php';
		break;
	case "product":
		require_once $PP . 'product-push.php';
		break;
	case "variation":
		require_once $PP . 'variation-push.php';
		break;
	default:
		require_once $PP . 'push-dashboard.php';
}