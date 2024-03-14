<?php

namespace beginner_blogger_com_useful_tab_block_free;

defined('ABSPATH') || exit;

require_once(plugin_dir_path(__FILE__) . "/lib/utils.php");


function style_amp_custom_tag($cocoon = false)
{
    $css = "";

    $amp = false;

    // Check if the page is AMP
    if ((function_exists('amp_is_request') && amp_is_request())) {
        $amp = true;
    } elseif (isset($_GET['amp']) && $_GET['amp'] == 1) {
        $amp = true;
    }

    // If the page is not AMP, do nothing
    if (!$amp) {
        return;
    }

    $css_path = plugin_dir_path(__FILE__) . "tab-block/build/style-b-b-c-tab.css";
    $css .= css_url_to_css_minify_code($css_path);

    $css_path = plugin_dir_path(__FILE__) . "tab-content-block/build/style-b-b-c-tab-content.css";
    $css .= css_url_to_css_minify_code($css_path);

    // For Cocoon, add AMP styles to `amp_all_css` filter
    if ($cocoon) {
        return $css;
    } else {
        // For other themes, add AMP styles to `wp_head` 
        // with the `<style amp-custom>` tag
        $css = '<style amp-custom>' . $css . '</style>';
        echo $css;
    }
}


// AMP styles for Cocoon
// As Cocoon does not call `wp_haed()` in `amp-header.php`,
// we need to add the styles to `amp_all_css` filter.
function cocoon_style_amp_custom_tag($css)
{
    // Add custom style tag to that of Cocoon
    $css .= style_amp_custom_tag(true);
    return $css;
}


if (wp_get_theme() == "Cocoon Child") {
    add_filter("amp_all_css", __NAMESPACE__ . "\cocoon_style_amp_custom_tag");
} else {
    add_action("wp_head",  __NAMESPACE__ . "\style_amp_custom_tag");
}
