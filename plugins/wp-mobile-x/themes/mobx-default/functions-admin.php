<?php
/**
 * 主题functions功能文件，此文件用户后台管理功能，前端不会加载
 * 
 * @author Lomu
 * @since Default - WP Mobile X 1.0
 */

// 注册主题设置相关信息
add_filter('mobx_form_options', 'mobx_form_options_default', 10);
function mobx_form_options_default( $options ){
    $options[] = array(
        'title' => __('Theme Options', 'wp-mobile-x'),
        'icon' => 'home',
        'option' => array(
            array(
                'title' => __('Slider', 'wp-mobile-x'),
                'type' => 'title'
            ),
            array(
                "type" => "repeat",
                "name" => "slider",
                "options" => array(
                    array(
                        "name" => 'slider_img',
                        "title" => __('Image', 'wp-mobile-x'),
                        "desc" => __('All images should be same size', 'wp-mobile-x'),
                        "type" => 'upload'
                    ),
                    array(
                        "name" => 'slider_title',
                        "title" => __('Title', 'wp-mobile-x'),
                        "type" => 'text'
                    ),
                    array(
                        "name" => 'slider_url',
                        "title" => __('Link', 'wp-mobile-x'),
                        "std" => '',
                        "type" => 'text'
                    )
                )
            ),
            array(
                'name' => 'color',
                'title' => __('Color', 'wp-mobile-x'),
                'desc' => __('Main color for pages', 'wp-mobile-x'),
                'type' => 'color'
            ),
            array(
                'name' => 'hover',
                'title' => __('Hover Color', 'wp-mobile-x'),
                'desc' => __('Color for hover and focus', 'wp-mobile-x'),
                'type' => 'color'
            )
        )
    );
    return $options;
}