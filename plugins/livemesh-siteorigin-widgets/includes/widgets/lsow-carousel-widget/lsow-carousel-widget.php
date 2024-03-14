<?php

/*
Widget Name: Carousel
Description: Display a list of custom HTML content as a carousel.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Carousel_Widget extends SiteOrigin_Widget {

    private $custom_css;

    function __construct() {
        parent::__construct(
            'lsow-carousel',
            __('Carousel', 'livemesh-so-widgets'),
            array(
                'description' => __('Display a collection of html elements as a carousel.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#carousel-widget'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'elements' => array(
                    'type' => 'repeater',
                    'label' => __('HTML Elements', 'livemesh-so-widgets'),
                    'item_name' => __('HTML Element', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='elements-name']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'name' => array(
                            'type' => 'text',
                            'label' => __('Name', 'livemesh-so-widgets'),
                            'description' => __('The title to identify the HTML element', 'livemesh-so-widgets'),
                        ),

                        'text' => array(
                            'type' => 'tinymce',
                            'label' => __('HTML element', 'livemesh-so-widgets'),
                            'description' => __('The HTML content for the carousel item.', 'livemesh-so-widgets'),
                        ),
                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('General Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'custom_css' => array(
                            'type' => 'textarea',
                            'label' => __('Custom CSS for presentation of the HTML elements. Will be embedded inline with the page.', 'livemesh-so-widgets'),
                            'rows' => 20
                        ),

                        'customize' => array(
                            'type' => 'notify',
                            'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets')  . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
                        ),
                    )
                ),

                'carousel_settings' => array(
                    'type' => 'section',
                    'label' => __('Carousel Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'arrows' => array(
                            'type' => 'checkbox',
                            'label' => __('Prev/Next Arrows?', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'dots' => array(
                            'type' => 'checkbox',
                            'label' => __('Show dot indicators for navigation?', 'livemesh-so-widgets'),
                        ),

                        'autoplay' => array(
                            'type' => 'checkbox',
                            'label' => __('Autoplay?', 'livemesh-so-widgets'),
                            'description' => __('Should the carousel autoplay as in a slideshow.', 'livemesh-so-widgets'),
                            'default' => false
                        ),


                        'autoplay_speed' => array(
                            'type' => 'number',
                            'label' => __('Autoplay speed in ms', 'livemesh-so-widgets'),
                            'default' => 3000
                        ),


                        'animation_speed' => array(
                            'type' => 'number',
                            'label' => __('Autoplay animation speed in ms', 'livemesh-so-widgets'),
                            'default' => 300
                        ),

                        'pause_on_hover' => array(
                            'type' => 'checkbox',
                            'label' => __('Pause on mouse hover?', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'display_columns' => array(
                            'type' => 'slider',
                            'label' => __('Columns per row', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 5,
                            'integer' => true,
                            'default' => 3
                        ),

                        'scroll_columns' => array(
                            'type' => 'slider',
                            'label' => __('Columns to scroll', 'livemesh-so-widgets'),
                            'min' => 1,
                            'max' => 5,
                            'integer' => true,
                            'default' => 3
                        ),

                        'gutter' => array(
                            'type' => 'number',
                            'label' => __('Gutter', 'livemesh-so-widgets'),
                            'description' => __('Space between columns.', 'livemesh-so-widgets'),
                            'default' => 10
                        ),

                        'responsive' => array(
                            'type' => 'section',
                            'label' => __('Responsive', 'livemesh-so-widgets'),
                            'hide' => true,
                            'fields' => array(
                                'tablet' => array(
                                    'type' => 'section',
                                    'label' => __('Tablet', 'livemesh-so-widgets'),
                                    'fields' => array(
                                        'display_columns' => array(
                                            'type' => 'slider',
                                            'label' => __('Columns per row', 'livemesh-so-widgets'),
                                            'min' => 1,
                                            'max' => 5,
                                            'integer' => true,
                                            'default' => 2
                                        ),
                                        'scroll_columns' => array(
                                            'type' => 'slider',
                                            'label' => __('Columns to scroll', 'livemesh-so-widgets'),
                                            'min' => 1,
                                            'max' => 5,
                                            'integer' => true,
                                            'default' => 2
                                        ),
                                        'gutter' => array(
                                            'type' => 'number',
                                            'label' => __('Gutter', 'livemesh-so-widgets'),
                                            'description' => __('Space between columns.', 'livemesh-so-widgets'),
                                            'default' => 10
                                        ),
                                        'width' => array(
                                            'type' => 'text',
                                            'label' => __('Resolution', 'livemesh-so-widgets'),
                                            'description' => __('The resolution to treat as a tablet resolution.', 'livemesh-so-widgets'),
                                            'default' => 800,
                                            'sanitize' => 'intval',
                                        )
                                    )
                                ),
                                'mobile' => array(
                                    'type' => 'section',
                                    'label' => __('Mobile Phone', 'livemesh-so-widgets'),
                                    'fields' => array(
                                        'display_columns' => array(
                                            'type' => 'slider',
                                            'label' => __('Columns per row', 'livemesh-so-widgets'),
                                            'min' => 1,
                                            'max' => 5,
                                            'integer' => true,
                                            'default' => 1
                                        ),
                                        'scroll_columns' => array(
                                            'type' => 'slider',
                                            'label' => __('Columns to scroll', 'livemesh-so-widgets'),
                                            'min' => 1,
                                            'max' => 5,
                                            'integer' => true,
                                            'default' => 1
                                        ),
                                        'gutter' => array(
                                            'type' => 'number',
                                            'label' => __('Gutter', 'livemesh-so-widgets'),
                                            'description' => __('Space between columns.', 'livemesh-so-widgets'),
                                            'default' => 10
                                        ),
                                        'width' => array(
                                            'type' => 'text',
                                            'label' => __('Resolution', 'livemesh-so-widgets'),
                                            'description' => __('The resolution to treat as a mobile resolution.', 'livemesh-so-widgets'),
                                            'default' => 480,
                                            'sanitize' => 'intval',
                                        )
                                    )
                                )

                            )
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
                    'lsow-slick-carousel',
                    LSOW_PLUGIN_URL . 'assets/js/slick' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(
            array(
                array(
                    'lsow-slick',
                    LSOW_PLUGIN_URL . 'assets/css/slick.css',
                    array(),
                    LSOW_VERSION
                ),
                array(
                    'lsow-icomoon',
                    LSOW_PLUGIN_URL . 'assets/css/icomoon.css',
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
                    'lsow-carousel',
                    plugin_dir_url(__FILE__) . 'css/style.css'
                )
            )
        );

        add_action('wp_enqueue_scripts', array($this, 'init_custom_css'), 15); // load as late as possible

    }

    function modify_instance($instance) {
        return $instance;
    }

    function init_custom_css() {

        if (!is_active_widget(false, false, $this->id_base)) {
            return;
        }

        $custom_css = '';

        $instances = $this->get_settings();

        if (array_key_exists($this->number, $instances)) {
            $instance = $instances[$this->number];
            if (!empty($instance))
                $custom_css = $instance['settings']['custom_css'];
        }

        if ($custom_css <> '') {
            $custom_css = $custom_css . "\n";
            wp_add_inline_style('lsow-carousel', $custom_css); // after custom.css file
        }

    }

    function get_less_variables($instance) {
        return array(

            'gutter' => intval($instance['carousel_settings']['gutter']) . 'px',

            // All the responsive sizes
            'tablet_width' => intval($instance['carousel_settings']['responsive']['tablet']['width']) . 'px',
            'tablet_gutter' => intval($instance['carousel_settings']['responsive']['tablet']['gutter']) . 'px',
            'mobile_width' => intval($instance['carousel_settings']['responsive']['mobile']['width']) . 'px',
            'mobile_gutter' => intval($instance['carousel_settings']['responsive']['mobile']['gutter']) . 'px',
        );
    }

    function get_template_variables($instance, $args) {
        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'elements' => !empty($instance['elements']) ? $instance['elements'] : array(),
            'carousel_settings' => $instance['carousel_settings']
        ));

        unset($settings['carousel_settings']['responsive']);

        $settings['carousel_settings']['tablet_width'] = $instance['carousel_settings']['responsive']['tablet']['width'];
        $settings['carousel_settings']['tablet_display_columns'] = $instance['carousel_settings']['responsive']['tablet']['display_columns'];
        $settings['carousel_settings']['tablet_scroll_columns'] = $instance['carousel_settings']['responsive']['tablet']['scroll_columns'];
        $settings['carousel_settings']['mobile_width'] = $instance['carousel_settings']['responsive']['mobile']['width'];
        $settings['carousel_settings']['mobile_display_columns'] = intval($instance['carousel_settings']['responsive']['mobile']['display_columns']);
        $settings['carousel_settings']['mobile_scroll_columns'] = $instance['carousel_settings']['responsive']['mobile']['scroll_columns'];

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-carousel', __FILE__, 'LSOW_Carousel_Widget');