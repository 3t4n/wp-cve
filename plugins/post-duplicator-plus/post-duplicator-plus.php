<?php

/**
 * Post Duplicator Plus
 *
 * The plugin adds a duplicate button to posts, pages and custom post types.
 *
 * @link              https://wp-tasker.com
 * @since             1.0.1
 * @package           Post_Duplicator_Plus
 *
 * @wordpress-plugin
 * Plugin Name:       Post Duplicator Plus
 * Plugin URI:        https://wordpress.org/plugins/post-duplicator-plus
 * Description:       Minimalist Post Duplicator. The plugin adds a duplicate button to posts, pages and custom post 
 * types. Does it's job with the fewest lines of code possible. 
 * Version:           1.0.1
 * Author:            WP Tasker
 * Author URI:        https://wp-tasker.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-duplicator-plus
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'includes/constants.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-post-duplicator-plus.php';

function post_duplicator_plus_run() {
	new Post_Duplicator_Plus();
}

post_duplicator_plus_run();