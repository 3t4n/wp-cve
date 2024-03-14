<?php
/**
 * Responsive_Accordion_With_Counter class.
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
 * AccordionWithCounter widget class.
 *
 * @since 7.0.0
 */
class Responsive_Accordion_With_Counter extends Widget_Base
{
  /**
   * AccordionWithCounter constructor.
   *
   * @param array $data
   * @param null  $args
   *
   * @throws \Exception
   */
  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);
    wp_register_style('responsive-accordion-with-counter', plugins_url('/assets/css/responsive-accordion-with-counter.min.css', RESPONSIVE_TABS_FOR_ELEMENTOR), [], VERSION);

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
    return 'responsive-accordion-with-counter';
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
    return __('Accordion With Counter', 'responsive-tabs-for-elementor');
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
    return 'icon-accordion-with-counter';
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
    $styles = ['responsive-accordion-with-counter'];

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
      'tab_name'    => __('Lorem ipsum', 'responsive-tabs-for-elementor'),
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
      'tab_name',
      [
        'label'              => __('Tab Name', 'responsive-tabs-for-elementor'),
        'type'               => Controls_Manager::TEXT,
        'default'            => __('Lorem ipsum', 'responsive-tabs-for-elementor'),
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
          '{{WRAPPER}} .accordion-with-counter-tabs-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
          '{{WRAPPER}} .accordion-with-counter-tabs-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'label' => 'Tabs Background',
          ],
        ],
        'selector'       => '{{WRAPPER}} .accordion-with-counter-tabs-block',
      ]
    );

    $this->add_responsive_control(
      'tab_position',
      [
        'label'     => esc_html__('Position', 'responsive-tabs-for-elementor'),
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
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_box'     => 'flex-direction: {{VALUE}}',
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_label'   => 'flex-direction: {{VALUE}}',
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_counter' => 'flex-direction: {{VALUE}}',
        ],
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
          "{{WRAPPER}} .accordion-with-counter-format-container" => 'height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tab_space',
      [
        'label'     => esc_html__('Spacing', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 140,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_box' => 'gap: {{SIZE}}{{UNIT}}',
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
      'tab_name_color',
      [
        'label'     => esc_html__('Name Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_label' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_name_typography',
        'label'    => esc_html__('Name Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_label',
      ]
    );
    $this->add_control(
      'active_tab_name_color',
      [
        'label'     => esc_html__('Active Tab Name Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_input:checked+label' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'active_tab_name_typography',
        'label'    => esc_html__('Active Tab Name Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_input:checked+label',
      ]
    );

    $this->add_responsive_control(
      'tab_title_space',
      [
        'label'     => esc_html__('Spacing', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 140,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_label' => 'gap: {{SIZE}}{{UNIT}}',
        ],
      ]
    );
    $this->end_controls_section();

    // Counter And Divider Styles Section
    $this->start_controls_section(
      'counter_divider_styles_section',
      [
        'label' => esc_html__('Counter/Divider Styles', 'responsive-tabs-for-elementor'),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_control(
      'tab_counter_color',
      [
        'label'     => esc_html__('Counter Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_counter' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_counter_typography',
        'label'    => esc_html__('Counter Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_counter',
      ]
    );
    $this->add_control(
      'active_tab_counter_color',
      [
        'label'     => esc_html__('Active Counter Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_input:checked + label .accordion-with-counter-tabs_counter' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'active_tab_counter_typography',
        'label'    => esc_html__('Active Counter Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_input:checked + label .accordion-with-counter-tabs_counter',
      ]
    );

    $this->add_control(
      'tab_divider_color',
      [
        'label'     => esc_html__('Divider Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_dash' => 'background-color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_responsive_control(
      'tab_divider_width',
      [
        'label'     => esc_html__('Divider Size', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 10,
            'max' => 30,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_input:hover + label .accordion-with-counter-tabs_dash'   => 'width: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_input:checked + label .accordion-with-counter-tabs_dash' => 'width: {{SIZE}}{{UNIT}}',
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
      'title_content_color',
      [
        'label'     => esc_html__('Title Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_title' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'title_content_typography',
        'label'    => esc_html__('Title Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_title',
      ]
    );
    $this->add_responsive_control(
      'title_content_align',
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
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_title' => 'text-align: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'content_color',
      [
        'label'     => esc_html__('Content Color', 'responsive-tabs-for-elementor'),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_discr' => 'color: {{VALUE}}',
        ],
        'separator' => 'before',
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'content_typography',
        'label'    => esc_html__('Content Typography', 'responsive-tabs-for-elementor'),
        'selector' => '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_discr',
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
          '{{WRAPPER}} .accordion-with-counter .accordion-with-counter-tabs_discr' => 'text-align: {{VALUE}}',
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

      <section class="accordion-with-counter accordion-with-counter-tabs-block">
        <div class="accordion-with-counter-format-container">
          <div class="accordion-with-counter-tabs_box">
            <div class="accordion-with-counter-tabs_wrapper">
              <?php $counter_tab = 1;
              foreach ($settings['tab'] as $item_tab) { ?>
                <input type="radio" id="tab-<?php echo $counter_tab; ?>" class="accordion-with-counter-tabs_input"
                       name="tabs" <?php echo $counter_tab === 1 ? 'checked=""' : '' ?>>
                <label for="tab-<?php echo $counter_tab; ?>" class="accordion-with-counter-tabs_label">
                  <span class="accordion-with-counter-tabs_counter">
                    <span class="accordion-with-counter-tabs_dash"></span>
                    <?php if ($counter_tab < 10)
                      echo '0';
                    echo $counter_tab; ?>
                  </span>
                  <?php echo wp_kses_post($item_tab['tab_name']); ?>
                </label>
                <?php $counter_tab++;
              } ?>
            </div>

            <div class="accordion-with-counter-tabs_content">
              <?php $counter = 1;
              foreach ($settings['tab'] as $item) { ?>
                <div class="accordion-with-counter-tabs_item" id="tabs__item-<?php echo $counter; ?>">
                  <h2 class="accordion-with-counter-tabs_title"><?php echo wp_kses_post($item['tab_name']); ?></h2>
                  <div class="accordion-with-counter-tabs_discr">
                    <?php echo wp_kses_post($item['tab_content']); ?>
                  </div>
                </div>
                <?php $counter++;
              } ?>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>
  <?php }
}
