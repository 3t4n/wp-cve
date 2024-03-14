<?php
/*
Plugin Name: Custom post types
Plugin URI: https://totalpress.org/plugins/custom-post-types?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types
Description: Create / manage custom post types, custom taxonomies, custom admin pages, custom fields and custom templates easily, directly from the WordPress dashboard without writing code.
Author: TotalPress.org
Author URI: https://totalpress.org/?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types
Text Domain: custom-post-types
Domain Path: /languages/
Version: 5.0.2
*/

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'CPT_PLUGIN_FILE' ) ) {
	define( 'CPT_PLUGIN_FILE', __FILE__ );
}

require_once dirname( CPT_PLUGIN_FILE ) . '/includes/abstracts/class-cpt-component.php';
require_once dirname( CPT_PLUGIN_FILE ) . '/includes/class-cpt-core.php';

require_once dirname( CPT_PLUGIN_FILE ) . '/includes/functions.php';

cpt_core();
