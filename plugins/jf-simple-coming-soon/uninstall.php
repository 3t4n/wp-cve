<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   JFSimpleComingSoon
 * @author    Jerome Fitzpatrick <jerome@jeromefitzpatrick.com>
 * @license   GPL-2.0+
 * @link      http://www.jeromefitzpatrick.com
 * @copyright 2013 Jerome Fitzpatrick
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here