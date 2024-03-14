<?php

/*
 *	Plugin Name: Disable Widget Block Editor
 *  Author: Ciobanu Marius-Catalin
 * Plugin URI: https://wordpress.org/plugins/disable-widget-block-editor
 *  Description: Disables the new widgets page of gutenberg and brings back the classic widgets page
 *  Version: 1.0.0
 * Author URI:  https://profiles.wordpress.org/ciobanu0151/
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */


add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );