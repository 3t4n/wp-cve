<?php

namespace Element_Ready\Widgets\shortcode;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Shortcode Widget.
 *
 * Shortcode widget that displays a shortcode-form with the ability to control every
 * aspect of the shortcode-form design.
 *
 * @since 1.0.0
 */
class Element_Ready_Shortcode_Widget extends Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve shortcode-form widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Element_Ready_Shortcode_Widget';
    }

    /**
     * Get widget title.
     *
     * Retrieve shortcode-form widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('ER Form Shortcode', 'element-ready-lite');
    }

    /**
     * Get widget icon.
     *
     * Retrieve shortcode-form widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-shortcode';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the shortcode-form widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['element-ready-addons'];
    }

    public function get_keywords()
    {
        return ['Shortcode', 'Form Shortcode', 'Forms'];
    }

    /**
     * Register shortcode-form widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {


        $this->start_controls_section(
            'subscribe_section_start',
            [
                'label' => esc_html__('Form Shortcode', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'shortcode_box',
            [
                'label'   => esc_html__('Shortcode', 'element-ready-lite'),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'description' => esc_html__('Please enter Shortcode.', 'element-ready-lite'),
                'default'     => '',
                'placeholder' => '',
            ]
        );

        $this->end_controls_section();

        /*--------------------------
            TITLE STYLE
        ----------------------------*/
        $this->start_controls_section(
            'top_title_section',
            [
                'label' => esc_html__('Title', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'top_title_typography',
                'selector' => '{{WRAPPER}} label',
            ]
        );

        $this->add_control(
            'top_title_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} label' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'top_title_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '15',
                    'left'     => '0',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'top_title_padding',
            [
                'label'   => esc_html__('Padding', 'element-ready-lite'),
                'type'    => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'isLinked' => true
                ],
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'top_title_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} label',
            ]
        );
        $this->add_responsive_control(
            'custom_top_title_css',
            [
                'label'     => esc_html__('Lavel Custom CSS', 'element-ready-lite'),
                'type'      => Controls_Manager::CODE,
                'rows'      => 20,
                'language'  => 'css',
                'selectors' => [
                    '{{WRAPPER}} label' => '{{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
        /*--------------------------
            TITLE STYLE END
        ----------------------------*/

        /*---------------------------
            INPUT STYLE
        ----------------------------*/
        $this->start_controls_section(
            'input_style_section',
            [
                'label' => esc_html__('Inputs', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('tabs_input_style');
        $this->start_controls_tab(
            'tab_input_normal',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea',
            ]
        );

        $this->add_responsive_control(
            'input_box_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_box_max_height',
            [
                'label'      => esc_html__('Max Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_box_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label'     => esc_html__('Text Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#79879d',
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea, {{WRAPPER}} ::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'input_background_color',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="url"],{{WRAPPER}} textarea',
            ]
        );

        $this->add_responsive_control(
            'input_radius',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top'      => '5',
                    'right'    => '5',
                    'bottom'   => '5',
                    'left'     => '5',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => 'border-radius : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea',
            ]
        );

        $this->add_responsive_control(
            'input_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label'   => esc_html__('Padding', 'element-ready-lite'),
                'type'    => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top'      => '12',
                    'right'    => '30',
                    'bottom'   => '12',
                    'left'     => '30',
                    'isLinked' => false
                ],
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'custom_input_css',
            [
                'label'     => esc_html__('Input Field CSS', 'element-ready-lite'),
                'type'      => Controls_Manager::CODE,
                'rows'      => 20,
                'language'  => 'css',
                'selectors' => [
                    '{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="email"],{{WRAPPER}} textarea' => '{{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus',
            [
                'label' => esc_html__('Hover & Focus', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'input_focus_color',
            [
                'label'     => esc_html__('Text Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#79879d',
                'selectors' => [
                    '{{WRAPPER}} input[type="number"]:focus,{{WRAPPER}} input[type="text"]:focus,{{WRAPPER}} input[type="tel"]:focus,{{WRAPPER}} input[type="date"]:focus,{{WRAPPER}} input[type="url"]:focus,{{WRAPPER}} input[type="email"]:focus,{{WRAPPER}} textarea:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'input_focus_background',
                'label'    => esc_html__('focus Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} input[type="number"]:focus,{{WRAPPER}} input[type="text"]:focus,{{WRAPPER}} input[type="tel"]:focus,{{WRAPPER}} input[type="date"]:focus,{{WRAPPER}} input[type="url"]:focus,{{WRAPPER}} input[type="email"]:focus,{{WRAPPER}} textarea:focus'
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label'     => esc_html__('Border Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="number"]:focus,{{WRAPPER}} input[type="text"]:focus,{{WRAPPER}} input[type="tel"]:focus,{{WRAPPER}} input[type="date"]:focus,{{WRAPPER}} input[type="url"]:focus,{{WRAPPER}} input[type="email"]:focus,{{WRAPPER}} textarea:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}} input[type="number"]:focus,{{WRAPPER}} input[type="text"]:focus,{{WRAPPER}} input[type="tel"]:focus,{{WRAPPER}} input[type="date"]:focus,{{WRAPPER}} input[type="url"]:focus,{{WRAPPER}} input[type="email"]:focus,{{WRAPPER}} textarea:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*---------------------------
            INPUT STYLE END
        ----------------------------*/

        /*---------------------------
            BUTTON STYLE
        ----------------------------*/
        $this->start_controls_section(
            'button_section_style',
            [
                'label' => esc_html__('Button', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('tabs_button_style');
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button',
            ]
        );
        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__('Text Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background_color',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button',
            ]
        );
        $this->add_responsive_control(
            'button_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button',
            ]
        );
        $this->add_responsive_control(
            'button_radius',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top'      => '5',
                    'right'    => '5',
                    'bottom'   => '5',
                    'left'     => '5',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button',
            ]
        );
        $this->add_responsive_control(
            'button_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'button_padding',
            [
                'label'   => esc_html__('Padding', 'element-ready-lite'),
                'type'    => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top'      => '12',
                    'right'    => '40',
                    'bottom'   => '12',
                    'left'     => '40',
                    'isLinked' => false
                ],
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_transition',
            [
                'label'      => esc_html__('Transition', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0.1,
                        'max'  => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'transition: {{SIZE}}s;',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_floting',
            [
                'label'   => esc_html__('Button Floating', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'none' => [
                        'title' => esc_html__('None', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"], {{WRAPPER}} button' => 'float: {{VALUE}};',
                ],
                'default'   => 'none',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            'button_hover_color',
            [
                'label'     => esc_html__('Text Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]:hover, {{WRAPPER}} button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_hover_background',
                'label'    => esc_html__('Hover Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} input[type="submit"]:hover, {{WRAPPER}} button:before,{{WRAPPER}} button:hover',
            ]
        );
        $this->add_control(
            'button_before_hidding',
            [
                'label' => esc_html__('Hover Before Background', 'element-ready-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_hover_before_background',
                'label'    => esc_html__('Hover Before Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} input[type="submit"]:hover:before, {{WRAPPER}} button:hover:before',
            ]
        );
        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]:hover, {{WRAPPER}} button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} input[type="submit"]:hover, {{WRAPPER}} button:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            BUTTON STYLE END
        ------------------------------*/
    }

    /**
     * Render shortcode-form widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
?>
        <div class="shortcode-form">
            <?php echo do_shortcode(shortcode_unautop($settings['shortcode_box'])); ?>
        </div>
<?php
    }
}
