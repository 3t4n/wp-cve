<?php

//enqueue style
if (!function_exists("ssign_shortcode_generator_styles_scripts")) {

    function ssign_shortcode_generator_styles_scripts() {
        wp_enqueue_style('ssign-shortcode-generator', plugins_url('electronic-signatures/shortcode-generator/css/swiftsignature_shortcode_generator.css'), '', '', '');
    }

}
add_action('admin_enqueue_scripts', 'ssign_shortcode_generator_styles_scripts');

// hooks your functions into the correct filters
if (!function_exists("ssign_add_mce_dropdown")) {

    function ssign_add_mce_dropdown() {
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        // check if WYSIWYG is enabled
        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', 'ssign_add_tinymce_plugin');
            add_filter('mce_buttons', 'ssign_register_mce_button');
        }
    }
}
add_action('admin_head', 'ssign_add_mce_dropdown');

/**
 *  register new button in the editor
 */
if (!function_exists("ssign_register_mce_button")) {

    function ssign_register_mce_button($buttons) {
        array_push($buttons, 'ssing_mce_button');
        return $buttons;
    }
}

/*
 *  the script will insert the shortcode on the click event
 */
if (!function_exists("ssign_add_tinymce_plugin")) {

    function ssign_add_tinymce_plugin($plugin_array) {
        $plugin_array['ssing_mce_button'] = plugins_url('electronic-signatures/shortcode-generator/js/swiftsignature_shortcode_generator_script.js');
        return $plugin_array;
    }

}