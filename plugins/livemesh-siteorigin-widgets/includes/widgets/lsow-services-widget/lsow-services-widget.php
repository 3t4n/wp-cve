<?php

/*
Widget Name: Services
Description: Capture services in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Services_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-services',
            __('Services', 'livemesh-so-widgets'),
            array(
                'description' => __('Create services to display in a column grid.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#services-widget'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'style' => array(
                    'type' => 'select',
                    'label' => __('Choose Style', 'livemesh-so-widgets'),
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array('style')
                    ),
                    'default' => 'style1',
                    'options' => array(
                        'style1' => __('Style 1', 'livemesh-so-widgets'),
                        'style2' => __('Style 2', 'livemesh-so-widgets'),
                        'style3' => __('Style 3', 'livemesh-so-widgets'),
                        'style4' => __('Style 4', 'livemesh-so-widgets'),
                        'style5' => __('Style 5', 'livemesh-so-widgets'),
                    )
                ),

                'icon_type' => array(
                    'type' => 'select',
                    'label' => __('Choose Icon Type', 'livemesh-so-widgets'),
                    'default' => 'icon',
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array('icon_type')
                    ),
                    'options' => array(
                        'icon' => __('Icon', 'livemesh-so-widgets'),
                        'icon_image' => __('Icon Image', 'livemesh-so-widgets'),
                    )
                ),

                'services' => array(
                    'type' => 'repeater',
                    'label' => __('Services', 'livemesh-so-widgets'),
                    'item_name' => __('Service', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='services-title']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(

                        'title' => array(
                            'type' => 'text',
                            'label' => __('Title', 'livemesh-so-widgets'),
                            'description' => __('Title of the service.', 'livemesh-so-widgets'),
                        ),

                        "href" => array(
                            "type" => "link",
                            "description" => __("The URL to which the service item should link to.(optional)", "livemesh-so-widgets"),
                            "label" => __("Target URL", "livemesh-so-widgets"),
                            "default" => '',
                        ),

                        'icon_image' => array(
                            'type' => 'media',
                            'label' => __('Service Image.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon_image]' => array('show'),
                                'icon_type[icon]' => array('hide'),
                            ),
                        ),

                        'icon' => array(
                            'type' => 'icon',
                            'label' => __('Service Icon.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                        ),

                        'excerpt' => array(
                            'type' => 'textarea',
                            'label' => __('Short description', 'livemesh-so-widgets'),
                            'description' => __('Provide a short description for the service', 'livemesh-so-widgets'),
                        ),

                        'animation' => array(
                            'type' => 'select',
                            'label' => __('Choose Animation Type', 'livemesh-so-widgets'),
                            'default' => 'none',
                            'options' => lsow_get_animation_options(),
                        ),

                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'per_line' => array(
                            'type' => 'slider',
                            'label' => __('Services per row', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 3
                        ),

                        'per_line_tablet' => array(
                            'type' => 'slider',
                            'label' => __('Services per row in Tablet Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 2
                        ),

                        'per_line_mobile' => array(
                            'type' => 'slider',
                            'label' => __('Services per row in Mobile Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 4,
                            'integer' => true,
                            'default' => 1
                        ),

                        "target" => array(
                            "type" => "checkbox",
                            "label" => __("Open the links in new window", "livemesh-so-widgets"),
                            "default" => true,
                        ),

                        'icon_size' => array(
                            'type' => 'number',
                            'label' => __('Icon Custom Size', 'livemesh-so-widgets'),
                            'description' => __('Specify a custom size for the font icon in pixels.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                            'optional' => true
                        ),

                        'icon_color' => array(
                            'type' => 'color',
                            'label' => __('Icon Custom Color', 'livemesh-so-widgets'),
                            'description' => __('Specify a custom color for the font icon.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                            'optional' => true
                        ),

                        'hover_color' => array(
                            'type' => 'color',
                            'label' => __('Icon Custom Hover Color', 'livemesh-so-widgets'),
                            'description' => __('Specify a custom hover color for the font icon.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                            'optional' => true
                        ),

                        'customize' => array(
                            'type' => 'notify',
                            'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets')  . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
                        ),

                    )
                ),
            )
        );
    }

    function initialize() {

        $this->register_frontend_scripts(
            array(
                array(
                    'lsow-waypoints',
                    LSOW_PLUGIN_URL . 'assets/js/jquery.waypoints' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(
            array(
                array(
                    'lsow-animate',
                    LSOW_PLUGIN_URL . 'assets/css/animate.css',
                    array(),
                    LSOW_VERSION
                ),
                array(
                    'lsow-frontend',
                    LSOW_PLUGIN_URL . 'assets/css/lsow-frontend.css',
                    array(),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(array(
            array(
                'lsow-services',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_less_variables($instance) {
        if( empty( $instance ) || empty( $instance['settings'] ) ) return array();

        return array(
            'icon_size' => isset($instance['settings']['icon_size']) ? $instance['settings']['icon_size'] : '',
            'icon_color' => isset($instance['settings']['icon_color']) ? $instance['settings']['icon_color'] : '',
            'hover_color' => isset($instance['settings']['hover_color']) ? $instance['settings']['hover_color'] : '',
        );
    }

    function get_template_variables($instance, $args) {
        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'style' => $instance['style'],
            'icon_type' => $instance['icon_type'],
            'services' => !empty($instance['services']) ? $instance['services'] : array()
        ));

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-services', __FILE__, 'LSOW_Services_Widget');