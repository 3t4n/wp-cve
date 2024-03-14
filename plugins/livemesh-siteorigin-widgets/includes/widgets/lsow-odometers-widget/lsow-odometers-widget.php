<?php

/*
Widget Name: Odometers
Description: Display one or more animated odometer statistics in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Odometer_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-odometers',
            __('Odometers', 'livemesh-so-widgets'),
            array(
                'description' => __('Display statistics as animated odometers in a multi-column grid.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#statistics-widgets'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'odometers' => array(
                    'type' => 'repeater',
                    'label' => __('Odometers', 'livemesh-so-widgets'),
                    'item_name' => __('Odometer', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='odometers-title']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'stats_title' => array(
                            'type' => 'text',
                            'label' => __('Stats Title', 'livemesh-so-widgets'),
                            'description' => __('The title for the odometer stats', 'livemesh-so-widgets'),
                        ),

                        'start_value' => array(
                            'type' => 'number',
                            'label' => __('Start Value', 'livemesh-so-widgets'),
                            'description' => __('The start value for the odometer stats.', 'livemesh-so-widgets'),
                        ),

                        'stop_value' => array(
                            'type' => 'number',
                            'label' => __('Stop Value', 'livemesh-so-widgets'),
                            'description' => __('The stop value for the odometer stats.', 'livemesh-so-widgets'),
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

                        'icon_image' => array(
                            'type' => 'media',
                            'label' => __('Stats Image.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon_image]' => array('show'),
                                'icon_type[icon]' => array('hide'),
                            ),
                        ),

                        'icon' => array(
                            'type' => 'icon',
                            'label' => __('Stats Icon.', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'icon_type[icon]' => array('show'),
                                'icon_type[icon_image]' => array('hide'),
                            ),
                        ),

                        'prefix' => array(
                            'type' => 'text',
                            'label' => __('Prefix', 'livemesh-so-widgets'),
                            'description' => __('The prefix string for the odometer stats. Examples include currency symbols like $ to indicate a monetary value.', 'livemesh-so-widgets'),
                        ),

                        'suffix' => array(
                            'type' => 'text',
                            'label' => __('Suffix', 'livemesh-so-widgets'),
                            'description' => __('The suffix string for the odometer stats. Examples include strings like hr for hours or m for million.', 'livemesh-so-widgets'),
                        ),
                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'per_line' => array(
                            'type' => 'slider',
                            'label' => __('Odometers per row', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 4
                        ),

                        'per_line_tablet' => array(
                            'type' => 'slider',
                            'label' => __('Odometers per row in Tablet Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 2
                        ),

                        'per_line_mobile' => array(
                            'type' => 'slider',
                            'label' => __('Odometers per row in Mobile Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 4,
                            'integer' => true,
                            'default' => 1
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
                array(
                    'lsow-stats',
                    LSOW_PLUGIN_URL . 'assets/js/jquery.stats' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );


        $this->register_frontend_scripts(
            array(
                array(
                    'lsow-odometers',
                    plugin_dir_url(__FILE__) . 'js/odometer' . LSOW_JS_SUFFIX . '.js',
                    array('jquery')
                )
            )
        );

        $this->register_frontend_styles(array(
            array(
                'lsow-frontend',
                LSOW_PLUGIN_URL . 'assets/css/lsow-frontend.css',
                array(),
                LSOW_VERSION
            ),
            array(
                'lsow-odometers',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_template_variables($instance, $args) {
        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'odometers' => !empty($instance['odometers']) ? $instance['odometers'] : array(),
        ));

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-odometers', __FILE__, 'LSOW_Odometer_Widget');