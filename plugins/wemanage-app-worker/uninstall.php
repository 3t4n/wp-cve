<?php
/**
 * Nouvello WeManage Worker Uninstaller
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // exit if uninstall constant is not defined.
}

// remove reinstall restrictions.
delete_option( 'nvl_wemanage_worker_wp' );
delete_option( 'nvl_wemanage_worker_wc' );

// fire webhook with plugin uninstall event.

$payload = array(
	'key' => get_option( 'nouvello-worker-activation-key' ),
	'url' => get_home_url(),
	'type' => 'WordPress',
	'trigger' => 'wp_plugin.uninstall',
);

$args = array(
	'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
	'body'        => json_encode( $payload ),
	'method'      => 'POST',
	'data_format' => 'body',
);

$url = 'https://wemanage-dev.xyz/api/webhooks/jhb123asd';
wp_remote_post( $url, $args );
