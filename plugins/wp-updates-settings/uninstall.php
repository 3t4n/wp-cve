<?php
/**
* Config automatic background updates Uninstall
*
* Uninstalling Config automatic background updates delete options.
*/

// If uninstall not called from Wordpress exit 
if( !defined('WP_UNINSTALL_PLUGIN') ) exit();

delete_option('yslo_wpus_options');
