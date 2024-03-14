<?php

/**
 * One Page Navigation widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined('ABSPATH') || die();

class One_Page_Nav extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('One Page Nav', 'skt-addons-elementor');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-dot-navigation';
    }

    public function get_keywords() {
        return ['one', 'page', 'nav', 'scroll', 'on'];
    }

    protected function register_content_controls() {

        $this->start_controls_section(
            '_section_navigation',
            [
                'label' => __('Navigation', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'select_design',
            [
                'label' => __('Navigation Style', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'skt-opn-design-default',
                'options' => [
                    'skt-opn-design-default'  => __('Default', 'skt-addons-elementor'),
                    'skt-opn-design-berta' => __('Berta', 'skt-addons-elementor'),
                    'skt-opn-design-hagos' => __('Hagos', 'skt-addons-elementor'),
                    'skt-opn-design-magool' => __('Magool', 'skt-addons-elementor'),
                    'skt-opn-design-maxamed' => __('Maxamed', 'skt-addons-elementor'),
                    'skt-opn-design-shamso' => __('Shamso', 'skt-addons-elementor'),
                    'skt-opn-design-ubax' => __('Ubax', 'skt-addons-elementor'),
                    'skt-opn-design-xusni' => __('Xusni', 'skt-addons-elementor'),
                    'skt-opn-design-zahi' => __('Zahi', 'skt-addons-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $navigation = new \Elementor\Repeater();

        $navigation->add_control(
            'section_id',
            [
                'label' => __('Section ID', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Add your section ID here', 'skt-addons-elementor'),
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $navigation->add_control(
            'nav_title',
            [
                'label' => __('Navigation Title', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Add your Navigation Title here', 'skt-addons-elementor'),
                'classes' => 'skt-opn-design-refactor-others-title',
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $navigation->add_control(
            'icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                'classes' => 'skt-opn-design-refactor-default',
            ]
        );

        $navigation->add_control(
            'tooltip_title',
            [
                'label' => __('Tooltip Title', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Add your Tooltip Title here', 'skt-addons-elementor'),
                'classes' => 'skt-opn-design-refactor-default',
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $navigation->add_control(
            'custom_style_enable',
            [
                'label' => __('Enable Custom Style?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $navigation->add_control(
            'nav_content_color',
            [
                'label' => __('Content Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style_enable' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav {{CURRENT_ITEM}}.skt-opn-dotted-item .skt-opn-dot' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav {{CURRENT_ITEM}}.skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi {{CURRENT_ITEM}}.skt_addons_elementor_opn__item:not(:last-child)::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi {{CURRENT_ITEM}}.skt_addons_elementor_opn__item::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool {{CURRENT_ITEM}}.skt_addons_elementor_opn__item::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $navigation->add_control(
            'nav_content_color_hover',
            [
                'label' => __('Content Color Hover', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style_enable' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav {{CURRENT_ITEM}}.skt-opn-dotted-item:hover .skt-opn-dot' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav {{CURRENT_ITEM}}.skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover .skt_addons_elementor_opn__item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi {{CURRENT_ITEM}}.skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool {{CURRENT_ITEM}}.skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $navigation->add_control(
            'nav_content_color_active',
            [
                'label' => __('Content Color Active', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style_enable' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav {{CURRENT_ITEM}}.skt-opn-dotted-item.skt_addons_elementor_opn__item--current .skt-opn-dot' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax {{CURRENT_ITEM}}.skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi {{CURRENT_ITEM}}.skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi {{CURRENT_ITEM}}.skt_addons_elementor_opn__item::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav {{CURRENT_ITEM}}.skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current .skt_addons_elementor_opn__item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool {{CURRENT_ITEM}}.skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'navigation_lists',
            [
                'label' => __('Navigation List', 'skt-addons-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $navigation->get_controls(),
                'default' => [
                    [
                        'section_id' => 'section1',
                        'tooltip_title' => __('Section 1', 'skt-addons-elementor'),
                    ],
                    [
                        'section_id' => 'section2',
                        'tooltip_title' => __('Section 2', 'skt-addons-elementor'),
                    ],
                    [
                        'section_id' => 'section3',
                        'tooltip_title' => __('Section 3', 'skt-addons-elementor'),
                    ],
                ],
            ]
        );

        $this->add_control(
            'nav_horizontal_align',
            [
                'label' => __('Horizontal Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'skt-opn-left-side' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'skt-opn-right-side' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'skt-opn-right-side',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'nav_vertical_align',
            [
                'label' => __('Vertical Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'skt-opn-position-top' => [
                        'title' => __('Top', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'skt-opn-position-middle' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'skt-opn-position-bottom' => [
                        'title' => __('Bottom', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'skt-opn-position-middle',
                'toggle' => false,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_tooltip',
            [
                'label' => __('Tooltip', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
            ]
        );

        $this->add_control(
            'tooltip',
            [
                'label' => __('Enable Tooltip?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'skt-addons-elementor'),
                'label_off' => __('Off', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tooltip_arrow',
            [
                'label' => __('Enable Tooltip Arrow?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'skt-addons-elementor'),
                'label_off' => __('Off', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'tooltip' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __('Settings', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'scroll_wheel',
            [
                'label' => __('Scroll Wheel', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'skt-addons-elementor'),
                'label_off' => __('Off', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Scroll to specific section with mouse wheel scroll.', 'skt-addons-elementor'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'touch_swipe',
            [
                'label' => __('Touch Swipe', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'skt-addons-elementor'),
                'label_off' => __('Off', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Scroll to specific section with touch swipe (Only for mobile).', 'skt-addons-elementor'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scroll_keys',
            [
                'label' => __('Scroll Keys', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'skt-addons-elementor'),
                'label_off' => __('Off', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Scroll to specific section with keyboard up/down arrow keys.', 'skt-addons-elementor'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'row_to_offset',
            [
                'label' => __('Row To Offset (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 0,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scrolling_speed',
            [
                'label' => __('Scrolling Speed (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 50,
                'default' => 700,
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_navigation',
            [
                'label' => __('Navigation', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'nav_margin',
            [
                'label' => __('Margin', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'label' => __('Border', 'skt-addons-elementor'),
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav',
            ]
        );

        $this->add_control(
            'nav_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_box_shadow',
                'label' => __('Box Shadow', 'skt-addons-elementor'),
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav',
            ]
        );

        $this->start_controls_tabs(
            '_section_style_tabs'
        );

        $this->start_controls_tab(
            '_section_style_tab_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'nav_background',
                'label' => __('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_section_style_tab_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'nav_background_hover',
                'label' => __('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav:hover',
            ]
        );

        $this->add_control(
            'nav_border_hover_color',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'nav_background_hover_border!' => '',
                    'select_design' => 'skt-opn-design-default',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __('Navigation Content', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'nav_icon_size',
            [
                'label' => __('Icons Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 16,
                        'step' => 0.1,
                    ],
                ],
                'condition' => [
                    'select_design' => ['skt-opn-design-default', 'skt-opn-design-hagos', 'skt-opn-design-magool', 'skt-opn-design-maxamed', 'skt-opn-design-shamso', 'skt-opn-design-ubax'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-hagos .skt_addons_elementor_opn__item::before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool .skt_addons_elementor_opn__item::after' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-maxamed .skt_addons_elementor_opn__item::before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-shamso .skt_addons_elementor_opn__item' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax .skt_addons_elementor_opn__item::after' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_fixed_height_width',
            [
                'label' => __('Fixed Height & Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'skt-addons-elementor'),
                'label_on' => __('Custom', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'select_design' => ['skt-opn-design-default'],
                ],
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'nav_fixed_height_width__width',
            [
                'label' => __('Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_fixed_height_width__height',
            [
                'label' => __('Height', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_responsive_control(
            'nav_space_between',
            [
                'label' => __('Space Between Icon & Title', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 720,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 16,
                    ],
                ],
                'condition' => [
                    'select_design' => ['skt-opn-design-default', 'skt-opn-design-hagos', 'skt-opn-design-xusni', 'skt-opn-design-maxamed', 'skt-opn-design-shamso', 'skt-opn-design-ubax', 'skt-opn-design-zahi'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot i + span' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-xusni .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-hagos .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-maxamed .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-shamso .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'nav_content_margin',
            [
                'label' => __('Space between nav', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 16,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item' => 'margin-top: calc({{SIZE}}{{UNIT}}/2);margin-bottom: calc({{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt_addons_elementor_opn__item' => 'margin-top: calc({{SIZE}}{{UNIT}}/2);margin-bottom: calc({{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item' => 'padding: {{SIZE}}{{UNIT}} 0; margin: 0 auto;',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item:not(:last-child)::before' => 'top: calc(1em + {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_content_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'condition' => [
                    'select_design' => ['skt-opn-design-default'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_content_border',
                'label' => __('Border', 'skt-addons-elementor'),
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot',
            ]
        );

        $this->add_control(
            'nav_content_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_content_box_shadow',
                'label' => __('Box Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot',
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nav_content_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
			],
                'condition' => [
                    'select_design!' => 'skt-opn-design-magool',
                ],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot, {{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title, {{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item',
            ]
        );

        $this->start_controls_tabs(
            '_section_content_style_tabs'
        );

        $this->start_controls_tab(
            '_section_content_style_tab_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'nav_content_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design!' => ['skt-opn-design-ubax', 'skt-opn-design-shamso', 'skt-opn-design-maxamed', 'skt-opn-design-hagos', 'skt-opn-design-berta', 'skt-opn-design-xusni'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav .skt_addons_elementor_opn__item .skt_addons_elementor_opn__item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item:not(:last-child)::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool .skt_addons_elementor_opn__item::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_content_background',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design!' => ['skt-opn-design-magool'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-dot' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax .skt_addons_elementor_opn__item::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-shamso .skt_addons_elementor_opn__item::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-maxamed .skt_addons_elementor_opn__item::before' => 'box-shadow: inset 0 0 0 calc(1em - 0.6em) {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-hagos .skt_addons_elementor_opn__item::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-berta .skt_addons_elementor_opn__item::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-xusni .skt_addons_elementor_opn__item::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_section_content_style_tab_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'nav_content_color_hover',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design!' => ['skt-opn-design-ubax', 'skt-opn-design-shamso', 'skt-opn-design-maxamed', 'skt-opn-design-hagos', 'skt-opn-design-berta', 'skt-opn-design-xusni'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item:hover .skt-opn-dot' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover .skt_addons_elementor_opn__item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_content_background_hover',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design!' => ['skt-opn-design-magool'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item:hover .skt-opn-dot' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-shamso .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-maxamed .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::before' => 'box-shadow: inset 0 0 0 calc(1em - 0.6em) {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-hagos .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-berta .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-xusni .skt_addons_elementor_opn__item:not(.skt_addons_elementor_opn__item--current):hover::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_content_border_color_hover',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                    'nav_content_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item:hover .skt-opn-dot' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_section_content_style_tab_active',
            [
                'label' => __('Active', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'nav_content_color_active',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item.skt_addons_elementor_opn__item--current .skt-opn-dot' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-nav .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current .skt_addons_elementor_opn__item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-magool .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_content_background_active',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design!' => ['skt-opn-design-magool'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item.skt_addons_elementor_opn__item--current .skt-opn-dot' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-ubax .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-shamso .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-shamso .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-maxamed .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::before' => 'box-shadow: inset 0 0 0 calc(1em - 0.95em) {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-hagos .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-zahi .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-berta .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-design-xusni .skt_addons_elementor_opn__item.skt_addons_elementor_opn__item--current::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_content_border_color_active',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'select_design' => 'skt-opn-design-default',
                    'nav_content_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item.skt_addons_elementor_opn__item--current .skt-opn-dot' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_Tooltip',
            [
                'label' => __('Tooltip', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'select_design' => 'skt-opn-design-default'
                ]
            ]
        );

        $this->add_responsive_control(
            'nav_tooltip_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nav_tooltip_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
                'selector' => '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-tooltip',
            ]
        );

        $this->add_control(
            'nav_tooltip_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-tooltip' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_tooltip_background_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-tooltip' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav.skt-opn-right-side .skt-opn-dotted-item .skt-opn-arrow' => 'border-left-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-opn-dotted-nav.skt-opn-left-side .skt-opn-dotted-item .skt-opn-arrow' => 'border-right-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'nav_tooltip_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-opn-dotted-nav .skt-opn-dotted-item .skt-opn-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $wrapper_class = $settings['select_design'];
        $wrapper_class .= " " . $settings['nav_horizontal_align'];
        $wrapper_class .= " " . $settings['nav_vertical_align'];
        if (skt_addons_elementor()->editor->is_edit_mode()) :
?>
            <div class="skt-editor-placeholder">
                <h4 class="skt-editor-placeholder-title">
                    <?php esc_html_e('One Page Nav', 'skt-addons-elementor'); ?>
                </h4>
                <div class="skt-editor-placeholder-content">
                    <?php esc_html_e('This placeholder text doesn\'t serve any purpose. It won\'t show up in the frontend either. Go to preview mode to see full functionalities.', 'skt-addons-elementor'); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="skt-opn-dotted-nav <?php echo esc_attr($wrapper_class); ?>">
            <?php if ($settings['select_design'] == 'skt-opn-design-default') : ?>
                <ul>
                    <?php if (is_array($settings['navigation_lists'])) :
                        foreach ($settings['navigation_lists'] as $i => $nav) :
                    ?>
                            <li class="skt-opn-dotted-item elementor-repeater-item-<?php echo esc_attr($nav['_id']); ?> <?php echo esc_attr(($i == 0) ? 'skt_addons_elementor_opn__item--current' : '') ?>">
                                <?php if (isset($settings['tooltip']) && $settings['tooltip'] == 'yes') : ?>
                                    <span class="skt-opn-tooltip">
                                        <?php echo esc_html($nav['tooltip_title']); ?>
                                        <?php if (isset($settings['tooltip_arrow']) && $settings['tooltip_arrow'] == 'yes') : ?>
                                            <div class="skt-opn-arrow"></div>
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                                <a href="#" data-section-id="<?php echo esc_attr($nav['section_id']); ?>">
                                    <span class="skt-opn-dot">
                                        <?php if (!empty($nav['icon']['value'])) : ?>
                                            <?php Icons_Manager::render_icon($nav['icon']); ?>
                                        <?php endif; ?>
                                        <?php if (!empty($nav['nav_title'])) : ?>
                                            <span><?php echo esc_html($nav['nav_title']); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            </li>
                    <?php endforeach;
                    endif; ?>
                </ul>
            <?php else : ?>
                <ul class="skt-opn-nav <?php echo esc_attr($settings['select_design']); ?>">
                    <?php if (is_array($settings['navigation_lists'])) :
                        foreach ($settings['navigation_lists'] as $i => $nav) :
                    ?>
                            <li class="skt_addons_elementor_opn__item <?php echo esc_attr(($i == 0) ? 'skt_addons_elementor_opn__item--current' : '') ?> elementor-repeater-item-<?php echo esc_attr($nav['_id']); ?>" aria-label="<?php echo esc_html($nav['nav_title']); ?>">
                                <a href="#" data-section-id="<?php echo esc_attr($nav['section_id']); ?>"></a>
                                <?php if ($settings['select_design'] != 'skt-opn-design-magool') : ?>
                                    <span class="skt_addons_elementor_opn__item-title">
                                        <?php
                                        if (empty($nav['nav_title']) && ($settings['select_design'] == 'skt-opn-design-berta' || $settings['select_design'] == 'skt-opn-design-xusni')) {
                                            echo esc_html__('Section ', 'skt-addons-elementor') . ($i + 1);
                                        } else if (!empty($nav['nav_title'])) {
                                            echo esc_html($nav['nav_title']);
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </li>
                    <?php endforeach;
                    endif; ?>
                </ul>
            <?php endif; ?>
        </div>
<?php
    }
}