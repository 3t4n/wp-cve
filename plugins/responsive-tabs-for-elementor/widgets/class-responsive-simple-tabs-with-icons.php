<?php
/**
 * Responsive_Simple_Tabs_With_Icons class.
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
 * ResponsiveSimpleTabsWithIcons widget class.
 *
 * @since 7.0.0
 */
class Responsive_Simple_Tabs_With_Icons extends Widget_Base
{
  /**
   * ResponsiveSimpleTabsWithIcons constructor.
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
    return 'responsive-simple-tabs-with-icons';
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
    return __('Responsive Simple Tabs With Icons', 'responsive-tabs-for-elementor');
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
    return 'icon-icon-tabs-right-accordion';
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
        'tab_icon'    => [
            'value'   => 'far fa-bell',
            'library' => 'fa-regular',
        ],
        'tab_name'    => __('Title', 'responsive-tabs-for-elementor'),
        'tab_content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
        'tab_icon',
        [
            'label'       => __('Choose Icon', 'responsive-tabs-for-elementor'),
            'type'        => Controls_Manager::ICONS,
            'default'     => [
                'value'   => 'far fa-bell',
                'library' => 'fa-regular',
            ],
            'recommended' => [
                'fa-solid'   => [
                    'crown',
                    'award',
                    'hourglass-half',
                    'location',
                ],
                'fa-regular' => [
                    'gem',
                    'lightbulb',
                ],
            ],
        ]
    );
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
            'default'   => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor'),
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
                    'label' => 'Main Background',
                ],
            ],
            'selector'       => '{{WRAPPER}} .responsive-simple-tabs-section',
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
    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'tabs-background',
            'types'          => ['classic', 'gradient'],
            'fields_options' => [
                'background' => [
                    'label' => 'Tabs Background',
                ],
            ],
            'selector'       => '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab',
        ]
    );
    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'active-tab-background',
            'types'          => ['classic', 'gradient'],
            'fields_options' => [
                'background' => [
                    'label' => 'Active Tab Background',
                ],
            ],
            'selector'       => '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab.active-tab',
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
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab'            => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab:last-child' => 'margin-bottom: 0;',
            ],
        ]
    );
    $this->add_responsive_control(
        'icon_size',
        [
            'label'     => esc_html__('Tab Icon Size', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 10,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab .responsive-tab-info i'   => 'font-size: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab .responsive-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}',
            ],
        ]
    );

    $this->add_control(
        'icon_color',
        [
            'label'     => esc_html__('Tab Icon Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab .responsive-tab-info .responsive-tab-icon > a'          => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab .responsive-tab-info .responsive-tab-icon > a svg path' => 'fill: {{VALUE}}',
            ],
        ]
    );

    $this->add_control(
        'active_icon_color',
        [
            'label'     => esc_html__('Active Tab Icon Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab.active-tab .responsive-tab-info .responsive-tab-icon > a'          => 'color: {{VALUE}}',
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab.active-tab .responsive-tab-info .responsive-tab-icon > a svg path' => 'fill: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'tab_name_color',
        [
            'label'     => esc_html__('Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab-info .responsive-tab-name > a' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'tab_name_typography',
            'label'    => esc_html__('Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab-info .responsive-tab-name > a',
        ]
    );
    $this->add_control(
        'active_tab_name_color',
        [
            'label'     => esc_html__('Active Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab.active-tab .responsive-tab-info .responsive-tab-name > a' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'active_tab_name_typography',
            'label'    => esc_html__('Active Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab.active-tab .responsive-tab-info .responsive-tab-name > a',
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
    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'sub-tab',
            'types'          => ['classic', 'gradient'],
            'fields_options' => [
                'background' => [
                    'label' => 'Sub Tab Background',
                ],
            ],
            'selector'       => '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name, {{WRAPPER}} .responsive-simple-tabs-section .responsive-tab-content>div, {{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name.active-sub-tab',
        ]
    );
    $this->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name'           => 'sub-tab-active',
            'types'          => ['classic', 'gradient'],
            'fields_options' => [
                'background' => [
                    'label' => 'Active Sub Tab Background',
                ],
            ],
            'selector'       => '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name.active-sub-tab',
        ]
    );
    $this->add_control(
        'space_between_sub_tab',
        [
            'label'     => esc_html__('Space Between Sub Tab', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name'                                                                           => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name:last-child'                                                                => 'margin-bottom: 0;',
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name.active-sub-tab, {{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name' => 'border-top-width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->add_control(
        'sub_tab_border_color',
        [
            'label'     => esc_html__('Sub Tab Border Color For Mobile', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name.active-sub-tab, {{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name' => 'border-top-color: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'sub_tab_name_color',
        [
            'label'     => esc_html__('Sub Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name h3 a' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'sub_tab_name_typography',
            'label'    => esc_html__('Sub Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name h3 a',
        ]
    );
    $this->add_control(
        'active_sub_tab_name_color',
        [
            'label'     => esc_html__('Active Sub Tab Name Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name.active-sub-tab h3 a' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'active_sub_tab_name_typography',
            'label'    => esc_html__('Active Sub Tab Name Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name.active-sub-tab h3 a',
        ]
    );
    $this->add_responsive_control(
        'accordion_icon_size',
        [
            'label'     => esc_html__('Accordion Icon Size', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 10,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name h3 a:before' => 'font-size: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .responsive-simple-tabs-section .sub-tab-name h3 a>span'   => 'width: calc(100% - {{SIZE}}{{UNIT}} - 5px);',
            ],
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
    $this->add_control(
        'content_color',
        [
            'label'     => esc_html__('Content Color', 'responsive-tabs-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .responsive-simple-tabs-section .responsive-tab-content'       => 'color: {{VALUE}}',
                '{{WRAPPER}}  .responsive-simple-tabs-section .responsive-sub-tab-content p' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'content_typography',
            'label'    => esc_html__('Content Typography', 'responsive-tabs-for-elementor'),
            'selector' => '{{WRAPPER}} .responsive-simple-tabs-section .responsive-tab-content, {{WRAPPER}} .responsive-simple-tabs-section .responsive-sub-tab-content p',
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


    if ($settings['tab']) { ?>

      <section
          class="responsive-tabs-section responsive-tabs-big-image-section responsive-tabs-images-section responsive-simple-tabs-section">
        <div class="responsive-tab-wrapper">
          <ul class="responsive-tabs-list">
            <?php $counter = 1;
            foreach ($settings['tab'] as $item) { ?>
              <li class="responsive-tab <?php if ($counter === 1) { ?>active-tab<?php } ?>">
                <div class="responsive-tab-info">
                                        <span class="responsive-tab-icon">
                                            <a class="responsive-tab-link"
                                               href=<?php echo esc_url("#responsive-tab-$counter") ?>><?php Icons_Manager::render_icon($item['tab_icon'], ['aria-hidden' => 'true']) ?></a>
                                        </span>
                  <h2 class="responsive-tab-name">
                    <a class="responsive-tab-link"
                       href=<?php echo esc_url("#responsive-tab-$counter") ?>><?php echo wp_kses($item['tab_name'], []); ?></a>
                  </h2>
                </div>

                <?php if ($item['sub_tabs_to_show'] > '0') { ?>
                  <ul class="responsive-sub-tab-name">
                    <?php for ($i = 1; $i <= (int)$item['sub_tabs_to_show']; $i++) { ?>
                      <li class="sub-tab-name <?php if ($i === 1) { ?>active-sub-tab <?php } ?>">
                        <h3>
                          <a href=<?php echo esc_url("#responsive-sub-tab-$counter-$i") ?>><span><?php echo wp_kses($item["sub_tab_name_" . $i], []); ?></span></a>
                        </h3>
                        <div class="accordion-item-body">
                          <div class="sub-tab-content-mobile"><?php echo wp_kses_post($item["sub_tab_content_" . $i]); ?></div>
                        </div>
                      </li>
                    <?php } ?>
                  </ul>
                <?php } else { ?>
                  <?php if ($item['tab_content']) { ?>
                    <div id=<?php echo esc_attr("#responsive-tab-$counter") ?> class="tab-content-mobile"><?php echo wp_kses_post($item['tab_content']); ?></div>
                  <?php } ?>
                <?php } ?>
              </li>
              <?php $counter++;
            } ?>
          </ul>
          <ul class="responsive-tabs-content-list">
            <?php $counter = 1;
            foreach ($settings['tab'] as $item) { ?>
              <li id=<?php echo esc_attr("responsive-tab-$counter") ?> class="responsive-tab-content <?php if ($counter === 1) { ?>active-tab<?php } ?>">
              <?php if ($item['sub_tabs_to_show'] > '0') { ?>
                <ul class="responsive-sub-tab-name">
                  <?php for ($i = 1; $i <= (int)$item['sub_tabs_to_show']; $i++) { ?>
                    <li class="sub-tab-name <?php if ($i === 1) { ?>active-sub-tab<?php } ?>">
                      <h3>
                        <a href=<?php echo esc_url("#responsive-sub-tab-$counter-$i") ?>><span><?php echo wp_kses($item["sub_tab_name_" . $i], []); ?></span></a>
                      </h3>
                      <div class="accordion-item-body">
                        <div class="sub-tab-content-accordion"><?php echo wp_kses_post($item["sub_tab_content_" . $i], []); ?></div>
                      </div>
                    </li>
                  <?php } ?>
                </ul>
              <?php } else { ?>
                <?php if ($item['tab_content']) { ?>
                  <div id=<?php echo esc_attr("#responsive-tab-$counter") ?>><?php echo wp_kses_post($item['tab_content']); ?></div>
                <?php } ?>
              <?php } ?>
              </li>
              <?php $counter++;
            } ?>
          </ul>
        </div>
      </section>
    <?php } ?>
  <?php }
}
