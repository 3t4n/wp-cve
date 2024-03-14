<?php
namespace Enteraddons\Widgets\Advanced_Tabs;

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
 * Enteraddons elementor Advanced Tab widget.
 *
 * @since 1.0
 */

class Advanced_Tabs extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-advanced-tabs';
	}

	public function get_title() {
		return esc_html__( 'Advanced Tabs', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-advance-tab';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Advanced Tab Content ------------------------------
        $this->start_controls_section(
            'enteraddons_advanced_tab_content_settings',
            [
                'label' => esc_html__( 'Advanced Tab Content', 'enteraddons' ),
            ]
        );

        $repeater->add_control(
            'tab_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Tab One','enteraddons')
            ]
        );

        $repeater->add_control(
            'tab_icon', [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,  
                'default' => [
					'value' => 'fas fa-home',
					'library' => 'fa-solid',
				], 
            ]
        );

        $repeater->add_control(
			'tab_content_type',
			[
				'label' => esc_html__( 'Content Type', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'content' => esc_html__( 'Content', 'enteraddons' ),
					'template'  => esc_html__( 'Template', 'enteraddons' ),
				],
			]
		);
        $repeater->add_control(
            'template_id',
            [
                'label' => esc_html__( 'Templates', 'enteraddons' ),
                'condition' => [ 'tab_content_type' => 'template' ],
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => \Enteraddons\Classes\Helper::getElementorTemplates(),
            ]
        );
        $repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry s standard dummy text ever', 'enteraddons' ),
				'placeholder' => esc_html__( 'Type your description here', 'enteraddons' ),
                'show_label' => false,
                'dynamic' => [
                    'active' => true,
                ],
                'condition'   =>['tab_content_type' => 'content']
			]
		);
        
        $this->add_control(
			'tabs_list',
			[
				'label' => esc_html__( 'Tabs List', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
                'show_label' => false,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Home', 'enteraddons' ),
						'tab_content_type' => 'content',
                        'tab_icon' => [
                            'value' => 'fas fa-home',
                        ], 
                        'content' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'enteraddons' ),	
					],
                    [
						'tab_title' => esc_html__( 'Portfolio', 'enteraddons' ),
						'tab_content_type' => 'content',
                        'tab_icon' => [
                            'value' => 'far fa-images',
                        ],
                        'content' => esc_html__( 'This years of experience we have in designing and developing help us craft a widget set that will add a bit of elegance to your website without consuming a lot of your timeLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled', 'enteraddons' ),	
					],
                    [
						'tab_title' => esc_html__( 'Typography', 'enteraddons' ),
						'tab_content_type' => 'content',
                        'tab_icon' => [
                            'value' => 'fas fa-pencil-alt',
                        ],
                        'content' => esc_html__( 'Craft a widget set that will add a bit of elegance to your website without consuming a lot of your time.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'enteraddons' ),	
					],
                    [
						'tab_title' => esc_html__( 'Contact', 'enteraddons' ),
						'tab_content_type' => 'content',
                        'tab_icon' => [
                            'value' => 'far fa-envelope',
                        ],
                        'content' => esc_html__( 'Contact Page and Craft a widget set that will add a bit of elegance to your website without consuming a lot of your time.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'enteraddons' ),	
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);
        $this->end_controls_section(); // End Advanced Tab Content

       //------------------------------ Advanced Tab Setting ------------------------------
       
        $this->start_controls_section(
            'enteraddons_advanced_tab_settings',
            [
                'label' => esc_html__( 'Advanced Tab Settings', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'atab_tabs_position',
            [
                'label' => esc_html__( 'Tab Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'column',
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper' => 'flex-direction: {{VALUE}};',
                    '{{WRAPPER}} .ea-atab-tabs' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_tabs_direction',
            [
                'label' => esc_html__( 'Tab Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Row', 'enteraddons' ),
                        'icon' => 'eicon-navigation-horizontal',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Column', 'enteraddons' ),
                        'icon' => 'eicon-navigation-vertical',
                    ]
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-tabs' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'row',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section(); // End Advanced Tab Content

        //------------------------------ Advanced Tab Wrapper Style ------------------------------

        $this->start_controls_section(
            'enteraddons_tabs_wrap_style', [
                'label' => esc_html__( 'Wrapper', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'atab_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'atab_wrapper_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-atab-wrapper',
            ]
        );
        $this->add_responsive_control(
            'atab_wrapper_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'atab_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-atab-wrapper',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atab_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-atab-wrapper',
            ]
        );
        $this->end_controls_section(); // End Advanced Tab Content

        //------------------------------ Advanced Tab Title Style ------------------------------

        $this->start_controls_section(
            'enteraddons_tabs_style', [
                'label' => esc_html__( ' Tab Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'tab_item_alignment',
            [
                'label' => esc_html__( 'Aligmnet', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-tabs' => 'justify-content: {{VALUE}};',   
                ],
                'condition' => [ 'atab_tabs_position' => 'column' ],
            ]
        );
        $this->add_responsive_control(
            'tab_width',
            [
                'label' => esc_html__( 'Tab Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_height',
            [
                'label' => esc_html__( 'Tab Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'atab_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'atab_title_stroke',
				'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link',
			]
		);
        $this->add_responsive_control(
            'atab_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tab_advanced_tab_title' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'atab_title_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'atab_title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'atab_title_border',
				'label'     => esc_html__( 'Border', 'enteraddons' ),
				'selector'  => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link',
			]
		);
		$this->add_responsive_control(
			'atab_title_radius',
			[
				'label' => esc_html__( 'Border Radius', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'atab_title_shadow',
				'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
				'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link',
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atab_title_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

         // Controls tab For Hover
         $this->start_controls_tab(
            'atab_title_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'atab_title_hover_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atab_title_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link:hover',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Active
        $this->start_controls_tab(
            'atab_title_active',
            [
                'label' => esc_html__( 'Active', 'enteraddons' ),
            ]
        );
        //
        $this->add_control(
            'active-line-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Active Line Settings', 'enteraddons' ),
                'label_off' => esc_html__( 'Default', 'enteraddons' ),
                'label_on' => esc_html__( 'Custom', 'enteraddons' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'active_line_position',
            [
                'label' => esc_html__( 'Line Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'line-bottom',
                'options' => [
                    'line-top' => esc_html__( 'Top', 'enteraddons' ),
                    'line-right' => esc_html__( 'Right', 'enteraddons' ),
                    'line-left'  => esc_html__( 'Left', 'enteraddons' ),
                    'line-bottom'  => esc_html__( 'Bottom', 'enteraddons' ),
                ]                    
            ]
        );
        $this->add_control(
        'atab_tab_line_after_color',
        [
            'label' => esc_html__( 'Active Tab Line Color', 'enteraddons' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link:after' => 'background: {{VALUE}}',
            ],
        ]
        );
        $this->add_responsive_control(
            'atab_tab_active_line_length',
            [
                'label' => esc_html__( 'Tab Active Line Length', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link:after' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_tab_active_line_thickness',
            [
                'label' => esc_html__( 'Tab Active Line Thickness', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link:after' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->end_popover();
        //
        $this->add_control(
            'atab_title_active_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link.current' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'atab_title_active_border',
				'label'     => esc_html__( 'Border', 'enteraddons' ),
				'selector'  => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link.current',
			]
		);
		$this->add_responsive_control(
			'atab_title_active_radius',
			[
				'label' => esc_html__( 'Border Radius', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'atab_title_active_shadow',
				'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
				'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link.current',
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atab_title_active_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-atab-wrapper .ea-atab-tabs .ea-atab-tab-link.current',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Advanced Tab Icon Style ------------------------------
         *
         */
        $this->start_controls_section(
            'atab_icon_style_settings', [
                'label' => esc_html__( 'Icon Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'atab_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-tab-link .ea-atab-icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'atab_icon_active_color',
            [
                'label' => esc_html__( 'Active Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-tab-link.current .ea-atab-icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-tab-link .ea-atab-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-tab-link .ea-atab-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_icon_size',
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
                    '{{WRAPPER}} .ea-atab-tab-link .ea-atab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section(); 

        /**
        * Style Tab
        * ------------------------------ Advanced tab Content Style ------------------------------
        *
        */
        
        $this->start_controls_section(
            'enteraddons_atab_content_style', [
                'label' => esc_html__( 'Content Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'atab_content_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'atab_content_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-atab-content',
            ]
        );
        $this->add_responsive_control(
            'atab_content_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'atab_content_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'atab_content_animation_text_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-atab-content',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'atab_content_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-atab-content',
            ]
        );
        $this->add_responsive_control(
            'atab_content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-atab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'atab_content_background',
				'label' => esc_html__( 'Background', 'enteraddons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-atab-content',
			]
		);
        $this->end_controls_section();
	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Template render
        $obj = new Advanced_Tabs_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }

    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }
}
