<?php

/*
Widget Name: Clients
Description: Display list of your clients in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Client_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-clients',
            __('Clients', 'livemesh-so-widgets'),
            array(
                'description' => __('Display one or more clients in a multi-column grid.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#clients-widget'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'clients' => array(
                    'type' => 'repeater',
                    'label' => __('Clients', 'livemesh-so-widgets'),
                    'item_name' => __('Client', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='clients-name']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'name' => array(
                            'type' => 'text',
                            'label' => __('Client Name', 'livemesh-so-widgets'),
                            'description' => __('The name of the client/customer.', 'livemesh-so-widgets'),
                        ),
                        'link' => array(
                            'type' => 'link',
                            'label' => __('Client URL', 'livemesh-so-widgets'),
                            'description' => __('The website of the client/customer.', 'livemesh-so-widgets'),
                        ),
                        'image' => array(
                            'type' => 'media',
                            'label' => __('Client Logo', 'livemesh-so-widgets'),
                            'library' => 'image',
                            'description' => __('The logo image for the client/customer.', 'livemesh-so-widgets'),
                        ),
                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'per_line' => array(
                            'type' => 'slider',
                            'label' => __('Clients per row', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 5
                        ),
                        'per_line_tablet' => array(
                            'type' => 'slider',
                            'label' => __('Clients per row in Tablet Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 4
                        ),
                        'per_line_mobile' => array(
                            'type' => 'slider',
                            'label' => __('Clients per row in Mobile Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 4,
                            'integer' => true,
                            'default' => 2
                        ),

                        'animation' => array(
                            'type' => 'select',
                            'label' => __('Choose Animation Type', 'livemesh-so-widgets'),
                            'default' => 'none',
                            'options' => lsow_get_animation_options(),
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
                'lsow-clients',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_template_variables($instance, $args) {

        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'clients' => !empty($instance['clients']) ? $instance['clients'] : array(),
        ));

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-clients', __FILE__, 'LSOW_Client_Widget');