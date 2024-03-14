<?php

namespace Element_Ready\Widgets\info_box;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Info_Box_Widget extends Widget_Base {

    use Content_Style;

    public function get_name() {
        return 'Element_Ready_Info_Box_Widget';
    }
    
    public function get_title() {
        return esc_html__( 'ER Info Box', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-toggle';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }

    public function get_keywords() {
        return [ 'Box', 'Info Box', 'Text Box' ];
    }

    public function element_ready_infobox_style(){
        return apply_filters( 'element_ready_infobox_style_presets', [
            'element__ready__infobox__style__1' => esc_html__( 'Style One', 'element-ready-lite' ),
        ]);
    }

    public function get_style_depends() {

        wp_register_style( 'eready-info-box' , ELEMENT_READY_ROOT_CSS. 'widgets/info-box.css' );
        return [ 'eready-info-box' ];
    }

    protected function register_controls() {
        /*--------------------------
            CONTENT SECTION
        ---------------------------*/
        $this->start_controls_section(
            'infob_box_content_section',
            [
                'label' => esc_html__( 'Infobox Content & Style', 'element-ready-lite' ),
            ]
        );
            $this->add_control(
                'info_box_style',
                [
                    'label'   => esc_html__( 'Infobox Style', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'element__ready__infobox__style__1',
                    'options' => $this->element_ready_infobox_style(),
                ]
            );
                $this->add_control(
                    'title', [
                        'label'       => esc_html__( 'Header Title', 'element-ready-lite' ),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => esc_html__( 'My Title' , 'element-ready-lite' ),
                        'label_block' => true,
                        'separator'   => 'before',
                    ]
                );
            $repeater = new Repeater();
            $repeater->start_controls_tabs(
                'element_ready_list_tabs'
            );
            $repeater->start_controls_tab(
                'list_content_tab',
                [
                    'label' => esc_html__( 'Content', 'element-ready-lite' ),
                ]
            );
                $repeater->add_control(
                    'show_icon',
                    [
                        'label'        => esc_html__( 'Show Icon', 'element-ready-lite' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                        'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default'      => 'yes',
                        'separator'    => 'before',
                    ]
                );
                $repeater->add_control(
                    'list_icon',
                    [
                        'label'     => esc_html__( 'Icon', 'element-ready-lite' ),
                        'type'      => Controls_Manager::ICONS,
                        'label_block' => true,
                        'default'   => [
                            'default' => 'fa fa-check',
                            'library' => 'solid',
                        ],
                        'condition' => [
                            'show_icon' => 'yes',
                        ],
                    ]
                );
                $repeater->add_control(
                    'list_title', [
                        'label'       => esc_html__( 'Info Title', 'element-ready-lite' ),
                        'type'        => Controls_Manager::TEXT,
                        'label_block' => true,
                        'separator'   => 'before',
                    ]
                );
                $repeater->add_control(
                    'list_content', [
                        'label'      => esc_html__( 'Info Content', 'element-ready-lite' ),
                        'type'       => Controls_Manager::WYSIWYG,
                        'label_block' => true,
                        'separator'   => 'before',
                    ]
                );
            $repeater->end_controls_tab();
            $repeater->start_controls_tab(
                'list_style_tab',
                [
                    'label' => esc_html__( 'Style', 'element-ready-lite' ),
                ]
            );
                $repeater->add_control(
                    'current_item_icon_heading',
                    [
                        'label' => esc_html__( 'Current Item Icon Style', 'element-ready-lite' ),
                        'type'  => Controls_Manager::HEADING,
                    ]
                );
                $repeater->add_control(
                    'current_item_icon_color',
                    [
                        'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} {{CURRENT_ITEM}} .info__box__icon' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'      => 'current_item_icon_background',
                        'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'     => [ 'classic', 'gradient' ],
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .info__box__icon',
                    ]
                );
                $repeater->add_control(
                    'current_item_heading',
                    [
                        'label'     => esc_html__( 'Current Item Style', 'element-ready-lite' ),
                        'type'      => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $repeater->add_control(
                    'current_item_title_color',
                    [
                        'label'     => esc_html__( 'Title Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}} .info__title' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_color',
                    [
                        'label'     => esc_html__( 'Description Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}} .info__details' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'      => 'current_item_background',
                        'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'     => [ 'classic', 'gradient' ],
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}',
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Border:: get_type(),
                    [
                        'name'      => 'current_item_border',
                        'label'     => esc_html__( 'Border', 'element-ready-lite' ),
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}',
                    ]
                );
                $repeater->add_responsive_control(
                    'wrapper_padding',
                    [
                        'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
                $repeater->add_responsive_control(
                    'wrapper_margin',
                    [
                        'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
            $repeater->end_controls_tab();
            $repeater->start_controls_tab(
                'list_style_hover_tab',
                [
                    'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                ]
            );
                $repeater->add_control(
                    'current_item_hover_icon_heading',
                    [
                        'label' => esc_html__( 'Current Item Hover Icon Style', 'element-ready-lite' ),
                        'type'  => Controls_Manager::HEADING,
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_icon_color',
                    [
                        'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}:hover .info__box__icon' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'      => 'current_item_hover_icon_background',
                        'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'     => [ 'classic', 'gradient' ],
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}:hover .info__box__icon',
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_heading',
                    [
                        'label'     => esc_html__( 'Current Item Hover Style', 'element-ready-lite' ),
                        'type'      => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_title_color',
                    [
                        'label'     => esc_html__( 'Title Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}:hover .info__title' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_color',
                    [
                        'label'     => esc_html__( 'Description Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}:hover .info__details' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'      => 'current_item_hover_background',
                        'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'     => [ 'classic', 'gradient' ],
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}:hover',
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Border:: get_type(),
                    [
                        'name'      => 'current_item_hover_border',
                        'label'     => esc_html__( 'Border', 'element-ready-lite' ),
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__info__box{{CURRENT_ITEM}}:hover',
                    ]
                );
            $repeater->end_controls_tab();
            $repeater->end_controls_tabs();
            $this->add_control(
                'list_content',
                [
                    'label'   => esc_html__( 'Add Info Boxes', 'element-ready-lite' ),
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'list_icon'   => [
                                'value' => 'fa fa-phone',
                            ],
                            'list_title'   => esc_html__( 'PHONE:', 'element-ready-lite' ),
                            'list_content' => esc_html__( '+88 01744 430 440', 'element-ready-lite' ),
                        ],
                        [
                            'list_icon'   => [
                                'value' => 'far fa-envelope',
                            ],
                            'list_title'   => esc_html__( 'EMAIL:', 'element-ready-lite' ),
                            'list_content' => esc_html__( 'abdur.rohman2003@gmail.com', 'element-ready-lite' ),
                        ],
                        [
                            'list_icon'   => [
                                'value' => 'fas fa-map-marker-alt',
                            ],
                            'list_title'   => esc_html__( 'LOCATION:', 'element-ready-lite' ),
                            'list_content' => esc_html__( '44 Canal Center Plaza #200 Alexandria, VA 22314, USA', 'element-ready-lite' ),
                        ],
                    ],
                    'title_field' => '{{{ list_title }}}',
                    'separator'   => 'before',
                ]
            );
        $this->end_controls_section();
        /*--------------------------
            CONTENT SECTION END
        ---------------------------*/

        /*--------------------------
            AREA STYLE
        ---------------------------*/
        $this->start_controls_section(
            'wrapper_style_section',
            [
                'label' => esc_html__( 'Infobox Wrapper', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $icon_opt = apply_filters( 'element_ready_infobox_wrap_pro_message', $this->pro_message('wrap_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_infobox_wrap_styles', $this );

        $this->end_controls_section();
        /*----------------------------
            AREA STYLE END
        -----------------------------*/

        /*----------------------------
            HEADER TITLE
        -----------------------------*/
        $this->start_controls_section(
            'header_title_style_section',
            [
                'label' => esc_html__( 'Header Title', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'header_title_typography',
                    'selector' => '{{WRAPPER}} .info__box__header__title h3',
                ]
            );
            $this->add_control(
                'header_title_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .info__box__header__title h3' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'header_title_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .info__box__header__title h3',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_infobox_header_pro_message', $this->pro_message('header_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_infobox_header_styles', $this );


        $this->end_controls_section();
        /*----------------------------
            HEADER TITLE END
        -----------------------------*/

        /*----------------------------
            IOCN STYLE
        -----------------------------*/
        $this->start_controls_section(
            'icon_style_section',
            [
                'label' => esc_html__( 'Icon', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'icon_tabs_style' );
                $this->start_controls_tab(
                    'icon_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_responsive_control(
                        'icon_width',
                        [
                            'label'      => esc_html__( 'Icon Wrap Width', 'element-ready-lite' ),
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
                                '{{WRAPPER}} .info__box__icon' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'icon_height',
                        [
                            'label'      => esc_html__( 'Icon Wrap Height', 'element-ready-lite' ),
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
                                '{{WRAPPER}} .info__box__icon' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'after',
                        ]
                    );
                    $this->add_responsive_control(
                        'icon_size',
                        [
                            'label'      => esc_html__( 'Icon Size', 'element-ready-lite' ),
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
                                'size' => '18',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .info__box__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .info__box__icon svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'after',
                        ]
                    );
                    $this->add_control(
                        'icon_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .info__box__icon' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'icon_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .info__box__icon',
                        ]
                    );

                    $icon_opt = apply_filters( 'element_ready_infobox_icon_pro_message', $this->pro_message('icon_pro_messagte'), false );
                    $this->run_controls( $icon_opt );
                    do_action( 'element_ready_infobox_icon_styles', $this );

                $this->end_controls_tab();
                $this->start_controls_tab(
                    'icon_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );
                    $this->add_control(
                        'hover_icon_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__info__box:hover .info__box__icon' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_icon_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__info__box:hover .info__box__icon',
                        ]
                    );

                    $icon_opt = apply_filters( 'element_ready_infobox_icon_hover_pro_message', $this->pro_message('icon_hover_pro_messagte'), false );
                    $this->run_controls( $icon_opt );
                    do_action( 'element_ready_infobox_icon_hover_styles', $this );

                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            IOCN STYLE END
        -----------------------------*/

        /*----------------------------
            TITLE STYLE
        -----------------------------*/
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__( 'Title', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'title_tabs_style' );
                $this->start_controls_tab(
                    'title_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'title_typography',
                            'selector' => '{{WRAPPER}} .single__info__box .info__title',
                        ]
                    );
                    $this->add_control(
                        'title_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .single__info__box .info__title' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'title_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .single__info__box .info__title',
                        ]
                    );
                    $this->add_responsive_control(
                        'title_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__info__box .info__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'title_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__info__box .info__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'title_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );
                    $this->add_control(
                        'hover_title_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__info__box:hover .info__title' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'hover_title_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .single__info__box:hover .info__title',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            TITLE STYLE END
        -----------------------------*/

        /*------------------------
			BOX STYLE
        -------------------------*/
        $this->start_controls_section(
            'box_style_section',
            [
                'label' => esc_html__( 'Single Info Box', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $icon_opt = apply_filters( 'element_ready_infobox_box_pro_message', $this->pro_message('box_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_infobox_box_styles', $this );

        $this->end_controls_section();
        /*-------------------------
			BOX STYLE END
        --------------------------*/
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'element_ready_info_box_attr', 'class', 'element__ready__info__box__wrap' );
        $this->add_render_attribute( 'element_ready_info_box_attr', 'class', $settings['info_box_style'] );

        ?>
            <div <?php echo $this->get_render_attribute_string('element_ready_info_box_attr'); ?> >

                <?php if( !empty( $settings['title'] ) ): ?>
                    <div class = "info__box__header__title">
                        <h3><?php echo esc_html( $settings['title'] ); ?></h3>
                    </div>
                <?php endif; ?>
                <?php if( !empty( $settings['list_content'] ) ): ?>
                    <div class = "info__box__list">
                        <?php foreach ( $settings['list_content'] as $content ): ?>
                            <?php
                                $icon = $list_title = $list_content = '';
                                if ( !empty( $content['list_title'] ) ) {
                                    $list_title = $content['list_title'];
                                }
                                if ( !empty( $content['list_content'] ) ) {
                                    $list_content = $content['list_content'];
                                }
                            ?>
                            <div class="single__info__box elementor-repeater-item-<?php echo $content['_id']; ?>">

                                <?php if( !empty( $content['list_icon'] ) && $content['show_icon'] == true ): ?>
                                    <div class="info__box__icon">
                                        <?php Icons_Manager::render_icon( $content['list_icon'] ); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ( !empty( $list_title || $list_content ) ) :?>
                                    <?php if( $list_title ) : ?>
                                        <div class="info__title"><?php echo esc_html( $list_title ); ?></div>
                                    <?php endif; ?>
                                    <?php if( $list_content ) : ?>
                                        <div class="info__details"><?php echo wp_kses_post( wpautop( $list_content ) ); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php
    }
}