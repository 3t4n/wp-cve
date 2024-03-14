<?php

/*
Widget Name: Posts Carousel
Description: Display blog posts or custom post types as a carousel.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Posts_Carousel_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-posts-carousel',
            __('Posts Carousel', 'livemesh-so-widgets'),
            array(
                'description' => __('Display blog posts or custom post types as a carousel', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#post-carousel'
            ),
            array(),
            false,
            plugin_dir_path(__FILE__)
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
                    'lsow-icomoon',
                    LSOW_PLUGIN_URL . 'assets/css/icomoon.css',
                    array(),
                    LSOW_VERSION
                ),

                array(
                    'lsow-slick',
                    LSOW_PLUGIN_URL . 'assets/css/slick.css',
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
                    'lsow-posts-carousel',
                    plugin_dir_url(__FILE__) . 'css/style.css'
                )
            )
        );

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

    function get_widget_form() {
        return
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'posts' => array(
                    'type' => 'posts',
                    'label' => __('Posts query', 'livemesh-so-widgets'),
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('General Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'taxonomy_chosen' => array(
                            'type' => 'select',
                            'label' => __('Choose the taxonomy to display info.', 'livemesh-so-widgets'),
                            'description' => __('Choose the taxonomy to use for display of taxonomy information for posts/custom post types.', 'livemesh-so-widgets'),
                            'options' => lsow_get_taxonomies_map(),
                            'default' => 'category',
                        ),

                        'image_linkable' => array(
                            'type' => 'checkbox',
                            'label' => __('Link Images to Posts?', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'image_size' => array(
                            'type' => 'image-size',
                            'label' => __('Image Size', 'livemesh-so-widgets'),
                            'default' => 'large',
                        ),

                        'link_target' => array(
                            'type' => 'select',
                            'label' => __('Link Target', 'livemesh-so-widgets'),
                            'default' => '_self',
                            'options' => array(
                                '_self' => __('Same Window', 'livemesh-so-widgets'),
                                '_blank' => __('New Window', 'livemesh-so-widgets'),
                            ),
                        ),

                        'display_title_on_thumbnail' => array(
                            'type' => 'checkbox',
                            'label' => __('Display project title on post/project thumbnail?', 'livemesh-so-widgets'),
                            'default' => true,
                        ),

                        'display_taxonomy_on_thumbnail' => array(
                            'type' => 'checkbox',
                            'label' => __('Display taxonomy info on post/project thumbnail?', 'livemesh-so-widgets'),
                            'default' => false,
                        ),

                        'display_title' => array(
                            'type' => 'checkbox',
                            'label' => __('Display posts title below the post item?', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'display_summary' => array(
                            'type' => 'checkbox',
                            'label' => __('Display post excerpt/summary below the post item?', 'livemesh-so-widgets'),
                            'default' => true
                        ),

                        'display_read_more' => array(
                            'type' => 'checkbox',
                            'label' => __('Display read more link to the post/portfolio?', 'livemesh-so-widgets'),
                            'default' => false,
                        ),

                        'read_more_text' => array(
                            'type' => 'text',
                            'label' => __('Read More Text', 'livemesh-so-widgets'),
                            'default' => __('Read More', 'livemesh-so-widgets'),
                        ),

                        'post_meta' => array(
                            'type' => 'section',
                            'label' => __('Post Meta', 'livemesh-so-widgets'),
                            'fields' => array(

                                'display_author' => array(
                                    'type' => 'checkbox',
                                    'label' => __('Display post author info below the post item?', 'livemesh-so-widgets'),
                                    'default' => false
                                ),

                                'display_post_date' => array(
                                    'type' => 'checkbox',
                                    'label' => __('Display post date info below the post item?', 'livemesh-so-widgets'),
                                    'default' => false
                                ),

                                'display_taxonomy' => array(
                                    'type' => 'checkbox',
                                    'label' => __('Display taxonomy info below the post item?', 'livemesh-so-widgets'),
                                    'default' => false
                                ),

                            )

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

                        'customize' => array(
                            'type' => 'notify',
                            'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets')  . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
                        ),
                    )
                ),
            );
    }

    function modify_form($form) {
        $form['settings']['fields']['taxonomy_chosen']['options'] = lsow_get_taxonomies_map();
        return $form;
    }

    function get_template_variables($instance, $args) {

        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'posts' => $instance['posts'],
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

siteorigin_widget_register('lsow-posts-carousel', __FILE__, 'LSOW_Posts_Carousel_Widget');