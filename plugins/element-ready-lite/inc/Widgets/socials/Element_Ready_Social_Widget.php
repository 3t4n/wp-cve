<?php
namespace Element_Ready\Widgets\socials;

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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Social_Widget extends Widget_Base {

    public function get_name() {
        return 'Element_Ready_Social_Widget';
    }
    
    public function get_title() {
        return esc_html__( 'ER Social Buttons', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-social-icons';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }

    public function get_keywords() {
        return [ 'Social', 'Social Profile', 'Social Links', 'Social Button' ];
    }

    public function get_style_depends() {

        wp_register_style( 'eready-social-button' , ELEMENT_READY_ROOT_CSS. 'widgets/social-buttons.css' );
        return [ 'eready-social-button' ];
    }

    protected function register_controls() {
        /*----------------------------
            CONTENT SECTION
        -----------------------------*/
        $this->start_controls_section(
            'social_media_sheres',
            [
                'label' => esc_html__( 'Social Shere', 'element-ready-lite' ),
            ]
        );
            $this->add_control(
                'social_view',
                [
                    'label'       => esc_html__( 'Social Icon Style', 'element-ready-lite' ),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => false,
                    'options'     => [
                        'icon'       => 'Icon',
                        'title'      => 'Title',
                        'icon-title' => 'Icon & Title',
                    ],
                    'default'      => 'icon',
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->start_controls_tabs('social_content_area_tabs');

                $repeater->start_controls_tab(
                    'social_content_tab',
                    [
                        'label' => esc_html__( 'Content', 'element-ready-lite' ),
                    ]
                );
                    $repeater->add_control(
                        'element_ready_social_icon',
                        [
                            'label'   => esc_html__( 'Icon', 'element-ready-lite' ),
                            'type'    => Controls_Manager::ICONS,
                            'label_block' => true,
                        ]
                    );

                    $repeater->add_control(
                        'element_ready_social_link',
                        [
                            'label'         => esc_html__( 'Url', 'element-ready-lite' ),
                            'type'          => Controls_Manager::URL,
                            'show_external' => true,
                            'default' => [
                                'url' => '#',
                            ],
                        ]
                    );
                    $repeater->add_control(
                        'element_ready_social_title',
                        [
                            'label'   => esc_html__( 'Title', 'element-ready-lite' ),
                            'type'    => Controls_Manager::TEXT,
                            'default' => esc_html__( 'Twitter', 'element-ready-lite' ),
                        ]
                    );
                $repeater->end_controls_tab();
                $repeater->start_controls_tab(
                    'social_rep_style',
                    [
                        'label' => esc_html__( 'Style', 'element-ready-lite' ),
                    ]
                );
                    $repeater->add_control(
                        'normal_style_heading',
                        [
                            'label'     => esc_html__( 'Normal Style', 'element-ready-lite' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );
                    $repeater->add_control(
                        'social_text_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'social_rep_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'     => 'social_rep_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a',
                        ]
                    );
                    $repeater->add_control(
                        'hover_style_heading',
                        [
                            'label' => esc_html__( 'Hover Style', 'element-ready-lite' ),
                            'type'  => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );
                    $repeater->add_control(
                        'social_text_hover_color',
                        [
                            'label'     => esc_html__( 'Hover color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'social_rep_hover_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a:hover',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'     => 'social_rep_hover_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a:hover',
                        ]
                    );

                $repeater->end_controls_tab();
                $repeater->start_controls_tab(
                    'social_rep_icon_style',
                    [
                        'label' => esc_html__( 'Icon Style', 'element-ready-lite' ),
                    ]
                );
                    $repeater->add_control(
                        'normal_style_icon_heading',
                        [
                            'label'     => esc_html__( 'Normal Style', 'element-ready-lite' ),
                            'type'      => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );
                    $repeater->add_control(
                        'social_icon_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a i' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'social_rep_icon_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a i',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'     => 'social_rep_icon_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a i',
                        ]
                    );
                    $repeater->add_responsive_control(
                        'social_rep_icon_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a i' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $repeater->add_control(
                        'hover_style_icon_heading',
                        [
                            'label' => esc_html__( 'Hover Style', 'element-ready-lite' ),
                            'type'  => Controls_Manager::HEADING,
                            'separator' =>'before',
                        ]
                    );
                    $repeater->add_control(
                        'social_icon_hover_color',
                        [
                            'label'     => esc_html__( 'Hover color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a:hover i' => 'color: {{VALUE}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'social_rep_icon_hover_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a:hover i',
                        ]
                    );
                    $repeater->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'     => 'social_rep_icon_hover_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons {{CURRENT_ITEM}} a:hover i',
                        ]
                    );
                $repeater->end_controls_tab();

            $repeater->end_controls_tabs();

            $this->add_control(
                'element_ready_socialmedia_list',
                [
                    'type'    => \Elementor\Controls_Manager::REPEATER,
                    'fields'  =>  $repeater->get_controls() ,
                    
                    'default' => [
                        [
                            'element_ready_social_icon'  => [
                                'value' => 'fa fa-twitter',
                            ],
                            'element_ready_social_title' => esc_html__( 'Twitter', 'element-ready-lite' ),
                        ],
                    ],
                    'title_field' => '{{{ element_ready_social_title }}}',
                ]
            );
            $this->add_responsive_control(
                'social_wrap_align',
                [
                    'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__( 'Justify', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__socials__buttons ul' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section();
        /*----------------------------
            CONTENT SECTION END
        -----------------------------*/

        /*----------------------------
            ICON STYLE
        -----------------------------*/
        $this->start_controls_section(
            'socialshere_icon_style_section',
            [
                'label'     => esc_html__( 'Icon', 'element-ready-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'social_view' => array( 'icon-title','icon'),
                ]
            ]
        );
            $this->add_responsive_control(
                'icon_fontsize',
                [
                    'label'      => esc_html__( 'Icon Font Size', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
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
                        '{{WRAPPER}} .element__ready__socials__buttons ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'social_icon_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .element__ready__socials__buttons li i',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'social_icon_border',
                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .element__ready__socials__buttons li i',
                ]
            );

            $this->add_responsive_control(
                'social_icon_radius',
                [
                    'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                    'type'      => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__socials__buttons li i' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'icon_height',
                [
                    'label'      => esc_html__( 'Icon Height', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__socials__buttons ul li i' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'icon_width',
                [
                    'label'      => esc_html__( 'Icon Width', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__socials__buttons ul li i' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            // Margin
            $this->add_responsive_control(
                'social_icon_margin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__socials__buttons ul li a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                'social_icon_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__socials__buttons ul li a i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();
        /*----------------------------
            ICON STYLE END
        -----------------------------*/

        /*----------------------------
            ITEM STYLE
        -----------------------------*/
        $this->start_controls_section(
            'social_button_item_style_section',
            [
                'label' => esc_html__( 'Social Item', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'social_button_tabs_style' );
                $this->start_controls_tab(
                    'social_button_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => 'social_button_typography',
                            'selector'  => '{{WRAPPER}} .element__ready__socials__buttons ul li a',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        'social_button_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'social_button_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons ul li a:before',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'social_button_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons ul li a',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'social_button_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'social_button_shadow',
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons ul li a',
                        ]
                    );
                    $this->add_responsive_control(
                        'social_button_display',
                        [
                            'label'   => esc_html__( 'Display', 'element-ready-lite' ),
                            'type'    => Controls_Manager::SELECT,          
                            'options' => [
                                'initial'      => esc_html__( 'Initial', 'element-ready-lite' ),
                                'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                                'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                                'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                                'inline-flex'  => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                                'none'         => esc_html__( 'none', 'element-ready-lite' ),
                            ],
                            'default' => 'inline-block',
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li' => 'display: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'social_button_link_display',
                        [
                            'label'   => esc_html__( 'Link Display', 'element-ready-lite' ),
                            'type'    => Controls_Manager::SELECT,          
                            'options' => [
                                'initial'      => esc_html__( 'Initial', 'element-ready-lite' ),
                                'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                                'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                                'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                                'inline-flex'  => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                                'none'         => esc_html__( 'none', 'element-ready-lite' ),
                            ],
                            'default' => 'inline-block',
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'display: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'social_button_width',
                        [
                            'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
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
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Height
                    $this->add_responsive_control(
                        'social_button_height',
                        [
                            'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Margin
                    $this->add_responsive_control(
                        'social_button_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Padding
                    $this->add_responsive_control(
                        'social_button_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    // Transition
                    $this->add_control(
                        'social_button_transition',
                        [
                            'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
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
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a,{{WRAPPER}} .element__ready__socials__buttons ul li a:before,{{WRAPPER}} .element__ready__socials__buttons ul li a:after' => 'transition: {{SIZE}}s;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'social_button_align',
                        [
                            'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                            'type'    => Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                    'title' => esc_html__( 'Justify', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-justify',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );
                $this->end_controls_tab();

                $this->start_controls_tab(
                    'social_button_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_social_button_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a:hover, {{WRAPPER}} .element__ready__socials__buttons ul li a:focus' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_social_button_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons ul li a:after',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'hover_social_button_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons ul li a:hover,{{WRAPPER}}.element__ready__socials__buttons ul li a:focus',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'hover_social_button_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__socials__buttons ul li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'hover_social_button_shadow',
                            'selector' => '{{WRAPPER}} .element__ready__socials__buttons ul li a:hover',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            ITEM STYLE END
        -----------------------------*/
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'element_ready_socials_buttons_attr', 'class', 'element__ready__socials__buttons' );
        $this->add_render_attribute( 'element_ready_socials_buttons_attr', 'class', 'socials__buttons__style__1' );
        if( 'icon-title' == $settings['social_view'] || 'title' == $settings['social_view'] ){
            $this->add_render_attribute( 'element_ready_socials_buttons_attr', 'class', 'element__ready__socials__view__'.$settings['social_view'] );
        }
        ?>
            <div <?php echo $this->get_render_attribute_string( 'element_ready_socials_buttons_attr' ); ?> >
                <ul>
                    <?php foreach ( $settings['element_ready_socialmedia_list'] as $socialmedia ) :?>
                        <?php 
                            $attribute = array();
                            if ( ! empty( $socialmedia['element_ready_social_link']['url'] ) ) {
                                $attribute[] = 'href="'.$socialmedia['element_ready_social_link']['url'].'"';
                                if ( $socialmedia['element_ready_social_link']['is_external'] ) {
                                    $attribute[] = 'target="_blank"';
                                }
                                if ( $socialmedia['element_ready_social_link']['nofollow'] ) {
                                    $attribute[] = 'rel="nofollow"';
                                }
                            }
                        ?>
                        <li class="elementor-repeater-item-<?php echo esc_attr($socialmedia['_id']); ?>">
                            <a <?php echo esc_attr( implode(' ', $attribute ) ); $attribute = array() ;?>>
                                <?php
                                    if( 'icon' == $settings['social_view'] ){
                                        Icons_Manager::render_icon( $socialmedia['element_ready_social_icon'] );
                                    }elseif( 'title' == $settings['social_view'] ){
                                        echo wp_kses_post( sprintf('<span>%1$s</span>', $socialmedia['element_ready_social_title'] ) );
                                    }else{
                                        ?>
                                            <?php Icons_Manager::render_icon( $socialmedia['element_ready_social_icon'] ); ?>
                                            <span><?php echo esc_html( $socialmedia['element_ready_social_title'] ); ?></span>
                                        <?php
                                    }
                                ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php
    }
}