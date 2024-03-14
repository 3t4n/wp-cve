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
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class MEAFE_Events extends Widget_Base
{

    public function get_name() {
        return 'meafe-events';
    }

    public function get_title() {
        return esc_html__( 'Events', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-events';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-events'];
    }

    public function get_script_depends() {
        return ['meafe-events'];
    }

    protected function register_controls() 
    {
        $this->start_controls_section(
            'meafe_events_content_general_settings',
            array(
                'label' => __( 'General Settings', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'becgs_events_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'       => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                    '3'       => esc_html__( 'Layout Three', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $events_repeater = new Repeater();

        $events_repeater->add_control(
            'becgs_events_image',
            [
                'label'       => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $events_repeater->add_control(
            'becgs_events_text',
            [
                'label'       => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active' => true,
                ),
                'label_block' => true,
                'placeholder' => __( 'Lorem Ipsum', 'mega-elements-addons-for-elementor' ),
                'default'     => __( 'Lorem Ipsum', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $events_repeater->add_control(
            'becgs_events_start_date_time',
            [
                'label'       => __( 'Start Date & Time', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::DATE_TIME,
            ]
        );

        $events_repeater->add_control(
            'becgs_events_end_date_time',
            [
                'label'       => __( 'End Date & Time', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::DATE_TIME,
            ]
        );

        $events_repeater->add_control(
            'becgs_events_selected_date_time_icon_new',
            [
                'label'            => esc_html__( 'Date & Time Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                'default'          => [],
                'fa4compatibility' => 'becgs_events_selected_date_time_icon',
            ]
        );

        $events_repeater->add_control(
            'becgs_events_location',
            [
                'label'       => __( 'Location', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
            ]
        );

        $events_repeater->add_control(
            'becgs_events_selected_location_icon_new',
            [
                'label'            => esc_html__( 'Location Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                'default'          => [],
                'condition'        => [
                    'becgs_events_location!' => '',
                ],
                'fa4compatibility' => 'becgs_events_selected_location_icon',
            ]
        );

        $events_repeater->add_control(
            'becgs_events_content',
            [
                'label'       => __( 'Content', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'mega-elements-addons-for-elementor' ),
                'dynamic'     => array(
                    'active' => true,
                ),
            ]
        );

        $events_repeater->add_control(
            'becgs_events_read_more',
            [
                'label'       => esc_html__( 'Read More Button', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
            ]
        );

        $events_repeater->add_control(
            'becgs_events_read_more_link',
            [
                'label'       => __( 'Read More Button Link', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
                'type'        => Controls_Manager::URL,
                'dynamic'     => array(
                    'active' => true,
                ),
                'placeholder' => 'https://www.your-link.com',
                'default'     => array(
                    'url' => '#',
                ),
                'condition'        => [
                    'becgs_events_read_more!' => '',
                ],
            ]
        );
        
        $events_repeater->add_control(
            'becgs_events_selected_read_more_icon_new',
            [
                'label'            => esc_html__( 'Read More Button Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                'default'          => [],
                'condition'        => [
                    'becgs_events_read_more!' => '',
                ],
                'fa4compatibility' => 'becgs_events_selected_read_more_icon',
            ]
        );
        

        $this->add_control(
            'becgs_events_repeater',
            [
                'label'       => esc_html__( 'ADD Events', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    [
                    'becgs_events_text'     => esc_html__( 'Lorem Ipsum', 'mega-elements-addons-for-elementor' ),
                    ]
                ],
                'fields'      => $events_repeater->get_controls(),
                'title_field' => '{{{ becgs_events_text }}}',
            ]
        );

        $this->end_controls_section();

        /**
         * Testimonial General Style
        */
        $this->start_controls_section(
            'meafe_events_style_general_style',
            [
                'label'     => __( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'besgs_events_title_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_title_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'besgs_events_title_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_title_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_title_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--title' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--title:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'besgs_events_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe--title',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_title_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besgs_events_title_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe--title',
            ]
        );

        $this->add_control(
            'besgs_events_content_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Content', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_content_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'besgs_events_content_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_content_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_content_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--event-content:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'besgs_events_content_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe--event-content',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_content_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besgs_events_content_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe--event-content',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_content_overall_padding',
            [
                'label'     => __( 'Overall Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event--wrapper--details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_content_overall_bg_color',
            [
                'label'     => __( 'Overall Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event--wrapper--details' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_readmore_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_readmore_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'besgs_events_readmore_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'besgs_events_readmore_btn_tabs' );

        // Normal State Tab
        $this->start_controls_tab(
            'besgs_events_readmore_btn_normal', 
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'besgs_events_readmore_normal_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button .event-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_readmore_bg_normal_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--event-button:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'besgs_events_readmore_normal_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besgs_events_readmore_normal_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe--event-button',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'besgs_events_readmore_btn_hover', 
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ) 
            ] 
        );

        $this->add_control(
            'besgs_events_readmore_hover_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button .event-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_readmore_hover_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'besgs_events_readmore_hover_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besgs_events_readmore_hover_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe--event-button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'besgs_events_readmore_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe--event-button',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_events_style_meta_style',
            [
                'label'     => __( 'Date Meta Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'besgs_events_meta_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .month--details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_meta_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .month--details' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_meta_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .month--details' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .month--details:after' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'becgs_events_layouts!' => '2',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_meta_day_bg_color',
            [
                'label'     => __( 'Day Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--main--wrapper.layout-2 .month--details .month' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--event--main--wrapper.layout-2 .month--details .month:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--event--main--wrapper.layout-2 .month--details .month:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'becgs_events_layouts' => '2',
                ],
            ]
        );

        $this->add_control(
            'besgs_events_meta_month_bg_color',
            [
                'label'     => __( 'Month Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--main--wrapper.layout-2 .month--details .date' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'becgs_events_layouts' => '2',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'besgs_events_meta_day_typography',
                'label'     => __( 'Day Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .month--details .date',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'besgs_events_meta_month_typography',
                'label'     => __( 'Month Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .month--details .month',
            ]
        );

        $this->add_responsive_control(
            'besgs_events_meta_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .month--details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besgs_events_meta_box_shadow',
                'selector'  => '{{WRAPPER}} .month--details',
            ]
        );

        $this->end_controls_section();


        /**
         * Image Style
        */
        $this->start_controls_section(
            'meafe_events_style_image_style',
            [
                'label'     => __( 'Image Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'besis_events_image_width',
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
                    '{{WRAPPER}} .event--image--wrapper .image--wrapper' => '-webkit-flex: 0 0 {{SIZE}}{{UNIT}}; -ms-flex: 0 0 {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'besis_events_image_height',
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
                    '{{WRAPPER}} .event--image--wrapper .image--wrapper' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'besis_events_image_border',
                'selector'  => '{{WRAPPER}} .event--image--wrapper .image--wrapper',
            ]
        );

        $this->add_responsive_control(
            'besis_events_image_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .event--image--wrapper .image--wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besis_events_image_box_shadow',
                'selector'  => '{{WRAPPER}} .event--image--wrapper .image--wrapper',
            ]
        );

        $this->end_controls_section();

        /**
         * Time Icon Style
        */
        $this->start_controls_section(
            'meafe_events_style_time_icon_style',
            [
                'label'     => __( 'Time Icon Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'besis_events_time_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px'     => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event--details .meafe--event--time span i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event--details .meafe--event--time span svg' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'besis_events_time_icon_color',
            [
                'label'         => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event--details .meafe--event--time span i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event--details .meafe--event--time span svg' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Icon Style
        */
        $this->start_controls_section(
            'meafe_events_style_location_icon_style',
            [
                'label'     => __( 'Location Icon Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'besis_events_location_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px'     => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event-place i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event-place svg' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'besis_events_location_icon_color',
            [
                'label'         => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event-place i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .meafe--event--main--wrapper .meafe--event-place svg' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Read More Icon Style
        */
        $this->start_controls_section(
            'meafe_events_style_rm_icon_style',
            [
                'label'     => __( 'Read More Icon Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'besis_events_rm_icon_width',
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
                    '{{WRAPPER}} .meafe--event--button a.event-but svg' => '-webkit-flex: 0 0 {{SIZE}}{{UNIT}}; -ms-flex: 0 0 {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'besis_events_rm_icon_height',
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
                    '{{WRAPPER}} .meafe--event--button a.event-but svg' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'besis_events_rm_icon_border',
                'selector'  => '{{WRAPPER}} .meafe--event--button a.event-but svg',
            ]
        );

        $this->add_responsive_control(
            'besis_events_rm_icon_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--event--button a.event-but svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'besis_events_icon_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe--event--button a.event-but svg',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $migration_allowed = Icons_Manager::is_migration_allowed();

        ?>
        <div class="meafe--event--main--wrapper layout-<?php echo esc_attr($settings['becgs_events_layouts']); ?>">
            <?php foreach( $settings['becgs_events_repeater'] as $index => $item ) : ?>
                <?php if( $item['becgs_events_image']['id'] || $item['becgs_events_image']['url'] || ( $item['becgs_events_start_date_time'] && $settings['becgs_events_layouts'] == '3' ) ) { ?>
                    <div class="event--image--wrapper">
                        <figure class="image--wrapper">
                        <?php if( $item['becgs_events_image']['id'] ) {
                            echo wp_get_attachment_image( $item['becgs_events_image']['id'], 'full', false );
                        } ?>
                        <?php if( ! $item['becgs_events_image']['id'] && $item['becgs_events_image']['url'] ) { ?>
                            <img src="<?php echo esc_url( $item['becgs_events_image']['url'] ); ?>" alt="<?php echo ( $item['becgs_events_text'] ) ? esc_attr($item['becgs_events_text']) : ''; ?>">
                        <?php } ?>
                        </figure>
                        <?php if( $item['becgs_events_start_date_time'] && $settings['becgs_events_layouts'] == '3' ) { ?>
                            <div class="month--details">
                                <span class="date"><?php echo esc_html(date('j', strtotime( $item['becgs_events_start_date_time'] ) )); ?></span>
                                <span class="month"><?php echo esc_html(date('M', strtotime( $item['becgs_events_start_date_time'] ) )); ?></span>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="meafe--event--wrapper--details">
                    <div class="rishi--event--wrapper">
                        <?php if( $item['becgs_events_start_date_time'] && $settings['becgs_events_layouts'] != '3' ) { ?>
                            <div class="month--details">
                                <span class="date"><?php echo esc_html(date('j', strtotime( $item['becgs_events_start_date_time'] ) )); ?></span>
                                <span class="month"><?php echo esc_html(date('M', strtotime( $item['becgs_events_start_date_time'] ) )); ?></span>
                            </div>
                        <?php } ?>
                        <?php 
                        if( $settings['becgs_events_layouts'] == '2' ) echo '<div class="meafe--event-details-main-wrapper">';
                        if( $item['becgs_events_text'] ) { ?>
                            <div class="meafe--event-title">
                                <h2 class="meafe--title"><?php echo esc_html( $item['becgs_events_text'] ); ?></h2>
                            </div>
                        <?php } ?>
                    <?php if( $settings['becgs_events_layouts'] != '2' ) echo '</div>';
                    
                    if( $item['becgs_events_content'] && $settings['becgs_events_layouts'] == '3' ) { ?>
                        <div class="meafe--event-content">
                            <?php echo wp_kses_post( wpautop( $item['becgs_events_content'] ) ); ?>
                        </div>
                    <?php } ?>
                    <?php if( ($item['becgs_events_start_date_time'] && $item['becgs_events_end_date_time']) || $item['becgs_events_location'] ) { ?>
                    <div class="meafe--event--details">
                        <?php if(  $item['becgs_events_start_date_time'] && $item['becgs_events_end_date_time'] ) { ?>
                            <div class="meafe--event--time">
                                <span class="meafe--event--time-icon">
                                    <?php if ( !empty( $item['becgs_events_selected_date_time_icon'] ) || !empty($item['becgs_events_selected_date_time_icon_new']['value'] ) ) {
                                        $migrated_dt = isset( $item['__fa4_migrated']['becgs_events_selected_date_time_icon_new'] );
                                        $is_new_dt = empty($item['becgs_events_selected_date_time_icon']) && $migration_allowed;
                                        
                                        if ( $is_new_dt || $migrated_dt ) {
                                            ob_start();
                                            Icons_Manager::render_icon( $item['becgs_events_selected_date_time_icon_new'], [
                                                'aria-hidden' => 'true',
                                            ] );
                                            $icon_html_dt = ob_get_contents();
                                            ob_end_clean();
                                        } else {
                                            $icon_html_dt = '<i class="' . esc_attr( $item['becgs_events_selected_date_time_icon'] ) . '" aria-hidden="true"></i>';
                                        }
                                        echo $icon_html_dt;
                                    } ?>
                                    <?php echo esc_html(date('h:i  A', strtotime( $item['becgs_events_start_date_time'] ) )) . ' - ' . esc_html(date('h:i  A', strtotime( $item['becgs_events_end_date_time'] ) )); ?>
                                </span>
                            </div>
                        <?php } ?>
                        <?php if( $item['becgs_events_location'] ) { ?>
                            <div class="meafe--event-place">
                                <?php if ( !empty( $item['becgs_events_selected_location_icon'] ) || !empty($item['becgs_events_selected_location_icon_new']['value'] ) ) {
                                    $migrated_loc = isset( $item['__fa4_migrated']['becgs_events_selected_location_icon_new'] );
                                    $is_new_loc = empty($item['becgs_events_selected_location_icon']) && $migration_allowed;
                                    
                                    if ( $is_new_loc || $migrated_loc ) {
                                        ob_start();
                                        Icons_Manager::render_icon( $item['becgs_events_selected_location_icon_new'], [
                                            'aria-hidden' => 'true',
                                        ] );
                                        $icon_html_loc = ob_get_contents();
                                        ob_end_clean();
                                    } else {
                                        $icon_html_loc = '<i class="' . esc_attr( $item['becgs_events_selected_location_icon'] ) . '" aria-hidden="true"></i>';
                                    }
                                    echo $icon_html_loc;
                                } ?>
                                <span><?php echo esc_html( $item['becgs_events_location'] ); ?></span>
                            </div>
                        <?php } ?>
                    </div>  
                    <?php } ?>
                    <?php if( $settings['becgs_events_layouts'] == '2' ) echo '</div>';
                    if( $settings['becgs_events_layouts'] == '2' ) echo '</div>';
                    if( $item['becgs_events_content'] && $settings['becgs_events_layouts'] != '3' ) { ?>
                        <div class="meafe--event-content">
                            <?php echo wp_kses_post( wpautop( $item['becgs_events_content'] ) ); ?>
                        </div>
                    <?php } ?>
                    <?php if( $item['becgs_events_read_more'] && $item['becgs_events_read_more_link']['url'] ) { ?>
                        <div class="meafe--event-button">
                            <?php 
                                $link_key = 'becgs_events_read_more_link_' . $index;

                                $this->add_link_attributes( $link_key, $item['becgs_events_read_more_link'] );

                                echo '<a ' . $this->get_render_attribute_string( $link_key ) . ' class="event-btn">';
                            
                                echo esc_html( $item['becgs_events_read_more'] );
                                
                                if ( !empty( $item['becgs_events_selected_read_more_icon'] ) || !empty($item['becgs_events_selected_read_more_icon_new']['value'] ) ) {
                                    $migrated_readmore = isset( $item['__fa4_migrated']['becgs_events_selected_read_more_icon_new'] );
                                    $is_new_readmore = empty($item['becgs_events_selected_read_more_icon']) && $migration_allowed;
                                    
                                    if ( $is_new_readmore || $migrated_readmore ) {
                                        ob_start();
                                        Icons_Manager::render_icon( $item['becgs_events_selected_read_more_icon_new'], [
                                            'aria-hidden' => 'true',
                                        ] );
                                        $icon_html_readmore = ob_get_contents();
                                        ob_end_clean();
                                    } else {
                                        $icon_html_readmore = '<i class="' . esc_attr( $item['becgs_events_selected_read_more_icon'] ) . '" aria-hidden="true"></i>';
                                    }
                                    echo $icon_html_readmore;
                                } ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function content_template() {
    }
}