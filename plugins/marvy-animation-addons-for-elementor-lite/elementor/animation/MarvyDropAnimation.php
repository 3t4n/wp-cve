<?php
namespace MarvyElementor\animation;

if( !defined( 'ABSPATH' ) ) exit;
use Elementor\Controls_Manager;

class MarvyDropAnimation {

  public function __construct(){
    add_action('elementor/frontend/section/before_render', array($this, 'before_render'), 1);
    add_action('elementor/element/section/section_layout/after_section_end',array($this,'register_controls'), 1 );

    add_action('elementor/frontend/container/before_render', array($this, 'before_render'), 1);
    add_action('elementor/element/container/section_layout_container/after_section_end', array($this, 'register_controls'), 1);
  }

  public function register_controls($element)
  {
    $element->start_controls_section('marvy_drop_animation_section',
      [
        'label' => __('<div style="float: right"><img src="'.plugin_dir_url(__DIR__).'assets/images/logo.png" height="15px" width="15px" style="float:left;" alt=""></div> Drops Animation', 'marvy-animation-addons-for-elementor-lite'),
        'tab' => Controls_Manager::TAB_LAYOUT
      ]
    );

    $element->add_control('marvy_enable_drop_animation',
      [
        'label' => esc_html__('Enable Drops Animation', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::SWITCHER,
      ]
    );

    $element->add_control(
      'marvy_drop_animation_types',
      [
        'label' => esc_html__('Shape Type', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::SELECT,
        'default' => 'dotWithLine',
        'options' => [
          'dotWithLine' => esc_html__('Dot With Line', 'marvy-animation-addons-for-elementor-lite'),
          'onlyLine' => esc_html__('Only Line', 'marvy-animation-addons-for-elementor-lite'),
          'onlyDot' => esc_html__('Only Dot', 'marvy-animation-addons-for-elementor-lite'),
          'candleShape' => esc_html__('Candle Shape', 'marvy-animation-addons-for-elementor-lite')
        ],
        'condition' => [
          'marvy_enable_drop_animation' => 'yes'
        ]
      ]
    );

    $element->add_control(
      'marvy_drop_animation_line_color',
      [
        'label' => esc_html__('Line Color First', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#3f87b1',
        'condition' => [
          'marvy_enable_drop_animation' => 'yes',
          'marvy_drop_animation_types!' => 'onlyDot',
        ]
      ]
    );

    $element->add_control(
      'marvy_drop_animation_line_color_second',
      [
        'label' => esc_html__('Line Color Second', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#dedede',
        'condition' => [
          'marvy_enable_drop_animation' => 'yes',
          'marvy_drop_animation_types!' => 'onlyDot',
        ]
      ]
    );

    $element->add_control(
      'marvy_drop_animation_drop_dot_color',
      [
        'label' => esc_html__('Dot Color', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#0851ff',
        'condition' => [
          'marvy_enable_drop_animation' => 'yes',
          'marvy_drop_animation_types!' => 'onlyLine'
        ]
      ]
    );

    $element->add_control(
      'marvy_drop_animation_drop_dot_size',
      [
        'label' => esc_html__('Dot Size', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::NUMBER,
        'default' => 0.9,
        'min' => 0,
        'max' => 2,
        'step' => 0.1,
        'condition' => [
          'marvy_enable_drop_animation' => 'yes',
          'marvy_drop_animation_types!' => ['onlyLine','candleShape']
        ]
      ]
    );

    $element->add_control(
      'marvy_drop_animation_drop_speed',
      [
        'label' => esc_html__('Speed', 'marvy-lang'),
        'type' => Controls_Manager::NUMBER,
        'default' => 4,
        'min' => 1,
        'max' => 10,
        'step' => 1,
        'condition' => [
          'marvy_enable_drop_animation' => 'yes'
        ]
      ]
    );

    $element->end_controls_section();

  }

  public function before_render($element) {
    $settings = $element->get_settings();

    $default_post_id = get_option('elementor_active_kit');
    $color =  get_post_meta($default_post_id, '_elementor_page_settings', true);

    if ($settings['marvy_enable_drop_animation'] === 'yes') {

      $marvy_settings =  [
        'data-marvy_drop_animation_types' => 'marvy_drop_animation_types',
        'data-marvy_drop_animation_line_color' =>'marvy_drop_animation_line_color',
        'data-marvy_drop_animation_line_color_second' => 'marvy_drop_animation_line_color_second',
        'data-marvy_drop_animation_drop_dot_color' => 'marvy_drop_animation_drop_dot_color',
        'data-marvy_drop_animation_drop_dot_size' => 'marvy_drop_animation_drop_dot_size',
        'data-marvy_drop_animation_drop_speed' => 'marvy_drop_animation_drop_speed',
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
      $marvy_settings['data-marvy_enable_drop_animation'] =   'true';

      $element->add_render_attribute(
        '_wrapper',
        $marvy_settings
      );
    } else {
      $element->add_render_attribute('_wrapper', 'data-marvy_enable_drop_animation', 'false');
    }
  }
}
