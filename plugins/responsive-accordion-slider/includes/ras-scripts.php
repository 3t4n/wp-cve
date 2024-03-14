<?php


add_action('admin_enqueue_scripts', 'resp_accordion_slider_enqueue_color_picker', 9);

/**
 * Enqueue colorpicket and chosen
 */
function resp_accordion_slider_enqueue_color_picker($hook_suffix) {

    
        global $wp_version;
        wp_enqueue_style(array('wp-color-picker', 'wp-jquery-ui-dialog'));
        if (function_exists('wp_enqueue_code_editor')) {
            wp_enqueue_code_editor(array('type' => 'text/css'));
        }
        wp_enqueue_script('accordion-script-handle', RESP_ACCORDION_SLIDER_JS_PATH . 'admin-script.js', array('wp-color-picker', 'jquery-ui-core', 'jquery-ui-dialog'), false, true);
        wp_localize_script(
                'accordion-script-handle', 'accordion_js', array(
                    'wp_version' => $wp_version,
                    'nothing_found' => __('Oops, nothing found!', 'responsive-accordion-slider'),
                    'reset_data' => __('Do you want to reset data?', 'responsive-accordion-slider'),
                    'choose_blog_template' => __('Select the Slider Design you like', 'responsive-accordion-slider'),
                    'close' => __('Close', 'responsive-accordion-slider'),
                    'set_blog_template' => __('Set Slider Template', 'responsive-accordion-slider'),
                    'no_template_exist' => __('No template exist for selection', 'responsive-accordion-slider'),
                )
        );
        
    }
