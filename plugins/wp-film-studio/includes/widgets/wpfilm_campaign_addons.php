<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPFilm_Elementor_Widget_Campaign extends Widget_Base {

    public function get_name() {
        return 'campaigns-post';
    }
    
    public function get_title() {
        return __( 'WPFilm Studio : Campaign', 'wpfilm-studio' );
    }

    public function get_icon() {
        return 'eicon-calendar';
    }
    public function get_categories() {
        return [ 'wpfilm-studio' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'campaign_setting',
            [
                'label' => esc_html__( 'Campaign', 'wpfilm-studio' ),
            ]
        );
        $this->start_controls_tabs(
                'wpfilm_tabs'
            );
                $this->start_controls_tab(
                    'wpfilm_content_tab',
                    [
                        'label' => __( 'Content', 'wpfilm-studio' ),
                    ]
                );

            $this->add_control(
                'content_show_ttie',
                [
                    'label' => __( 'Content Source Option', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'wpfilm_categories',
                [
                    'label' => esc_html__( 'Select Campaign Category', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => wpfilm_categories(),                   
                ]
            );
            $this->add_control(
                'thumbnail_show_hide',
                [
                    'label' => esc_html__( 'Image Show/Hide', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            ); 
            $this->add_control(
                'campaign_date_show_hide',
                [
                    'label' => esc_html__( 'Date Show/Hide', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );                      
            $this->add_control(
                'title_length',
                [
                    'label' => __( 'Title Length', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                    'default' => 3,
                ]
            );
            $this->add_control(
                'read_more_btn_show_hide',
                [
                    'label' => esc_html__( 'Read More Show/Hide', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'read_more_btn_txt',
                [
                    'label' => __( 'Read More Text', 'wpfilm-studio' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'Read More',
                    'title' => __( 'Enter button text', 'wpfilm-studio' ),
                    'condition' => [
                        'read_more_btn_show_hide' => 'yes',
                    ]
                ]
            );           
            $this->add_control(
                'content_length',
                [
                    'label' => __( 'Content Length', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 200,
                    'step' => 1,
                    'default' => 15,                   
                ]
            );

            $this->end_controls_tab();

                $this->start_controls_tab(
                    'wpfilm_option_tab',
                    [
                        'label' => __( 'Option', 'wpfilm-studio' ),
                    ]
                );

            $this->add_control(
                'item_show_ttie',
                [
                    'label' => __( 'Item Show Option', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'iteam_layout',
                [
                    'label' => esc_html__( 'Select layout', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'grid',
                    'options' => [
                        'carosul' => esc_html__( 'Carousel', 'wpfilm-studio' ),
                        'grid' => esc_html__( 'Grid', 'wpfilm-studio' ),
                    ],
                ]
            );               
            $this->add_control(
                'post_per_page',
                [
                    'label' => __( 'Show Total Item', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 1,
                    'default' => 6,
                ]
            );
            $this->add_control(
                'caselautoplay',
                [
                    'label' => esc_html__( 'Auto play', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );
            $this->add_control(
                'caselautoplayspeed',
                [
                    'label' => __( 'Auto play speed', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 5,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 5000,
                    'condition' => [
                        'caselautoplay' => 'yes',
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );
            $this->add_control(
                'caselarrows',
                [
                    'label' => esc_html__( 'Arrow', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );

            $this->add_control(
                'arrow_icon_next',
                [
                    'label' => __( 'Icon Next', 'wpfilm-studio' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fa fa-angle-right',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'caselarrows' => 'yes',
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );

            $this->add_control(
                'arrow_icon_prev',
                [
                    'label' => __( 'Icon Prev', 'wpfilm-studio' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fa fa-angle-left',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'caselarrows' => 'yes',
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );

            $this->add_control(
                'showitem',
                [
                    'label' => __( 'Show Item', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                    'default' => 2,
                    'condition' => [
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );
            $this->add_control(
                'showitemtablet',
                [
                    'label' => __( 'Show Item (Tablet)', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                    'default' => 2,
                    'condition' => [
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );
            $this->add_control(
                'showitemmobile',
                [
                    'label' => __( 'Show Item (Large Mobile)', 'wpfilm-studio' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'iteam_layout' => 'carosul',
                    ]
                ]
            );
            $this->add_control(
              'wp_film_campaign_item_gutter',
              [
                 'label'   => __( 'Item Gutter', 'shieldem' ),
                 'type'    => Controls_Manager::NUMBER,
                 'default' => 30,
                 'min'     => 0,
                 'max'     => 100,
                 'step'    => 1,
                    'condition' => [
                        'iteam_layout' => 'carosul',
                    ]
              ]
            );

            $this->add_control(
                'grid_column_number',
                [
                    'label' => esc_html__( 'Columns', 'wpfilm-studio' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '2',
                    'options' => [
                        '1' => esc_html__( '1', 'wpfilm-studio' ),
                        '2' => esc_html__( '2', 'wpfilm-studio' ),
                        '3' => esc_html__( '3', 'wpfilm-studio' ),
                        '4' => esc_html__( '4', 'wpfilm-studio' ),
                        '5' => esc_html__( '5', 'wpfilm-studio' ),
                        '6' => esc_html__( '6', 'wpfilm-studio' ),
                    ],
                    'condition' => [
                        'iteam_layout' => 'grid',
                    ]
                ]
            );            
            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'section_title_style1s',
            [
                'label' => __( 'Content Style', 'wpfilm-studio' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
                'wpfilm_style_tabs'
            );
                $this->start_controls_tab(
                    'wpfilm_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'wpfilm-studio' ),
                    ]
                );

            $this->add_control(
                'item_title_heading',
                [
                    'label' => __( 'Title Color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            // Title Style
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Title color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => 'rgba(0, 0, 0, 0.85)',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box h3 a' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'titletypography',
                    'selector' => '{{WRAPPER}} .wp-campaign-box h3',
                ]
            );
            $this->add_responsive_control(
                'margin',
                [
                    'label' => __( 'Title Margin', 'wpfilm-studio' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'item_content_heading',
                [
                    'label' => __( 'Content Style', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'item_content_color',
                [
                    'label' => __( 'Content color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#555',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box p,{{WRAPPER}} .wpfilm_single-event .wpfilm_event-desc p' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'contetnttyphography',                    
                    'selector' => '{{WRAPPER}} .wp-campaign-box > p,{{WRAPPER}} .wpfilm_single-event .wpfilm_event-desc p',
                ]
            );
           
            // Icon Style
            $this->add_control(
                'item_meta_heading',
                [
                    'label' => __( 'Date Style', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            ); 
            $this->add_control(
                'item_meta_info_color',
                [
                    'label' => __( 'Date color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#555',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box h5' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datetyphography',                    
                    'selector' => '{{WRAPPER}} .wp-campaign-box h5',
                ]
            );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'item_date_border_color',
                        'label' => __( 'Date Border', 'wpfilm-studio' ),
                        'selector' => '{{WRAPPER}} .wp-campaign-box h5:after',
                    ]
                );
            $this->add_control(
                'item_read_more_heading',
                [
                    'label' => __( 'Read More Style', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );             
            $this->add_control(
                'item_read_more_color',
                [
                    'label' => __( 'Read More color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#555',
                    'selectors' => [
                        '{{WRAPPER}} .cmapaign-redmore' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'read_more_typhography',
                    'selector' => '{{WRAPPER}} .cmapaign-redmore',
                ]
            );            
            $this->end_controls_tab();

            $this->start_controls_tab(
                'wpfilm_style_hover_tab',
                [
                    'label' => __( 'Hover', 'wpfilm-studio' ),
                ]
            );
            $this->add_control(
                'item_title_heading_hover',
                [
                    'label' => __( 'Title Hover Color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'title_color_hover',
                [
                    'label' => __( 'Title Hover color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => 'rgba(0, 0, 0, 0.85)',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box:hover h3' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'title_color_hover_link',
                [
                    'label' => __( 'Title Hover Link color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#e2a750',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box h3 a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );            
            $this->add_control(
                'item_content_heading_hover',
                [
                    'label' => __( 'Content Hover Style', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'item_content_color_hover',
                [
                    'label' => __( 'Content Hover color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#555',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box:hover p' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'item_read_more_color_hover',
                [
                    'label' => __( 'Read More Hover color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#e2a750',
                    'selectors' => [
                        '{{WRAPPER}} .cmapaign-redmore:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'item_box_style',
            [
                'label' => __( 'Box Style', 'wpfilm-studio' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
                'wpfilm_item_box_style'
            );
                $this->start_controls_tab(
                    'item_box_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'wpfilm-studio' ),
                    ]
                );
            $this->add_control(
                'overlay_style',
                [
                    'label' => __( 'Overlay Style', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'box_overlay_color',
                [
                    'label' => __( 'Overlay BG Color', 'wpfilm-studio' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'rgba(255,255,255,0)',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box, {{WRAPPER}} .wpfilm_single-event' => 'background: {{VALUE}};',
                    ],
                ]
            );


            $this->add_responsive_control(
                'box_alignment',
                [
                    'label' => __( 'Content Alignment', 'wpfilm-studio' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'wpfilm-studio' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'wpfilm-studio' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'wpfilm-studio' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => 'Left',
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box, {{WRAPPER}} .wpfilm_single-event,{{WRAPPER}} .campaign_style4' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'box_margin',
                [
                    'label' => __( 'Margin', 'wpfilm-studio' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box, {{WRAPPER}} .wpfilm_single-event,{{WRAPPER}} .campaign_style4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'box_padding',
                [
                    'label' => __( 'Padding', 'wpfilm-studio' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box, {{WRAPPER}} .wpfilm_single-event,{{WRAPPER}} .campaign_style4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'box_border_radious',
                [
                    'label' => __( 'Box Border Radius', 'wpfilm-studio' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-box, {{WRAPPER}} .wpfilm_single-event,{{WRAPPER}} .campaign_style4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'box_border_single',
                    'label' => __( 'Box Border', 'wpfilm-studio' ),
                    'selector' => '{{WRAPPER}} .wp-campaign-box,{{WRAPPER}} .campaign_style4',
                ]
            ); 
            $this->add_control(
                'content_box_haeading',
                [
                    'label' => __( 'Content Box Style', 'wpfilm-studio' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_responsive_control(
                'content_box_margin',
                [
                    'label' => __( 'Content Box Margin', 'wpfilm-studio' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-content,{{WRAPPER}} .wp_film_content_st4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'content_box_padding',
                [
                    'label' => __( 'Content Box Padding', 'wpfilm-studio' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wp-campaign-content,{{WRAPPER}} .wp_film_content_st4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->start_controls_tab(
                    'item_box_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'wpfilm-studio' ),
                    ]
                );
                $this->add_control(
                    'box_overlay_hover_color',
                    [
                        'label' => __( 'Overlay Hover  BG Color', 'wpfilm-studio' ),
                        'type' => Controls_Manager::COLOR,
                        'default'=>'rgba(255,255,255,0)',
                        'selectors' => [
                            '{{WRAPPER}} .wp-campaign-box:hover' => 'background: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'box_border_single_hover',
                        'label' => __( 'Box Border Hover', 'wpfilm-studio' ),
                        'selector' => '{{WRAPPER}} .wp-campaign-box:hover',
                    ]
                ); 
            $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        // Carousel Style
        $this->start_controls_section(
            'carousel_style',
            [
                'label' => __( 'Carousel Button', 'wpfilm-studio' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs(
                    'wpfilm_carousel_style_tabs'
                );
                $this->start_controls_tab(
                    'wpfilm_carouse_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'wpfilm-studio' ),
                    ]
                );
                    $this->add_control(
                        'slider_arrow_button_heading',
                        [
                            'label' => __( 'Arrow Button', 'wpfilm-studio' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );   
                    $this->add_control(
                        'carousel_nav_color',
                        [
                            'label' => __( 'COlor', 'wpfilm-studio' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#000',
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'carousel_nav_bg_color',
                        [
                            'label' => __( 'BG COlor', 'wpfilm-studio' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'rgba(0,0,0,0)',
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow' => 'background: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'arrwo_border',
                            'label' => __( 'Border', 'wpfilm-studio' ),
                            'selector' => '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow',
                        ]
                    ); 
                    $this->add_control(
                        'carousel_nav_border_radius',
                        [
                            'label' => __( 'Border Radius', 'elementor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],

                        ]
                    );

                    $this->add_responsive_control(
                        'carousel_navicon_width',
                        [
                            'label' => __( 'Width', 'wpfilm-studio' ),
                            'type' => Controls_Manager::NUMBER,
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow' => 'width: {{VALUE}}px;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'carousel_navicon_height',
                        [
                            'label' => __( 'Height', 'wpfilm-studio' ),
                            'type' => Controls_Manager::NUMBER,
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow' => 'height: {{VALUE}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'carousel_nav_typography',
                            'selector' => '{{WRAPPER}} .campaign-active .slick-arrow,{{WRAPPER}} .slider-nav-video-item .slick-arrow',
                        ]
                    );
                    $this->add_responsive_control(
                        'carousel_navicon_top_margin',
                        [
                            'label' => __( 'Button Top Position', 'wpfilm-studio' ),
                            'type' => Controls_Manager::NUMBER,
                            'min' => -200,
                            'max' => 200,
                            'step' => 1,
                            'default' => -87,
                            'selectors' => [
                                '{{WRAPPER}} .indicator-style-two.campaign-active .slick-arrow' => 'top: {{VALUE}}px;',
                            ],
                        ]
                    );                    
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'wpfilm_carouse_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'wpfilm-studio' ),
                    ]
                );
                  $this->add_control(
                        'carousel_nav_color_hover',
                        [
                            'label' => __( 'COlor', 'wpfilm-studio' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#e2a750',
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow:hover,{{WRAPPER}} .slider-nav-video-item .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'carousel_nav_bg_color_hover',
                        [
                            'label' => __( 'BG COlor', 'wpfilm-studio' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow:hover,{{WRAPPER}} .slider-nav-video-item .slick-arrow:hover' => 'background: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'arrwo_border_hover',
                            'label' => __( 'Border', 'wpfilm-studio' ),
                            'selector' => '{{WRAPPER}} .campaign-active .slick-arrow:hover,{{WRAPPER}} .slider-nav-video-item .slick-arrow:hover',
                        ]
                    ); 
                    $this->add_control(
                        'carousel_nav_border_radius_hover',
                        [
                            'label' => __( 'Border Radius', 'elementor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .campaign-active .slick-arrow:hover,{{WRAPPER}} .slider-nav-video-item .slick-arrow:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],

                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();                
        $this->end_controls_section();
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $iteam_layout = $settings['iteam_layout'];
        $caselautoplay = $settings['caselautoplay'];
        $caselarrows = $settings['caselarrows'];
        $caselautoplayspeed = $settings['caselautoplayspeed'];
        $wp_film_campaign_item_gutter = $this->get_settings_for_display('wp_film_campaign_item_gutter');
        $showitem = $settings['showitem'];
        $showitemtablet = $settings['showitemtablet'];
        $showitemmobile = $settings['showitemmobile'];
        $thumbnail_show_hide = $settings['thumbnail_show_hide'];
        $campaign_date_show_hide = $settings['campaign_date_show_hide'];
        $read_more_btn_show_hide = $settings['read_more_btn_show_hide'];
        $columns = $this->get_settings_for_display('grid_column_number');
        $arrow_icon_prev  = $this->get_settings_for_display('arrow_icon_prev');
        $arrow_icon_next  = $this->get_settings_for_display('arrow_icon_next');
        $get_item_categories = $settings['wpfilm_categories'];
        $per_page       = ! empty( $settings['post_per_page'] ) ? $settings['post_per_page'] : 6;
        $titlelength    = ! empty( $settings['title_length'] ) ? $settings['title_length'] : 2;
        $contetnlength  = ! empty( $settings['content_length'] ) ? $settings['content_length'] : 20;
        $btntext        = ! empty( $settings['read_more_btn_txt'] ) ? $settings['read_more_btn_txt'] : '';
        $sectionid =  $this-> get_id();
        $collumval = 'col-lg-3 col-sm-12';
        if( $columns !='' ){
            $colwidth = round(12/$columns);
            $collumval = 'col-lg-'.$colwidth.' col-sm-12';
        }

        ?>
            <div class="campaigns-area">
                <div class="<?php if($iteam_layout == 'carosul'){ echo 'campaign-active indicator-style-two '.esc_attr($sectionid); } else echo 'row';?>">
                    <?php
                    $item_cates = str_replace(' ', '', $get_item_categories);
                        $htsargs = array(
                            'post_type'            => 'wpcampaign',
                            'posts_per_page'       => $per_page, 
                            'ignore_sticky_posts'  => 1,
                            'order'                => 'DESC',
                        );

                        if ( "0" != $get_item_categories) {
                            if( is_array($item_cates) && count($item_cates) > 0 ){
                                $field_name = is_numeric($item_cates[0])?'term_id':'slug';
                                $htsargs['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'campaign_category',
                                        'terms' => $item_cates,
                                        'field' => $field_name,
                                        'include_children' => false
                                    )
                                );
                            }
                        }
                        $htspost = new \WP_Query($htsargs);
                        while($htspost->have_posts()):$htspost->the_post();

                        $icon_images = get_post_meta(get_the_id(),'_wpfilm_campaign_icon_img',true); 
                        $servce_icon  = get_post_meta( get_the_ID(),'_wpfilm_campaign_icon', true );
                        $servce_icon_type  = get_post_meta( get_the_ID(),'_wpfilm_campaign_icon_type', true );
                        $short_des = get_post_meta( get_the_ID(),'_wpfilm_campaign_short_des', true ); 
                        $campaign_location  = get_post_meta( get_the_ID(),'_wpfilm_loaction', true );
                        $campaign_time  = get_post_meta( get_the_ID(),'_wpfilm_campaign_time', true );
                        $campaign_date  = get_post_meta( get_the_ID(),'_wpfilm_campaign_date', true );
                              ?>                    
                    <!-- Single Item --> 
                    <?php if($iteam_layout == 'grid') { echo '<div class="'.esc_attr($collumval).'">'; } ?>

                    <!-- Single Item -->
                        <div class="wp-campaign-box">
                            <?php if($thumbnail_show_hide == 'yes'){ ?>
                                <div class="wp-campaign-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('wpfilm_img580x436');?>
                                    </a>
                                </div>
                                <?php }?>

                            <div class="wp-campaign-content">
                                <?php if($campaign_date_show_hide == 'yes'){ ?>
                                <h5><?php echo esc_html($campaign_date);?></h5>
                                <?php }?>
                                <h3><a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), $titlelength, '' );?></a></h3>     
                                <?php echo '<p>'.wp_trim_words( $short_des, $contetnlength, '' ).'</p>';?>
                                <?php if( $read_more_btn_show_hide == 'yes' && !empty($btntext)){ ?>
                                <a class="cmapaign-redmore" href="<?php the_permalink(); ?>"><?php echo $btntext; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php if($iteam_layout == 'grid'){echo '</div> ';}?>
                <?php endwhile; ?>
                </div>
            </div>
        <?php if(!empty($wp_film_campaign_item_gutter)){ ?>
            <style>
                .campaign-active .slick-list{
                    margin: 0 -<?php echo $wp_film_campaign_item_gutter; ?>px;
                }
                .campaign-active .wp-campaign-box {
                    margin: 0 <?php echo $wp_film_campaign_item_gutter; ?>px;
                }
                </style>
       <?php } ?>
    

            

 <?php if($iteam_layout == 'carosul'){ ?>
    
            <script type="text/javascript">
               

                jQuery(document).ready(function($) {

                    var _arrows_set = <?php echo esc_js( $caselarrows ) == 'yes' ? 'true': 'false'; ?>;
                    var _autoplay_set = <?php echo esc_js( $caselautoplay ) == 'yes' ? 'true': 'false'; ?>;
                    var _autoplay_speed = <?php if( isset($caselautoplayspeed) ){ echo esc_js($caselautoplayspeed); }else{ echo esc_js(5000); }; ?>;
                    var _showitem_set = <?php if( isset($showitem) ){ echo esc_js($showitem); }else{ echo esc_js(3); }; ?>;
                    var _showitemtablet_set = <?php if( isset($showitemtablet) ){ echo esc_js($showitemtablet); }else{ echo esc_js(2); }; ?>;
                    var _showitemmobile_set = <?php if( isset($showitemmobile) ){ echo esc_js($showitemmobile); }else{ echo esc_js(2); }; ?>;
                    $('.campaign-active.<?php echo esc_attr($sectionid);?>').slick({
                        slidesToShow: _showitem_set,
                        arrows:_arrows_set,
                        dots: false,
                        autoplay: _autoplay_set,
                        autoplaySpeed: _autoplay_speed,
                        prevArrow: '<div class="btn-prev"><?php \Elementor\Icons_Manager::render_icon( $settings['arrow_icon_prev'], [ 'aria-hidden' => 'true' ] );?></div>',
                        nextArrow: '<div><?php \Elementor\Icons_Manager::render_icon( $settings['arrow_icon_next'], [ 'aria-hidden' => 'true' ] );?></div>',

                        responsive: [
                                {
                                  breakpoint: 991,
                                  settings: {
                                    slidesToShow: _showitemtablet_set
                                  }
                                },
                                {
                                  breakpoint: 768,
                                  settings: {
                                    slidesToShow: _showitemmobile_set
                                  }
                                },
                                {
                                  breakpoint: 575,
                                  settings: {
                                    slidesToShow: 1
                                  }
                                }
                              ]
                        });
                    
                });

            </script>

        <?php } ?>

        <?php

        wp_reset_query(); wp_reset_postdata();

    }


}

Plugin::instance()->widgets_manager->register( new WPFilm_Elementor_Widget_Campaign() );