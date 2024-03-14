<?php
/**
 * The plugin bootstrap file
 *
 * @package pluginette-todo-list
 * @link    https://pluginette.com
 * @since   1.0
 *
 * @wordpress-plugin
 * Plugin Name:       ToDo Block
 * Description:       ToDo List Block for Gutenberg.
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Version:           1.0.7
 * Author:            David Towoju
 * Author URI:        https://pluginette.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pluginette-todo-list
 */

// Load the core plugin class that contains all hooks.
require plugin_dir_path( __FILE__ ) . 'class-todo-block.php';

$todo = new ToDo_Block();
$todo->run_hooks();
