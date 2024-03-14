<?php
/**
 * Plugin Name: Wordable - Export Google Docs to WordPress
 * Plugin URI: http://wordable.io
 * Description: This plugin allows you to instantly export Google Docs to WordPress posts or pages.
 * Version: 8.2.7
 * Author: Wordable
 * Author URI: https://wordable.io
 * Tested up to: 6.4.3
 * Requires at least: 5.0
 * Stable tag: 8.2.7
 *
 * Wordpress 5.0+
 */

define('WORDABLE_VERSION', '8.2.7');

include 'includes/wordable_exception.php';
include 'includes/wordable_plugin.php';
include 'includes/activator.php';
include 'includes/connector.php';
include 'includes/action_params.php';
include 'includes/actions.php';
include 'settings/index.php';

register_activation_hook(__FILE__, 'WordablePluginActivator::activation_hook');
