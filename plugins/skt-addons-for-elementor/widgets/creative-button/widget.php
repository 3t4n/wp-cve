<?php
/**
 * Dual Button widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Skt_Addons_Elementor\Elementor\Traits\Creative_Button_Markup;

defined( 'ABSPATH' ) || die();

class Creative_Button extends Base {
	use Creative_Button_Markup;
	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'Creative Button', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'skti skti-motion-button';
	}

	public function get_keywords() {
		return [ 'button', 'btn', 'advance', 'link', 'creative', 'creative-utton' ];
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_button',
			[
				'label' => __( 'Creative Button', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'btn_style',
			[
				'label'   => __( 'Style', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hermosa',
				'options' => [
					'hermosa'  => __( 'Hermosa', 'skt-addons-elementor' ),
					'montino'  => __( 'Montino', 'skt-addons-elementor' ),
					'iconica'  => __( 'Iconica', 'skt-addons-elementor' ),
					'symbolab' => __( 'Symbolab', 'skt-addons-elementor' ),
					'estilo'   => __( 'Estilo', 'skt-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'estilo_effect',
			[
				'label'     => __( 'Effects', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dissolve',
				'options'   => [
					'dissolve'     => __( 'Dissolve', 'skt-addons-elementor' ),
					'slide-down'   => __( 'Slide In Down', 'skt-addons-elementor' ),
					'slide-right'  => __( 'Slide In Right', 'skt-addons-elementor' ),
					'slide-x'      => __( 'Slide Out X', 'skt-addons-elementor' ),
					'cross-slider' => __( 'Cross Slider', 'skt-addons-elementor' ),
					'slide-y'      => __( 'Slide Out Y', 'skt-addons-elementor' ),
				],
				'condition' => [
					'btn_style' => 'estilo',
				],
			]
		);

		$this->add_control(
			'symbolab_effect',
			[
				'label'     => __( 'Effects', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'back-in-right',
				'options'   => [
					'back-in-right'  => __( 'Back In Right', 'skt-addons-elementor' ),
					'back-in-left'   => __( 'Back In Left', 'skt-addons-elementor' ),
					'back-out-right' => __( 'Back Out Right', 'skt-addons-elementor' ),
					'back-out-left'  => __( 'Back Out Left', 'skt-addons-elementor' ),
				],
				'condition' => [
					'btn_style' => 'symbolab',
				],
			]
		);

		$this->add_control(
			'iconica_effect',
			[
				'label'     => __( 'Effects', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide-in-down',
				'options'   => [
					'slide-in-down'  => __( 'Slide In Down', 'skt-addons-elementor' ),
					'slide-in-top'   => __( 'Slide In Top', 'skt-addons-elementor' ),
					'slide-in-right' => __( 'Slide In Right', 'skt-addons-elementor' ),
					'slide-in-left'  => __( 'Slide In Left', 'skt-addons-elementor' ),
				],
				'condition' => [
					'btn_style' => 'iconica',
				],
			]
		);

		$this->add_control(
			'montino_effect',
			[
				'label'     => __( 'Effects', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'winona',
				'options'   => [
					'winona'  => __( 'Winona', 'skt-addons-elementor' ),
					'rayen'   => __( 'Rayen', 'skt-addons-elementor' ),
					'aylen'   => __( 'Aylen', 'skt-addons-elementor' ),
					'wapasha' => __( 'Wapasha', 'skt-addons-elementor' ),
					'nina'    => __( 'Nina', 'skt-addons-elementor' ),
					'antiman' => __( 'Antiman', 'skt-addons-elementor' ),
					'sacnite' => __( 'Sacnite', 'skt-addons-elementor' ),
				],
				'condition' => [
					'btn_style' => 'montino',
				],
			]
		);

		$this->add_control(
			'hermosa_effect',
			[
				'label'     => __( 'Effects', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'exploit',
				'options'   => [
					'exploit'    => __( 'Exploit', 'skt-addons-elementor' ),
					'upward'     => __( 'Upward', 'skt-addons-elementor' ),
					'newbie'     => __( 'Newbie', 'skt-addons-elementor' ),
					'render'     => __( 'Render', 'skt-addons-elementor' ),
					'reshape'    => __( 'Reshape', 'skt-addons-elementor' ),
					'expandable' => __( 'Expandable', 'skt-addons-elementor' ),
					'downhill'   => __( 'Downhill', 'skt-addons-elementor' ),
					'bloom'      => __( 'Bloom', 'skt-addons-elementor' ),
					'roundup'    => __( 'Roundup', 'skt-addons-elementor' ),
				],
				'condition' => [
					'btn_style' => 'hermosa',
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Text', 'skt-addons-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Button Text',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_link',
			array(
				'label'         => __( 'Link', 'skt-addons-elementor' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'skt-addons-elementor' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => true,
				),
				'dynamic'       => [
					'active' => true,
				],
			)
		);

		$this->add_control(
			'icon',
			[
				'label'                  => __( 'Icon', 'skt-addons-elementor' ),
				'description'            => __( 'Please set an icon for the button.', 'skt-addons-elementor' ),
				'label_block'            => false,
				'type'                   => Controls_Manager::ICONS,
				'skin'                   => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'default'                => [
					'value'   => 'fas fa-rocket',
					'library' => 'fa-solid',
				],
				'conditions'             => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'symbolab',
								],
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'iconica',
								],
							],
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'hermosa',
								],
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'expandable',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'align_x',
			[
				'label'       => __( 'Alignment', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'toggle'      => true,
				'selectors'   => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
					// '{{WRAPPER}} .skt-creative-btn-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'magnetic_enable',
			[
				'label'        => __( 'Magnetic Effect', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'threshold',
			[
				'label'     => __( 'Threshold', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'default'   => 30,
				'condition' => [
					'magnetic_enable' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn' => 'margin: {{VALUE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget style controls
	 */
	protected function register_style_controls() {
		$this->__common_style_controls();
	}

	protected function _color_template() {

		$this->start_controls_section(
			'_button_style_color',
			[
				'label' => __( 'Color Tamplate', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'white_color',
			[
				'label'     => __( 'White', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap' => '--skt-ctv-btn-clr-white: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'offwhite_color',
			[
				'label'     => __( 'Off White', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f0f0f0',
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap' => '--skt-ctv-btn-clr-offwhite: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'black_color',
			[
				'label'     => __( 'Black', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#222222',
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap' => '--skt-ctv-btn-clr-black: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cranberry_color',
			[
				'label'     => __( 'Cranberry', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e2498a',
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap' => '--skt-ctv-btn-clr-cranberry: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'purple_color',
			[
				'label'     => __( 'Purple', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#562dd4',
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap' => '--skt-ctv-btn-clr-purple: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Style section for Estilo, Symbolab, Iconica
	 *
	 * @return void
	 */
	protected function __common_style_controls() {

		$this->start_controls_section(
			'_estilo_symbolab_iconica_style_section',
			[
				'label' => __( 'Common', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_item_width',
			[
				'label'      => __( 'Size', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn.skt-eft--downhill' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-creative-btn.skt-eft--roundup' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-creative-btn.skt-eft--roundup .progress' => 'width: calc({{SIZE}}{{UNIT}} - (({{SIZE}}{{UNIT}} / 100) * 20) ); height:auto;',
				],
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'roundup',
								],
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'downhill',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'hermosa',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
			[
				'label'      => __( 'Icon Size', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'symbolab',
								],
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'iconica',
								],
							],
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'hermosa',
								],
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'expandable',
								],
							],
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-creative-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'       => 'button_border',
				'exclude'    => ['color'], //remove border color
				'selector'   => '{{WRAPPER}} .skt-creative-btn, {{WRAPPER}} .skt-creative-btn.skt-eft--bloom div',
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '!=',
									'value'    => 'roundup',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '!=',
									'value'    => '',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-creative-btn.skt-stl--hermosa.skt-eft--bloom div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_hermosa_roundup_stroke_width',
			[
				'label'      => __( 'Stroke Width', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn.skt-eft--roundup' => '--skt-ctv-btn-stroke-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'roundup',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'hermosa',
								],
							],
						],
					],
				],
			]
		);

		$this->__btn_tab_style_controls();

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					'{{WRAPPER}} .skt-creative-btn.skt-stl--iconica > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					'{{WRAPPER}} .skt-creative-btn.skt-stl--montino.skt-eft--winona > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-creative-btn.skt-stl--montino.skt-eft--winona::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					'{{WRAPPER}} .skt-creative-btn.skt-stl--montino.skt-eft--rayen > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-creative-btn.skt-stl--montino.skt-eft--rayen::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					'{{WRAPPER}} .skt-creative-btn.skt-stl--montino.skt-eft--nina' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-creative-btn.skt-stl--montino.skt-eft--nina::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

					'{{WRAPPER}} .skt-creative-btn.skt-stl--hermosa.skt-eft--bloom span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function __btn_tab_style_controls() {

		$conditions = [
			'terms' => [
				[
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'hermosa_effect',
							'operator' => '!=',
							'value'    => 'roundup',
						],
						// [
						// 	'name' => 'hermosa_effect',
						// 	'operator' => '!=',
						// 	'value' => 'downhill',
						// ],
					],
				],
				[
					'terms' => [
						[
							'name'     => 'btn_style',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			],
		];

		$this->start_controls_tabs( '_tabs_button' );
		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn' => '--skt-ctv-btn-txt-clr: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'      => __( 'Background Color', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn' => '--skt-ctv-btn-bg-clr: {{VALUE}}',
				],
				'conditions' => $conditions,
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'      => __( 'Border Color', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn' => '--skt-ctv-btn-border-clr: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '!=',
									'value'    => 'roundup',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '!=',
									'value'    => '',
								],
								[
									'name'     => 'button_border_border',
									'operator' => '!=',
									'value'    => '',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'button_roundup_circle_color',
			[
				'label'      => __( 'Circle Color', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn.skt-eft--roundup' => '--skt-ctv-btn-border-clr: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'roundup',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'hermosa',
								],
							],
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-creative-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tabs_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn' => '--skt-ctv-btn-txt-hvr-clr: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label'      => __( 'Background Color', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn' => '--skt-ctv-btn-bg-hvr-clr: {{VALUE}}',
				],
				'conditions' => $conditions,
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'      => __( 'Border Color', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn' => '--skt-ctv-btn-border-hvr-clr: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '!=',
									'value'    => 'roundup',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '!=',
									'value'    => '',
								],
								[
									'name'     => 'button_border_border',
									'operator' => '!=',
									'value'    => '',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'button_hover_roundup_circle_color',
			[
				'label'      => __( 'Circle Color', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .skt-creative-btn-wrap .skt-creative-btn.skt-eft--roundup' => '--skt-ctv-btn-border-hvr-clr: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'hermosa_effect',
									'operator' => '==',
									'value'    => 'roundup',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'btn_style',
									'operator' => '==',
									'value'    => 'hermosa',
								],
							],
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .skt-creative-btn:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'wrap', 'data-magnetic', $settings['magnetic_enable'] ? $settings['magnetic_enable'] : 'no' );
		$this->{'render_' . $settings['btn_style'] . '_markup'}( $settings );
	}
}