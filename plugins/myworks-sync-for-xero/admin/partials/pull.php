<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;

$tab = $MWXS_L->var_g('tab');
# Path Prefix
$PP = plugin_dir_path( __FILE__ ) . 'pull-pages/';
# URL Prefix
$UP = admin_url('admin.php?page=myworks-wc-xero-sync-pull&tab=');

$sync_window_url = $MWXS_L->get_sync_window_url();

switch($tab){
	case "product":
		require_once $PP . 'product-pull.php';
		break;	
	default:
		require_once $PP . 'pull-dashboard.php';
}