<?php
/*
Plugin Name: Weaver Xtreme Theme Support
Plugin URI: http://weavertheme.com/plugins
Description: Weaver Xtreme Theme Support - Legacy Admin plus a package of useful shortcodes and widgets that integrate closely with the Weaver Xtreme theme.
Author: wpweaver
Author URI: http://weavertheme.com/about/
Version: 6.4
License: GPL V3
Requires PHP: 7.2
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 6.4

Weaver Xtreme Theme Support

Copyright (C) 2014-2024 Bruce E. Wampler - weaver@weavertheme.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Allow plugin to try to start in this file, so this file is PHP 5.4 compatible.
*/


/* CORE FUNCTIONS
*/
$theme = get_template_directory();

function wvrx_ts_alert($msg)
{
    echo "<script> alert('" . esc_html($msg) . "'); </script>";
}
$php_version = phpversion();
$php_version_ok = true;

if (version_compare($php_version, '7.2', '<')) {
    $php_version_ok = false;
    wvrx_ts_alert('WARNING! Weaver Xtreme Theme Support requires PHP Version 7.2 or greater. Your version is ' . $php_version . '. The plugin will not be installed. The associated Weaver Xtreme Theme is also like to crash now.');
}


if ($php_version_ok && (strpos($theme, '/weaver-xtreme') !== false ||
    strpos($theme, '/weaver-xtreme-5') !== false )) {        // only load if Weaver Xtreme is the theme

    define('WVRX_TS_VERSION', '6.4');
    define('WVRX_TS_PAGEBUILDERS', true);

    function wvrx_ts_installed()
    {
        return true;
    }


    function wvrx_ts_plugins_url($file, $ext)
    {
        return plugins_url($file, __FILE__) . $ext;
    }

    function wvrx_ts_enqueue_scripts()
    {    // action definition

        if (function_exists('wvrx_ts_slider_header')) {
            wvrx_ts_slider_header();
        }

        // add plugin CSS here, too.

        // need new admin styling for Gutenberg

    }

    add_action('wp_enqueue_scripts', 'wvrx_ts_enqueue_scripts');

//require_once(dirname( __FILE__ ) . '/includes/wvrx-ts-editor-style.php'); // Load the editor style generation

    require_once(dirname(__FILE__) . '/includes/wvrx-ts-runtime-lib.php'); // NOW - load the basic library
    require_once(dirname(__FILE__) . '/includes/wvrx-ts-widgets.php');        // widgets runtime library
    require_once(dirname(__FILE__) . '/includes/wvrx-ts-shortcodes.php'); // load the shortcode definitions

// load traditional Weaver Xtreme Options

    function weaver_xtreme_load_admin_action()
    {
        require_once(dirname(__FILE__) . '/admin/add-weaverx-sapi-options.php'); // NOW - load the traditional options admin
    }

    add_action('weaver_xtreme_load_admin', 'weaver_xtreme_load_admin_action');


// ======================================== subthemes ========================================
    add_action('weaverx_child_show_extrathemes', 'wvrx_ts_child_show_extrathemes_action');

    function wvrx_ts_child_show_extrathemes_action()
    {
// old code found in version before 2.0.4
    }

    add_action('weaverx_child_process_options', 'wvrx_ts_child_process_options');
    function wvrx_ts_child_process_options()
    {
// old code found in version before 2.0.4

        if (weaverx_submitted('toggle_shortcode_prefix')) {
            $val = get_option('wvrx_toggle_shortcode_prefix');
            if ($val) {
                delete_option('wvrx_toggle_shortcode_prefix');
                weaverx_save_msg(__("Weaver Xtreme Theme Support Shortcodes NOT prefixed with 'wvrx_'", 'weaverx-theme-support'));
            } else {
                update_option('wvrx_toggle_shortcode_prefix', 'wvrx_');
                weaverx_save_msg(__("Weaver Xtreme Theme Support Shortcodes now prefixed with 'wvrx_'", 'weaverx-theme-support'));
            }
        } elseif (weaverx_submitted('show_per_page_report')) {
            wvrx_ts_per_page_report();
        }

    }

// old code found in version before 2.0.4

    add_action('weaverx_child_saverestore', 'wvrx_ts_child_saverestore_action');
    function wvrx_ts_child_saverestore_action()
    {
    }

// --------------------------------------
    function wvrx_ts_per_page_report()
    {
        echo '<div style="border:1px solid black; padding:1em;background:#F8FFCC;width:70%;margin:1em auto 1em auto;">';
        echo "<h2>" . esc_html__('Show Pages and Posts with  Per Page / Per Post Settings', 'weaverx-xtreme') . "</h2>\n";
        echo "<h3>" . esc_html__('Posts', 'weaverx-xtreme') . "</h3>\n";
        wvrx_ts_scan_section('post');
        echo "<h3>" . esc_html__('Pages', 'weaverx-xtreme') . "</h3>\n";
        wvrx_ts_scan_section('page');
        echo "</div>\n";
    }

    function wvrx_ts_scan_section($what)
    {

        $post_fields = array(
            '_pp_category',
            '_pp_tag',
            '_pp_onepost',
            '_pp_orderby',
            '_pp_sort_order',
            '_pp_author',
            '_pp_posts_per_page',
            '_pp_primary-widget-area',
            '_pp_secondary-widget-area',
            '_pp_sidebar_width',
            '_pp_top-widget-area',
            '_pp_bottom-widget-area',
            '_pp_sitewide-top-widget-area',
            '_pp_sitewide-bottom-widget-area',
            '_pp_post_type',
            '_pp_hide_page_title',
            '_pp_hide_site_title',
            '_pp_hide_menus',
            '_pp_hide_header_image',
            '_pp_hide_footer',
            '_pp_hide_header',
            '_pp_hide_sticky',
            '_pp_force_post_full',
            '_pp_force_post_excerpt',
            '_pp_show_post_avatar',
            '_pp_bodyclass',
            '_pp_fi_link',
            '_pp_fi_location',
            '_pp_post_fi_location',
            '_pp_post_styles',
            '_pp_hide_top_post_meta',
            '_pp_hide_bottom_post_meta',
            '_pp_stay_on_page',
            '_pp_hide_on_menu',
            '_pp_show_featured_img',
            '_pp_hide_infotop',
            '_pp_hide_infobottom',
            '_pp_hide_visual_editor',
            '_pp_masonry_span2',
            '_show_post_bubble',
            '_pp_hide_post_title',
            '_pp_post_add_link',
            '_pp_hide_post_format_label',
            '_pp_page_layout',
            '_pp_wvrx_pwp_type',
            '_pp_wvrx_pwp_cols',
            '_pp_post_filter',
            '_pp_header-widget-area',
            '_pp_footer-widget-area',
            '_pp_hide_page_infobar',
            '_pp_hide_n_posts',
            '_pp_fullposts',
            '_pp_pwp_masonry',
            '_pp_pwp_compact',
            '_pp_pwp_compact_posts',
            '_primary-widget-area',
            '_secondary-widget-area',
            '_header-widget-area',
            '_footer-widget-area',
            '_sitewide-top-widget-area',
            '_sitewide-bottom-widget-area',
            '_page-top-widget-area',
            '_page-bottom-widget-area',
            '_pp_full_browser_height',
            '_pp_page_cols',
            // Plus options
            '_pp_bgcolor',
            '_pp_color',
            '_pp_bg_fullwidth',
            '_pp_lr_padding',
            '_pp_tb_padding',
            '_pp_margin',
            '_pp_post_class',
            '_pp_bgimg',
            '_pp_mobile_bgimg',
            '_pp_parallax_height',
            '_pp_use_parallax',
            '_pp_parallax_not_wide',
            '_pp_footer_add_class',
            '_pp_container_add_class',
            '_pp_content_add_class',
            '_pp_post_add_class',
            '_pp_infobar_add_class',
            '_pp_wrapper_add_class',
            '_pp_header_add_class',
            '_pp_header_image_html_text',
            '_pp_alt_primary_menu',
            '_pp_alt_secondary_menu',
            '_pp_alt_mini_menu',
        );

        $args = array('posts_per_page' => -1, 'post_type' => $what, 'post_status' => 'any');
        echo '<ul>';

        $allposts = get_posts($args);
        foreach ($allposts as $post) {
            $id = $post->ID;
            setup_postdata($post);
            $meta = get_post_meta($id);
            if (!empty($meta)) {
                $type = $post->post_type;
                $title = esc_html($post->post_title);
                $link = esc_url(get_permalink($id));
                $tlink = "<a href='$link' target='_blank'>$title</a>";
                foreach ($meta as $name => $val_array) {        // old value gets put into $val_array[0]
                    if (in_array($name, $post_fields)) {
                        if ($type == 'page') {
                            echo "<li><strong><em>$tlink</em></strong> " . esc_html__('has Per Page settings.', 'weaverx-xtreme') . "</li>\n";
                        } else {
                            echo "<li><strong><em>$tlink</em></strong> " . esc_html__('has Per Post settings.', 'weaverx-xtreme') . "</li>\n";
                        }
                        break;
                    }
                }
            }
        }
        echo '</ul>';
    }

} // end only load if Weaver Xtreme installed

add_action('plugins_loaded', 'wvrx_check_jetpack');
function wvrx_check_jetpack()
{
    if (method_exists('Jetpack', 'is_module_active') && Jetpack::is_module_active('minileven') && is_admin()) {
        wvrx_ts_alert(__('**** IMPORTANT: The Jetpack Mobile theme is active. ****\nIt is NOT compatible with Weaver Xtreme, and will break the theme. Please deactivate it from the Jetpack control panel.\n\n**** This message will continue to be displayed until you deactivate the Jetpack Mobile Theme from the Jetpack settings panel. ****', 'weaverx-theme-support'));
    }
}

