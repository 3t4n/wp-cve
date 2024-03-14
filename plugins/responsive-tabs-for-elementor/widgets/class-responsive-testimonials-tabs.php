<?php
/**
 * Responsive_Testimonials_Tabs class.
 *
 * @category   Class
 * @package    ResponsiveTabsForElementor
 * @subpackage WordPress
 * @author     UAPP GROUP
 * @copyright  2024 UAPP GROUP
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link
 * @since      7.0.0
 * php version 7.4.1
 */

namespace ResponsiveTabsForElementor\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

/**
 * ResponsiveTestimonialsTabs widget class.
 *
 * @since 7.0.0
 */
class Responsive_Testimonials_Tabs extends Widget_Base
{
  /**
   * ResponsiveTestimonialsTabs constructor.
   *
   * @param array $data
   * @param null $args
   *
   * @throws \Exception
   */
  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_register_style('responsive-testimonials-tabs', plugins_url('/assets/css/responsive-testimonials-tabs.min.css', RESPONSIVE_TABS_FOR_ELEMENTOR), [], VERSION);

    if (!function_exists('get_plugin_data')) {
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    if (get_plugin_data(ELEMENTOR__FILE__)['Version'] >= "3.5.0") {
      wp_register_script('responsive-tabs', plugins_url('/assets/js/responsive-tabs-widget-handler.min.js', RESPONSIVE_TABS_FOR_ELEMENTOR), ['elementor-frontend'], VERSION, true);
    } else {
      wp_register_script('responsive-tabs', plugins_url('/assets/js/responsive-tabs-widget-old-elementor-handler.min.js', RESPONSIVE_TABS_FOR_ELEMENTOR), ['elementor-frontend'], VERSION, true);
    }
  }

  /**
   * Retrieve the widget name.
   *
   * @return string Widget name.
   * @since  7.0.0
   *
   * @access public
   *
   */
  public function get_name()
  {
    return 'responsive-testimonials-tabs';
  }

  /**
   * Retrieve the widget title.
   *
   * @return string Widget title.
   * @since  7.0.0
   *
   * @access public
   *
   */
  public function get_title()
  {
    return __('Responsive Testimonials Tabs', 'responsive-tabs-for-elementor');
  }

  /**
   * Retrieve the widget icon.
   *
   * @return string Widget icon.
   * @since  7.0.0
   *
   * @access public
   *
   */
  public function get_icon()
  {
    return 'icon-icon-tabs-testimonials-accordion';
  }

  /**
   * Retrieve the list of categories the widget belongs to.
   *
   * Used to determine where to display the widget in the editor.
   *
   * Note that currently Elementor supports only one category.
   * When multiple categories passed, Elementor uses the first one.
   *
   * @return array Widget categories.
   * @since  7.0.0
   *
   * @access public
   *
   */
  public function get_categories()
  {
    return ['responsive_tabs'];
  }

  /**
   * Enqueue styles.
   */
  public function get_style_depends()
  {
    $styles = ['responsive-testimonials-tabs'];

    return $styles;
  }

  public function get_script_depends()
  {
    $scripts = ['responsive-tabs'];

    return $scripts;
  }

  /**
   * Get default tab.
   *
   * @return array Default tab.
   * @since  7.0.0
   *
   * @access protected
   *
   */
  protected function get_default_tab()
  {
    return [
      'tab_image'         => [
        'url' => Utils::get_placeholder_image_src(),
      ],
      'tab_name'          => __('Milton Austin', 'responsive-tabs-for-elementor'),
      'tab_subtitle'      => __('Manager', 'responsive-tabs-for-elementor'),
      'tab_title_content' => __('Great collaboration', 'responsive-tabs-for-elementor'),
      'tab_content'       => __('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'responsive-tabs-for-elementor'),
    ];
  }

  /**
   * Register the widget controls.
   *
   * Adds different input fields to allow the user to change and customize the widget settings.
   *
   * @since  7.0.0
   *
   * @access protected
   */
  protected function _register_controls()
  {
    // Content Section
    $this->start_controls_section(
      'section_content',
      [
        'label' => __('Content', 'responsive-tabs-for-elementor'),
      ]
    );
    $repeater = new Repeater();
    $repeater->add_control(
      'tab_image',
      [
        'label'   => __('Choose Image', 'responsive-tabs-for-elementor'),
        'type'    => Controls_Manager::MEDIA,
        'default' => [
          'url' => Utils::get_placeholder_image_src(),
        ],
      ]
    );
    $repeater->add_control(
      'tab_name',
      [
        'label'              => __('Name', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('Milton Austin', 'responsive-tabs-for-elementor'),
        'label_block'        => true,
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
      ]
    );
    $repeater->add_control(
      'tab_subtitle',
      [
        'label'              => __('Title', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('Manager', 'responsive-tabs-for-elementor'),
        'label_block'        => true,
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
      ]
    );
    $repeater->add_control(
      'tab_rating_enable',
      [
        'label'        => __('Rating', 'responsive-tabs-for-elementor'),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => __('Show', 'responsive-tabs-for-elementor'),
        'label_off'    => __('Hide', 'responsive-tabs-for-elementor'),
        'return_value' => 'yes',
        'default'      => 'yes',
      ]
    );
    $repeater->add_control(
      'tab_rating',
      [
        'label'              => __('Rating', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::NUMBER,
        'min'                => 0,
        'max'                => 5,
        'step'               => 1,
        'default'            => 4,
        'frontend_available' => true,
        'condition'          => [
          'tab_rating_enable' => 'yes',
        ],
      ]
    );

    $repeater->add_control(
      'tab_title_content',
      [
        'label'              => __('Title Content', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('Great collaboration', 'responsive-tabs-for-elementor'),
        'label_block'        => true,
        'frontend_available' => true,
        'dynamic'            => [
          'active' => true,
        ],
      ]
    );

    $repeater->add_control(
      'tab_content',
      [
        'label'   => __('Tab Content', 'responsive-tabs-for-elementor'),
        'type'    => Controls_Manager::WYSIWYG,
        'default' => __('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'responsive-tabs-for-elementor'),
        'rows'    => 20,
        'dynamic' => [
          'active' => true,
        ],
      ]
    );
    $this->add_control(
      'tab',
      [
        'label'       => __('Repeater Tab', 'responsive-tabs-for-elementor'),
        'type'        => Controls_Manager::REPEATER,
        'fields'      => $repeater->get_controls(),
        'title_field' => 'Tab',
        'default'     => [$this->get_default_tab(), $this->get_default_tab(), $this->get_default_tab()],
      ]
    );
    $this->end_controls_section();

    // Additional Options Section
    $this->start_controls_section(
      'section_additional_options',
      [
        'label' => esc_html__('Additional Options', 'responsive-tabs-for-elementor'),
      ]
    );

    $this->add_responsive_control(
      'tab_select_position',
      [
        'label'              => esc_html__('Tab Select Position', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::SELECT,
        'options'            => [
          'row'    => 'Row',
          'column' => 'Column'
        ],
        'default'            => 'row',
        'frontend_available' => true,
      ]
    );
    $this->add_responsive_control(
      'tab_row_position',
      [
        'label'     => esc_html__('Tab Position', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'row'         => [
            'title' => esc_html__('Row', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-order-start',
          ],
          'row-reverse' => [
            'title' => esc_html__('Row-Reverse', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-order-end',
          ],
        ],
        'default'   => 'row',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs' => 'flex-direction: {{VALUE}}',
        ],
        'condition' => [
          'tab_select_position' => 'row'
        ]
      ]
    );

    $this->add_responsive_control(
      'tab_column_position',
      [
        'label'     => esc_html__('Tab Position', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'column'         => [
            'title' => esc_html__('Column', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-v-align-top',
          ],
          'column-reverse' => [
            'title' => esc_html__('Column-Reverse', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-v-align-bottom',
          ],
        ],
        'default'   => 'column',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs' => 'flex-direction: {{VALUE}}',
        ],
        'condition' => [
          'tab_select_position' => 'column'
        ]
      ]
    );

    $this->add_responsive_control(
      'tab_event_active',
      [
        'label'              => esc_html__('Tab Event Active', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::SELECT,
        'options'            => [
          'click' => 'Click',
          'hover' => 'Hover'
        ],
        'default'            => 'hover',
        'frontend_available' => true,
      ]
    );
    $this->end_controls_section();

    // General styles Section
    $this->start_controls_section(
      'general_styles_section',
      [
        'label' => esc_html__('General Styles', 'responsive-tabs-for-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_responsive_control(
      'tab_margin',
      [
        'label'      => esc_html__('Margin', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'tab_padding',
      [
        'label'      => esc_html__('Padding', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Background::get_type(),
      [
        'name'           => 'background',
        'types'          => ['classic', 'gradient'],
        'fields_options' => [
          'background' => [
            'label' => 'Background',
          ],
        ],
        'selector'       => '{{WRAPPER}} .testimonials-tabs',
      ]
    );
    $this->add_responsive_control(
      'tab_height',
      [
        'label'      => esc_html__('Height', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', 'vh'],
        'default'    => [
          'unit' => 'px',
        ],
        'range'      => [
          'px' => [
            'min' => 0,
            'max' => 800,
          ],
          'vh' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'selectors'  => [
          "{{WRAPPER}} .testimonials-tabs"                => 'height: {{SIZE}}{{UNIT}};',
          "{{WRAPPER}} .testimonials-tabs .cards"         => 'height: {{SIZE}}{{UNIT}};',
          "{{WRAPPER}} .testimonials-tabs .cards-wrapper" => 'height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_space',
      [
        'label'     => esc_html__('Content Spacing', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 140,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Tab styles Section
    $this->start_controls_section(
      'tabs_styles_section',
      [
        'label' => esc_html__('Tabs Styles', 'responsive-tabs-for-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_responsive_control(
      'tabs_margin',
      [
        'label'      => esc_html__('Margin', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'tabs_padding',
      [
        'label'      => esc_html__('Padding', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tabs_justify',
      [
        'label'     => esc_html__('Justified', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'flex-start'    => [
            'title' => esc_html__('Flex-Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-justify-start-h',
          ],
          'center'        => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-justify-center-h',
          ],
          'flex-end'      => [
            'title' => esc_html__('Flex-End', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-justify-end-h',
          ],
          'space-between' => [
            'title' => esc_html__('Space-Between', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-justify-space-between-h',
          ],
          'space-around'  => [
            'title' => esc_html__('Space-Around', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-justify-space-around-h',
          ],
          'space-evenly'  => [
            'title' => esc_html__('Space-Evenly', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-justify-space-evenly-h',
          ],
        ],
        'default'   => 'flex-start',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper .card'        => 'justify-content: {{VALUE}}',
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper .card.active' => 'justify-content: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_width',
      [
        'label'                => esc_html__('Width', 'responsive-tabs-for-elementor'),
        'type'                 => Controls_Manager::SELECT,
        'default'              => '',
        'options'              => [
          ''        => esc_html__('Default', 'responsive-tabs-for-elementor'),
          'inherit' => esc_html__('Full Width', 'responsive-tabs-for-elementor') . ' (100%)',
          'initial' => esc_html__('Custom', 'responsive-tabs-for-elementor'),
        ],
        'selectors_dictionary' => [
          'inherit' => '100%',
        ],
        'prefix_class'         => 'elementor-widget%s__width-',
        'selectors'            => [
          '{{WRAPPER}} .testimonials-tabs .cards' => 'width: {{VALUE}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'tab_custom_width',
      [
        'label'      => esc_html__('Width', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'default'    => [
          'unit' => '%',
        ],
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'range'      => [
          '%'  => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
          ],
          'px' => [
            'min'  => 0,
            'max'  => 300,
            'step' => 1,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards' => '--container-widget-width: {{SIZE}}{{UNIT}}; --container-widget-flex-grow: 0; width: var( --container-widget-width, {{SIZE}}{{UNIT}} );',
        ],
        'condition'  => ['tab_width' => 'initial'],
      ]
    );
    $this->add_responsive_control(
      'tabs_space',
      [
        'label'     => esc_html__('Tabs Spacing', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 140,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->start_controls_tabs('tabs_style');

    $this->start_controls_tab(
      'tab_normal',
      [
        'label' => esc_html__('Normal', 'responsive-tabs-for-elementor'),
      ]
    );

    $this->add_responsive_control(
      'tab_normal_margin',
      [
        'label'      => esc_html__('Margin', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'tab_normal_padding',
      [
        'label'      => esc_html__('Padding', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Background::get_type(),
      [
        'name'     => 'background_tab_normal',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .testimonials-tabs .cards .card',
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'tab_normal_border',
        'selector' => '{{WRAPPER}} .testimonials-tabs .cards .card',
      ]
    );
    $this->add_responsive_control(
      'tab_normal_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_control(
      'tab_name_color',
      [
        'label'     => esc_html__('Tab Name Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card h3' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_name_typography',
        'label'    => esc_html__('Tab Name Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .testimonials-tabs .card h3',
      ]
    );
    $this->add_responsive_control(
      'tab_name_align',
      [
        'label'     => esc_html__('Alignment Name', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card h3' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'tab_subtitle_color',
      [
        'label'     => esc_html__('Tab Position Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card p' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_subtitle_typography',
        'label'    => esc_html__('Tab Position Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .testimonials-tabs .card p',
      ]
    );
    $this->add_responsive_control(
      'tab_subtitle_align',
      [
        'label'     => esc_html__('Alignment Position', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card p' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_tab();

    $this->start_controls_tab(
      'tab_active',
      [
        'label' => esc_html__('Active', 'responsive-tabs-for-elementor'),
      ]
    );

    $this->add_responsive_control(
      'tab_active_margin',
      [
        'label'      => esc_html__('Margin', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .card.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'tab_active_padding',
      [
        'label'      => esc_html__('Padding', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .card.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Background::get_type(),
      [
        'name'     => 'background_tab_active',
        'types'    => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .testimonials-tabs .cards .card.active',
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'tab_active_border',
        'selector' => '{{WRAPPER}} .testimonials-tabs .cards .card.active',
      ]
    );
    $this->add_responsive_control(
      'tab_active_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .card.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_control(
      'active_tab_name_color',
      [
        'label'     => esc_html__('Active Tab Name Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card.active h3' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'active_tab_name_typography',
        'label'    => esc_html__('Active Tab Name Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .testimonials-tabs .card.active h3',
      ]
    );
    $this->add_responsive_control(
      'tab_name_align_active',
      [
        'label'     => esc_html__('Alignment Active Name', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card.active h3' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'active_tab_subtitle_color',
      [
        'label'     => esc_html__('Active Tab Position Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card.active p' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'active_tab_subtitle_typography',
        'label'    => esc_html__('Active Tab Position Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .testimonials-tabs .card.active p',
      ]
    );
    $this->add_responsive_control(
      'tab_subtitle_align_active',
      [
        'label'     => esc_html__('Alignment Position', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .card.active p' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_tab();
    $this->end_controls_tabs();
    $this->end_controls_section();

    // Content Styles Section
    $this->start_controls_section(
      'content_styles_section',
      [
        'label' => esc_html__('Content Styles', 'responsive-tabs-for-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_responsive_control(
      'tab_content_margin',
      [
        'label'      => esc_html__('Margin', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'tab_content_padding',
      [
        'label'      => esc_html__('Padding', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label'     => esc_html__('Title Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .content .contentBox h2' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'content_typography',
        'label'    => esc_html__('Title Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .testimonials-tabs .content .contentBox h2',
      ]
    );
    $this->add_responsive_control(
      'tab_title_align',
      [
        'label'     => esc_html__('Alignment Title', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'   => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'  => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .content .contentBox h2' => 'text-align: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'content_color',
      [
        'label'     => esc_html__('Content Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .content .contentBox .card-content-wrapper p' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'content_typography',
        'label'    => esc_html__('Content Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .testimonials-tabs .content .contentBox .card-content-wrapper p',
      ]
    );
    $this->add_responsive_control(
      'tab_content_align',
      [
        'label'     => esc_html__('Alignment Content', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'    => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center'  => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'   => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__('Justify', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-justify',
          ],
        ],
        'default'   => 'left',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .content .contentBox .card-content-wrapper p' => 'text-align: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'tabs_stars_color',
      [
        'label'     => esc_html__('Rating icon color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .contentBox .text span svg' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'tabs_icon_size',
      [
        'label'     => esc_html__('Rating icon size', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 5,
            'max' => 50,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .contentBox .text span svg' => 'width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'tabs_icon_space',
      [
        'label'     => esc_html__('Rating icon spacing', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 20,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .contentBox .text span' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_rating_align',
      [
        'label'     => esc_html__('Alignment Rating', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'flex-start' => [
            'title' => esc_html__('Left', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-left',
          ],
          'center'     => [
            'title' => esc_html__('Center', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-center',
          ],
          'flex-end'   => [
            'title' => esc_html__('Right', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-text-align-right',
          ],
        ],
        'default'   => 'flex-start',
        'selectors' => [
          '{{WRAPPER}} .testimonials-tabs .contentBox .text span' => 'justify-content: {{VALUE}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Image Styles Section
    $this->start_controls_section(
      'image_styles_section',
      [
        'label' => esc_html__('Image Styles', 'responsive-tabs-for-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_responsive_control(
      'tab_image_width',
      [
        'label'                => esc_html__('Width', 'responsive-tabs-for-elementor'),
        'type'                 => Controls_Manager::SELECT,
        'default'              => '',
        'options'              => [
          ''        => esc_html__('Default', 'responsive-tabs-for-elementor'),
          'inherit' => esc_html__('Full Width', 'responsive-tabs-for-elementor') . ' (100%)',
          'initial' => esc_html__('Custom', 'responsive-tabs-for-elementor'),
        ],
        'selectors_dictionary' => [
          'inherit' => '100%',
        ],
        'prefix_class'         => 'elementor-widget%s__width-',
        'selectors'            => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper .card img' => 'width: {{VALUE}}; height: {{VALUE}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_image_custom_width',
      [
        'label'      => esc_html__('Width', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::SLIDER,
        'default'    => [
          'unit' => '%',
        ],
        'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
        'range'      => [
          '%'  => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
          ],
          'px' => [
            'min'  => 0,
            'max'  => 800,
            'step' => 1,
          ],
        ],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper .card img' => '--container-widget-width: {{SIZE}}{{UNIT}}; --container-widget-flex-grow: 0; width: var( --container-widget-width, {{SIZE}}{{UNIT}} ); --container-widget-height: {{SIZE}}{{UNIT}}; --container-widget-flex-grow: 0; height: var( --container-widget-height, {{SIZE}}{{UNIT}} );',
        ],
        'condition'  => ['tab_image_width' => 'initial'],
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'tab_image_border',
        'selector' => '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper .card img',
      ]
    );
    $this->add_responsive_control(
      'tab_image_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .testimonials-tabs .cards .cards-wrapper .card img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->end_controls_section();
  }

  /**
   * Render the widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since  7.0.0
   *
   * @access protected
   */
  protected function render()
  {
    $settings = $this->get_settings_for_display();

    if (get_plugin_data(ELEMENTOR__FILE__)['Version'] < "3.5.0") {
      $this->add_render_attribute(
        'responsive_tabs',
        [
          'class'                 => ['tabs-params'],
          'data-eventactive-tabs' => esc_attr($settings['tab_event_active']),
        ]
      );
    }

    if ($settings['tab']) {
      if (get_plugin_data(ELEMENTOR__FILE__)['Version'] < "3.5.0") { ?>
        <div <?php echo $this->get_render_attribute_string('responsive_tabs'); ?>></div>
      <?php } ?>

      <section
          class="testimonials-tabs<?= esc_attr($settings['tab_select_position']) == 'column' ? ' testimonials-tabs-column' : ''; ?>">
        <div class="cards">
          <div class="cards-wrapper">
            <?php $counter = 1;
            foreach ($settings['tab'] as $item) { ?>
              <div class="card <?php if ($counter === 1) { ?>active<?php } ?>"
                   data-id="content-<?php echo $counter; ?>">
                <img src="<?php echo esc_url($item['tab_image']['url']) ?>" alt="">
                <div>
                  <h3><?php echo wp_kses_post($item['tab_name']); ?></h3>
                  <p><?php echo wp_kses($item['tab_subtitle'], []); ?></p>
                </div>
              </div>
              <?php $counter++;
            } ?>
          </div>
        </div>
        <div class="content">
          <?php $counter = 1;
          foreach ($settings['tab'] as $item) { ?>
            <div class="contentBox <?php if ($counter === 1) { ?>active<?php } ?>"
                 id="content-<?php echo $counter; ?>">
              <div class="text">
                <h2><?php echo wp_kses_post($item['tab_title_content']); ?></h2>
                <?php if ($item['tab_rating_enable'] === 'yes') { ?>
                  <span>
                    <?php
                    for ($i = 0; $i < $item['tab_rating']; $i++) { ?>
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                              clip-rule="evenodd"/>
                      </svg>
                    <?php } ?>
                  </span>
                <?php } ?>
                <div class="card-content-wrapper">
                  <?php echo wp_kses_post($item['tab_content']); ?>
                </div>
              </div>
            </div>
            <?php $counter++;
          } ?>
        </div>
      </section>
    <?php } ?>
  <?php }
}
