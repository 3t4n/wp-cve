<?php
/**
 * Plugin Name: Blogsqode Posts
 * Plugin URI: https://up2client.com/envato/blogsqode-3.0/preview_page.html
 * Description: Provides a Various Layouts for blogs.
 * Version: 1.0.0
 * Author: The_Krishna
 * Author URI: https://themeforest.net/user/the_krishna
 * Text Domain: blogsqode-blog-layout-design
 *
 * @package Blogsqode
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! defined( 'BLOGSQODE_PLUGIN_FILE' ) ) {
	define( 'BLOGSQODE_PLUGIN_FILE', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'BLOGSQODE_PLUGIN_PATH' ) ) {
    define( 'BLOGSQODE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if( !defined('BLOGSQODE_IMG_PATH')){
    define( 'BLOGSQODE_IMG_PATH',  plugin_dir_url( __FILE__ ).'public/assets/images' );
}
/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BLOGSQODE_VERSION', '1.0.0' );


add_action('wp_footer', 'add_scripts_in_footer_func');
function add_scripts_in_footer_func(){
    if(!is_single()){
        $dark_mode = strtolower(get_option("blogsqode_dark_mode"));
        $post_grid = strtolower(get_option("blogsqode_blog_post_grid"));

        ?> 
        <script type="text/javascript">
            "use strict";
            jQuery('body').addClass('dark-mode-<?php echo esc_js($dark_mode); ?>');
            jQuery('body').addClass('post-grid-<?php echo esc_js($post_grid); ?>');

        </script>
        <?php
    }
}

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

require_once('admin/blogsqode-admin.php');
require_once('admin/settings/elementor/blogsqode-widgets.php');
require_once('admin/settings/wpbackery/blogsqode-backery-shortcode.php');
require_once('admin/settings/wpbackery/blogsqode-backery-blockquote.php');

require_once('public/class-blogsqode-blog-templates.php');
require_once('public/class-blogsqode-blog-public.php');