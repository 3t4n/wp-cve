<?php
/*
 * File for uninstalling the plugin 
 */
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

//Require the plugin files
require_once( __DIR__ . '/class.htaccess.php' );
 
global $WPSOS_HP;
$WPSOS_HP = new WPSOS_HP();

//Remove rows for locking up
$WPSOS_HP->remove_root_htaccess_rows();
$WPSOS_HP->remove_admin_htaccess_rows();

//Delete all the users of the file
$users = $WPSOS_HP->get_htpasswd_users();
//Remove all the users from htpasswd file
foreach( $users as $username ){
	$WPSOS_HP->modify_htpasswd( $username, '', 'delete' );
}

//Delete the htpasswd file
if ( file_exists( $WPSOS_HP->htpasswd_file ) )
	@unlink( $WPSOS_HP->htpasswd_file );

//Delete the plugin options from DB
delete_option( 'wpsos_hp_options' );

?>