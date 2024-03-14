<?php

/*
Widget Name: Icon List
Description: Use images or icon fonts to create social icons list, show payment options etc.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Icon_List_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-icon-list',
            __('Icon List', 'livemesh-so-widgets'),
            array(
                'description' => __('Use images or icon fonts to create social icons list, show payment options etc.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL . '#icon-list'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
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

                'icon_list' => array(
                    'type' => 'repeater',
                    'label' => __('Icon List', 'livemesh-so-widgets'),
                    'item_name' => __('Icon', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='icon_list-title']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(

                        'title' => array(
                            'type' => 'text',
                            'label' => __('Title', 'livemesh-so-widgets'),
                            'description' => __('Title for the Icon.', 'livemesh-so-widgets'),
                        ),

                        'icon_image' => array(
                            'type' => 'media',
                            'label' => __('Icon Image.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon_image]' => array('show'),
                                'icon_type[icon]' => array('hide'),
                            ),
                        ),

                        'icon' => array(
                            'type' => 'icon',
                            'label' => __('Icon.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                        ),


                        "href" => array(
                            "type" => "link",
                            "label" => __("Target URL", "livemesh-so-widgets"),
                            "description" => __("The URL to which icon/image should point to. (optional)", "livemesh-so-widgets"),
                        ),

                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'icon_size' => array(
                            'type' => 'slider',
                            'label' => __('Icon/Image size in pixels', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 128,
                            'integer' => true,
                            'default' => 32
                        ),

                        'icon_color' => array(
                            'type' => 'color',
                            'label' => __('Icon color', 'livemesh-so-widgets'),
                            'default' => '#666666',
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                        ),

                        'hover_color' => array(
                            'type' => 'color',
                            'label' => __('Icon hover color', 'livemesh-so-widgets'),
                            'default' => '#444444',
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                        ),

                        "target" => array(
                            "type" => "checkbox",
                            "label" => __("Open the links in new window", "livemesh-so-widgets"),
                            "default" => true,
                        ),

                        'align' => array(
                            'type' => 'select',
                            'label' => __('Alignment', 'livemesh-so-widgets'),
                            'default' => 'left',
                            'options' => array(
                                'left' => __('Left', 'livemesh-so-widgets'),
                                'right' => __('Right', 'livemesh-so-widgets'),
                                'center' => __('Center', 'livemesh-so-widgets'),
                            )
                        ),

                        'animation' => array(
                            'type' => 'select',
                            'label' => __('Choose Animation Type', 'livemesh-so-widgets'),
                            'default' => 'none',
                            'options' => lsow_get_animation_options(),
                        ),

                        'customize' => array(
                            'type' => 'notify',
                            'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets') . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
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

        $this->register_frontend_scripts(
            array(
                array(
                    'lsow-tooltips',
                    LSOW_PLUGIN_URL . 'assets/js/jquery.powertip' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_scripts(array(
                array(
                    'lsow-icon-list',
                    plugin_dir_url(__FILE__) . 'js/icon-list' . LSOW_JS_SUFFIX . '.js',
                    array('jquery')
                )
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
                    'lsow-icon-list',
                    plugin_dir_url(__FILE__) . 'css/style.css'
                )
            )
        );
    }

    function get_less_variables($instance) {
        if (empty($instance) || empty($instance['settings']))
            return array();

        return array(
            'icon_size' => isset($instance['settings']['icon_size']) ? $instance['settings']['icon_size'] : '',
            'icon_color' => isset($instance['settings']['icon_color']) ? $instance['settings']['icon_color'] : '',
            'hover_color' => isset($instance['settings']['hover_color']) ? $instance['settings']['hover_color'] : '',
        );
    }

    function get_template_variables($instance, $args) {
        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'icon_type' => $instance['icon_type'],
            'icon_list' => !empty($instance['icon_list']) ? $instance['icon_list'] : array()
        ));

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-icon-list', __FILE__, 'LSOW_Icon_List_Widget');