<?php
/*
 * Plugin Name: Clio Grow Form
 * Version: 1.0.2
 * Plugin URI:
 * Description: Creates a short code form to allow leads to be submitted directly into a Clio Grow account.
 * Author: Themis Solutions, Inc.
 * Author URI: https://clio.com
 * Requires at least: 4.0
 * Tested up to: 6.4
 *
 * Text Domain: grow-form
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Themis Solutions, Inc.
 * @since 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-grow-form.php' );
require_once( 'includes/class-grow-form-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-grow-form-admin-api.php' );
require_once( 'includes/lib/class-grow-form-post-type.php' );
require_once( 'includes/lib/class-grow-form-taxonomy.php' );

$plugin_version = '1.0.2';
/**
 * Returns the main instance of Grow_Form to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Grow_Form
 */
function Grow_Form () {
	global $plugin_version;
	$instance = Grow_Form::instance( __FILE__, $plugin_version );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Grow_Form_Settings::instance( $instance );
	}

	return $instance;
}

Grow_Form();
