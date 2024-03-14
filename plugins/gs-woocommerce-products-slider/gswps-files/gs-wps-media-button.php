<?php

function gswps_register_button($gswps_buttons) {
    array_push($gswps_buttons, "|", "gswps_visual_code");
    return $gswps_buttons;
}

function gswps_add_plugin($gswps_plugin_array) {
    $gswps_plugin_array['gswps_visual_code'] = plugins_url('/assets/js/gswps-media-button.js', __FILE__);
    GSWPS_visual_form();
    return $gswps_plugin_array;
}

function gswps_visual_btn() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'gswps_add_plugin');
        add_filter('mce_buttons', 'gswps_register_button');
    }
}
//add_action('admin_init', 'gswps_visual_btn',1);

function gswps_visual_media_button() { ?>
    <a href="javascript:void(0)" id="gswps-visual-media-btn" class="button">GS Woo Slider</a>
    <?php
}
add_action('media_buttons', 'gswps_visual_media_button', 15);

function gswps_enq_media_button_js() {
    wp_enqueue_script('media_button', plugins_url('/assets/js/gswps-media-button.js', __FILE__,array('jquery'), '1.0', true));
}
add_action('wp_enqueue_media', 'gswps_enq_media_button_js');