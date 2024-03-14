<?php

require 'awesome-icons.php';

/* style shortocodów w edytorze */
add_action('init', 'smartlib_schortcodes');

function   smartlib_schortcodes()
{
    add_shortcode('smartlib_icon_shortcode', 'smartlib_icon_shortcode');

}

function smartlib_icon_shortcode($attr, $content = '')
{
    $attr = wp_parse_args($attr, array(
        'source' => ''
    ));

    ob_start();


}

shortcode_ui_register_for_shortcode('smartlib_icon_shortcode', array(
        // Display label. String. Required.
        'label' => 'Slider',

        // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
        'listItemImage' => 'dashicons-images-alt2',
        'attrs' => array(
            array(
                'label' => __('Select Icon', 'smartlib'),
                'attr' => 'icon_name',
                'type' => 'select',
                'id' => 'awesome-icons-select',
                'options' => smartlib_icon_list_radio()

            )

        )


    )
);