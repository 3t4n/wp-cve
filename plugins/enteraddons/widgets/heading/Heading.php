<?php
namespace Enteraddons\Widgets\Heading;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Heading widget.
 *
 * @since 1.0
 */
class Heading extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-heading';
	}

	public function get_title() {
		return esc_html__( 'Heading', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-section-title';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  Heading content ------------------------------
        $this->start_controls_section(
            'enteraddons_heading_title_content_settings',
            [
                'label' => esc_html__( 'Heading Title', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'TOP OF THE TRENDING ALBUM'
            ]
        );
        $this->add_control(
            'tag',
            [
                'label' => esc_html__( 'Set HTML Tag', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'span' => 'span',
                    'p' => 'p',
                    'div' => 'div'
                ],
                'default' => 'h2'
            ]
        );
        
        $this->end_controls_section(); // End Heading content

        // ----------------------------------------  Sub title content ------------------------------
        $this->start_controls_section(
            'enteraddons_subtitle_content_settings',
            [
                'label' => esc_html__( 'Sub Title', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'show_subtitle',
            [
                'label'         => esc_html__( 'Show Sub Title', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'enteraddons' ),
                'label_off'     => esc_html__( 'No', 'enteraddons' ),
                'return_value'  => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__( 'Sub Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'TRENDING ALBUMS'
            ]
        );
        $this->add_control(
            'subtitle_tag',
            [
                'label' => esc_html__( 'Set HTML Tag', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'span' => 'span',
                    'p' => 'p',
                    'div' => 'div'
                ],
                'default' => 'span'
            ]
        );
        
        $this->end_controls_section(); // End Sub title content

        // ----------------------------------------  Description content ------------------------------
        $this->start_controls_section(
            'enteraddons_description_content_settings',
            [
                'label' => esc_html__( 'Description', 'enteraddons' ),
            ]
        );
                
        $this->add_control(
            'show_description',
            [
                'label'         => esc_html__( 'Show Description', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'enteraddons' ),
                'label_off'     => esc_html__( 'No', 'enteraddons' ),
                'return_value'  => 'yes',
                'default' => 'yes',
            ]
        );       
        $this->add_control(
            'description',
            [
                'label'         => esc_html__( 'Description', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ]
                
            ]
        );
        $this->end_controls_section(); // End description content

        // ----------------------------------------  Divider content ------------------------------
        $this->start_controls_section(
            'enteraddons_divider_content_settings',
            [
                'label' => esc_html__( 'Divider', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'show_description_divider',
            [
                'label'         => esc_html__( 'Show Divider', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'enteraddons' ),
                'label_off'     => esc_html__( 'No', 'enteraddons' ),
                'return_value'  => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'divider_style',
            [
                'label' => esc_html__( 'Divider Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'style-1'    => esc_html__( 'Style 1', 'enteraddons' ),
                    'style-2'    => esc_html__( 'Style 2', 'enteraddons' ),
                    'style-3'    => esc_html__( 'Style 3', 'enteraddons' ),
                    'custom'    => esc_html__( 'Custom', 'enteraddons' )
                ],
                'default' => 'style-1'
            ]
        );
        $this->add_control(
            'divider_image',
            [
                'label' => esc_html__( 'Choose Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [ 'divider_style' => 'custom' ]
            ]
        );
        $this->end_controls_section(); // End description content

        // ---------------------------------------- Content Ordering ------------------------------
        $this->start_controls_section(
            'enteraddons_content_order_settings',
            [
                'label' => esc_html__( 'Content Ordering', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'heading_order',
            [
                'label' => esc_html__( 'Heading Order', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-title' => 'order: {{SIZE}};',
                ],
                'default' => [
					'unit' => 'px',
					'size' => 2,
				],
            ]
        );
        $this->add_responsive_control(
            'sub_title_order',
            [
                'label' => esc_html__( 'Sub Title Order', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-title' => 'order: {{SIZE}};',
                ],
                'default' => [
					'unit' => 'px',
					'size' => 1,
				],
            ]
        );
        $this->add_responsive_control(
            'desc_order',
            [
                'label' => esc_html__( 'Descriptions Order', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-desc' => 'order: {{SIZE}};',
                ],
                'default' => [
					'unit' => 'px',
					'size' => 4,
				],
                
            ]
        );
        $this->add_responsive_control(
            'seperator_order',
            [
                'label' => esc_html__( 'Seperator Order', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .enteraddons-seperator' => 'order: {{SIZE}};',
                ],
                'default' => [
					'unit' => 'px',
					'size' => 3,
				],
            ]
        );
        $this->end_controls_section(); // End content ordering

        /**
         * Style Tab
         * ------------------------------ Content Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_team_wrapper_style_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'content_alignment',
                [
                    'label' => esc_html__( 'Content Flex Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-section-title' => 'align-items: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'text_alignment',
                [
                    'label' => esc_html__( 'Content Text Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-section-title' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-section-title',
                ]
            );
            $this->add_responsive_control(
                'item_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-section-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-section-title',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-section-title',
                ]
            );
        $this->end_controls_section();

        //------------------------------ Title Style ------------------------------
        $this->start_controls_section(
            'enteraddons_title_style', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-section-title .heading-title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'title_ba_heading',
            [
                'label' => esc_html__( 'Title Before/After', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'active_title_ba',
            [
                'label' => esc_html__( 'Active Before/After', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'title_ba_hide_tab',
            [
                'label' => esc_html__( 'Hide Before/After On Tab', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [ 'active_title_ba' => 'yes' ]
            ]
        );
        $this->add_control(
            'title_ba_hide_mob',
            [
                'label' => esc_html__( 'Hide Before/After On Mobile', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [ 'active_title_ba' => 'yes' ]
            ]
        );
        $this->add_responsive_control(
            'title_ba_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '150',
                ],
                'selectors' => [
                    '{{WRAPPER}} .entera-heading-title-ba:before' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .entera-heading-title-ba:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_ba_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '2',
                ],
                'selectors' => [
                    '{{WRAPPER}} .entera-heading-title-ba:before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .entera-heading-title-ba:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_ba_top_btom_position',
            [
                'label' => esc_html__( 'Position Top/Bottom', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '2',
                ],
                'selectors' => [
                    '{{WRAPPER}} .entera-heading-title-ba:before' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .entera-heading-title-ba:after' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_ba_before_left',
            [
                'label' => esc_html__( 'Before Position Right/Left', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '2',
                ],
                'selectors' => [
                    '{{WRAPPER}} .entera-heading-title-ba:before' => 'left: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'title_ba_after_left',
            [
                'label' => esc_html__( 'After Position Right/Left', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '2',
                ],
                'selectors' => [
                    '{{WRAPPER}} .entera-heading-title-ba:after' => 'left: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'ba_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .entera-heading-title-ba:after, {{WRAPPER}} .entera-heading-title-ba:before',
            ]
        );


        $this->end_controls_section();

        //------------------------------ Sub title Style ------------------------------
        $this->start_controls_section(
            'enteraddons_sub_title_style', [
                'label' => esc_html__( 'Sub Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-section-title .heading-short-title',
            ]
        );
        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'sub_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        //------------------------------ Descriptions Style ------------------------------
        $this->start_controls_section(
            'enteraddons_descriptions_style', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'descriptions_area_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-desc' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'descriptions_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-desc' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-section-title .heading-short-desc',
            ]
        );
        $this->add_responsive_control(
            'descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-section-title .heading-short-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        //------------------------------ Divider Style ------------------------------
        $this->start_controls_section(
            'enteraddons_divider_style', [
                'label' => esc_html__( 'Divider', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'divider_line_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '60',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-seperator.seperator' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'divider_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator',
            ]
        );
        $this->add_responsive_control(
            'divider_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-seperator.seperator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'divider_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'divider_bg_color',
                'label' => esc_html__( 'Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator',
            ]
        );
        $this->add_responsive_control(
            'divider_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-seperator.seperator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-seperator.seperator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //  Controls tab start
        $this->start_controls_tabs( 'divider_tabs_start' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'divider_before_tab',
            [
                'label' => esc_html__( 'Before', 'enteraddons' ),
            ]
        );        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'divider_before_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator:before',
            ]
        );
        $this->add_responsive_control(
            'divider_before_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-seperator.seperator:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'divider_before_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator:before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'divider_before_bg_color',
                'label' => esc_html__( 'Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator:before',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For after
        $this->start_controls_tab(
            'divider_after_tab',
            [
                'label' => esc_html__( 'After', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'divider_after_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator:after',
            ]
        );
        $this->add_responsive_control(
            'divider_after_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-seperator.seperator:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'divider_after_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator:after',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'divider_after_bg_color',
                'label' => esc_html__( 'Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-seperator.seperator:after',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Heading_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
