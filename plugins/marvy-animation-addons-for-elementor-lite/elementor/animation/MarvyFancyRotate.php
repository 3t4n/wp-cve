<?php

namespace MarvyElementor\animation;

if (!defined('ABSPATH')) exit;

use Elementor\Controls_Manager;

class MarvyFancyRotate
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
    $element->start_controls_section(
      'marvy_fancy_rotate_section',
      [
        'label' => __('<div style="float: right"><img src="' . plugin_dir_url(__DIR__) . 'assets/images/logo.png" height="15px" width="15px" style="float:left;" alt=""></div> Fancy Rotate Animation', 'marvy-animation-addons-for-elementor-lite'),
        'tab' => Controls_Manager::TAB_LAYOUT
      ]
    );

    $element->add_control(
      'marvy_enable_fancy_rotate',
      [
        'label' => esc_html__('Enable Fancy Rotate Animation', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::SWITCHER,
      ]
    );

    $element->add_control(
      'marvy_enable_fancy_rotate_circle',
      [
        'label' => esc_html__('Show Circle', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'condition' => [
          'marvy_enable_fancy_rotate' => 'yes',
        ]
      ]
    );

    $element->add_control(
      'marvy_enable_fancy_rotate_particle',
      [
        'label' => esc_html__('Show Particle', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'condition' => [
          'marvy_enable_fancy_rotate' => 'yes',
        ]
      ]
    );

    $element->add_control(
      'marvy_fancy_rotate_first_color',
      [
        'label' => esc_html__('First Particle Color', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#ec542f',
        'condition' => [
          'marvy_enable_fancy_rotate' => 'yes',
          'marvy_enable_fancy_rotate_particle' => 'yes',
        ]
      ]
    );

    $element->add_control(
      'marvy_fancy_rotate_second_color',
      [
        'label' => esc_html__('Second Particle Color', 'marvy-animation-addons-for-elementor-lite'),
        'type' => Controls_Manager::COLOR,
        'default' => '#ffffff',
        'condition' => [
          'marvy_enable_fancy_rotate' => 'yes',
          'marvy_enable_fancy_rotate_particle' => 'yes',
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

    if ($settings['marvy_enable_fancy_rotate'] === 'yes') {
      $marvy_settings =  [
        'data-marvy_enable_fancy_rotate_circle' => 'marvy_enable_fancy_rotate_circle',
        'data-marvy_enable_fancy_rotate_particle' => 'marvy_enable_fancy_rotate_particle',
        'data-marvy_fancy_rotate_first_color' => 'marvy_fancy_rotate_first_color',
        'data-marvy_fancy_rotate_second_color' => 'marvy_fancy_rotate_second_color',
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
      $marvy_settings['data-marvy_enable_fancy_rotate'] =   'true';

      $element->add_render_attribute(
        '_wrapper',
        $marvy_settings
      );
    } else {
      $element->add_render_attribute('_wrapper', 'data-marvy_enable_fancy_rotate', 'false');
    }
  }
}
