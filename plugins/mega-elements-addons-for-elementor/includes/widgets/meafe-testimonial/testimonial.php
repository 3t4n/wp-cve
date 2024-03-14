<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class MEAFE_Testimonial extends Widget_Base
{

    public function get_name() {
        return 'meafe-testimonial';
    }

    public function get_title() {
        return esc_html__( 'Testimonial', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-testimonial';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-testimonial'];
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

        $this->add_control(
            'btcgs_testimonial_layouts',
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
                ],
            ]
        );

        $this->add_responsive_control(
            'btcgs_testimonial_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
            ]
        );

        $this->add_control(
            'btcgs_testimonial_image',
            [
                'label'     => __( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'btcgs_testimonial_thumbnail',
                'default'   => 'full',
                'condition' => [
                    'btcgs_testimonial_image[url]!' => '',
                ],
            ]
        );

        $this->add_control(
            'btcgs_testimonial_title',
            [
                'label'         => __( 'Testimonial Title', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::TEXT,
                'default'       => __( 'The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'btcgs_testimonial_content',
            [
                'label'         => __( 'Testimonial Content', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'btcgs_testimonial_name',
            [
                'label'         => __( 'Name', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::TEXT,
                'default'       => __( 'John Doe', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'btcgs_testimonial_position',
            [
                'label'         => __( 'Designation', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::TEXT,
                'default'       => __( 'Managing Director', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_testimonial_content_rating_settings',
            [
                'label'     => __( 'Rating Settings', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'btcrs_testimonial_rating_enable',
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
            'btcrs_testimonial_rating_scale',
            [
                'label' => __( 'Rating Scale', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '5' => '0-5',
                    '10' => '0-10',
                ],
                'condition'        => [
                    'btcrs_testimonial_rating_enable' => 'yes',
                ],
                'default' => '5',
            ]
        );

        $this->add_control(
            'btcrs_testimonial_rating',
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
                    'btcrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'btcrs_testimonial_star_style',
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
                    'btcrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'btcrs_testimonial_unmarked_star_style',
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
                    'btcrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'btcrs_testimonial_title',
            [
                'label' => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition'        => [
                    'btcrs_testimonial_rating_enable' => 'yes',
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
                'label'     => __( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btsgs_testimonial_title_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Testimonial Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btsgs_testimonial_title_padding',
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
            'btsgs_testimonial_title_spacing',
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
            'btsgs_testimonial_title_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btsgs_testimonial_title_bg_color',
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
                'name'      => 'btsgs_testimonial_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title',
            ]
        );

        $this->add_responsive_control(
            'btsgs_testimonial_title_border_radius',
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
                'name'      => 'btsgs_testimonial_title_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-title',
            ]
        );

        $this->add_control(
            'btsgs_testimonial_content_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Testimonial Content', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btsgs_testimonial_content_padding',
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
            'btsgs_testimonial_content_spacing',
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
            'btsgs_testimonial_content_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btsgs_testimonial_content_bg_color',
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
                'name'      => 'btsgs_testimonial_content_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content',
            ]
        );

        $this->add_responsive_control(
            'btsgs_testimonial_content_border_radius',
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
                'name'      => 'btsgs_testimonial_content_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-content',
            ]
        );

        $this->add_control(
            'btsgs_testimonial_ribbon_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Ribbon Style', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
                'condition'        => [
                    'btcgs_testimonial_layouts' => '4',
                ],
            ]
        );

        $this->add_control(
            'btsgs_testimonial_ribbon_border_color',
            [
                'label'     => __( 'Ribbon Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-main.layout-4.left-aligned-content .meafe-testimonial-inner-wrap' => 'border-color: {{VALUE}}',
                ],
                'condition'        => [
                    'btcgs_testimonial_layouts' => '4',
                ],
            ]
        );

        $this->add_control(
            'btsgs_testimonial_ribbon_position',
            [
                'label'         => esc_html__( 'Select Position', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'left',
                'label_block'   => false,
                'options'       => [
                    'left'      => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                    'right'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                    'top'       => esc_html__( 'Top', 'mega-elements-addons-for-elementor' ),
                    'bottom'    => esc_html__( 'Bottom', 'mega-elements-addons-for-elementor' ),
                ],
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
            'btsis_testimonial_image_width',
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
                    '{{WRAPPER}} .meafe-testimonial-reviewer-thumb img' => '-webkit-flex: 0 0 {{SIZE}}{{UNIT}}; -ms-flex: 0 0 {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsis_testimonial_image_height',
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
                    '{{WRAPPER}} .meafe-testimonial-reviewer-thumb img' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btsis_testimonial_image_border',
                'selector'  => '{{WRAPPER}} .meafe-testimonial-reviewer-thumb',
            ]
        );

        $this->add_responsive_control(
            'btsis_testimonial_image_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-testimonial-reviewer-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'btsis_testimonial_image_box_shadow',
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
            'meafe_testimonial_style_rating_title_style',
            [
                'label' => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'btsrts_testimonial_title_color',
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
                'name' => 'btsrts_testimonial_title_typography',
                'selector' => '{{WRAPPER}} .elementor-star-rating__title',
            ]
        );

        $this->add_responsive_control(
            'btsrts_testimonial_title_gap',
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
            'meafe_testimonial_style_rating_stars_style',
            [
                'label' => __( 'Rating Stars', 'mega-elements-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'        => [
                    'btcrs_testimonial_rating_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsrss_testimonial_icon_size',
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
            'btsrss_testimonial_icon_space',
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
            'btsrss_testimonial_stars_color',
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
            'btsrss_testimonial_stars_unmarked_color',
            [
                'label' => __( 'Unmarked Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @since 2.3.0
     * @access protected
     */
    protected function get_rating() {
        $settings = $this->get_settings_for_display();
        $rating_scale = (int) $settings['btcrs_testimonial_rating_scale'];
        $rating = (float) $settings['btcrs_testimonial_rating'] > $rating_scale ? $rating_scale : $settings['btcrs_testimonial_rating'];

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
    protected function render_stars( $icon ) {
        $rating_data = $this->get_rating();
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

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'btcgs_testimonial_title', 'basic' );
        $this->add_render_attribute( 'btcgs_testimonial_title', 'class', 'meafe-testimonial-title' );

        $this->add_inline_editing_attributes( 'btcgs_testimonial_content', 'intermediate' );
        $this->add_render_attribute( 'btcgs_testimonial_content', 'class', 'meafe-testimonial-content' );

        $this->add_inline_editing_attributes( 'btcgs_testimonial_name', 'basic' );
        $this->add_render_attribute( 'btcgs_testimonial_name', 'class', 'meafe-testimonial-reviewer-name' );

        $this->add_inline_editing_attributes( 'btcgs_testimonial_position', 'basic' );
        $this->add_render_attribute( 'btcgs_testimonial_position', 'class', 'meafe-testimonial-reviewer-title' );

        $rating_data = $this->get_rating();
        $textual_rating = $rating_data[0] . '/' . $rating_data[1];
        $icon = '&#xE934;';

        if ( 'star_fontawesome' === $settings['btcrs_testimonial_star_style'] ) {
            if ( 'outline' === $settings['btcrs_testimonial_unmarked_star_style'] ) {
                $icon = '&#xE933;';
            }
        } elseif ( 'star_unicode' === $settings['btcrs_testimonial_star_style'] ) {
            $icon = '&#9733;';

            if ( 'outline' === $settings['btcrs_testimonial_unmarked_star_style'] ) {
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
        $stars_element = '<div ' . $this->get_render_attribute_string( 'icon_wrapper' ) . '>' . $this->render_stars( $icon ) . ' ' . $schema_rating . '</div>';
        $allowedOptions = ['1', '2', '3', '4', '5'];
        $layouts_safe = in_array($settings['btcgs_testimonial_layouts'], $allowedOptions) ? $settings['btcgs_testimonial_layouts'] : '1';
        ?>
        <div class="meafe-testimonial-main layout-<?php echo esc_attr($layouts_safe); ?> <?php echo esc_attr($settings['btcgs_testimonial_alignment']); ?>-aligned-content">
            <div class="meafe-testimonial-inner-wrap position-<?php echo esc_attr($settings['btsgs_testimonial_ribbon_position']); ?>">
                <div class="meafe-testimonial-wrap">
                    <?php if ( ( $settings['btcgs_testimonial_image']['url'] || $settings['btcgs_testimonial_image']['id'] ) && ( $settings['btcgs_testimonial_layouts'] == '1' || $settings['btcgs_testimonial_layouts'] == '2' ) ) : ?>
                        <div class="meafe-testimonial-reviewer-thumb">
                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'btcgs_testimonial_thumbnail', 'btcgs_testimonial_image' ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if( $settings['btcgs_testimonial_title'] || $settings['btcgs_testimonial_content'] || $settings['btcgs_testimonial_name'] || $settings['btcgs_testimonial_position'] ) : ?>
                        <div class="meafe-testimonial-desc-wrap">
                            <div class="meafe-testimonial-desc-inner">
                                <?php if ( $settings['btcgs_testimonial_layouts'] == '5' ) echo '<div class="meafe-testimonial-second-desc-inner">'; ?>
                                <?php if( $settings['btcrs_testimonial_rating_enable'] ) : ?>
                                    <div class="elementor-star-rating__wrapper">
                                        <?php if ( ! Utils::is_empty( $settings['btcrs_testimonial_title'] ) ) : ?>
                                            <div class="elementor-star-rating__title"><?php echo esc_html($settings['btcrs_testimonial_title']); ?></div>
                                        <?php endif; ?>
                                        <?php echo $stars_element; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if( $settings['btcgs_testimonial_title'] ) : ?>
                                    <div <?php $this->print_render_attribute_string( 'btcgs_testimonial_title' ); ?>>
                                        <?php echo esc_html($settings['btcgs_testimonial_title']); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if( $settings['btcgs_testimonial_content'] ) : ?>
                                    <div <?php $this->print_render_attribute_string( 'btcgs_testimonial_content' ); ?>>
                                        <?php echo wp_kses_post($settings['btcgs_testimonial_content']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $settings['btcgs_testimonial_layouts'] == '5' ) echo '</div>'; ?>
                                <?php if ( !( $settings['btcgs_testimonial_layouts'] == '1' || $settings['btcgs_testimonial_layouts'] == '2' ) ) : ?>
                                    <div class="meafe-testimonial-designation-wrap">
                                        <?php if ( $settings['btcgs_testimonial_image']['url'] || $settings['btcgs_testimonial_image']['id'] ) : ?>
                                            <div class="meafe-testimonial-reviewer-thumb">
                                                <?php if( $settings['btcgs_testimonial_layouts'] == '1' ) {
                                                    echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'btcgs_testimonial_thumbnail', 'btcgs_testimonial_image' ); 
                                                }else{
                                                    echo wp_get_attachment_image( $settings['btcgs_testimonial_image']['id'], 'thumbnail', false ); 
                                                } ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="meafe-testimonial-design-only-wrap">
                                <?php endif; ?>
                                        <?php if( $settings['btcgs_testimonial_name'] ) : ?>
                                            <div <?php $this->print_render_attribute_string( 'btcgs_testimonial_name' ); ?>><?php echo esc_html($settings['btcgs_testimonial_name']); ?></div>
                                        <?php endif; ?>
                                        <?php if( $settings['btcgs_testimonial_position'] ) : ?>
                                            <div <?php $this->print_render_attribute_string( 'btcgs_testimonial_position' ); ?>><?php echo esc_html($settings['btcgs_testimonial_position']); ?></div>
                                        <?php endif; ?>
                                <?php if ( !( $settings['btcgs_testimonial_layouts'] == '1' || $settings['btcgs_testimonial_layouts'] == '2' ) ) : ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#

        view.addInlineEditingAttributes( 'btcgs_testimonial_title', 'basic' );
        view.addRenderAttribute( 'btcgs_testimonial_title', 'class', 'meafe-testimonial-title' );

        view.addInlineEditingAttributes( 'btcgs_testimonial_content', 'intermediate' );
        view.addRenderAttribute( 'btcgs_testimonial_content', 'class', 'meafe-testimonial-content' );

        view.addInlineEditingAttributes( 'btcgs_testimonial_name', 'basic' );
        view.addRenderAttribute( 'btcgs_testimonial_name', 'class', 'meafe-testimonial-reviewer-name' );

        view.addInlineEditingAttributes( 'btcgs_testimonial_position', 'basic' );
        view.addRenderAttribute( 'btcgs_testimonial_position', 'class', 'meafe-testimonial-reviewer-title' );

        var getRating = function() {
            var ratingScale = parseInt( settings.btcrs_testimonial_rating_scale, 10 ),
                rating = settings.btcrs_testimonial_rating > ratingScale ? ratingScale : settings.btcrs_testimonial_rating;

            return [ rating, ratingScale ];
        },
        ratingData = getRating(),
        rating = ratingData[0],
        textualRating = ratingData[0] + '/' + ratingData[1],
        renderStars = function( icon ) {
            var starsHtml = '',
                flooredRating = Math.floor( rating );

            for ( var stars = 1; stars <= ratingData[1]; stars++ ) {
                if ( stars <= flooredRating  ) {
                    starsHtml += '<i class="elementor-star-full">' + icon + '</i>';
                } else if ( flooredRating + 1 === stars && rating !== flooredRating ) {
                    starsHtml += '<i class="elementor-star-' + ( rating - flooredRating ).toFixed( 1 ) * 10 + '">' + icon + '</i>';
                } else {
                    starsHtml += '<i class="elementor-star-empty">' + icon + '</i>';
                }
            }

            return starsHtml;
        },
        icon = '&#xE934;';

        if ( 'star_fontawesome' === settings.btcrs_testimonial_star_style ) {
            if ( 'outline' === settings.btcrs_testimonial_unmarked_star_style ) {
                icon = '&#xE933;';
            }
        } else if ( 'star_unicode' === settings.btcrs_testimonial_star_style ) {
            icon = '&#9733;';

            if ( 'outline' === settings.btcrs_testimonial_unmarked_star_style ) {
                icon = '&#9734;';
            }
        }

        view.addRenderAttribute( 'iconWrapper', 'class', 'elementor-star-rating' );
        view.addRenderAttribute( 'iconWrapper', 'itemtype', 'http://schema.org/Rating' );
        view.addRenderAttribute( 'iconWrapper', 'title', textualRating );
        view.addRenderAttribute( 'iconWrapper', 'itemscope', '' );
        view.addRenderAttribute( 'iconWrapper', 'itemprop', 'reviewRating' );

        var stars = renderStars( icon );

        function getStructureOne() {
            if ( settings.btcgs_testimonial_image.url || settings.btcgs_testimonial_image.id ) {
                var image = {
                    id: settings.btcgs_testimonial_image.id,
                    url: settings.btcgs_testimonial_image.url,
                    size: settings.btcgs_testimonial_thumbnail_size,
                    dimension: settings.btcgs_testimonial_thumbnail_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image );
                #>
                <div class="meafe-testimonial-reviewer-thumb">
                    <img src="{{ image_url }}">
                </div>
            <# } #>

            <div class="meafe-testimonial-desc-wrap">
                <div class="meafe-testimonial-desc-inner">
                    <# if (settings.btcrs_testimonial_rating_enable == 'yes' ) { #>
                    <div class="elementor-star-rating__wrapper">
                        <# if ( ! _.isEmpty( settings.btcrs_testimonial_title ) ) { #>
                            <div class="elementor-star-rating__title">{{ settings.btcrs_testimonial_title }}</div>
                        <# } #>
                        <div {{{ view.getRenderAttributeString( 'iconWrapper' ) }}} >
                            {{{ stars }}}
                            <span itemprop="ratingValue" class="elementor-screen-only">{{ textualRating }}</span>
                        </div>
                    </div>
                    <# } #>
                    <# if (settings.btcgs_testimonial_title) { #>
                        <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_title' ) }}}>{{ settings.btcgs_testimonial_title }}</div>
                    <# } #>

                    <# if (settings.btcgs_testimonial_content) { #>
                        <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_content' ) }}}>{{ settings.btcgs_testimonial_content }}</div>
                    <# } #>
                    <# if (settings.btcgs_testimonial_name) { #>
                        <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_name' ) }}}>{{ settings.btcgs_testimonial_name }}</div>
                    <# } #>

                    <# if (settings.btcgs_testimonial_position) { #>
                        <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_position' ) }}}>{{ settings.btcgs_testimonial_position }}</div>
                    <# } #>
                </div>
            </div>
        <# }

        function getStructureTwo() { #>
            <div class="meafe-testimonial-desc-wrap">
                <div class="meafe-testimonial-desc-inner">
                    <# if ( settings.btcgs_testimonial_layouts == '5' ) { #>
                        <div class="meafe-testimonial-second-desc-inner">
                    <# } #>
                    <# if (settings.btcrs_testimonial_rating_enable == 'yes' ) { #>
                    <div class="elementor-star-rating__wrapper">
                        <# if ( ! _.isEmpty( settings.btcrs_testimonial_title ) ) { #>
                            <div class="elementor-star-rating__title">{{ settings.btcrs_testimonial_title }}</div>
                        <# } #>
                        <div {{{ view.getRenderAttributeString( 'iconWrapper' ) }}} >
                            {{{ stars }}}
                            <span itemprop="ratingValue" class="elementor-screen-only">{{ textualRating }}</span>
                        </div>
                    </div>
                    <# } #>
                    <# if (settings.btcgs_testimonial_title) { #>
                        <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_title' ) }}}>{{ settings.btcgs_testimonial_title }}</div>
                    <# } #>

                    <# if (settings.btcgs_testimonial_content) { #>
                        <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_content' ) }}}>{{ settings.btcgs_testimonial_content }}</div>
                    <# } #>
                    <# if ( settings.btcgs_testimonial_layouts == '5' ) { #>
                        </div>
                    <# } #>
                    <div class="meafe-testimonial-designation-wrap">
                        <# if ( settings.btcgs_testimonial_image.url || settings.btcgs_testimonial_image.id ) {
                            var image = {
                                id: settings.btcgs_testimonial_image.id,
                                url: settings.btcgs_testimonial_image.url,
                                size: settings.btcgs_testimonial_thumbnail_size,
                                dimension: settings.btcgs_testimonial_thumbnail_custom_dimension,
                                model: view.getEditModel()
                            };

                            var image_url = elementor.imagesManager.getImageUrl( image );
                            #>
                            <div class="meafe-testimonial-reviewer-thumb">
                                <img src="{{ image_url }}">
                            </div>
                        <# } #>
                        <div class="meafe-testimonial-design-only-wrap">
                            <# if (settings.btcgs_testimonial_name) { #>
                                <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_name' ) }}}>{{ settings.btcgs_testimonial_name }}</div>
                            <# } #>

                            <# if (settings.btcgs_testimonial_position) { #>
                                <div {{{ view.getRenderAttributeString( 'btcgs_testimonial_position' ) }}}>{{ settings.btcgs_testimonial_position }}</div>
                            <# } #>
                        </div>
                    </div>
                </div>
            </div>
        <# } #>

        <#  
            var allowedLayouts = ['1', '2', '3', '4', '5'];
            function validateSelectOptions(option) {
                return allowedLayouts.some(element => element === option) ? option : '1';
            }
        #>
        <div class="meafe-testimonial-main layout-{{{validateSelectOptions(settings.btcgs_testimonial_layouts)}}} {{{settings.btcgs_testimonial_alignment}}}-aligned-content">
            <div class="meafe-testimonial-inner-wrap position-{{{settings.btsgs_testimonial_ribbon_position}}}">
                <div class="meafe-testimonial-wrap">
                    
                    <# if( settings.btcgs_testimonial_layouts == '1' || settings.btcgs_testimonial_layouts == '2' ) {
                        getStructureOne();
                    } else {
                        getStructureTwo();
                    } #>
                </div>
            </div>
        </div>
    <?php
    }
}
