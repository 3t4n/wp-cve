<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

class MEAFE_Advanced_Testimonial extends Widget_Base
{

    public function get_name() {
        return 'meafe-advanced-testimonial';
    }

    public function get_title() {
        return esc_html__( 'Advanced Testimonial', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-advanced-testimonial';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-advanced-testimonial'];
    }

    public function get_script_depends() {
        return ['meafe-advanced-testimonial'];
    }

    protected function register_controls()
    {
        /**
         * Testimonial General Settings
        */
        $this->start_controls_section(
            'meafe_testimonial_content_general_settings',
            [
                'label'     => __( 'General Settings', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'adv_testimonial_image',
            [
                'label'       => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'adv_testimonial_title',
            [
                'label'       => esc_html__( 'Testimonial Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Excellent Customer Service and Support', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'adv_testimonial_content',
            [
                'label'       => esc_html__( 'Testimonial Content', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => esc_html__( 'I have been thoroughly impressed with the level of customer service and support offered by this company. From the initial consultation to ongoing support, the team has been nothing but professional and responsive. I would highly recommend this company for anyone in need of a supportive and dependable partner in their business endeavors.', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'adv_testimonial_name',
            [
                'label'       => esc_html__( 'Name', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Albert Flores', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'adv_testimonial_position',
            [
                'label'       => esc_html__( 'Designation', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'CEO - Flores Group', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control( 
            'adv_testimonial', 
            array(
                'label'       => esc_html__( 'Advanced Testimonial', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array( 
                    array(
                        'adv_testimonial_title'     => esc_html__( 'Excellent Customer Service and Support', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_content'       => esc_html__( 'I have been thoroughly impressed with the level of customer service and support offered by this company. From the initial consultation to ongoing support, the team has been nothing but professional and responsive. I would highly recommend this company for anyone in need of a supportive and dependable partner in their business endeavors.', 'mega-elements-addons-for-elementor' ), 
                        'adv_testimonial_name'       => esc_html__( 'Albert Flores', 'mega-elements-addons-for-elementor' ), 
                        'adv_testimonial_position' => esc_html__( 'CEO - Flores Group', 'mega-elements-addons-for-elementor' ), 
                    ),
                    array(
                        'adv_testimonial_title'     => esc_html__( 'Excellent Customer Service and Support', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_content'       => esc_html__( 'I have been thoroughly impressed with the level of customer service and support offered by this company. From the initial consultation to ongoing support, the team has been nothing but professional and responsive. I would highly recommend this company for anyone in need of a supportive and dependable partner in their business endeavors.', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_name'       => esc_html__( 'Albert Flores', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_position' => esc_html__( 'CEO - Flores Group', 'mega-elements-addons-for-elementor' ),
                    ),
                    array(
                        'adv_testimonial_title'     => esc_html__( 'Excellent Customer Service and Support', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_content'       => esc_html__( 'I have been thoroughly impressed with the level of customer service and support offered by this company. From the initial consultation to ongoing support, the team has been nothing but professional and responsive. I would highly recommend this company for anyone in need of a supportive and dependable partner in their business endeavors.', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_name'       => esc_html__( 'Albert Flores', 'mega-elements-addons-for-elementor' ),
                        'adv_testimonial_position' => esc_html__( 'CEO - Flores Group', 'mega-elements-addons-for-elementor' ),
                    ), 
                     
                ),                
                'title_field' => '{{{ adv_testimonial_name }}}',
            ) 
        );

        $this->add_control(
            'adv_testimonial_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'       => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_show_swiper_nav',
            [
                'label'     => esc_html__( 'Enable Carousel Navigation', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_arrow_prev_icon',
            [
                'label' => __( 'Previous Icon', 'mega-elements-addons-for-elementor' ),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'adv_testimonial_show_swiper_nav' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_arrow_next_icon',
            [
                'label' => __( 'Next Icon', 'mega-elements-addons-for-elementor' ),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'adv_testimonial_show_swiper_nav' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_show_swiper_dots',
            [
                'label'     => esc_html__( 'Enable Carousel Dots', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_swiper_auto',
            [
                'label'     => esc_html__( 'Enable Carousel AutoPlay', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_autoplay_speed',
            [
                'label'     => __( 'Autoplay Speed', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 100,
                'step'      => 100,
                'max'       => 10000,
                'default'   => 3000,
                'description' => __( 'Autoplay speed in milliseconds', 'mega-elements-addons-for-elementor' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'adv_testimonial_content_alignment',
            [
                'label' => __( 'Content Alignment', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'default' => 'left'
            ]
        );

        $this->end_controls_section();
        
        
        /**
         * Testimonial Box Style
        */
        $this->start_controls_section(
            'meafe_testimonial_style_box_style',
            [
                'label'     => __( 'Box Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_box_padding',
            [
                'label'     => __( 'Box Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'  => [ 'top' => 40, 'right' => 40, 'bottom' => 40, 'left' => 40, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'adv_testimonial_box_border_type',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-wrap'
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_box_border_radius',
            [
                'label'     => __( 'Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'adv_testimonial_box_shadow',
                'selector'  => '{{WRAPPER}} .swiper-slide.swiper-slide-active',
            ]
        );

        $this->add_control(
            'adv_testimonial_box_width',
            [
                'label'     => __( 'Box Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'     => [
                    '%' => [
                        'min' => 30,
                        'max' => 80,
                    ],
                ],
                'default' => [
					'unit' => '%',
					'size' => 80,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-adv-testimonial-main.layout-1 .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'adv_testimonial_layouts' => '1'
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_box_opacity',
            [
                'label'     => __( 'Box Opacity', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'     => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
					'unit' => '%',
					'size' => 40,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-wrap.swiper-slide:not(.swiper-slide-active)' => 'opacity: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_box_color',
            [
                'label'     => __( 'Box Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Testimonial General Style
        */
        $this->start_controls_section(
            'meafe_testimonial_style_general_style',
            [
                'label'     => __( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'adv_testimonial_title_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Testimonial Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_title_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_title_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 18,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_content_title_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title .meafe-entry-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_title_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-testimonial-title:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'adv_testimonial_content_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title .meafe-entry-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'adv_testimonial_title_border_type',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title'
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_title_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'adv_testimonial_title_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title',
            ]
        );

        $this->add_control(
            'adv_testimonial_content_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Testimonial Content', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_content_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_content_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 16,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_content_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_content_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-testimonial-content:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'adv_testimonial_content_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'adv_testimonial_content_border_type',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content'
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_content_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'adv_testimonial_content_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content',
            ]
        );

        $this->end_controls_section();

        /**
         * Testimonial Image Style
        */
        $this->start_controls_section(
            'meafe_testimonial_style_image_style',
            [
                'label'     => __( 'Image Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_image_width',
            [
                'label'     => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50
                ],
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 65,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-thumb' => '-webkit-flex: 0 0 {{SIZE}}{{UNIT}}; -ms-flex: 0 0 {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_image_height',
            [
                'label'     => __( 'Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 50
                ],
                'range'     => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-thumb' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'adv_testimonial_image_border',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-thumb',
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_image_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'  => [ 'top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'adv_testimonial_image_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-thumb',
            ]
        );

        $this->end_controls_section();

        /**
         * Testimonial General Style
        */
        $this->start_controls_section(
            'meafe_testimonial_style_reviewer_style',
            [
                'label'     => __( 'Reviewer Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'adv_testimonial_heading_name',
            [
                'label'     => __( 'Name', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'adv_testimonial_name_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'adv_testimonial_name_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-name',
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_name_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 5,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-name' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_heading_title',
            [
                'label'     => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'adv_testimonial_title_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'adv_testimonial_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_style_nav_arrow',
            [
                'label' => __( 'Navigation :: Arrow', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_arrow_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_arrow_width',
            [
                'label' => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 44,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-wrap .nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'adv_testimonial_arrow_border',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#5081F5',
                    ],
                ],
                'selector' => '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next',
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'  => [ 'top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( 'adv_testimonial_tabs_arrow' );

        $this->start_controls_tab(
            'adv_testimonial_tab_arrow_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'adv_testimonial_arrow_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_arrow_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'adv_testimonial_tab_arrow_hover',
            [
                'label' => __( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'adv_testimonial_arrow_hover_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_style_nav_dots',
            [
                'label' => __( 'Navigation :: Dots', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_dots_nav_spacing_top',
            [
                'label' => __( 'Spacing Top', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 48,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_dots_nav_spacing',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 8,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'adv_testimonial_dots_nav_align',
            [
                'label' => __( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( 'adv_testimonial_tabs_dots' );
        $this->start_controls_tab(
            'adv_testimonial_tab_dots_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'adv_testimonial_dots_nav_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 8,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_dots_nav_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'adv_testimonial_tab_dots_active',
            [
                'label' => __( 'Active', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'adv_testimonial_dots_nav_active_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 12,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'adv_testimonial_dots_nav_active_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function get_nav_details(){
        $settings = $this->get_settings_for_display();
        $nav      = $settings['adv_testimonial_show_swiper_nav'];
        $nav_prev = $settings['adv_testimonial_arrow_prev_icon'];
        $nav_next = $settings['adv_testimonial_arrow_next_icon'];

        if( $nav ) {
            $return_all = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_alls = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_start = [ '', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_end = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '' ];
            
            if( $nav_prev['library'] != 'svg' && $nav_next['library'] != 'svg' ) {
                return ( [ '<i class="' . esc_attr( $nav_prev['value'] ) . '" aria-hidden="true"></i>', '<i class="' . esc_attr( $nav_next['value'] ) . '" aria-hidden="true"></i>' ] );                    
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == 'svg' ){
                return ( [ '<img src="' . esc_url( $nav_prev['value']['url'] ) . '">', '<img src="' . esc_url( $nav_next['value']['url'] ). '">' ] );
            }
            
            if ( $nav_prev['library'] == '' && $nav_next['library'] == 'svg' ){
                array_pop($return_all_start);
                array_push($return_all_start, $nav_next['value']['url']);
                return ( [ '', '<img src="' . esc_url( $return_all_start[1] ) . '">' ] );
                // return return_all_start;
            }

            if ( $nav_prev['library'] != 'svg' && $nav_next['library'] == 'svg' ){
                array_pop($return_all);
                array_push($return_all, '<img src="' . esc_url( $nav_next['value']['url'] ) . '">');
                return $return_all;
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == '' ){
                array_reverse($return_all_end);
                array_pop($return_all_end);
                array_push($return_all_end, $nav_prev['value']['url']);
                array_reverse($return_all_end);
                return ( [ '<img src="' . esc_url( $return_all_end[0] ). '">', '' ] );
            }

            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] != 'svg' ){
                array_reverse($return_alls);
                array_pop($return_alls);
                array_push($return_alls, '<img src="' . esc_url( $nav_prev['value']['url'] ) . '">');
                array_reverse($return_alls);
                return $return_alls;
            }   
        }
        
        return ( [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ] );

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $nav_icons = $this->get_nav_details();
        $nav       = $settings['adv_testimonial_show_swiper_nav'];
        $dots      = $settings['adv_testimonial_show_swiper_dots'];

        $this->add_render_attribute( 'adv_testimonial_title', 'class', 'meafe-testimonial-title' );
        $this->add_render_attribute( 'adv_testimonial_content', 'class', 'meafe-testimonial-content' );
        $this->add_render_attribute( 'adv_testimonial_name', 'class', 'meafe-testimonial-reviewer-name' );
        $this->add_render_attribute( 'adv_testimonial_position', 'class', 'meafe-testimonial-reviewer-title' );

        ?>
        <div id=<?php echo esc_attr( $widget_id ); ?> class="meafe-adv-testimonial-main layout-<?php echo esc_attr( $settings['adv_testimonial_layouts'] ); ?> content-align-<?php echo esc_attr($settings['adv_testimonial_content_alignment']); ?>">
            <div class="meafe-testimonial-inner-wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ( $settings['adv_testimonial'] as $index => $testimonial ) { ?>
                            <div class="meafe-testimonial-wrap swiper-slide">
                                <div class="meafe-testimonial-desc-wrap">
                                    <?php if( $testimonial['adv_testimonial_title'] ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'adv_testimonial_title' ); ?>>
                                            <h2 class="meafe-entry-title"> <?php echo esc_html( $testimonial['adv_testimonial_title'] ); ?> </h2>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( $testimonial['adv_testimonial_content'] ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'adv_testimonial_content' ); ?>>
                                            <?php echo wp_kses_post( $testimonial['adv_testimonial_content'] ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="meafe-testimonial-tst-inner-wrap">
                                    <?php if ( ( $testimonial['adv_testimonial_image']['url'] || $testimonial['adv_testimonial_image']['id'] ) ) : ?>
                                        <div class="meafe-testimonial-reviewer-thumb">
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'full', 'adv_testimonial_image' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="meafe-adv-testimonial-name-wrap">
                                        <?php if( $testimonial['adv_testimonial_name'] ) : ?>
                                            <div <?php $this->print_render_attribute_string( 'adv_testimonial_name' ); ?>><?php echo esc_html( $testimonial['adv_testimonial_name'] ); ?></div>
                                        <?php endif; ?>
                                        <?php if( $testimonial['adv_testimonial_position'] ) : ?>
                                            <div <?php $this->print_render_attribute_string( 'adv_testimonial_position' ); ?>><?php echo esc_html( $testimonial['adv_testimonial_position'] ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            
                <?php if($dots === 'yes') { ?>
                    <!-- If we need pagination -->
                    <div class="adv-testimonial meafa-swiper-pagination"></div>
                <?php }
                
                if($nav === 'yes') { ?>
                    <!-- If we need navigation buttons -->
                    <div class="meafa-navigation-wrap">
                        <div class="adv-testimonial meafa-navigation-prev nav">
                            <?php echo $nav_icons[0]; ?>
                        </div>
                        <div class="adv-testimonial meafa-navigation-next nav">
                            <?php echo $nav_icons[1]; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php

    }

    protected function content_template() { 
    }
}
