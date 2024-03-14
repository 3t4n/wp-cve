<?php

namespace MarvyElementor\animation;

if (!defined('ABSPATH')) exit;

use Elementor\Controls_Manager;

class MarvySnowAnimation
{

    public function __construct()
    {
        add_action('elementor/frontend/section/before_render', array($this, 'before_render'), 1);
        add_action('elementor/element/section/section_layout/after_section_end', array($this, 'register_controls'), 1);

        add_action('elementor/frontend/container/before_render', array($this, 'before_render'), 1);
        add_action('elementor/element/container/section_layout_container/after_section_end', array($this, 'register_controls'), 1);
    }

    public function register_controls($element)
    {
        $element->start_controls_section('marvy_snow_animation_section',
            [
                'label' => __('<div style="float: right"><img src="' . plugin_dir_url(__DIR__) . 'assets/images/logo.png" height="15px" width="15px" style="float:left;" alt=""></div> Snow Animation', 'marvy-animation-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $element->add_control('marvy_enable_snow_animation',
            [
                'label' => esc_html__('Enable Snow Animation', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $element->add_control(
            'marvy_snow_animation_count',
            [
                'label' => esc_html__('Count', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 200,
                'min' => 1,
                'max' => 1000,
                'step' => 100,
                'condition' => [
                    'marvy_enable_snow_animation' => 'yes',
                ]
            ]
        );

        $element->add_control(
            'marvy_snow_animation_size',
            [
                'label' => esc_html__('Size', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 50,
                'condition' => [
                    'marvy_enable_snow_animation' => 'yes',
                ]
            ]
        );

        $element->add_control(
            'marvy_snow_animation_color',
            [
                'label' => esc_html__('Color', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => [
                    'marvy_enable_snow_animation' => 'yes',
                ]
            ]
        );

        $element->add_control(
            'marvy_snow_animation_shadow_color',
            [
                'label' => esc_html__('Shadow Color', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'condition' => [
                    'marvy_enable_snow_animation' => 'yes',
                ]
            ]
        );

        $element->add_control(
            'marvy_snow_animation_shadow_size',
            [
                'label' => esc_html__('Shadow Size', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
                'max' => 50,
                'condition' => [
                    'marvy_enable_snow_animation' => 'yes',
                ]
            ]
        );

        $element->end_controls_section();

    }

    public function before_render($element)
    {
        $settings = $element->get_settings();

        $default_post_id = get_option('elementor_active_kit');
        $color =  get_post_meta($default_post_id, '_elementor_page_settings', true);

        if ($settings['marvy_enable_snow_animation'] === 'yes') {

            $marvy_settings =  [
                'data-marvy_snow_animation_count' => 'marvy_snow_animation_count',
                'data-marvy_snow_animation_size' => 'marvy_snow_animation_size',
                'data-marvy_snow_animation_color' => 'marvy_snow_animation_color',
                'data-marvy_snow_animation_shadow_color' => 'marvy_snow_animation_shadow_color',
                'data-marvy_snow_animation_shadow_size' => 'marvy_snow_animation_shadow_size',
            ];
        
            foreach ($marvy_settings as $key => $value) {
                if (isset($settings['__globals__'][$value]) && !empty($settings['__globals__'][$value]) && !empty($color)) {
                    $control_color = explode("=", $settings['__globals__'][$value])[1];
                    $global_color = array_merge($color['system_colors'], $color['custom_colors']);
                    $index = array_search($control_color, array_column($global_color, "_id"));
                    $marvy_settings[$key] = $global_color[$index]['color'];
                } else {
                    $marvy_settings[$key] = $settings[$value];
                }
            }
            $marvy_settings['data-marvy_enable_snow_animation'] =   'true';

            $element->add_render_attribute(
                '_wrapper',
                $marvy_settings
            );
        } else {
            $element->add_render_attribute('_wrapper', 'data-marvy_enable_snow_animation', 'false');
        }
    }
}
