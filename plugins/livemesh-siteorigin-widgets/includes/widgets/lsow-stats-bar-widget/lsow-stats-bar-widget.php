<?php

/*
Widget Name: Stats Bars
Description: Display multiple stats bars that talk about skills or other percentage stats.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Stats_Bars_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-stats-bars',
            __('Stats Bars', 'livemesh-so-widgets'),
            array(
                'description' => __('Display statistics or skills as a percentage stats bar.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#statistics-widgets'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),
                'stats-bars' => array(
                    'type' => 'repeater',
                    'label' => __('Stats Bars', 'livemesh-so-widgets'),
                    'item_name' => __('Stats Bar', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='stats-bars-title']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'title' => array(
                            'type' => 'text',
                            'label' => __('Stats Title', 'livemesh-so-widgets'),
                            'description' => __('The title for the stats bar', 'livemesh-so-widgets'),
                        ),

                        'value' => array(
                            'type' => 'text',
                            'label' => __('Percentage Value', 'livemesh-so-widgets'),
                            'description' => __('The percentage value for the stats.', 'livemesh-so-widgets'),
                        ),

                        'color' => array(
                            'type' => 'color',
                            'label' => __('Bar color', 'livemesh-so-widgets'),
                        ),
                    )
                ),

                'customize' => array(
                    'type' => 'notify',
                    'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets')  . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
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
                    'lsow-stats-bar',
                    plugin_dir_url(__FILE__) . 'js/stats-bar' . LSOW_JS_SUFFIX . '.js',
                    array('jquery')
                ),
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
                'lsow-stats-bar',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_template_variables($instance, $args) {
        $settings = array(
            'stats_bars' => !empty($instance['stats-bars']) ? $instance['stats-bars'] : array()
        );

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-stats-bars', __FILE__, 'LSOW_Stats_Bars_Widget');