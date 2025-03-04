<?php
/**
 * Responsive_Accordion class.
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
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

/**
 * ResponsiveAccordion widget class.
 *
 * @since 7.0.0
 */
class Responsive_Accordion extends Widget_Base
{
  /**
   * ResponsiveAccordion constructor.
   *
   * @param array $data
   * @param null  $args
   *
   * @throws \Exception
   */
  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_register_style('responsive-tabs', plugins_url('/assets/css/responsive-tabs.min.css', RESPONSIVE_TABS_FOR_ELEMENTOR), [], VERSION);

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
    return 'responsive-accordion';
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
    return __('Responsive Accordion', 'responsive-tabs-for-elementor');
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
    return 'icon-icon-tabs-left-accordion';
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
    return ['responsive_accordions'];
  }

  /**
   * Enqueue styles.
   */
  public function get_style_depends()
  {
    $styles = ['responsive-tabs'];

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
        'tab_name'    => __('Title', 'responsive-tabs-for-elementor'),
        'tab_content' => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
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
        'tab_name',
        [
            'label'              => __('Tab Name', 'responsive-tabs-for-elementor'),
            'type'               => Controls_Manager::TEXT,
            'default'            => __('Title', 'responsive-tabs-for-elementor'),
            'label_block'        => true,
            'frontend_available' => true,
            'dynamic'            => [
                'active' => true,
            ],
        ]
    );

    $sub_tabs_number = range(0, 10);
    $sub_tabs_number = array_combine($sub_tabs_number, $sub_tabs_number);

    $repeater->add_responsive_control(
        'sub_tabs_to_show',
        [
            'label'   => esc_html__('Sub Tabs To Show', 'responsive-tabs-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => '0',
            'options' => $sub_tabs_number,
        ]
    );
    $repeater->add_control(
        'tab_content',
        [
            'label'     => __('Tab Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'rows'      => 20,
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => '0',
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_1',
        [
            'label'       => __('<span class="editor-sub-tab-title">First Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_1',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_2',
        [
            'label'       => __('<span class="editor-sub-tab-title">Second Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['2', '3', '4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_2',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['2', '3', '4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_3',
        [
            'label'       => __('<span class="editor-sub-tab-title">Third Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['3', '4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_3',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['3', '4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_4',
        [
            'label'       => __('<span class="editor-sub-tab-title">Fourth Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_4',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['4', '5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_5',
        [
            'label'       => __('<span class="editor-sub-tab-title">Fifth Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_5',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['5', '6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_6',
        [
            'label'       => __('<span class="editor-sub-tab-title">Sixth Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_6',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['6', '7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_7',
        [
            'label'       => __('<span class="editor-sub-tab-title">Seventh Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_7',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['7', '8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_8',
        [
            'label'       => __('<span class="editor-sub-tab-title">Eighth Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_8',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['8', '9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_9',
        [
            'label'       => __('<span class="editor-sub-tab-title">Ninth Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => ['9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_9',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => ['9', '10'],
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_name_10',
        [
            'label'       => __('<span class="editor-sub-tab-title">Tenth Sub Tab</span>Name', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Title', 'responsive-tabs-for-elementor'),
            'label_block' => true,
            'dynamic'     => [
                'active' => true,
            ],
            'condition'   => [
                'sub_tabs_to_show' => '10',
            ],
        ]
    );
    $repeater->add_control(
        'sub_tab_content_10',
        [
            'label'     => __('Content', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => __('<h3>Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
            'dynamic'   => [
                'active' => true,
            ],
            'condition' => [
                'sub_tabs_to_show' => '10',
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
            'default'     => [$this->get_default_tab()],
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
    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'background',
            'types'          => ['classic', 'gradient'],
            'fields_options' => [
                'background' => [
                    'label' => 'Tabs Background',
                ],
            ],
            'selector'       => '{{WRAPPER}} .responsive-accordion-section',
        ]
    );
    $this->add_control(
        'tabs_active_background',
        [
            'label'     => 'Tabs Active Background',
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab'                                                                                       => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tabs-content-list'                                                                                    => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tabs-content-list .responsive-tab-content.active-tab'                                                 => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab:hover, {{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab:hover'     => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name.active-sub-tab:hover, {{WRAPPER}} .responsive-accordion-section .sub-tab-name.active-sub-tab:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab .accordion-wrapper-counter'                                                            => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab .accordion-wrapper-title .accordion-item-title'                                        => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab .accordion-wrapper-title .accordion-items-sub-title'                                   => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .responsive-sub-tab-name .sub-tab-name.active-sub-tab'                                                            => 'background-color: {{VALUE}};',
            ],
        ]
    );
    $this->add_control(
        'tabs_hover_background',
        [
            'label'     => esc_html__('Tabs hover background', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab:hover, {{WRAPPER}} .responsive-accordion-section .responsive-tab:hover' => 'background-color: {{VALUE}};',
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
    $this->add_control(
        'tab_space',
        [
            'label'     => esc_html__('Space Between Tabs', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .accordion-item-title h2.responsive-tab-name a.responsive-tab-link' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2); padding-top: calc({{SIZE}}{{UNIT}} / 2);',
                '{{WRAPPER}} .responsive-accordion-section .accordion-wrapper-counter a.responsive-tab-link'                   => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2); padding-top: calc({{SIZE}}{{UNIT}} / 2);',
                '{{WRAPPER}} .responsive-accordion-section a.responsive-tab-link'                                              => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2); padding-top: calc({{SIZE}}{{UNIT}} / 2);',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab-content.active-tab'                                 => 'padding-top: calc({{SIZE}}{{UNIT}} / 2.5);',
            ],
        ]
    );
    $this->add_control(
        'tab_name_color',
        [
            'label'     => esc_html__('Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab-info h2>a>span' => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab span'           => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'tab_name_typography',
            'label'    => esc_html__('Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .responsive-tab-info h2>a>span',
        ]
    );
    $this->add_control(
        'arrow_size',
        [
            'label'     => esc_html__('Arrow size', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 12,
                    'max' => 60,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .accordion-arrow .icon-arrow, {{WRAPPER}} .accordion-arrow span.icon-arrow i'       => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .accordion-arrow .icon-arrow svg, {{WRAPPER}} .accordion-arrow span.icon-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->add_control(
        'active_tab_name_color',
        [
            'label'     => esc_html__('Active Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab h2>a>span'  => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab:hover a span'          => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab span'       => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab:hover span.icon-arrow' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'active_tab_name_typography',
            'label'    => esc_html__('Active Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab h2>a>span',
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'tab_counter_typography',
            'label'    => esc_html__('Tab Counter Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .responsive-tab-link-counter span',
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'active_tab_counter_typography',
            'label'    => esc_html__('Active Tab Counter Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .responsive-tab.active-tab .responsive-tab-link-counter span',
        ]
    );
    $this->end_controls_section();

    // Sub Tab styles Section
    $this->start_controls_section(
        'sub_tabs_styles_section',
        [
            'label' => esc_html__('Sub Tabs Styles ', 'responsive-tabs-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'active_sub_tab_border_width',
        [
            'label'     => esc_html__('Active Sub Tab Border Width', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 10,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name.active-sub-tab:before' => 'border-width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name:before'                => 'border-width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name:hover'                 => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->add_control(
        'active_sub_tab_border_color',
        [
            'label'     => esc_html__('Active Sub Tab Border Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name.active-sub-tab:before' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name:hover:before'          => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name'                       => 'border-color: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'sub_tab_name_color',
        [
            'label'     => esc_html__('Sub Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name h3 a' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'sub_tab_name_typography',
            'label'    => esc_html__('Sub Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .sub-tab-name h3 a',
        ]
    );
    $this->add_control(
        'active_sub_tab_name_color',
        [
            'label'     => esc_html__('Active Sub Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name.active-sub-tab h3 a' => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-accordion-section .sub-tab-name:hover h3 a'          => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'active_sub_tab_name_typography',
            'label'    => esc_html__('Active Sub Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .sub-tab-name.active-sub-tab h3 a',
        ]
    );
    $this->end_controls_section();

    // Content Styles Section
    $this->start_controls_section(
        'content_styles_section',
        [
            'label' => esc_html__('Content Styles', 'responsive-tabs-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'content_title_typography',
            'label'    => esc_html__('Title Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .responsive-tab-content h3, {{WRAPPER}} .responsive-accordion-section .responsive-sub-tab-content h3, {{WRAPPER}} .responsive-accordion-section .responsive-tab .tab-content-mobile h3, {{WRAPPER}} .responsive-accordion-section .sub-tab-name .sub-tab-content-mobile h3',
        ]
    );
    $this->add_control(
        'content_color',
        [
            'label'     => esc_html__('Content Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .responsive-accordion-section .responsive-tab-content p, {{WRAPPER}} .responsive-accordion-section .responsive-sub-tab-content p, {{WRAPPER}} .responsive-accordion-section .responsive-tab .tab-content-mobile p, {{WRAPPER}} .responsive-accordion-section .sub-tab-name .sub-tab-content-mobile p'    => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-accordion-section .responsive-tab-content h3, {{WRAPPER}} .responsive-accordion-section .responsive-sub-tab-content h3, {{WRAPPER}} .responsive-accordion-section .responsive-tab .tab-content-mobile h3, {{WRAPPER}} .responsive-accordion-section .sub-tab-name .sub-tab-content-mobile h3' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'content_typography',
            'label'    => esc_html__('Content Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-accordion-section .responsive-tab-content p, {{WRAPPER}} .responsive-accordion-section .responsive-sub-tab-content p, {{WRAPPER}} .responsive-accordion-section .responsive-tab .tab-content-mobile p, {{WRAPPER}} .responsive-accordion-section .sub-tab-name .sub-tab-content-mobile p',
        ]
    );
    $this->end_controls_section();

    // Arrow Style
    $this->start_controls_section(
        'style_arrow',
        [
            'label' => __('Arrow', 'responsive-tabs-for-elementor'),
        ]
    );
    $this->add_control(
        'icon_arrow',
        [
            'label'       => __('Choose Arrow Icon', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::ICONS,
            'default'     => [
                'value'   => 'fas fa-chevron-down',
                'library' => 'fa-solid',
            ],
            'recommended' => [
                'fa-solid' => [
                    'arrow-down',
                    'caret-down',
                    'angle-down',
                ],
            ],
        ]
    );
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


    if ($settings['tab']) { ?>

      <section class="responsive-tabs-section responsive-accordion-section">
        <div class="responsive-tabs-block">
          <ul class="responsive-tabs-list">
            <?php $counter = 1;
            foreach ($settings['tab'] as $item) { ?>
              <li class="responsive-tab <?php if ($counter === 1) { ?>active-tab <?php } ?> tab-desktop">
                <div class="responsive-tab-info <?php if ($item['sub_tabs_to_show']) {
                  echo 'subtitle-item';
                } ?>">
                  <div class="accordion-wrapper-counter">
                    <a class="responsive-tab-link responsive-tab-link-counter"
                       href=<?php echo esc_url("#responsive-tab-$counter") ?>>
                          <span>
                              <?php if ($counter < 10)
                                echo '0';
                              echo $counter; ?>
                          </span>
                    </a>
                  </div>
                  <div class="accordion-wrapper-title">
                    <div class="accordion-item-title">
                      <h2 class="responsive-tab-name">
                        <a class="responsive-tab-link" href=<?php echo esc_url("#responsive-tab-$counter") ?>>
                          <span><?php echo wp_kses($item['tab_name'], []); ?></span>
                          <div class="accordion-arrow"><span
                                class="icon-arrow"><?php Icons_Manager::render_icon($settings['icon_arrow']); ?></span>
                          </div>
                        </a>
                      </h2>
                    </div>

                    <div class="accordion-items-sub-title">
                      <?php if ($item['sub_tabs_to_show'] > '0') { ?>
                        <ul class="responsive-sub-tab-name">
                          <?php for ($i = 1; $i <= (int)$item['sub_tabs_to_show']; $i++) { ?>
                            <li class="sub-tab-name <?php if ($i === 1) { ?>active-sub-tab <?php } ?>">
                              <h3 class="sub-tab-title">
                                <a href=<?php echo esc_url("#responsive-sub-tab-$counter-$i") ?>><?php echo wp_kses($item["sub_tab_name_" . $i], []); ?></a>
                              </h3>
                              <div class="accordion-item-body">
                                <div class="sub-tab-content-mobile"><?php echo wp_kses_post($item["sub_tab_content_" . $i]); ?></div>
                              </div>
                            </li>
                          <?php } ?>
                        </ul>
                      <?php } else { ?>
                        <?php if ($item['tab_content']) { ?>
                          <div
                              id=<?php echo esc_attr("#responsive-tab-$counter") ?> class="tab-content-mobile"><?php echo wp_kses_post($item['tab_content']); ?></div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </li>
              <?php $counter++;
            } ?>
          </ul>
        </div>
        <ul class="responsive-tabs-content-list">
          <?php $counter = 1;
          foreach ($settings['tab'] as $item) { ?>
            <li id=<?php echo esc_attr("responsive-tab-$counter") ?> class="responsive-tab-content <?php if (
                $counter === 1
            ) { ?>active-tab <?php } ?>">
            <?php if ($item['sub_tabs_to_show'] > '0') { ?>
              <ul class="responsive-sub-tab-content">
                <?php for ($i = 1; $i <= (int)$item['sub_tabs_to_show']; $i++) { ?>
                  <li id=<?php echo esc_attr("responsive-sub-tab-$counter-$i") ?> class="sub-tab-content <?php if (
                      $i === 1
                  ) { ?>active-sub-tab <?php } ?>">
                  <?php echo wp_kses_post($item["sub_tab_content_" . $i]); ?>
                  </li>
                <?php } ?>
              </ul>
            <?php } else { ?>
              <?php if ($item['tab_content']) { ?>
                <div
                    id=<?php echo esc_attr("#responsive-tab-$counter") ?>><?php echo wp_kses_post($item['tab_content']); ?></div>
              <?php } ?>
            <?php } ?>
            </li>
            <?php $counter++;
          } ?>
        </ul>
      </section>
    <?php } ?>
  <?php }
}
