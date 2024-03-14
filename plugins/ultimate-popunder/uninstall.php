<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! current_user_can( 'update_plugins' ))
{
	die;
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ))
{
	die;
}

global $wpdb;

unregister_setting( 'ultimatePopunder', '_ultimate_popunder_settings' );

delete_option( '_ultimate_popunder_version' );
delete_option( '_ultimate_popunder_settings' );

$sql = "DELETE p, pm FROM wp_posts p JOIN wp_postmeta pm ON pm.post_id = p.id WHERE p.post_type = 'ultimate_popunder';";

$wpdb->query( $sql );