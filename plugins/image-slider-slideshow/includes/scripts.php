<?php


add_action('admin_enqueue_scripts', 'rpg_enqueue_color_picker', 9);

/**
 * Enqueue colorpicket and chosen
 */
function rpg_enqueue_color_picker($hook_suffix) {

    
        global $wp_version;
        wp_enqueue_style(array('wp-color-picker', 'wp-jquery-ui-dialog'));
        if (function_exists('wp_enqueue_code_editor')) {
            wp_enqueue_code_editor(array('type' => 'text/css'));
        }
        wp_enqueue_script('my-script-handle', IMG_SLIDER_JS . 'admin_script.js', array('wp-color-picker', 'jquery-ui-core', 'jquery-ui-dialog'), false, true);
        wp_localize_script(
                'my-script-handle', 'bdlite_js', array(
                    'wp_version' => $wp_version,
                    'nothing_found' => __('Oops, nothing found!', 'img-slider'),
                    'reset_data' => __('Do you want to reset data?', 'img-slider'),
                    'choose_blog_template' => __('Select the Slider Design you like', 'img-slider'),
                    'close' => __('Close', 'img-slider'),
                    'set_blog_template' => __('Set Slider Template', 'img-slider'),
                    /*'default_style_template' => __('Apply default style of this selected template', 'img-slider'),*/
                    'no_template_exist' => __('No template exist for selection', 'img-slider'),
                )
        );
        
    }
