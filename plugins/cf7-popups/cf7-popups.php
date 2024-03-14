<?php
/**
 * Plugin Name: Popups - Submission Messages For Contact Form 7
 * Plugin URI: https://codeworkweb.com/plugins/cf7-popups/
 * Description: Useful plugin to display contact form 7 error messages, success messages in beautiful popups.
 * Version: 1.0.8
 * Author: Code Work Web
 * Author URI:  https://codeworkweb.com/
 * Text Domain: cf7-popups
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 */


if ( !defined( 'WPINC' ) ) {
    die();
}

define( 'CF7_POPUPS_DATA', 'CF7 Popups' ); ;
define( 'CF7_POPUPS_VER', '1.0.8' );

define( 'CF7_POPUPS_FILE', __FILE__ );
define( 'CF7_POPUPS_BASENAME', plugin_basename( CF7_POPUPS_FILE ) );
define( 'CF7_POPUPS_PATH', plugin_dir_path( CF7_POPUPS_FILE ) );
define( 'CF7_POPUPS_URL', plugins_url( '/', CF7_POPUPS_FILE ) );


require_once CF7_POPUPS_PATH .'cf7-popups-class.php';