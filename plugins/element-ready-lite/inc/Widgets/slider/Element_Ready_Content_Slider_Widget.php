<?php

namespace Element_Ready\Widgets\slider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Ready_Content_Slider_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'er_content_sldier_widget';
    }

    public function get_title()
    {
        return __('ER Content Slider', 'element-ready-lite');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['element-ready-addons'];
    }

    public function get_keywords()
    {
        return ['content slider', 'slick'];
    }

    public function get_script_depends()
    {

        return [
            'slick',
            'element-ready-core',
        ];
    }

    public function get_style_depends()
    {


        return [
            'slick',
        ];
    }


    protected function register_controls()
    {

        /*---------------------------
            CONTENT SECTION
        -----------------------------*/
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Slider Settings', 'element-ready-lite'),
            ]
        );


        $this->add_control(
            'slider_on',
            [
                'label'        => esc_html__('Slider On', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('On', 'element-ready-lite'),
                'label_off'    => esc_html__('Off', 'element-ready-lite'),
                'return_value' => 'yes',
                'default'      => 'on',
                'separator'    => 'before',

            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_title',
            [
                'label' => esc_html__('Title', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('List Title', 'element-ready-lite'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'template_id',
            [
                'label' => esc_html__('Template', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => element_ready_get_elementor_templates_arr(),

            ]
        );

        $this->add_control(
            'template_list',
            [
                'label' => esc_html__('Content List', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => esc_html__('Template #1', 'element-ready-lite'),
                        'template_id' => 0,
                    ]

                ],
                'title_field' => '{{{ list_title }}}',
            ]
        );


        $this->end_controls_section();
        /*---------------------------
            CONTENT SECTION END
        -----------------------------*/
        /*---------------------------
            CAROUSEL SETTING
        -----------------------------*/
        $this->start_controls_section(
            'slider_option',
            [
                'label'     => esc_html__('Carousel Option', 'element-ready-lite'),
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slitems',
            [
                'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 20,
                'step'      => 1,
                'default'   => 3,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slrows',
            [
                'label'     => esc_html__('Slider Rows', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 5,
                'step'      => 1,
                'default'   => 0,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'slitemmargin',
            [
                'label'     => esc_html__('Slider Item Margin', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 100,
                'step'      => 1,
                'default'   => 1,
                'selectors' => [
                    '{{WRAPPER}} .single__cat__item' => 'margin: calc( {{VALUE}}px / 2 );',
                    '{{WRAPPER}} .slick-list'  => 'margin: calc( -{{VALUE}}px / 2 );',
                ],
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'content_slider_padding_slick',
            [
                'label' => esc_html__('Item Padding', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .slick-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slarrows',
            [
                'label'        => esc_html__('Slider Arrow', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'nav_position',
            [
                'label'   => esc_html__('Arrow Position', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'outside_vertical_center_nav',
                'options' => [
                    'inside_vertical_center_nav'  => esc_html__('Hover Inside Vertical Center', 'element-ready-lite'),
                    'inside_vertical_center_nav show'  => esc_html__('Inside Vertical Center', 'element-ready-lite'),
                    'outside_vertical_center_nav' => esc_html__('Hover Outside Vertical Center ', 'element-ready-lite'),
                    'outside_vertical_center_nav show' => esc_html__('Outside Vertical Center', 'element-ready-lite'),
                    'top_left_nav'                => esc_html__('Top Left', 'element-ready-lite'),
                    'top_center_nav'              => esc_html__('Top Center', 'element-ready-lite'),
                    'top_right_nav'               => esc_html__('Top Right', 'element-ready-lite'),
                    'bottom_left_nav'             => esc_html__('Bottom Left', 'element-ready-lite'),
                    'bottom_center_nav'           => esc_html__('Bottom Center', 'element-ready-lite'),
                    'bottom_right_nav'            => esc_html__('Bottom Right', 'element-ready-lite'),
                ],
                'condition' => [
                    'slarrows' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slprevicon',
            [
                'label'     => esc_html__('Previous icon', 'element-ready-lite'),
                'type'      => Controls_Manager::ICON,
                'label_block' => true,
                'default'   => 'fa fa-angle-left',
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slnexticon',
            [
                'label'     => esc_html__('Next icon', 'element-ready-lite'),
                'type'      => Controls_Manager::ICON,
                'label_block' => true,
                'default'   => 'fa fa-angle-right',
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ]
            ]
        );

        $this->add_control(
            'nav_visible',
            [
                'label'        => esc_html__('Arrow Visibility', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'visibility:visible;opacity:1;',
                'default'      => 'no',
                'selectors'    => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div' => '{{VALUE}}',
                ],
                'condition'   => [
                    'slarrows' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sldots',
            [
                'label'        => esc_html__('Slider dots', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slpause_on_hover',
            [
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__('No', 'element-ready-lite'),
                'label_on'     => esc_html__('Yes', 'element-ready-lite'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'label'        => esc_html__('Pause on Hover?', 'element-ready-lite'),
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slcentermode',
            [
                'label'        => esc_html__('Center Mode', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slcenterpadding',
            [
                'label'     => esc_html__('Center padding', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 500,
                'step'      => 1,
                'default'   => 50,
                'condition' => [
                    'slider_on'    => 'yes',
                    'slcentermode' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slfade',
            [
                'label'        => esc_html__('Slider Fade', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slfocusonselect',
            [
                'label'        => esc_html__('Focus On Select', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slvertical',
            [
                'label'        => esc_html__('Vertical Slide', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slinfinite',
            [
                'label'        => esc_html__('Infinite', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slrtl',
            [
                'label'        => esc_html__('RTL Slide', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slautolay',
            [
                'label'        => esc_html__('Slider auto play', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slautoplay_speed',
            [
                'label'     => esc_html__('Autoplay speed', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );


        $this->add_control(
            'slanimation_speed',
            [
                'label'     => esc_html__('Autoplay animation speed', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slscroll_columns',
            [
                'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 10,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'heading_tablet',
            [
                'label'     => esc_html__('Tablet', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sltablet_display_columns',
            [
                'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 8,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sltablet_scroll_columns',
            [
                'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 8,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sltablet_width',
            [
                'label'       => esc_html__('Tablet Resolution', 'element-ready-lite'),
                'description' => esc_html__('The resolution to tablet.', 'element-ready-lite'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 750,
                'condition'   => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'heading_mobile',
            [
                'label'     => esc_html__('Mobile Phone', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slmobile_display_columns',
            [
                'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 4,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slmobile_scroll_columns',
            [
                'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 4,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slmobile_width',
            [
                'label'       => esc_html__('Mobile Resolution', 'element-ready-lite'),
                'description' => esc_html__('The resolution to mobile.', 'element-ready-lite'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 480,
                'condition'   => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        /*-----------------------
            SLIDER OPTIONS END
        -------------------------*/

        /*-------------------------
            AREA STYLE
        --------------------------*/
        $this->start_controls_section(
            'items_area_style_section',
            [
                'label' => esc_html__('Area Style', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'area_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 9999,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'area_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
        /*-------------------------
            AREA STYLE END
        --------------------------*/


        /*-------------------------
            ITEM COLUMNS STYLE END
        --------------------------*/

        /*-------------------------
            CENTER ITEM STYLE
        --------------------------*/
        $this->start_controls_section(
            'center_item_style_section',
            [
                'label'     => esc_html__('Center Item Style', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on'    => 'yes',
                    'slcentermode' => 'yes',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'center_item_image_filters',
                'selector' => '{{WRAPPER}} .element__ready__category__item__parent.slick-active.slick-center img',
            ]
        );

        $this->add_control(
            'center_item_opacity',
            [
                'label' => esc_html__('Opacity', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__category__item__parent.slick-active.slick-center'  => 'opacity:{{SIZE}};',
                    '{{WRAPPER}} .slick-active.slick-center .element__ready__category__item__parent' => 'opacity:{{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'center_item_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__category__item__parent.slick-active.slick-center'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .slick-active.slick-center .element__ready__category__item__parent' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'center_item_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__category__item__parent.slick-active.slick-center'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .slick-active.slick-center .element__ready__category__item__parent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'center_item_scale',
            [
                'label' => esc_html__('Scale', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__category__item__parent.slick-active.slick-center'  => 'transform: scale({{SIZE}});',
                    '{{WRAPPER}} .slick-active.slick-center .element__ready__category__item__parent' => 'transform: scale({{SIZE}});',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'center_item_transition',
            [
                'label' => esc_html__('Transition', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__category__item__parent' => 'transition: {{SIZE}}s;',
                    '{{WRAPPER}} .slick-slide'                     => 'transition: {{SIZE}}s;',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
        /*-------------------------
            END CENTER ITEM STYLE
        --------------------------*/





        /*-------------------------
            TITLE STYLE END
        --------------------------*/

        /*----------------------------
            SLIDER NAV WARP
        -----------------------------*/
        $this->start_controls_section(
            'slider_control_warp_style_section',
            [
                'label'     => esc_html__('Slider Arrow Warp', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slider_nav_warp_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slider_nav_warp_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
            ]
        );
        $this->add_control(
            'slider_nav_warp_radius',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_nav_warp_shadow',
                'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_display',
            [
                'label'   => esc_html__('Display', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'initial'      => esc_html__('Initial', 'element-ready-lite'),
                    'block'        => esc_html__('Block', 'element-ready-lite'),
                    'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
                    'flex'         => esc_html__('Flex', 'element-ready-lite'),
                    'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
                    'none'         => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_position',
            [
                'label'   => esc_html__('Position', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',

                'options' => [
                    'initial'  => esc_html__('Initial', 'element-ready-lite'),
                    'absolute' => esc_html__('Absolute', 'element-ready-lite'),
                    'relative' => esc_html__('Relative', 'element-ready-lite'),
                    'static'   => esc_html__('Static', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_position_from_left',
            [
                'label'      => esc_html__('From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_position_from_right',
            [
                'label'      => esc_html__('From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_position_from_top',
            [
                'label'      => esc_html__('From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_position_from_bottom',
            [
                'label'      => esc_html__('From Bottom', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'text-align: {{VALUE}};',
                ],
                'default' => '',
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_width',
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
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_height',
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
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'slider_nav_warp_opacity',
            [
                'label' => esc_html__('Opacity', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'slider_nav_warp_zindex',
            [
                'label'     => esc_html__('Z-Index', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => -99,
                'max'       => 99,
                'step'      => 1,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_nav_warp_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*----------------------------
            SLIDER NAV WARP END
        -----------------------------*/

        /*------------------------
            ARROW STYLE
        --------------------------*/
        $this->start_controls_section(
            'slider_arrow_style',
            [
                'label'     => esc_html__('Arrow', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
        $this->start_controls_tabs('slider_arrow_style_tabs');
        $this->start_controls_tab(
            'slider_arrow_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            'slider_arrow_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_arrow_fontsize',
            [
                'label'      => esc_html__('Font Size', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slider_arrow_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slider_arrow_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow',
            ]
        );
        $this->add_responsive_control(
            'slider_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_arrow_shadow',
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow',
            ]
        );
        $this->add_responsive_control(
            'slider_arrow_height',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_arrow_width',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 46,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_arrow_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'slider_arrow_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'slide_button_position_from_left',
            [
                'label'      => esc_html__('Left Arrow Position From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slide_button_position_from_bottom',
            [
                'label'      => esc_html__('Left Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slide_button_position_from_right',
            [
                'label'      => esc_html__('Right Arrow Position From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slide_button_position_from_top',
            [
                'label'      => esc_html__('Right Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'slider_arrow_style_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            'slider_arrow_hover_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slider_arrow_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slider_arrow_hover_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow:hover',
            ]
        );
        $this->add_responsive_control(
            'slider_arrow_hover_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_arrow_hover_shadow',
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow:hover',
            ]
        );
        $this->add_responsive_control(
            'slide_button_hover_position_from_left',
            [
                'label'      => esc_html__('Left Arrow Position From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slide_button_hover_position_from_bottom',
            [
                'label'      => esc_html__('Left Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slide_button_hover_position_from_right',
            [
                'label'      => esc_html__('Right Arrow Position From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slide_button_hover_position_from_top',
            [
                'label'      => esc_html__('Right Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*------------------------
             ARROW STYLE END
        --------------------------*/

        /*------------------------
             DOTS STYLE
        --------------------------*/
        $this->start_controls_section(
            'post_slider_pagination_style_section',
            [
                'label'     => esc_html__('Pagination', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'slider_on' => 'yes',
                    'sldots'    => 'yes',
                ],
            ]
        );
        $this->start_controls_tabs('pagination_style_tabs');
        $this->start_controls_tab(
            'pagination_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );
        $this->add_responsive_control(
            'slider_pagination_height',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_pagination_width',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pagination_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li',
            ]
        );
        $this->add_responsive_control(
            'pagination_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li',
            ]
        );
        $this->add_responsive_control(
            'pagination_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_warp_margin',
            [
                'label'      => esc_html__('Pagination Warp Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagi_war_align',
            [
                'label'   => esc_html__('Pagination Warp Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'pagination_style_active_tab',
            [
                'label' => esc_html__('Active', 'element-ready-lite'),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pagination_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li:hover, {{WRAPPER}} .sldier-content-area .slick-dots li.slick-active',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_hover_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li:hover, {{WRAPPER}} .sldier-content-area .slick-dots li.slick-active',
            ]
        );
        $this->add_responsive_control(
            'pagination_hover_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li.slick-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .sldier-content-area .slick-dots li:hover'        => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*------------------------
             DOTS STYLE END
        --------------------------*/
    }

    protected function render($instance = [])
    {
        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute('element_ready_category_main_wrap', 'class', 'sldier-content-area');
        $this->add_render_attribute('element_ready_category_main_wrap', 'class', $settings['nav_position']);

        if ($settings['slider_on'] == 'yes') {

            $this->add_render_attribute('element_ready_category_wrap_attr', 'class', 'element-ready-carousel-activation');
            $slideid = rand(2564, 1245);

            $slider_settings = [
                'slideid'         => $slideid,
                'arrows'          => ('yes' === $settings['slarrows']),
                'arrow_prev_txt'  => $settings['slprevicon'],
                'arrow_next_txt'  => $settings['slnexticon'],
                'dots'            => ('yes' === $settings['sldots']),
                'autoplay'        => ('yes' === $settings['slautolay']),
                'autoplay_speed'  => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover'  => ('yes' === $settings['slpause_on_hover']),
                'center_mode'     => ('yes' === $settings['slcentermode']),
                'center_padding'  => absint($settings['slcenterpadding']),
                'rows'            => absint($settings['slrows']),
                'fade'            => ('yes' === $settings['slfade']),
                'focusonselect'   => ('yes' === $settings['slfocusonselect']),
                'vertical'        => ('yes' === $settings['slvertical']),
                'rtl'             => ('yes' === $settings['slrtl']),
                'infinite'        => ('yes' === $settings['slinfinite']),
            ];

            $slider_responsive_settings = [
                'display_columns'        => $settings['slitems'],
                'scroll_columns'         => $settings['slscroll_columns'],
                'tablet_width'           => $settings['sltablet_width'],
                'tablet_display_columns' => $settings['sltablet_display_columns'],
                'tablet_scroll_columns'  => $settings['sltablet_scroll_columns'],
                'mobile_width'           => $settings['slmobile_width'],
                'mobile_display_columns' => $settings['slmobile_display_columns'],
                'mobile_scroll_columns'  => $settings['slmobile_scroll_columns'],
            ];

            $slider_settings = array_merge($slider_settings, $slider_responsive_settings);
            $this->add_render_attribute('element_ready_category_wrap_attr', 'data-settings', wp_json_encode($slider_settings));
        } else {
            $this->add_render_attribute('element_ready_category_wrap_attr', 'class', 'product__category__lists');
        }

        $this->add_render_attribute('element_ready_cat_item_attr', 'class', 'single__cat__item');
        $this->add_render_attribute('element_ready_cat_item_attr', 'class', 'default');

?>
        <div <?php echo $this->get_render_attribute_string('element_ready_category_main_wrap'); ?>>

            <div <?php echo $this->get_render_attribute_string('element_ready_category_wrap_attr'); ?>>

                <?php foreach ($settings['template_list'] as $item) : ?>
                    <div>
                        <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($item['template_id'], true); ?>
                    </div>
                <?php endforeach; ?>

            </div>

            <?php if (($settings['slarrows'] == 'yes' || $settings['sldots'] == 'yes') && 'yes' == $settings['slider_on']) : ?>
                <!-- CUSTOM SLIDER CONTROL -->
                <div class="owl-controls">
                    <?php if ($settings['slarrows'] == 'yes') : ?>
                        <div class="element-ready-carousel-nav<?php echo esc_attr($slideid); ?> owl-nav"></div>
                    <?php endif; ?>
                    <?php if ($settings['sldots'] == 'yes') : ?>
                        <div class="element-ready-carousel-dots<?php echo esc_attr($slideid); ?> owl-dots"></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
<?php
    }
}
