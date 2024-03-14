<?php
/*
 * Plugin Name: Beam me up Scotty - Back to Top Button
 * Version: 1.0.19
 * Plugin URI: https://www.outtheboxthemes.com/wordpress-plugins/beam-me-up-scotty
 * Description: Add a back to top button to your site quickly and easily with this simple and easy to configure plugin.
 * Author: Out the Box
 * Author URI: https://www.outtheboxthemes.com/
 * Requires at least: 4.0
 * Tested up to: 6.3
 * Requires PHP: 5.3
 *
 * Text Domain: beam-me-up-scotty
 * Domain Path: /languages/
 *
 * @package WordPress
 * @author Out the Box
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'OTB_BEAM_ME_UP_SCOTTY_DEBUG', false );

require_once( 'library/classes/otb-beam-me-up-scotty.php' );
require_once( 'library/classes/otb-beam-me-up-scotty-settings.php' );
require_once( 'library/classes/otb-beam-me-up-scotty-admin-api.php' );

/**
 * Returns the main instance of OTB_Beam_Me_Up_Scotty to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object OTB_Beam_me_up_Scotty
 */
function OTB_Beam_Me_Up_Scotty () {
	$instance = OTB_Beam_Me_Up_Scotty::instance( __FILE__, '1.0.19' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = OTB_Beam_Me_Up_Scotty_Settings::instance( $instance );
	}

	return $instance;
}

$otb_beam_me_up_scotty = OTB_Beam_Me_Up_Scotty();
