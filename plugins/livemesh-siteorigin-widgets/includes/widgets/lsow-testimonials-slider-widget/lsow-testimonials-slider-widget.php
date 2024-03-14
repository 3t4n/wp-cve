<?php

/*
Widget Name: Testimonials Slider
Description: Display responsive touch friendly slider of testimonials from clients/customers.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Testimonials_Slider_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-testimonials-slider',
            __('Testimonials Slider', 'livemesh-so-widgets'),
            array(
                'description' => __('Share your product/service testimonials in a responsive slider.', 'livemesh-so-widgets'),
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
                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(
                        "slide_animation" => array(
                            "type" => "select",
                            "description" => __("Select your animation type.", "livemesh-so-widgets"),
                            "label" => __("Animation", "livemesh-so-widgets"),
                            "options" => array(
                                "slide" => __("Slide", "livemesh-so-widgets"),
                                "fade" => __("Fade", "livemesh-so-widgets"),
                            ),
                            "default" => "slide",
                        ),
                        'slideshow_speed' => array(
                            'type' => 'number',
                            'label' => __('Slideshow speed', 'livemesh-so-widgets'),
                            'default' => 5000
                        ),

                        'animation_speed' => array(
                            'type' => 'number',
                            'label' => __('Animation Speed', 'livemesh-so-widgets'),
                            'default' => 600
                        ),

                        'pause_on_action' => array(
                            'type' => 'checkbox',
                            'label' => __('Pause slider on action.', 'livemesh-so-widgets'),
                            'description' => __('Should the slideshow pause once user initiates an action using navigation/direction controls.', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'pause_on_hover' => array(
                            'type' => 'checkbox',
                            'label' => __('Pause on Hover', 'livemesh-so-widgets'),
                            'description' => __('Should the slider pause on mouse hover over the slider.', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'direction_nav' => array(
                            'type' => 'checkbox',
                            'label' => __('Direction Navigation', 'livemesh-so-widgets'),
                            'description' => __('Should the slider have direction navigation.', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'control_nav' => array(
                            'type' => 'checkbox',
                            'label' => __('Navigation Controls', 'livemesh-so-widgets'),
                            'description' => __('Should the slider have navigation controls.', 'livemesh-so-widgets')
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
                    'lsow-flexslider',
                    LSOW_PLUGIN_URL . 'assets/js/jquery.flexslider' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(
            array(
                array(
                    'lsow-icomoon',
                    LSOW_PLUGIN_URL . 'assets/css/icomoon.css',
                    array(),
                    LSOW_VERSION
                ),
                array(
                    'lsow-flexslider',
                    LSOW_PLUGIN_URL . 'assets/css/flexslider.css',
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


        $this->register_frontend_scripts(array(
            array(
                'lsow-testimonials-slider',
                plugin_dir_url(__FILE__) . 'js/testimonials' . LSOW_JS_SUFFIX . '.js',
                array('lsow-flexslider')
            )
        ));

        $this->register_frontend_styles(array(
            array(
                'lsow-testimonials-slider',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_template_variables($instance, $args) {

        $settings = $instance['settings'];

        $settings = array_merge($settings,array(
            'testimonials' => !empty($instance['testimonials']) ? $instance['testimonials'] : array(),
        ));
        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-testimonials-slider', __FILE__, 'LSOW_Testimonials_Slider_Widget');