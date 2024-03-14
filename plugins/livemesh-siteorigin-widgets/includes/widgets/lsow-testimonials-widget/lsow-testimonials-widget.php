<?php

/*
Widget Name: Testimonials
Description: Display testimonials from your clients/customers in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Testimonials_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-testimonials',
            __('Testimonials', 'livemesh-so-widgets'),
            array(
                'description' => __('Display testimonials in a responsive multi-column grid.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#testimonials-widgets'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),
                'testimonials' => array(
                    'type' => 'repeater',
                    'label' => __('Testimonials', 'livemesh-so-widgets'),
                    'item_name' => __('Testimonial', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='testimonials-name']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'name' => array(
                            'type' => 'text',
                            'label' => __('Name', 'livemesh-so-widgets'),
                            'description' => __('The author of the testimonial', 'livemesh-so-widgets'),
                        ),

                        'credentials' => array(
                            'type' => 'text',
                            'label' => __('Author Details', 'livemesh-so-widgets'),
                            'description' => __('The details of the author like company name, position held, company URL etc. HTML accepted.', 'livemesh-so-widgets'),
                        ),

                        'image' => array(
                            'type' => 'media',
                            'label' => __('Image', 'livemesh-so-widgets'),
                        ),

                        'text' => array(
                            'type' => 'tinymce',
                            'label' => __('Text', 'livemesh-so-widgets'),
                            'description' => __('What your customer had to say', 'livemesh-so-widgets'),
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
                            'label' => __('Testimonials per row', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 3
                        ),

                        'per_line_tablet' => array(
                            'type' => 'slider',
                            'label' => __('Testimonials per row in Tablet Resolution', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 2
                        ),

                        'per_line_mobile' => array(
                            'type' => 'slider',
                            'label' => __('Testimonials per row in Mobile Resolution', 'livemesh-so-widgets'),
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
                'lsow-testimonials',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_template_variables($instance, $args) {
        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'testimonials' => !empty($instance['testimonials']) ? $instance['testimonials'] : array(),
        ));
        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-testimonials', __FILE__, 'LSOW_Testimonials_Widget');