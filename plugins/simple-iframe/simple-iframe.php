<?php
/**
 * Plugin Name: Simple Iframe
 * Plugin URI: https://wordpress.org/plugins/simple-iframe/
 * Description: Easily insert iframes into the block editor.
 * Author: Jorge González
 * Version: 1.2.0
 * Author URI: http://unapersona.com
 * License: GPLv2 or later
 * Text Domain: simple-iframe
 */


add_action(
	'init',
	static function () {
		register_block_type( __DIR__ . '/build' );
		wp_set_script_translations(
			'unapersona-simple-iframe-editor-script',
			'simple-iframe',
			plugin_dir_path( __FILE__ ) . 'languages'
		);
	}
);
