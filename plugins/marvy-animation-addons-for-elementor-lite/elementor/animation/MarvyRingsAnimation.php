<?php
namespace MarvyElementor\animation;

if( !defined( 'ABSPATH' ) ) exit;
use Elementor\Controls_Manager;

class MarvyRingsAnimation {

  public function __construct(){
    add_action('elementor/frontend/section/before_render', array($this, 'before_render'), 1);
    add_action('elementor/element/section/section_layout/after_section_end',array($this,'register_controls'), 1 );
    
    add_action('elementor/frontend/container/before_render', array($this, 'before_render'), 1);
    add_action('elementor/element/container/section_layout_container/after_section_end', array($this, 'register_controls'), 1);
  }

  public function register_controls($element)
  {
    $element->start_controls_section('marvy_rings_animation_section',
      [
        'label' => __('<div style="float: right"><img src="'.plugin_dir_url(__DIR__).'assets/images/logo.png" height="15px" width="15px" style="float:left;" alt=""></div> Rings Animation', 'marvy-animation-addons-for-elementor-lite'),
        'tab' => Controls_Manager::TAB_LAYOUT
      ]
    );

    $element->add_control('marvy_enable_rings_animation',
      [
        'label' => esc_html__('Enable Rings Animation', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::SWITCHER,
      ]
    );

    $element->add_control(
      'marvy_rings_animation_rings_random_color',
      [
        'label' => esc_html__('Rings Random Color', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#87fd02',
        'condition' => [
          'marvy_enable_rings_animation' => 'yes',
        ]
      ]
    );

    $element->add_control(
      'marvy_rings_animation_background_color',
      [
        'label' => esc_html__('Background Color', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#202428',
        'condition' => [
          'marvy_enable_rings_animation' => 'yes',
        ]
      ]
    );

    $element->add_control(
      'marvy_rings_animation_background_opacity',
      [
        'label' => esc_html__('Background Opacity', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::NUMBER,
        'default' => 1,
        'min' => 0,
        'max' => 1,
        'step' => 0.1,
        'condition' => [
          'marvy_enable_rings_animation' => 'yes',
        ]
      ]
    );

    $element->end_controls_section();

  }

  public function before_render($element) {
    $settings = $element->get_settings();

    $default_post_id = get_option('elementor_active_kit');
    $color =  get_post_meta($default_post_id, '_elementor_page_settings', true);

    if ($settings['marvy_enable_rings_animation'] === 'yes') {

      $marvy_settings =  [
          'data-marvy_rings_animation_rings_random_color' => 'marvy_rings_animation_rings_random_color',
          'data-marvy_rings_animation_background_color' => 'marvy_rings_animation_background_color',
          'data-marvy_rings_animation_background_opacity' => 'marvy_rings_animation_background_opacity',
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
      $marvy_settings['data-marvy_enable_rings_animation'] =   'true';

      $element->add_render_attribute(
        '_wrapper',
        $marvy_settings
      );
    } else {
      $element->add_render_attribute('_wrapper', 'data-marvy_enable_rings_animation', 'false');
    }
  }
}
