<?php
/**
 * @link              https://www.david-manson.com
 * @since             1.0.0
 * @package           Say_It
 *
 * @wordpress-plugin
 * Plugin Name:       Say It!
 * Description:       Text to speech plugin helping your website easily say something !
 * Version:           4.0.1
 * Author:            David Manson
 * Author URI:        https://www.david-manson.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       say-it
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

// Plugin version
define( 'SAY_IT_VERSION', '4.0.1' );

/* Main script */
require plugin_dir_path( __FILE__ ) . 'includes/class-say-it.php';
function run_say_it() {
	$plugin = new Say_It();
	$plugin->run();
}
run_say_it();




/* Should be moved */
add_action('init', function() {
		// Register Gutenberg blocks
		wp_register_script('block-say-it-js', plugin_dir_url( __FILE__ ) . '/gutenberg/js/block-say-it.js');
		register_block_type('davidmanson/sayit', ['editor_script' => 'block-say-it-js']);

		// Register the sayit format
		wp_register_script('sayit-format-js', plugins_url( '/gutenberg/js/sayit-format.js', __FILE__ ),
			array( 'wp-rich-text' )
		);

		// Register sayit css
		wp_register_style("sayit-block-css", plugins_url("/gutenberg/style.css", __FILE__), array() , '1.1.0', 'all');
});

function my_custom_format_enqueue_assets_editor() {
    wp_enqueue_script( 'sayit-format-js' );
	wp_enqueue_style( 'sayit-block-css' );
}
add_action( 'enqueue_block_editor_assets', 'my_custom_format_enqueue_assets_editor' );