<?php
/**
 * Advanced Heading widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Advanced_Heading extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Advanced Heading', 'skt-addons-elementor' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-advanced-heading';
    }

    public function get_keywords() {
        return [ 'gradient', 'advanced', 'heading', 'title' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {

        $this->start_controls_section(
            '_section_title',
            [
                'label' => __( 'Advanced Heading', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading_before',
            [
                'label' => __( 'Before Text', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'SKT', 'skt-addons-elementor' ),
                'placeholder' => __( 'Before Text', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $this->add_control(
            'heading_center',
            [
                'label' => __( 'Center Text', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Addon', 'skt-addons-elementor' ),
                'placeholder' => __( 'Center Text', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $this->add_control(
            'heading_after',
            [
                'label' => __( 'After Text', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Rocks', 'skt-addons-elementor' ),
                'placeholder' => __( 'After Text', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

		$this->add_control(
			'show_background_text',
			[
				'label' => __( 'Background Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'background_text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Background', 'skt-addons-elementor' ),
				'placeholder' => __( 'Background Text', 'skt-addons-elementor' ),
				'condition' => [
					'show_background_text' => 'yes'
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'skt-addons-elementor' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'skt-addons-elementor' ),
				'separator' => 'after',
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'HTML Tag', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'h2',
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'toggle' => false,
            ]
        );

        $this->add_responsive_control(
            'heading_align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
                'default' => 'left',
                'toggle' => false,
                'prefix_class' => 'skt-align-',
                'selectors_dictionary' => [
                    'left' => 'justify-content: flex-start',
                    'center' => 'justify-content: center',
                    'right' => 'justify-content: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-tag' => '{{VALUE}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_position',
            [
                'label' => __( 'Layout', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'inline' => [
                        'title' => __( 'Inline', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                    'block' => [
                        'title' => __( 'Block', 'skt-addons-elementor' ),
                        'icon' => 'eicon-menu-bar',
                    ]
                ],
                'toggle' => false,
                'selectors_dictionary' => [
                    'inline' => 'flex-direction: row',
                    'block' => 'flex-direction: column',
                ],
                'default' => 'inline',
                'prefix_class' => 'skt-layout-',
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-wrap' => '{{VALUE}}',
                ]
            ]
        );

        $this->end_controls_section();

    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__before_text_style_controls();
		$this->__center_text_style_controls();
		$this->__after_text_style_controls();
		$this->__border_style_controls();
		$this->__background_style_controls();
	}

    protected function __before_text_style_controls() {

        $this->start_controls_section(
            '_section_before_text',
            [
                'label' => __( 'Before Text', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'before_text_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'before_text_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}.skt-layout-inline .skt-advanced-heading-before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-layout-block .skt-advanced-heading-before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'before_text_border',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-before',
            ]
        );

        $this->add_control(
            'before_text_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'before_text_typography',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-before',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'before_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-advanced-heading-before',
            ]
        );

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'before_text_gradient',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'before_text_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'before_text_gradient_color_type' => 'classic'
                ],
                'selector' => '{{WRAPPER}} .skt-advanced-heading-before',
            ]
        );

        $this->add_control(
            'before_text_blend_mode',
            [
                'label' => __( 'Blend Mode', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'none',
                'options' => [
                    '' => __( 'Normal', 'skt-addons-elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-before' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __center_text_style_controls() {

        $this->start_controls_section(
            '_section_center_text',
            [
                'label' => __( 'Center Text', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'center_text_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-center' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'center_text_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}.skt-layout-inline .skt-advanced-heading-center' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-layout-block .skt-advanced-heading-center' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'center_text_border',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-center',
            ]
        );

        $this->add_control(
            'center_text_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-center' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'center_text_typography',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-center',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'center_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-advanced-heading-center',
            ]
        );

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'center_text_gradient',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-center',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'center_text_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'center_text_gradient_color_type' => 'classic'
                ],
                'selector' => '{{WRAPPER}} .skt-advanced-heading-center',
            ]
        );

        $this->add_control(
            'center_text_blend_mode',
            [
                'label' => __( 'Blend Mode', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'none',
                'options' => [
                    '' => __( 'Normal', 'skt-addons-elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-center' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __after_text_style_controls() {

        $this->start_controls_section(
            '_section_after_text',
            [
                'label' => __( 'After Text', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'after_text_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'after_text_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}.skt-layout-inline .skt-advanced-heading-after' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-layout-block .skt-advanced-heading-after' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'after_text_border',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-after',
            ]
        );

        $this->add_control(
            'after_text_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'after_text_typography',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-after',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'after_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-advanced-heading-after',
            ]
        );

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'after_text_gradient',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-after',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'after_text_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'condition' => [
                    'after_text_gradient_color_type' => 'classic'
                ],
                'selector' => '{{WRAPPER}} .skt-advanced-heading-after',
            ]
        );

        $this->add_control(
            'after_text_blend_mode',
            [
                'label' => __( 'Blend Mode', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'none',
                'options' => [
                    '' => __( 'Normal', 'skt-addons-elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-after' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __border_style_controls() {

        $this->start_controls_section(
            '_section_style_border',
            [
                'label' => __( 'Border', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_type',
            [
                'label' => __( 'Border Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'solid' => __( 'Solid', 'skt-addons-elementor' ),
                    'double' => __( 'Double', 'skt-addons-elementor' ),
                    'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
                    'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
                    'groove' => __( 'Groove', 'skt-addons-elementor' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_length',
            [
                'label' => __( 'Length', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 800,
                    ],
                ],
                'condition' => [
                    'border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 3
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'border_offset_toggle',
            [
                'label' => __( 'Offset', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'condition' => [
                    'border_type!' => 'none',
                ],
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'border_horizontal_position',
            [
                'label' => __( 'Horizontal Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => -20,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                   'border_offset_toggle' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_vertical_position',
            [
                'label' => __( 'Vertical Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'border_offset_toggle' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-border:after' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->end_controls_section();
	}

    protected function __background_style_controls() {

        $this->start_controls_section(
            '_section_style_background',
            [
                'label' => __( 'Background Text', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Background Text</strong> is Hidden on Content Tab', 'skt-addons-elementor' ),
                'condition' => [
                    'show_background_text!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'background_text_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_background_text' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-wrap:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'background_text_typography',
                'selector' => '{{WRAPPER}} .skt-advanced-heading-wrap:before',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'condition' => [
                    'show_background_text' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'background_offset_toggle',
            [
                'label' => __( 'Offset', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'condition' => [
                    'show_background_text' => 'yes',
                ],
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'background_horizontal_position',
            [
                'label' => __( 'Horizontal Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'background_offset_toggle' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-wrap:before' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'background_vertical_position',
            [
                'label' => __( 'Vertical Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 200,
                    ],
                ],
                'condition' => [
                    'background_offset_toggle' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-advanced-heading-wrap:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
		$has_link = false;

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$has_link = true;
		}
		?>

		<<?php echo wp_kses_post(skt_addons_elementor_escape_tags($settings['title_tag'])); ?> class="skt-advanced-heading-tag">
			<?php if ( $has_link ) : ?>
			<a <?php $this->print_render_attribute_string( 'link' ) ?>>
			<?php endif; ?>
			<div class="skt-advanced-heading-wrap" data-background-text="<?php echo esc_attr( $settings['background_text']); ?>">
				<span class="skt-advanced-heading-before"><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings[ 'heading_before' ] )); ?></span>
				<span class="skt-advanced-heading-center"><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings[ 'heading_center' ] )); ?></span>
				<span class="skt-advanced-heading-after"><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings[ 'heading_after' ] )); ?></span>
				<span class="skt-advanced-heading-border"></span>
			</div>
			<?php if ( $has_link ) : ?>
			</a>
			<?php endif; ?>
		</<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'] )); ?>>

		<?php
    }
}