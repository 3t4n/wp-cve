<?php
namespace Enteraddons\Widgets\Social_Icon;

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
 * Enteraddons elementor Social Icon widget.
 *
 * @since 1.0
 */
class Social_Icon extends Widget_Base {

	public function get_name() {
		return 'enteraddons-social-icon';
	}

	public function get_title() {
		return esc_html__( 'Social Icon', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-social-icon';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Social icon content ------------------------------
        $this->start_controls_section(
            'enteraddons_social_icon_content',
            [
                'label' => esc_html__( 'Social Icon Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'social_heading', [
                'label' => esc_html__( 'Heading', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Follow Me On :' , 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'social_icon_title', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Facebook' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'enteraddons' ),
                    'img'  => esc_html__( 'Image', 'enteraddons' ),
                ],
            ]
        );
        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'icon_type' => 'icon' ],
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'solid',
                ],
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [ 'icon_type' => 'img' ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
            ]
        );
        $this->add_control(
            'social_icon',
            [
                'label' => esc_html__( 'Sicial Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ social_icon_title }}}',
                'default' => [
                    [
                        'social_icon_title' => esc_html__( 'Facebook', 'enteraddons' ),
                        'icon' => [
                                'value' => 'fab fa-facebook-f',
                                'library' => 'solid'
                            ]
                    ],
                    [
                        'social_icon_title' => esc_html__( 'Twitter', 'enteraddons' ),
                        'icon' => [
                                'value' => 'fab fa-twitter',
                                'library' => 'solid'
                            ]
                    ],
                    [
                        'social_icon_title' => esc_html__( 'Instagram', 'enteraddons' ),
                        'icon' => [
                                'value' => 'fab fa-instagram',
                                'library' => 'solid'
                            ]
                    ]
                    
                ]
            ]
        );

        $this->end_controls_section(); // End  content

        /**
         * Style Tab
         * ------------------------------ Wrapper Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_social_icon_content_wrapper_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon-wrap',
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon-wrap',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-social-icon-wrap',
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Inner wrapper Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_social_icon_content_inner_wrapper_settings', [
                'label' => esc_html__( 'Inner Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_inner_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon-inner-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_inner_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon-inner-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_inner_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon-inner-wrap',
            ]
        );
        $this->add_responsive_control(
            'content_inner_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_inner_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon-inner-wrap',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_inner_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-social-icon-inner-wrap',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ layout Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'social_icon_layout_settings', [
                'label' => esc_html__( 'Layout Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'social_icon_content_layout',
                [
                    'label' => esc_html__( 'Content Layout', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'column' => [
                            'title' => esc_html__( 'Column', 'enteraddons' ),
                            'icon' => 'eicon-ellipsis-v',
                        ],
                        'row' => [
                            'title' => esc_html__( 'Row', 'enteraddons' ),
                            'icon' => 'eicon-ellipsis-h',
                        ],
                    ],
                    'default' => 'row',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-social-icon-inner-wrap' => 'flex-direction: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'content_vertical_alignment',
                [
                    'label' => esc_html__( 'Content Vertical Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'condition' => [ 'social_icon_content_layout' => 'row' ],
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Middle', 'enteraddons' ),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-social-icon-wrap .enteraddons-social-icon-inner-wrap' => 'align-items: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'social_icon_layout',
                [
                    'label' => esc_html__( 'Icon Layout', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'column' => [
                            'title' => esc_html__( 'Column', 'enteraddons' ),
                            'icon' => 'eicon-ellipsis-v',
                        ],
                        'row' => [
                            'title' => esc_html__( 'Row', 'enteraddons' ),
                            'icon' => 'eicon-ellipsis-h',
                        ],
                    ],
                    'default' => 'row',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-social-icon' => 'flex-direction: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'social_icon_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-social-icon-inner-wrap' => 'align-items:{{VALUE}};justify-content: {{VALUE}} !important',
                    ],
                ]
            );
                        
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Title Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'social_icon_title_style_settings', [
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
                    '{{WRAPPER}} .social-heading h2' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .social-heading h2',
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
                    '{{WRAPPER}} .social-heading h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .social-heading h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'title_divider_heading',
            [
                'label' => esc_html__( 'Divider', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'active_heading_divider',
            [
                'label' => esc_html__( 'Show Divider', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_responsive_control(
            'title_divider_width',
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
                    '{{WRAPPER}} .social-heading.heading-divider h2:after' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'title_divider_height',
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
                    '{{WRAPPER}} .social-heading.heading-divider h2:after' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'title_divider_top_btom_position',
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
                    '{{WRAPPER}} .social-heading.heading-divider h2:after' => 'top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'title_divider_position_right_left',
            [
                'label' => esc_html__( 'Position Right/Left', 'enteraddons' ),
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
                    '{{WRAPPER}} .social-heading.heading-divider h2:after' => 'left: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'divider_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .social-heading.heading-divider h2:after',
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Icon Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'social_icon_icon_style_settings', [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_style',
            [
                'label' => esc_html__( 'Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => esc_html__( 'Style 1', 'enteraddons' ),
                    '2' => esc_html__( 'Style 2', 'enteraddons' ),
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_title_typography',
                'label' => esc_html__( 'Title Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon .soci-media-title',
                'condition' => [ 'icon_style' => '2' ],
            ]
        );
        $this->add_control(
            'icon_text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [ 'icon_style' => '2' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a .soci-media-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_line_color',
            [
                'label' => esc_html__( 'Line Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [ 'icon_style' => '2' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a .soci-media-border' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->start_controls_tabs( 'tab_icon_wrap' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'icon_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'icon_width',
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
                
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_height',
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
                
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_item_gap',
            [
                'label' => esc_html__( 'Icon Gap', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a',
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_box_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'icon_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_hover_border',
            [
                'label' => esc_html__( 'Border Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_box_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-social-icon.icon-style--three a:hover',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();   

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        // Template render
        $obj = new Social_Icon_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
