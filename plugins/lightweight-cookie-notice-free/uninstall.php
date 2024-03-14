<?php

//exit if this file is called outside wordpress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daextlwcnf-shared.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daextlwcnf-admin.php' );

//delete options and tables
daextlwcnf_Admin::un_delete();
