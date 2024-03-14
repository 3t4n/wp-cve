<?php
/**
 * Plugin Name: SocialFlow
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'No direct script access allowed' );
}

/*
Plugin Name: SocialFlow NEW
Description: SocialFlow's WordPress plugin enhances your WordPress experience by allowing you to utilize the power of SocialFlow from right inside WordPress.
Author: SocialFlow, Dizzain
Version: 3.3.6
Author URI: http://dizzain.com/
Plugin URI: http://wordpress.org/plugins/socialflow/
License: GPLv2 or later
Text Domain: socialflow
Domain Path: /i18n
*/

/**
 * Current plugin version
 * Each time on plugin initialization
 * we are checking this version with one stored in plugin options
 * and if they don't match plugin update hook will be fired
 *
 * @since 2.1
 */
if ( ! defined( 'SF_VERSION' ) ) {
    define( 'SF_VERSION', '3.3.6' );
}

/**
 * The name of the SocialFlow Core file
 *
 * @since 2.0
 */
if ( ! defined( 'SF_FILE' ) ) {
    define( 'SF_FILE', __FILE__ );
}

/**
 * Absolute location of SocialFlow Plugin
 *
 * @since 2.0
 */
if ( ! defined( 'SF_ABSPATH' ) ) {
    define( 'SF_ABSPATH', dirname( SF_FILE ) );
}

/**
 * The name of the SocialFlow directory
 *
 * @since 2.0
 */
if ( ! defined( 'SF_DIRNAME' ) ) {
    define( 'SF_DIRNAME', basename( SF_ABSPATH ) );
}

/**
 * Define Consumer Key
 *
 * @since 1.0
 */
define( 'SF_KEY', '5c7a4dda269976adad0c' );
/**
 * Define Consumer Secret
 *
 * @since 1.0
 */
define( 'SF_SECRET', '1ed6a7963a69da5457ab' );
require_once SF_ABSPATH . '/includes/class-sf-debug.php';
require_once SF_ABSPATH . '/includes/class-socialflow-methods.php';
require_once SF_ABSPATH . '/includes/class-socialflow.php';
require_once SF_ABSPATH . '/includes/class-socialflow-admin.php';
require_once SF_ABSPATH . '/includes/post/class-socialflow-compose-form-logs.php';
require_once SF_ABSPATH . '/includes/post/class-socialflow-post-accounts.php';
require_once SF_ABSPATH . '/includes/post/class-socialflow-post-form-data.php';
require_once SF_ABSPATH . '/includes/post/class-socialflow-post-compose.php';
require_once SF_ABSPATH . '/includes/class-socialflow-post.php';
require_once SF_ABSPATH . '/includes/class-socialflow-account.php';
require_once SF_ABSPATH . '/includes/class-socialflow-accounts.php';
require_once SF_ABSPATH . '/includes/class-socialflow-update.php';
require_once SF_ABSPATH . '/modules/replace-site-url-in-request/class-sf-module-replace-site-url.php';
require_once SF_ABSPATH . '/includes/class-sf-plugin-options.php';
require_once SF_ABSPATH . '/includes/class-sf-plugin-view.php';
/**
 * SocialFlow object
 *
 * @global object $socialflow
 * @since 2.0
 */
$GLOBALS['socialflow'] = new SocialFlow();
