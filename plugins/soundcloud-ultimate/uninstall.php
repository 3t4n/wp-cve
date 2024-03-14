<?php
// If uninstall not called from WordPress exit
if(!defined('WP_UNINSTALL_PLUGIN'))
exit ();
// Delete option from options table
delete_option( 'soundcloud_settings' );
delete_option( 'soundcloud_track_data' );
?>