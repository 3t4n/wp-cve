<?php
namespace Enteraddons\Widgets\Testimonial_Multi_Rows;

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
 * Enteraddons elementor testimonial widget.
 *
 * @since 1.0
 */
class Testimonial_Multi_Rows extends Widget_Base {

	public function get_name() {
		return 'enteraddons-testimonial-multi-rows';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Multi Rows', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-testimonial-multi-rows';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Testimonial content ------------------------------
        $this->start_controls_section(
            'enteraddons_testimonial_content',
            [
                'label' => esc_html__( 'Testimonial Content', 'enteraddons' ),
            ]
        );
        
        // Slider Settings
        $this->add_control(
            'per_row_item_number',
            [
                'label' => esc_html__( 'Item Number Per Row', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 5
            ]
        );
        $repeater->add_control(
            'testimonial_name', [
                'label' => esc_html__( 'Name', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'MARY P. JOHNSON' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_designation', [
                'label' => esc_html__( 'Designation', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'CEO, Octen, Melborne,' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_location', [
                'label' => esc_html__( 'Location', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Auenteralia.' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_rating', [
                'label' => esc_html__( 'Rating', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__( '1', 'enteraddons' ),
                    '2' => esc_html__( '2', 'enteraddons' ),
                    '3' => esc_html__( '3', 'enteraddons' ),
                    '4' => esc_html__( '4', 'enteraddons' ),
                    '5' => esc_html__( '5', 'enteraddons' )
                ],
                'default' => 5
            ]
        );
        $repeater->add_control(
            'testimonial_desc', [
                'label' => esc_html__( 'Review Comment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'It is a long established fact that a reader will be dienteracted by the readable content of a page' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_img', [
                'label' => esc_html__( 'Picture', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->add_control(
            'testimonial',
            [
                'label' => esc_html__( 'Testimonial', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ testimonial_name }}}',
                'default' => [
                    [
                        'testimonial_name'          => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'testimonial_designation'   => esc_html__( 'CEO, Octen, Melborne,', 'enteraddons' ),
                        'testimonial_location'      => esc_html__( 'Auenteralia.', 'enteraddons' ),
                        'testimonial_rating'        => esc_html__( '5', 'enteraddons' ),
                        'testimonial_desc'          => esc_html__( 'It is a long established fact that a reader will be dienteracted by the readable content of a page when looking at its layout.', 'enteraddons' ),
                        'testimonial_img'           => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'testimonial_name'          => esc_html__( 'Diane M. Tackett', 'enteraddons' ),
                        'testimonial_designation'   => esc_html__( 'CEO, Octen, Melborne,', 'enteraddons' ),
                        'testimonial_location'      => esc_html__( 'Auenteralia.', 'enteraddons' ),
                        'testimonial_rating'        => esc_html__( '4', 'enteraddons' ),
                        'testimonial_desc'          => esc_html__( 'It is a long established fact that a reader will be dienteracted by the readable content of a page when looking at its layout.', 'enteraddons' ),
                        'testimonial_img'           => \Elementor\Utils::get_placeholder_image_src(),
                    ]
                    
                ]
            ]
        );

        $this->end_controls_section(); // End Testimonial content

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider Content area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_multi_row_content_wrapper_settings', [
                'label' => esc_html__( 'Content Wrapper Settings', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-review-multi-row-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-review-multi-row-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .enteraddons-review-multi-row-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-review-multi-row-container',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-review-multi-row-container',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider layout Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_layout_settings', [
                'label' => esc_html__( 'Item Layout Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'layout_area_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .review-single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'layout_area_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .review-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'single_testimonial_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .review-single',
                ]
            );
            $this->add_responsive_control(
                'layout_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .review-single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'layout_area_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .review-single',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'layout_area_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .review-single',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider Name Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_name_settings', [
                'label' => esc_html__( 'Name', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
             
        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-info .content h5' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .review-info .content h5',
            ]
        );
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .content h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .content h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Descriptions Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_descriptions_settings', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'descriptions_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-single p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .review-single p',
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
                    '{{WRAPPER}} .review-single p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .review-single p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'descriptions_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .review-single p',
                ]
            );
            $this->add_responsive_control(
                'descriptions_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .review-single p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'descriptions_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .review-single p',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'descriptions_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .review-single p',
                ]
            );

        $this->end_controls_section();


        /**
         * Style Tab
         * ------------------------------ Testimonial Rating Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_rating_settings', [
                'label' => esc_html__( 'Rating', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-info .rating i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_margin',
            [
                'label' => esc_html__( 'Icon Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_block_margin',
            [
                'label' => esc_html__( 'Icon Block Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_block_padding',
            [
                'label' => esc_html__( 'Icon Block Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .rating i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Quote Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_quote_settings', [
                'label' => esc_html__( 'Quote', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'left_quote_icon',
            [
                'label' => esc_html__( 'Left Quote Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-quote-left',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'right_quote_icon',
            [
                'label' => esc_html__( 'Right Quote Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-quote-right',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__( 'Quote Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-single p i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'quote_bg_color',
            [
                'label' => esc_html__( 'Quote Background Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review-single p i' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'quote_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .review-single p i',
            ]
        );
        $this->add_responsive_control(
            'quote_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-single p i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-single p i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-single p i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Image Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_img_settings', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .avatar img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
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
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .avatar img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .avatar img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .avatar img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .review-info .avatar img',
            ]
        );
        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .review-info .avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .review-info .avatar img',
            ]
        );
        $this->end_controls_section();

        

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        // Testimonial template render
        $obj = new Testimonial_Carousel_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }

    public function get_style_depends() {
        return [ 'fontawesome' ];
    }


}
