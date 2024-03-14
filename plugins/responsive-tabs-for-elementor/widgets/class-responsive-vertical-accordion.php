<?php
/**
 * Responsive_Vertical_Accordion class.
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
 * ResponsiveVerticalTabs widget class.
 *
 * @since 7.0.0
 */
class Responsive_Vertical_Accordion extends Widget_Base
{
  /**
   * ResponsiveVerticalTabs constructor.
   *
   * @param array $data
   * @param null $args
   *
   * @throws \Exception
   */
  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_register_style('responsive-vertical-accordion', plugins_url('/assets/css/responsive-vertical-accordion.min.css', RESPONSIVE_TABS_FOR_ELEMENTOR), [], VERSION);

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
    return 'responsive-vertical-accordion';
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
    return __('Responsive Vertical Accordion', 'responsive-tabs-for-elementor');
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
    return 'icon-icon-tabs-vertical-accordion';
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
    $styles = ['responsive-vertical-accordion'];

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
      'tab_image'   => [
        'url' => Utils::get_placeholder_image_src(),
      ],
      'tab_name'    => __('Title', 'responsive-tabs-for-elementor'),
      'tab_content' => __('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
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
      'tab_image_active',
      [
        'label'        => __('Active Image', 'responsive-tabs-for-elementor'),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => __('On', 'responsive-tabs-for-elementor'),
        'label_off'    => __('Off', 'responsive-tabs-for-elementor'),
        'return_value' => 'yes',
        'default'      => 'yes',
      ]
    );
    $repeater->add_control(
      'tab_image',
      [
        'label'     => __('Choose Image', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::MEDIA,
        'default'   => [
          'url' => Utils::get_placeholder_image_src(),
        ],
        'condition' => [
          'tab_image_active' => 'yes',
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

    $repeater->add_control(
      'tab_content',
      [
        'label'   => __('Tab Content', 'responsive-tabs-for-elementor'),
        'type'    => Controls_Manager::WYSIWYG,
        'default' => __('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-tabs-for-elementor</p>'),
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_direction',
      [
        'label'   => esc_html__('Tabs Position', 'responsive-tabs-for-elementor'),
        'type'    => Controls_Manager::SELECT,
        'options' => [
          'row'    => "Row",
          'column' => "Column",
        ],
        'default' => 'row'
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
        'selector'       => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper',
      ]
    );
    $this->add_group_control(
      Group_Control_Background::get_type(),
      [
        'name'           => 'active_background',
        'types'          => ['classic', 'gradient'],
        'fields_options' => [
          'background' => [
            'label' => 'Active Tabs Background',
          ],
        ],
        'selector'       => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active',
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
          "{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper" => 'height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_space',
      [
        'label'     => esc_html__('Image Spacing', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 140,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-image-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
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
      'tab_border_width',
      [
        'label'     => esc_html__('Tab Border Width', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 10,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );
    $this->add_control(
      'tab_border_color',
      [
        'label'     => esc_html__('Tab Border Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper' => 'border-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'tab_active_border_width',
      [
        'label'     => esc_html__('Active Tab Border Width', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 10,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active' => 'border-width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );
    $this->add_control(
      'tab_active_border_color',
      [
        'label'     => esc_html__('Active Tab Border Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active' => 'border-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_control(
      'tab_name_color',
      [
        'label'     => esc_html__('Tab Name Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-title'    => 'color: {{VALUE}}',
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-subtitle' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_name_typography',
        'label'    => esc_html__('Tab Name Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-title',
      ]
    );
    $this->add_control(
      'active_tab_name_color',
      [
        'label'     => esc_html__('Active Tab Name Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active .vertical-tab-subtitle' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'active_tab_name_typography',
        'label'    => esc_html__('Active Tab Name Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active .vertical-tab-subtitle',
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-subtitle' => 'text-align: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'tab_counter_color',
      [
        'label'     => esc_html__('Tab Counter Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active .vertical-tab-counter' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_counter_typography',
        'label'    => esc_html__('Tab Counter Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active .vertical-tab-counter',
      ]
    );
    $this->add_responsive_control(
      'tab_counter_align',
      [
        'label'     => esc_html__('Alignment Counter', 'responsive-tabs-for-elementor'),
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper .vertical-tab-counter' => 'text-align: {{VALUE}}',
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active p' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'content_typography',
        'label'    => esc_html__('Content Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper.active p',
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper p' => 'text-align: {{VALUE}}',
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
      'tab_image_row_position',
      [
        'label'     => esc_html__('Images Position', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'row-reverse' => [
            'title' => esc_html__('Row-Reverse', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-order-start',
          ],
          'row'         => [
            'title' => esc_html__('Row', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-order-end',
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-image-wrapper' => 'flex-direction: {{VALUE}}',
        ],
        'condition' => ['tab_direction' => 'row'],
      ]
    );
    $this->add_responsive_control(
      'tab_image_column_position',
      [
        'label'     => esc_html__('Images Position', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'column-reverse' => [
            'title' => esc_html__('Column-Reverse', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-v-align-top',
          ],
          'column'         => [
            'title' => esc_html__('Column', 'responsive-tabs-for-elementor'),
            'icon'  => 'eicon-v-align-bottom',
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-image-wrapper' => 'flex-direction: {{VALUE}}',
        ],
        'condition' => ['tab_direction' => 'column'],
      ]
    );
    $this->add_responsive_control(
      'tab_image_alignment',
      [
        'label'     => esc_html__('Alignment', 'responsive-tabs-for-elementor'),
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-img' => 'text-align: {{VALUE}}',
        ],
        'condition' => ['tab_direction' => 'column'],
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper .vertical-tab-content-img img' => 'width: {{VALUE}};',
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
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper .vertical-tab-content-img img' => '--container-widget-width: {{SIZE}}{{UNIT}}; --container-widget-flex-grow: 0; width: var( --container-widget-width, {{SIZE}}{{UNIT}} );',
        ],
        'condition'  => ['tab_image_width' => 'initial'],
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'tab_image_border',
        'selector' => '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper .work-items-content img',
      ]
    );
    $this->add_responsive_control(
      'tab_image_border_radius',
      [
        'label'      => esc_html__('Border Radius', 'responsive-tabs-for-elementor'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors'  => [
          '{{WRAPPER}} .vertical-tabs-section .vertical-tab-content-wrapper .work-items-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

    if ($settings['tab']) { ?>

      <section id="vertical-tabs" class="vertical-tabs-section">
        <div class="vertical-tab<?php if ($settings['tab_direction'] === "column") { ?> column-tab<?php } ?>">
          <?php $counter = 1;
          foreach ($settings['tab'] as $item) { ?>
            <div class="vertical-tab-content-wrapper">
              <h3 class="vertical-tab-title"><?php echo wp_kses_post($item['tab_name']); ?></h3>
              <div class="work-items-content">
                <div class="vertical-tab-image-wrapper">
                  <div class="vertical-tab-content">
                    <h2 class="vertical-tab-counter"><?php if ($counter < 10)
                        echo '0';
                      echo $counter; ?>.</h2>
                    <h4 class="vertical-tab-subtitle"><?php echo wp_kses_post($item['tab_name']); ?></h4>
                    <?php echo wp_kses_post($item['tab_content']); ?>
                  </div>
                  <?php if (wp_kses_post($item['tab_image_active']) === 'yes') { ?>
                    <div class="vertical-tab-content-img">
                      <img src="<?php echo esc_url($item['tab_image']['url']) ?>">
                    </div>
                  <?php } ?>
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
