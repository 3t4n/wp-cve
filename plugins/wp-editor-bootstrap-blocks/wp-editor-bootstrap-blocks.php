<?php
/*
Plugin Name: Bootstrap Blocks for WP Editor
Plugin URI: https://gutenberg-bootstrap.com
Description: Enables your WordPress site to use Bootstrap container, row and column blocks in the WP editor (Gutenberg).
Version: 2.2.0
Author: Virgial Berveling
Author URI: https://www.freeamigos.nl
Text Domain: wp-editor-bootstrap-blocks
Domain Path: /languages/
*/

/*  
Copyright (c) Free amigos

INSTALLATION PROCEDURE:
Just put it in your plugins directory.
*/

if (!defined('ABSPATH')) die();


define( 'GUTENBERGBOOTSTRAP_SLUG', 'wp-editor-bootstrap-blocks' );
define( 'GUTENBERGBOOTSTRAP_VERSION', '2.2.0' );
define( 'GUTENBERGBOOTSTRAP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'GUTENBERGBOOTSTRAP_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );


include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/layout/layout.php');
include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/page-template/page-template.php');
include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/settings-page/settings-page.php');
include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/metaboxes/metaboxes.php');
include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/gtb-title.php');
include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/theming/gtb-theming.php');

function gtb_bootstrap_load()
{
	require_once GUTENBERGBOOTSTRAP_PLUGIN_DIR . '/core/class.gtbBootstrap.php';
	new GutenbergBootstrap();
}

add_action('plugins_loaded','gtb_bootstrap_load');

function gtb_bootstrap_uninstall()
{
	require_once GUTENBERGBOOTSTRAP_PLUGIN_DIR . '/core/class.gtbBootstrap.php';
	GutenbergBootstrap::uninstall();
}
register_uninstall_hook(__FILE__, 'gtb_bootstrap_uninstall');