<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers the Single Calendar block
 *
 */
if (function_exists('register_block_type')) {

    function wpsbc_register_block_type_single_calendar()
    {

        wp_register_script('wpsbc-script-block-single-calendar', WPSBC_PLUGIN_DIR_URL . 'includes/modules/blocks/single-calendar/assets/js/build/script-block-single-calendar.js', array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n'));

        register_block_type(
            'wp-simple-booking-calendar/single-calendar',
            array(
                'attributes' => array(
                    'id' => array(
                        'type' => 'string',
                    ),
                    'title' => array(
                        'type' => 'string',
                    ),
                    'legend' => array(
                        'type' => 'string',
                    ),
                    'legend_position' => array(
                        'type' => 'string',
                    ),
                    'language' => array(
                        'type' => 'string',
                    ),
                ),
                'editor_script' => 'wpsbc-script-block-single-calendar',
                'render_callback' => 'wpsbc_block_to_shortcode_single_calendar',
            )
        );

        register_block_type(
            'wp-simple-booking-calendar/sbc',
            array(
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                    ),
                ),
                'editor_script' => 'wpsbc-script-block-single-calendar',
                'render_callback' => 'wpsbc_block_to_shortcode_single_calendar',
            )
        );

    }
    add_action('init', 'wpsbc_register_block_type_single_calendar');

}

/**
 * Render callback for the server render block
 * Transforms the attributes from the blocks into the needed shortcode arguments
 *
 * @param array $args
 *
 * @return string
 *
 */
function wpsbc_block_to_shortcode_single_calendar($args)
{

    if (!isset($args['id'])) {
        $args['id'] = 1;
    }

    if (empty($args['id'])) {

        return '<div style="padding: 20px; background-color: #f1f1f1;">' . __('Please select a calendar to display.') . '</div>';

    }

    // Execute the shortcode
    return WPSBC_Shortcodes::single_calendar($args);

}
