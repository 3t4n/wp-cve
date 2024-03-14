<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Book a Place
 * @author    ArtkanMedia
 * @license   GPL-2.0+
 * @copyright 2018 ArtkanMedia
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here