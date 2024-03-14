<?php
/**
 * Plugin Name:       Rs Author Info Box
 * Plugin URI:
 * Description:       This widget allow you to display your name, image, title, description, social links, etc in sidebar area. this is plugin is very much compatible with Author Portfolio WordPress Theme.
 * Version:           2.0.5
 * Requires at least: 4.9
 * Requires PHP:      5.6
 * Author:            RS WP THEMES
 * Author URI:        https://rswpthemes.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rs-author-info-box
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('RS_AUTHOR_INFO_BOX_PLUGIN_PATH')) {
    define('RS_AUTHOR_INFO_BOX_PLUGIN_PATH', plugin_dir_path( __file__ ));
}
if (!defined('RS_AUTHOR_INFO_BOX_PLUGIN_URL')) {
    define('RS_AUTHOR_INFO_BOX_PLUGIN_URL', plugin_dir_url( __file__ ));
}
// require RS_AUTHOR_INFO_BOX_PLUGIN_PATH . '/includes/opt-in/opt-in.php';
require RS_AUTHOR_INFO_BOX_PLUGIN_PATH . '/includes/author-info-box-widget.php';

add_action('wp_enqueue_scripts', 'rs_author_info_box_enqueue_assets');
function rs_author_info_box_enqueue_assets(){
    $is_rs_author_info_box_active = is_active_widget( false, false, 'rs_info_box_widget' );
    $getRswpThemesSlug = get_stylesheet();
    if ($is_rs_author_info_box_active) :
        if ('author-portfolio-pro' !== $getRswpThemesSlug) :
            wp_enqueue_style( 'rswpthemes-icons', RS_AUTHOR_INFO_BOX_PLUGIN_URL . 'assets/webfonts/icons.css');
        endif;
        wp_enqueue_style( 'rs-author-info-box-style', RS_AUTHOR_INFO_BOX_PLUGIN_URL . 'assets/css/style.css');
    endif;
}
