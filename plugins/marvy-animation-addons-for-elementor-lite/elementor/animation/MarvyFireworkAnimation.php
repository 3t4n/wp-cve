<?php


namespace MarvyElementor\animation;

if (!defined('ABSPATH')) exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;


class MarvyFireworkAnimation
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
        $element->start_controls_section('marvy_firework_animation_section',
            [
                'label' => __('<div style="float: right"><img src="' . plugin_dir_url(__DIR__) . 'assets/images/logo.png" height="15px" width="15px" style="float:left;" alt=""></div> Firework Animation', 'marvy-animation-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $element->add_control('marvy_enable_firework_animation',
            [
                'label' => esc_html__('Enable Firework Animation', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $element->add_responsive_control(
            'marvy_firework_animation_circle_min_size',
            [
                'label' => esc_html__('Circle Min Size', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
                'desktop_default' => 30,
                'tablet_default' => 30,
                'mobile_default' => 20,
            ]
        );

        $element->add_responsive_control(
            'marvy_firework_animation_circle_max_size',
            [
                'label' => esc_html__('Circle Max Size', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
                'desktop_default' => 30,
                'tablet_default' => 30,
                'mobile_default' => 20,
            ]
        );

        $element->add_control(
            'marvy_firework_animation_background_color',
            [
                'label' => esc_html__('Background Color', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000'
            ]
        );

        $element->add_control(
            'marvy_firework_animation_color_type',
            [
                'label' => esc_html__('Color Variant', 'marvy-animation-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'random',
                'options' => [
                    'single' => [
                        'title' => esc_html__('Single', 'marvy-animation-addons-for-elementor-lite'),
                        'icon' => 'eicon-paint-brush'
                    ],
                    'multiple' => [
                        'title' => esc_html__('Multiple', 'marvy-animation-addons-for-elementor-lite'),
                        'icon' => 'eicon-barcode'
                    ],
                    'random' => [
                        'title' => esc_html__('Random', 'marvy-lang'),
                        'icon' => 'eicon-sync'
                    ]
                ],
                'condition' => [
                    'marvy_enable_firework_animation' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'marvy_firework_animation_color_single',
            [
                'label' => esc_html__('Single Color', 'marvy-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ecf87f',
                'condition' => [
                    'marvy_enable_firework_animation' => 'yes',
                    'marvy_firework_animation_color_type' => 'single'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'marvy-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f51720'
            ]
        );

        $element->add_control(
            'marvy_firework_animation_color_multiples',
            [
                'label' => esc_html__('Multiple Colors', 'marvy-lang'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['color' => '#f51720'],
                    ['color' => '#fa26a0'],
                    ['color' => '#f8d210'],
                    ['color' => '#2ff3e0'],
                ],
                'condition' => [
                    'marvy_enable_firework_animation' => 'yes',
                    'marvy_firework_animation_color_type' => 'multiple'
                ],
                'title_field' => '{{{ color }}}',
            ]
        );

        $element->add_control(
            'firework_important_note',
            [
                'show_label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<p>Colors will set randomly from the list</p>', 'marvy-lang'),
                'condition' => [
                    'marvy_enable_firework_animation' => 'yes',
                    'marvy_firework_animation_color_type' => 'multiple'
                ],
                'content_classes' => 'marvy-editor-notice',
            ]
        );

        $element->end_controls_section();
    }

    public function before_render($element)
    {
        $settings = $element->get_settings_for_display();

        $default_post_id = get_option('elementor_active_kit');
        $color =  get_post_meta($default_post_id, '_elementor_page_settings', true);

        if ($settings['marvy_enable_firework_animation'] === 'yes') {
            $colors = [];
            if($settings['marvy_firework_animation_color_type'] === 'multiple') {               
                foreach ($settings['marvy_firework_animation_color_multiples'] as $multi) {               
                    if(!empty($multi) && !empty($multi['__globals__']['color'])){
                        $control_color = explode("=",$multi['__globals__']['color'])[1];
                        $global_color = array_merge($color['system_colors'], $color['custom_colors']);
                        $index = array_search($control_color, array_column($global_color, "_id"));
                        $colors[] = $global_color[$index]['color'];
                    }
                    else{
                        $colors[] = $multi['color'];
                    }                 
                }             
            }

            $marvy_settings =  [
                'data-marvy_firework_animation_circle_min_size' => 'marvy_firework_animation_circle_min_size',
                'data-marvy_firework_animation_circle_max_size' => 'marvy_firework_animation_circle_max_size',
                'data-marvy_firework_animation_color_type' => 'marvy_firework_animation_color_type',
                'data-marvy_firework_animation_color_single' => 'marvy_firework_animation_color_single',
                // 'data-marvy_firework_animation_color_multiples' => implode("--,--",$colors),
                'data-marvy_firework_animation_background_color' => 'marvy_firework_animation_background_color',

                // 'data-marvy_firework_animation_circle_min_size_tablet' => 'marvy_firework_animation_circle_min_size_tablet',
                // 'data-marvy_firework_animation_circle_max_size_tablet' => 'marvy_firework_animation_circle_max_size_tablet',

                // 'data-marvy_firework_animation_circle_min_size_mobile' => 'marvy_firework_animation_circle_min_size_mobile',
                // 'data-marvy_firework_animation_circle_max_size_mobile' => 'marvy_firework_animation_circle_max_size_mobile',
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
            $marvy_settings['data-marvy_enable_firework_animation'] =   'true';
            $marvy_settings['data-marvy_firework_animation_color_multiples'] = implode("--,--",$colors);

            $element->add_render_attribute(
                '_wrapper',
                $marvy_settings
            );
        } else {
            $element->add_render_attribute('_wrapper', 'data-marvy_enable_firework_animation', 'false');
        }
    }

}