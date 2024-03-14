<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

function mandegarweb_delete_plugin() {
	global $wpdb;
	$table_name=$table=$wpdb->prefix."replace_mandegarweb";
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
mandegarweb_delete_plugin();
?>