<?php

/*
Widget Name: Heading
Description: Create heading for display on the top of a section.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Heading_Widget extends SiteOrigin_Widget {

    function __construct() {

        parent::__construct(
            'lsow-heading',
            __('Heading', 'livemesh-so-widgets'),
            array(
                'description' => __('Create heading for display on the top of a section.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#heading-widget'
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
                    )
                ),
                'align' => array(
                    'type' => 'select',
                    'description' => __('Alignment of the heading.', 'livemesh-so-widgets'),
                    'label' => __('Align', 'livemesh-so-widgets'),
                    'options' => array(
                        'center' => __('Center', 'livemesh-so-widgets'),
                        'left' => __('Left', 'livemesh-so-widgets'),
                        'right' => __('Right', 'livemesh-so-widgets'),
                    ),
                    'default' => 'center'
                ),

                'heading_font' => array(
                    'type' => 'font',
                    'label' => __('Heading font', 'livemesh-so-widgets'),
                    'default' => 'default'
                ),

                'heading' => array(
                    'type' => 'text',
                    'label' => __('Heading Title', 'livemesh-so-widgets'),
                    'description' => __('Title for the heading.', 'livemesh-so-widgets'),
                ),

                'subtitle' => array(
                    'type' => 'text',
                    'label' => __('Subheading', 'livemesh-so-widgets'),
                    'description' => __('A subtitle displayed above the title heading.', 'livemesh-so-widgets'),
                    'state_handler' => array(
                        'style[style2]' => array('show'),
                        '_else[style]' => array('hide'),
                    ),
                ),

                'short_text' => array(
                    'type' => 'textarea',
                    'label' => __('Short Text', 'livemesh-so-widgets'),
                    'description' => __('Short text generally displayed below the heading title.', 'livemesh-so-widgets'),
                    'state_handler' => array(
                        'style[style3]' => array('hide'),
                        '_else[style]' => array('show')
                    ),
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => esc_html__('Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'animation' => array(
                            'type' => 'select',
                            'label' => __('Choose Animation', 'livemesh-so-widgets'),
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
                'lsow-heading',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_template_variables($instance, $args) {

        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'style' => $instance['style'],
            'align' => $instance['align'],
            'heading' => $instance['heading'],
            'short_text' => !empty($instance['short_text']) ? $instance['short_text'] : '',
            'subtitle' => !empty($instance['subtitle']) ? $instance['subtitle'] : '',

            'heading' => $instance['heading']
        ));

        return array('settings' => $settings);
    }

    function get_less_variables($instance) {

        if (empty($instance) || !isset($instance['heading_font']))
            return;

        $less = array();

        $font = siteorigin_widget_get_font($instance['heading_font']);
        $less['heading_font'] = $font['family'];
        if (!empty($font['weight'])) {
            $less['heading_font_weight'] = $font['weight'];
        }

        return $less;
    }

    /**
     * Less function for importing Google web fonts.
     */
    function less_import_google_font($instance, $args) {
        if (empty($instance) || !isset($instance['heading_font']))
            return;

        $font_import = siteorigin_widget_get_font($instance['heading_font']);
        if (!empty($font_import['css_import'])) {
            return $font_import['css_import'];
        }
    }

}

siteorigin_widget_register('lsow-heading', __FILE__, 'LSOW_Heading_Widget');