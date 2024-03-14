<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   SearchTerms_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: SEO SearchTerms Admin
 * Plugin URI:  http://magn.com/seo-searchterms-admin-for-wordpress/
 * Description: Show the terms under each post/page or custom post type
 * Version:     0.1.0
 * Author:      Julian Magnone
 * Author URI:  http://magn.com
 * Text Domain: searchterms-admin
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-searchterms-admin.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace Plugin_Name with the name of the plugin defined in `class-plugin-name.php`
register_activation_hook( __FILE__, array( 'searchterms-admin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'searchterms-admin', 'deactivate' ) );

// TODO: replace Plugin_Name with the name of the plugin defined in `class-plugin-name.php`
SearchTerms_Admin::get_instance();