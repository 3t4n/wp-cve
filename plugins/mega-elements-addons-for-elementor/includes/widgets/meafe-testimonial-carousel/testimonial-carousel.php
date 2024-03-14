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

class MEAFE_Testimonial_Carousel extends Widget_Base
{

    public function get_name() {
        return 'meafe-testimonial-carousel';
    }

    public function get_title() {
        return esc_html__( 'Testimonial Carousel', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-testimonial-carousel';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-testimonial-carousel'];
    }

    public function get_script_depends() {
        return ['meafe-testimonial-carousel'];
    }

    protected function register_controls()
    {
        /**
         * Testimonial General Settings
        */
        $this->start_controls_section(
            'meafe_testimonial_carousel_content_general_settings',
            [
                'label'     => __( 'General Settings', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'btccgs_testimonial_carousel_image',
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
            'btccgs_testimonial_carousel_title',
            [
                'label'       => esc_html__( 'Testimonial Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'btccgs_testimonial_carousel_content',
            [
                'label'       => esc_html__( 'Testimonial Content', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'btccgs_testimonial_carousel_name',
            [
                'label'       => esc_html__( 'Name', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'btccgs_testimonial_carousel_position',
            [
                'label'       => esc_html__( 'Designation', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control( 
            'btccgs_testimonial_carousel', 
            array(
                'label'       => esc_html__( 'Testimonial Carousel', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array( 
                    array(
                        'btccgs_testimonial_carousel_title'     => esc_html__( 'The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ), 
                        'btccgs_testimonial_carousel_name'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ), 
                        'btccgs_testimonial_carousel_position' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ), 
                    ),
                    array(
                        'btccgs_testimonial_carousel_title'     => esc_html__( 'The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_name'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_position' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ),
                    ),
                    array(
                        'btccgs_testimonial_carousel_title'     => esc_html__( 'The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_name'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ),
                        'btccgs_testimonial_carousel_position' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ),
                    ), 
                     
                ),                
                'title_field' => '{{{ btccgs_testimonial_carousel_name }}}',
            ) 
        );

        $this->add_control(
            'btccgs_testimonial_carousel_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'       => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                    '3'       => esc_html__( 'Layout Three', 'mega-elements-addons-for-elementor' ),
                    '4'       => esc_html__( 'Layout Four', 'mega-elements-addons-for-elementor' ),
                    '5'       => esc_html__( 'Layout Five', 'mega-elements-addons-for-elementor' ),
                    '6'       => esc_html__( 'Layout Six', 'mega-elements-addons-for-elementor' ),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btccgs_testimonial_carousel_show_carousel_nav',
            [
                'label'     => esc_html__( 'Enable Carousel Navigation', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btccgs_testimonial_carousel_change_nav',
            [
                'label'     => esc_html__( 'Change Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'description' => __( 'Set to true to change the icon for previous icon and next icon option. Otherwise default will be use.', 'mega-elements-addons-for-elementor' ),
                'frontend_available' => true,
                'condition' => [
                    'btccgs_testimonial_carousel_layouts' => '2'
                ],
            ]
        );

        $this->add_control(
            'btccgs_testimonial_carousel_arrow_prev_icon',
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
                    'btccgs_testimonial_carousel_show_carousel_nav' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btccgs_testimonial_carousel_arrow_next_icon',
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
                    'btccgs_testimonial_carousel_show_carousel_nav' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btccgs_testimonial_carousel_show_carousel_dots',
            [
                'label'     => esc_html__( 'Enable Carousel Dots', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btccgs_testimonial_carousel_show_carousel_auto',
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
            'btccgs_testimonial_carousel_carousel_autoplay_speed',
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
            'btccgs_testimonial_carousel_show_carousel_loop',
            [
                'label'     => esc_html__( 'Enable Carousel Infinite Loop', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_carousel_content_rating_settings',
            [
                'label'     => __( 'Rating Settings', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'btccrs_testimonial_rating_enable',
            [
                'label' => __( 'Enable Rating', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
            ]
        );

        $this->add_control(
            'btccrs_testimonial_rating_scale',
            [
                'label' => __( 'Rating Scale', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '5' => '0-5',
                    '10' => '0-10',
                ],
                'condition'        => [
                    'btccrs_testimonial_rating_enable' => 'yes',
                ],
                'default' => '5',
            ]
        );

        $this->add_control(
            'btccrs_testimonial_rating',
            [
                'label' => __( 'Rating', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'default' => 5,
                'dynamic' => [
                    'active' => true,
                ],
                'condition'        => [
                    'btccrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'btccrs_testimonial_star_style',
            [
                'label' => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'star_fontawesome' => 'Font Awesome',
                    'star_unicode' => 'Unicode',
                ],
                'default' => 'star_fontawesome',
                'render_type' => 'template',
                'prefix_class' => 'elementor--star-style-',
                'separator' => 'before',
                'condition'        => [
                    'btccrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'btccrs_testimonial_unmarked_star_style',
            [
                'label' => __( 'Unmarked Style', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'solid' => [
                        'title' => __( 'Solid', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-star',
                    ],
                    'outline' => [
                        'title' => __( 'Outline', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-star-o',
                    ],
                ],
                'default' => 'solid',
                'condition'        => [
                    'btccrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'btccrs_testimonial_title',
            [
                'label' => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition'        => [
                    'btccrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        
        /**
         * Testimonial General Style
        */
        $this->start_controls_section(
            'meafe_testimonial_carousel_style_general_style',
            [
                'label'     => __( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btcsgs_testimonial_title_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Testimonial Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_testimonial_title_padding',
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
            'btcsgs_testimonial_title_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_testimonial_title_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title .meafe-entry-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_testimonial_title_bg_color',
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
                'name'      => 'btcsgs_testimonial_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title .meafe-entry-title',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_testimonial_title_border_radius',
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
                'name'      => 'btcsgs_testimonial_title_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title',
            ]
        );

        $this->add_control(
            'btcsgs_testimonial_content_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Testimonial Content', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_testimonial_content_padding',
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
            'btcsgs_testimonial_content_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_testimonial_content_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_testimonial_content_bg_color',
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
                'name'      => 'btcsgs_testimonial_content_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_testimonial_content_border_radius',
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
                'name'      => 'btcsgs_testimonial_content_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content',
            ]
        );

        $this->end_controls_section();

        /**
         * Testimonial Image Style
        */
        $this->start_controls_section(
            'meafe_testimonial_carousel_style_image_style',
            [
                'label'     => __( 'Image Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcsis_testimonial_image_width',
            [
                'label'     => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
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
            'btcsis_testimonial_image_height',
            [
                'label'     => __( 'Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
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
                'name'      => 'btcsis_testimonial_image_border',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-thumb',
            ]
        );

        $this->add_responsive_control(
            'btcsis_testimonial_image_border_radius',
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
                'name'      => 'btcsis_testimonial_image_box_shadow',
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
            'btsrs_testimonial_heading_name',
            [
                'label'     => __( 'Name', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'btsrs_testimonial_name_color',
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
                'name'      => 'btsrs_testimonial_name_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-name',
            ]
        );

        $this->add_responsive_control(
            'btsrs_testimonial_name_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-name' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btsrs_testimonial_heading_title',
            [
                'label'     => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btsrs_testimonial_title_color',
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
                'name'      => 'btsrs_testimonial_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_carousel_style_rating_title_style',
            [
                'label' => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'btcsrts_testimonial_title_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating__title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btcsrts_testimonial_title_typography',
                'selector' => '{{WRAPPER}} .elementor-star-rating__title',
            ]
        );

        $this->add_responsive_control(
            'btcsrts_testimonial_title_gap',
            [
                'label' => __( 'Gap', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}}:not(.elementor-star-rating--align-justify) .elementor-star-rating__title' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}}:not(.elementor-star-rating--align-justify) .elementor-star-rating__title' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_carousel_style_rating_stars_style',
            [
                'label' => __( 'Rating Stars', 'mega-elements-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'        => [
                    'btccrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsrss_testimonial_icon_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsrss_testimonial_icon_space',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcsrss_testimonial_stars_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btcsrss_testimonial_stars_unmarked_color',
            [
                'label' => __( 'Unmarked Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_carousel_style_nav_arrow',
            [
                'label' => __( 'Navigation :: Arrow', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcsna_testimonial_arrow_size',
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
            'btcsna_testimonial_arrow_width',
            [
                'label' => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-wrap .nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btcsna_testimonial_arrow_border',
                'selector' => '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next',
            ]
        );

        $this->add_responsive_control(
            'btcsna_testimonial_arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( 'btcsna_testimonial_tabs_arrow' );

        $this->start_controls_tab(
            'btcsna_testimonial_tab_arrow_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsna_testimonial_arrow_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btcsna_testimonial_arrow_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btcsna_testimonial_tab_arrow_hover',
            [
                'label' => __( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsna_testimonial_arrow_hover_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btcsna_testimonial_arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_carousel_style_nav_dots',
            [
                'label' => __( 'Navigation :: Dots', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcsnd_testimonial_dots_nav_spacing',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsnd_testimonial_dots_nav_align',
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( 'btcsnd_testimonial_tabs_dots' );
        $this->start_controls_tab(
            'btcsnd_testimonial_tab_dots_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsnd_testimonial_dots_nav_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btcsnd_testimonial_dots_nav_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btcsnd_testimonial_tab_dots_active',
            [
                'label' => __( 'Active', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsnd_testimonial_dots_nav_active_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * @since 2.3.0
     * @access protected
     */
    protected function get_tc_rating() {
        $settings = $this->get_settings_for_display();
        $rating_scale = (int) $settings['btccrs_testimonial_rating_scale'];
        $rating = (float) $settings['btccrs_testimonial_rating'] > $rating_scale ? $rating_scale : $settings['btccrs_testimonial_rating'];

        return [ $rating, $rating_scale ];
    }

    /**
     * Print the actual stars and calculate their filling.
     *
     * Rating type is float to allow stars-count to be a fraction.
     * Floored-rating type is int, to represent the rounded-down stars count.
     * In the `for` loop, the index type is float to allow comparing with the rating value.
     *
     * @since 2.3.0
     * @access protected
     */
    protected function render_tc_stars( $icon ) {
        $rating_data = $this->get_tc_rating();
        $rating = (float) $rating_data[0];
        $floored_rating = floor( $rating );
        $stars_html = '';

        for ( $stars = 1.0; $stars <= $rating_data[1]; $stars++ ) {
            if ( $stars <= $floored_rating ) {
                $stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
            } elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
                $stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
            } else {
                $stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
            }
        }

        return $stars_html;
    }

    public function render_testimonial_carousel_template( $testimonial_carousel, $settings ) { ?>
        <div class="meafe-testimonial-designation-wrap">
            <?php if ( ( $testimonial_carousel['btccgs_testimonial_carousel_image']['url'] || $testimonial_carousel['btccgs_testimonial_carousel_image']['id'] ) && $settings['btccgs_testimonial_carousel_layouts'] != '3' ) : ?>
                <div class="meafe-testimonial-reviewer-thumb">
                    <?php echo wp_get_attachment_image( $testimonial_carousel['btccgs_testimonial_carousel_image']['id'], 'thumbnail', false ); ?>
                </div>
            <?php endif; ?>
            <div class="meafe-testimonial-design-only-wrap">
                <?php if( $testimonial_carousel['btccgs_testimonial_carousel_name'] ) : ?>
                    <div <?php $this->print_render_attribute_string( 'btccgs_testimonial_carousel_name' ); ?>><?php echo esc_html($testimonial_carousel['btccgs_testimonial_carousel_name']); ?></div>
                <?php endif; ?>
                <?php if( $testimonial_carousel['btccgs_testimonial_carousel_position'] ) : ?>
                    <div <?php $this->print_render_attribute_string( 'btccgs_testimonial_carousel_position' ); ?>><?php echo esc_html($testimonial_carousel['btccgs_testimonial_carousel_position']); ?></div>
                <?php endif; ?>
                <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '6' ) $this->render_testimonial_carousel_rating_template( $settings ); ?>
            </div>
        </div>
        <?php
    }

    public function render_testimonial_carousel_rating_template( $settings ) { 
        $rating_data = $this->get_tc_rating();
        $textual_rating = $rating_data[0] . '/' . $rating_data[1];
        $icon = '&#xE934;';

        if ( 'star_fontawesome' === $settings['btccrs_testimonial_star_style'] ) {
            if ( 'outline' === $settings['btccrs_testimonial_unmarked_star_style'] ) {
                $icon = '&#xE933;';
            }
        } elseif ( 'star_unicode' === $settings['btccrs_testimonial_star_style'] ) {
            $icon = '&#9733;';

            if ( 'outline' === $settings['btccrs_testimonial_unmarked_star_style'] ) {
                $icon = '&#9734;';
            }
        }

        $this->add_render_attribute( 'icon_wrapper', [
            'class' => 'elementor-star-rating',
            'title' => esc_attr($textual_rating),
            'itemtype' => 'http://schema.org/Rating',
            'itemscope' => '',
            'itemprop' => 'reviewRating',
        ] );

        $schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . esc_html($textual_rating) . '</span>';
        $stars_element = '<div ' . $this->get_render_attribute_string( 'icon_wrapper' ) . '>' . $this->render_tc_stars( $icon ) . ' ' . $schema_rating . '</div>';

        if( $settings['btccrs_testimonial_rating_enable'] ) : ?>
            <div class="elementor-star-rating__wrapper">
                <?php if ( ! Utils::is_empty( $settings['btccrs_testimonial_title'] ) ) : ?>
                    <div class="elementor-star-rating__title"><?php echo esc_html($settings['btccrs_testimonial_title']); ?></div>
                <?php endif; ?>
                <?php echo $stars_element; ?>
            </div>
        <?php endif;
    }

    public function get_nav_details(){
        $settings  = $this->get_settings_for_display();
        $layout   = $settings['btccgs_testimonial_carousel_layouts'];
        $enable   = $settings['btccgs_testimonial_carousel_change_nav'];
        $nav      = $settings['btccgs_testimonial_carousel_show_carousel_nav'];
        $nav_prev = $settings['btccgs_testimonial_carousel_arrow_prev_icon'];
        $nav_next = $settings['btccgs_testimonial_carousel_arrow_next_icon'];

        if( $layout == '2' && !$enable ){
            return ( [ '<svg id="right" xmlns="http://www.w3.org/2000/svg" width="45.521" height="30.348" viewBox="0 0 45.521 30.348"><g id="Group_30" data-name="Group 30" transform="translate(0 0)"><path id="Path_1" data-name="Path 1" d="M.278,99.836,14.5,85.611a.948.948,0,1,1,1.341,1.341L3.238,99.558H44.573a.948.948,0,1,1,0,1.9H3.238l12.607,12.606A.948.948,0,1,1,14.5,115.4L.278,101.177A.948.948,0,0,1,.278,99.836Z" transform="translate(0 -85.333)"/></g></svg>', '<svg id="right" xmlns="http://www.w3.org/2000/svg" width="45.521" height="30.347" viewBox="0 0 45.521 30.347"><g id="Group_30" data-name="Group 30" transform="translate(0 0)"><path id="Path_1" data-name="Path 1" d="M45.243,99.836,31.018,85.611a.948.948,0,1,0-1.341,1.341L42.284,99.558H.948a.948.948,0,1,0,0,1.9H42.284L29.677,114.061a.948.948,0,1,0,1.341,1.341l14.225-14.225A.948.948,0,0,0,45.243,99.836Z" transform="translate(0 -85.333)"/></g></svg>' ] );
        }

        if( $nav ) {
            $return_all = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_alls = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_start = [ '', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_end = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '' ];
            
            if( $nav_prev['library'] != 'svg' && $nav_next['library'] != 'svg' ) {
                return ( [ '<i class="' . esc_attr($nav_prev['value']) . '" aria-hidden="true"></i>', '<i class="' . esc_attr($nav_next['value']) . '" aria-hidden="true"></i>' ] );                    
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == 'svg' ){
                return ( [ '<img src="' . esc_url($nav_prev['value']['url']) . '">', '<img src="' . esc_url($nav_next['value']['url']) . '">' ] );
            }
            
            if ( $nav_prev['library'] == '' && $nav_next['library'] == 'svg' ){
                array_pop($return_all_start);
                array_push($return_all_start, esc_url($nav_next['value']['url']));
                return ( [ '', '<img src="' . $return_all_start[1] . '">' ] );
                // return return_all_start;
            }

            if ( $nav_prev['library'] != 'svg' && $nav_next['library'] == 'svg' ){
                array_pop($return_all);
                array_push($return_all, '<img src="' . esc_url($nav_next['value']['url']) . '">');
                return $return_all;
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == '' ){
                array_reverse($return_all_end);
                array_pop($return_all_end);
                array_push($return_all_end, esc_url($nav_prev['value']['url']));
                array_reverse($return_all_end);
                return ( [ '<img src="' . $return_all_end[0] . '">', '' ] );
            }

            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] != 'svg' ){
                array_reverse($return_alls);
                array_pop($return_alls);
                array_push($return_alls, '<img src="' . esc_url($nav_prev['value']['url']) . '">');
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
        $nav       = $settings['btccgs_testimonial_carousel_show_carousel_nav'];
        $dots      = $settings['btccgs_testimonial_carousel_show_carousel_dots'];

        $this->add_render_attribute( 'btccgs_testimonial_carousel_title', 'class', 'meafe-testimonial-title' );
        $this->add_render_attribute( 'btccgs_testimonial_carousel_content', 'class', 'meafe-testimonial-content' );
        $this->add_render_attribute( 'btccgs_testimonial_carousel_name', 'class', 'meafe-testimonial-reviewer-name' );
        $this->add_render_attribute( 'btccgs_testimonial_carousel_position', 'class', 'meafe-testimonial-reviewer-title' );
        $allowedOptions = ['1', '2', '3', '4', '5', '6'];
        $layouts_safe = in_array($settings['btccgs_testimonial_carousel_layouts'], $allowedOptions) ? $settings['btccgs_testimonial_carousel_layouts'] : '1';
        ?>
        <div id=<?php echo esc_attr( $widget_id ); ?> class="meafe-testimonial-carousel-main layout-<?php echo esc_attr($layouts_safe); ?> center-aligned-content">
            <div class="meafe-testimonial-inner-wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ( $settings['btccgs_testimonial_carousel'] as $index => $testimonial_carousel ) { ?>
                            <div class="meafe-testimonial-wrap swiper-slide">
                                <?php if ( ( $testimonial_carousel['btccgs_testimonial_carousel_image']['url'] || $testimonial_carousel['btccgs_testimonial_carousel_image']['id'] ) && $settings['btccgs_testimonial_carousel_layouts'] == '3' ) : ?>
                                    <div class="meafe-testimonial-tst-inner-wrap">
                                        <div class="meafe-testimonial-reviewer-thumb">
                                            <?php echo wp_get_attachment_image( $testimonial_carousel['btccgs_testimonial_carousel_image']['id'], 'meafe-testimonial-two', false ); ?>
                                        </div>
                                <?php endif; ?>
                                <div class="meafe-testimonial-desc-wrap">
                                    <div class="meafe-testimonial-desc-inner">
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '1' || $settings['btccgs_testimonial_carousel_layouts'] == '3' || $settings['btccgs_testimonial_carousel_layouts'] == '4' ) $this->render_testimonial_carousel_rating_template( $settings ); ?>
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '5' || $settings['btccgs_testimonial_carousel_layouts'] == '6' ) {
                                            echo '<div class="meafe-testimonial-inner-top-wrap">';
                                        } ?>
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '2' || $settings['btccgs_testimonial_carousel_layouts'] == '5' || $settings['btccgs_testimonial_carousel_layouts'] == '6' ) self::render_testimonial_carousel_template( $testimonial_carousel, $settings ); ?>
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '2' || $settings['btccgs_testimonial_carousel_layouts'] == '5' ) $this->render_testimonial_carousel_rating_template( $settings ); ?>
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '5' || $settings['btccgs_testimonial_carousel_layouts'] == '6' ) {
                                            echo '</div>';
                                        } ?>

                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '5' || $settings['btccgs_testimonial_carousel_layouts'] == '6' ) {
                                            echo '<div class="meafe-testimonial-inner-bottom-wrap">';
                                        } ?>
                                        <?php if( $testimonial_carousel['btccgs_testimonial_carousel_title'] ) : ?>
                                            <div <?php $this->print_render_attribute_string( 'btccgs_testimonial_carousel_title' ); ?>>
                                                <h2 class="meafe-entry-title"> <?php echo esc_html($testimonial_carousel['btccgs_testimonial_carousel_title']); ?> </h2>
                                            </div>
                                        <?php endif; ?>

                                        <?php if( $testimonial_carousel['btccgs_testimonial_carousel_content'] ) : ?>
                                            <div <?php $this->print_render_attribute_string( 'btccgs_testimonial_carousel_content' ); ?>>
                                                <?php echo wp_kses_post($testimonial_carousel['btccgs_testimonial_carousel_content']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] == '5' || $settings['btccgs_testimonial_carousel_layouts'] == '6' ) {
                                            echo '</div>';
                                        } ?>
                                        <?php if( $settings['btccgs_testimonial_carousel_layouts'] != '2' && $settings['btccgs_testimonial_carousel_layouts'] != '5' && $settings['btccgs_testimonial_carousel_layouts'] != '6' ) self::render_testimonial_carousel_template( $testimonial_carousel, $settings ); ?>
                                    </div>
                                </div>
                                <?php if ( $settings['btccgs_testimonial_carousel_layouts'] == '3' ) echo '</div>'; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            
                <?php if($dots === 'yes') { ?>
                    <!-- If we need pagination -->
                    <div class="testimonial meafa-swiper-pagination"></div>
                <?php }
                
                if($nav === 'yes') { ?>
                    <!-- If we need navigation buttons -->
                    <div class="meafa-navigation-wrap">
                        <div class="testimonial meafa-navigation-prev nav">
                            <?php echo $nav_icons[0]; ?>
                        </div>
                        <div class="testimonial meafa-navigation-next nav">
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
