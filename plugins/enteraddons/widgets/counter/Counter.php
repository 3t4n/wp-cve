<?php
namespace Enteraddons\Widgets\Counter;

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
 * Enteraddons elementor Counter widget.
 *
 * @since 1.0
 */
class Counter extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-counter';
	}

	public function get_title() {
		return esc_html__( 'Counter', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-counter';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        //------------------------------ Counter Style ------------------------------
        $this->start_controls_section( 
            'enteraddons_counter_layout', [
                'label' => esc_html__( 'Layout', 'enteraddons' )
            ]
        );
        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__( 'Content Top', 'enteraddons' ),
                    'bottom' => esc_html__( 'Content Bottom', 'enteraddons' ),
                    'left'   => esc_html__( 'Content Left', 'enteraddons' ),
                    'right'  => esc_html__( 'Content Right', 'enteraddons' )
                ],
                'default' => 'top'
            ]
        );
        $this->add_control(
            'number_position',
            [
                'label' => esc_html__( 'Number Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'before' => esc_html__( 'Before Title', 'enteraddons' ),
                    'after' => esc_html__( 'After Title', 'enteraddons' )
                ],
                'default' => 'before'
            ]
        );
        $this->add_responsive_control(
            'item_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-single-counter' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'item_flex_alignment',
            [
                'label' => esc_html__( 'Flex Alignment', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'enteraddons-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'enteraddons-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [ 'icon_position' => ['left','right'] ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter' => 'justify-content: {{VALUE}} !important',
                ],
            ]
        );
        $this->end_controls_section();

        // ----------------------------------------  Counter Title content ------------------------------
        $this->start_controls_section(
            'enteraddons_counter_content_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
            ]
        );
       
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Happy Clients'
            ]
        );
        $this->add_control(
            'title_tag',
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
                'default' => 'p'
            ]
        );
        
        $this->end_controls_section(); // End Team content
        // ----------------------------------------  Counter Number content ------------------------------
        $this->start_controls_section(
            'enteraddons_counter_content_number',
            [
                'label' => esc_html__( 'Number', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__( 'Number', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => '285'
            ]
        );
        $this->add_control(
            'number_unit',
            [
                'label' => esc_html__( 'Number Unit', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => ''
            ]
        );
        $this->add_control(
            'number_tag',
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

        $this->end_controls_section(); // End Counter content

        // ----------------------------------------  Counter Icon content ------------------------------
        $this->start_controls_section(
            'enteraddons_counter_content_icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_show',
            [
                'label' => esc_html__( 'Show Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'icon' => 'Icon',
                    'img'  => 'Image'
                ],
                'default' => 'icon',
                'condition' => [ 'icon_show' => 'yes' ]
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icons', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
					'value' => 'far fa-smile',
					'library' => 'fa-solid',
				],
                'condition' => [ 
                    'icon_type' => 'icon',
                    'icon_show' => 'yes'
                 ]
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                     'icon_type' => 'img',
                     'icon_show' => 'yes'
                 ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->end_controls_section(); // End Counter content

        
        /**
         * Style Tab
         * ------------------------------ Item Content Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_Counter_wrapper_style_settings', [
                'label' => esc_html__( 'Item Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_counter_wrapper' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_wrapper_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
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
                        '{{WRAPPER}} .enteraddons-single-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .enteraddons-single-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-single-counter',
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
                        '{{WRAPPER}} .enteraddons-single-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-counter',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-counter',
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_hover_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-single-counter:hover',
                ]
            );
            $this->add_responsive_control(
                'item_hover_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-counter:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_hover_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-counter:hover',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_hover_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-counter:hover',
                ]
            );


        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        //------------------------------ Title Style ------------------------------
        $this->start_controls_section( 
            'enteraddons_counter_title_style', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_counter_title' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_title_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-title',
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
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_title_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        //------------------------------ Number Style ------------------------------
        $this->start_controls_section( 
            'enteraddons_counter_nuber_style', [
                'label' => esc_html__( 'Number', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_counter_number' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_number_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-number .enteraddons-count' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'number_unit_color',
            [
                'label' => esc_html__( 'Unit Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-number' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-number',
            ]
        );
        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'number_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter .enteraddons-counter-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_number_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'number_hover_color',
            [
                'label' => esc_html__( 'Number Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-number .enteraddons-count' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'number_unit_hover_color',
            [
                'label' => esc_html__( 'Unit Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-number' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();
        
        //------------------------------ Counter Icon Style ------------------------------
        $this->start_controls_section(
            'enteraddons_counter_icon_style', [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'icon_show' => 'yes' ]
            ]
        );
        $this->start_controls_tabs( 'tab_counter_icon' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_counter_icon_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        // This field work on icon type icon
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-counter-icon i' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'icon_type' => 'icon' ]
            ]
        );
        $this->add_responsive_control(
            'counter_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
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
                    '{{WRAPPER}} .enteraddons-counter-icon i ' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .enteraddons-counter-icon img ' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'icon_type' => 'icon' ]
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-counter-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-counter-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_image_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-counter-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'icon_type' => 'img' ]
            ]
        );
        $this->add_responsive_control(
            'icon_image_height',
            [
                'label' => esc_html__( 'Image Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-counter-icon img' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'icon_type' => 'img' ]
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
                    '{{WRAPPER}} .enteraddons-counter-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-counter-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-counter-icon',
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
                    '{{WRAPPER}} .enteraddons-counter-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-counter-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-counter-icon',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'icon_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [ 'icon_type' => 'icon' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_hover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-counter:hover .enteraddons-counter-icon',
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
        $obj = new Counter_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }

    public function get_script_depends() {
        return [ 'enteraddons-main','waypoints','counterup'];
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
