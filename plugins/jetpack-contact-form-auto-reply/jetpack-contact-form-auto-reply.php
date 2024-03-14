<?php
/*
 * Plugin Name: Jetpack Contact Form Auto Reply
 * Version: 1.1
 * Plugin URI: https://wordpress.org/plugins/jetpack-contact-form-auto-reply/
 * Description: Send an automatic reply to anyone who fills in your Jetpack contact form
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 4.0
 * Tested up to: 5.0
 *
 * Text Domain: jetpack-contact-form-auto-reply
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-jetpack-contact-form-auto-reply.php' );
require_once( 'includes/class-jetpack-contact-form-auto-reply-settings.php' );

/**
 * Returns the main instance of Jetpack_Contact_Form_Auto_Reply to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Jetpack_Contact_Form_Auto_Reply
 */
function Jetpack_Contact_Form_Auto_Reply () {
	$instance = Jetpack_Contact_Form_Auto_Reply::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Jetpack_Contact_Form_Auto_Reply_Settings::instance( $instance );
	}

	return $instance;
}

Jetpack_Contact_Form_Auto_Reply();
