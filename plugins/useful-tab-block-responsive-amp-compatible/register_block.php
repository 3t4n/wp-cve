<?php

/**
 * Plugin Name: Useful Tab Block – Responsive & AMP-Compatible
 * Plugin URI: https://beginner-blogger.com/useful-tab-block
 * Description: Useful Tab Block offers responsive and AMP-compatible tab blocks to your Gutenberg editor. The blocks, which do not use JavaScript, render and function quickly.
 * Version: 1.3.2
 * Author: beginner-blogger.com
 * Author URI: https://beginner-blogger.com
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: useful-tab-block
 * Domain Path: /languages
 */

namespace beginner_blogger_com_useful_tab_block_free;

defined('ABSPATH') || exit;

include(plugin_dir_path(__FILE__) . "/dynamic.php");
require_once(plugin_dir_path(__FILE__) . "/amp.php");

const TEXT_DOMAIN = "useful-tab-block";

function b_b_c_utbf_tab_block_enqueue()
{
    $asset_file = include(plugin_dir_path(__FILE__) . "/tab-block/build/index.asset.php");
    $index_js = "/tab-block/build/index.js";
    wp_register_script(
        "beginner-blogger-tab-script",
        plugins_url($index_js, __FILE__),
        $asset_file["dependencies"],
        $asset_file["version"]
    );

    wp_set_script_translations(
        "beginner-blogger-tab-script",
        TEXT_DOMAIN,
        plugin_dir_path(__FILE__) . '/languages'
    );

    register_block_type_from_metadata(
        __DIR__ . "/tab-block/build",
        [
            "render_callback" => __NAMESPACE__ . "\beginner_blogger_useful_tab_block_free_dynamic_render",
            "title" => _x("Useful Tab", "block title", TEXT_DOMAIN),
            "description" => _x("Give useful and easy-to-use tabs.", "block description", TEXT_DOMAIN),
        ]
    );
}

add_action("init", __NAMESPACE__ . "\b_b_c_utbf_tab_block_enqueue");


function b_b_c_utbf_tab_content_block_enqueue()
{
    register_block_type_from_metadata(
        __DIR__ . "/tab-content-block/build",
        [
            "render_callback" => __NAMESPACE__ . "\beginner_blogger_useful_tab_block_free_content_dynamic_render",
            "title" => _x("Tab Content (from Useful Tab)", "block title", TEXT_DOMAIN),
            "description" => _x("Can have whatever you want inside.", "block description", TEXT_DOMAIN),
        ]
    );
}

add_action("init", __NAMESPACE__ . "\b_b_c_utbf_tab_content_block_enqueue");


function custom_block_load_textdomain()
{
    load_plugin_textdomain('useful-tab-block', false, basename(dirname(__FILE__)) . '/languages');
}
add_action("plugins_loaded", __NAMESPACE__ . "\custom_block_load_textdomain");


include(plugin_dir_path(__FILE__) . "/setting-page.php");
