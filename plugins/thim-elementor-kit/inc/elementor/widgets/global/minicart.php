<?php

namespace Elementor;

use Thim_EL_Kit\Modules\MegaMenu\Main_Walker;
use Thim_EL_Kit\Settings;

class Thim_Ekit_Widget_Minicart extends Widget_Base {
	public $base;

	public function get_name() {
		return 'thim-ekits-minicart';
	}

	public function get_title() {
		return esc_html__( 'Mini Cart', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-cart';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'minicart',
			'cart',
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_menu__content',
			array(
				'label' => esc_html__( 'Menu', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'heading_title_cart',
			array(
				'label'     => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control( 
			'title_cart', [
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'  => Controls_Manager::TEXT,
				'default'   => '',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h3',
			]
		);
		$this->add_control(
			'sec_icon_cart',
			array(
				'label'     => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Choosen Icon', 'thim-elementor-kit' ),
				'label_block' => true,
				'skin' 		  => 'inline',
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fab fa-opencart',
					'library' => 'Font Awesome 5 Free',
				),

			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide Empty', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'No', 'thim-elementor-kit' ),
				'return_value' => 'hide',
			)
		);
		$this->add_control(
			'items_indicator',
			array(
				'label'     => esc_html__( 'Items Indicator', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off' => esc_html__( 'No', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--main-alignment: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_section();

		// register style
		$this->register_style_menu_icon();

		$this->register_style_cart();

		$this->register_style_product();

		$this->register_style_sub_total();

		$this->register_style_button();

		$this->register_stye_message();

	}

	protected function register_style_menu_icon() {
		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label' => esc_html__( 'Menu Icon', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'toggle_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( '%', 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon i' => '--thim-ekits-mini-cart-font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon svg' => '--thim-ekits-mini-cart-font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( 'em', 'px' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .thim-ekits-mini-cart .minicart-icon'       => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_menu_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'icon_settings_border',
			array(
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_icon_border_width',
			array(
				'label'     => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array(
					'icon_settings_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs( 'toggle_menu_icon_colors' );

		$this->start_controls_tab( 'toggle_menu_icon_normal_colors', array( 'label' => esc_html__( 'Normal', 'thim-elementor-kit' ) ) );

		$this->add_control(
			'toggle_menu_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon svg path' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_menu_icon_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toggle_menu_icon_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_settings_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_menu_icon_hover_colors', array( 'label' => esc_html__( 'Hover', 'thim-elementor-kit' ) ) );

		$this->add_control(
			'toggle_menu_icon_hover_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon:hover svg path' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_menu_icon_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_icon_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_settings_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_menu_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				),

			)
		);

		$this->add_control(
			'items_indicator_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Items Indicator', 'thim-elementor-kit' ),
				'separator' => 'before',
				'condition' => array(
					'items_indicator' => 'yes',
				),
			)
		);
		$this->add_control(
			'items_indicator_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_indicator' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-indicator-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'items_indicator_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-indicator-background-color: {{VALUE}};',
				),
				'condition' => array(
					'items_indicator' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'items_indicator_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'condition'  => array(
					'items_indicator' => 'yes',
				),
				'size_units' => array( '%', 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--thim-ekits-indicator-font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_indicator_width',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'condition'  => array(
					'items_indicator' => 'yes',
				),
				'size_units' => array( '%', 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--thim-ekits-indicator-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_indicator_height',
			array(
				'label'      => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( '%', 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--thim-ekits-indicator-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'items_indicator' => 'yes',
				),
			)
		);
		$this->add_control(
			'indicator_position',
			array(
				'label'     => esc_html__( 'Position', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''         => esc_html__( 'Default', 'thim-elementor-kit' ),
					'absolute' => esc_html__( 'Absolute', 'thim-elementor-kit' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number' => 'position: {{VALUE}};',
				),
				'condition' => array(
					'items_indicator' => 'yes',
				),
			)
		);

		$start = is_rtl() ? esc_html__( 'Right', 'thim-elementor-kit' ) : esc_html__( 'Left', 'thim-elementor-kit' );
		$end   = ! is_rtl() ? esc_html__( 'Right', 'thim-elementor-kit' ) : esc_html__( 'Left', 'thim-elementor-kit' );

		$this->add_control(
			'indicator_offset_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
				'condition'   => array(
					'indicator_position!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_offset_x',
			array(
				'label'      => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => - 500,
						'max'  => 500,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number'       => 'right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'indicator_offset_orientation_h!' => 'end',
					'indicator_position!'             => '',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_offset_x_end',
			array(
				'label'      => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => - 500,
						'max'  => 500,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number'       => 'left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'indicator_offset_orientation_h' => 'end',
					'indicator_position!'            => '',
				),
			)
		);

		$this->add_control(
			'indicator_offset_orientation_v',
			array(
				'label'       => esc_html__( 'Vertical Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'end'   => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
				'condition'   => array(
					'indicator_position!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_offset_y',
			array(
				'label'      => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => - 500,
						'max'  => 500,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number' => 'top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'indicator_offset_orientation_v!' => 'end',
					'indicator_position!'             => '',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_offset_y_end',
			array(
				'label'      => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => - 500,
						'max'  => 500,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .minicart-icon .cart-items-number' => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'indicator_offset_orientation_v' => 'end',
					'indicator_position!'            => '',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_cart() {
		$this->start_controls_section(
			'section_cart_style',
			array(
				'label' => esc_html__( 'Cart', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cart_type',
			array(
				'label'   => esc_html__( 'Cart Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'mini-cart' => esc_html__( 'Mini Cart', 'thim-elementor-kit' ),
					'side-cart' => esc_html__( 'Side Cart', 'thim-elementor-kit' ),
				),
				'default' => 'mini-cart',
			)
		);
		$this->add_responsive_control(
			'side_cart_alignment',
			array(
				'label'                => esc_html__( 'Cart Position', 'thim-elementor-kit' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start' => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
				'selectors_dictionary' => array(
					'start' => '--side-cart-alignment-transform: -100%; --side-cart-alignment-right: auto; --side-cart-alignment-left: 0;',
					'end'   => '--side-cart-alignment-transform: 100%; --side-cart-alignment-left: auto; --side-cart-alignment-right: 0;',
				),
			)
		);
		$this->add_responsive_control(
			'cart_size',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 200,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart__content' => '--ekits-mini-cart-width-content: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart__content,{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content'                 => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-mini-cart.mini-cart .widget_shopping_cart_content::after' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),  
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cart_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart__content',
			)
		);

		$this->add_responsive_control(
			'cart_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart__content'        => '--ekits-mini-cart-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart_content .total' => 'margin-bottom:{{BOTTOM}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'heading_close_cart_button',
			[
				'label' => esc_html__( 'Close Cart', 'thim-elementor-kit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
			]
		);
		$this->add_control(
			'close_cart_icon',
			[
				'label' => esc_html__( 'choosen Icon', 'thim-elementor-kit' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-times',
					'library' => 'fa-solid',
				],
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
			]
		);
		$this->add_responsive_control(
			'close_text_size',
			array(
				'label'      => esc_html__( 'Size text', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart__close' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
			)
		);
		$this->add_responsive_control(
			'close_icon_size',
			array(
				'label'      => esc_html__( 'Size Icon', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart__close i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekits-mini-cart__close svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
			)
		);

		$this->start_controls_tabs(
			'cart_close__style',
			array(
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
			)
		);

		$this->start_controls_tab(
			'cart_close_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'cart_close_text_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart__close' => 'color: {{VALUE}}',
					'{{WRAPPER}} .thim-ekits-mini-cart__close svg path' => 'stroke: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_close_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'cart_close_text_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart__close:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-mini-cart__close:hover svg path' => 'stroke: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'section_cart_sidebar_style',
			array(
				'label' => esc_html__( 'Menu Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'heading_cart_sidebar_typography',

				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .ekits-mini-cart-header-title',
			)
		);
		$this->add_control(
			'heading_cart_sidebar_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .ekits-mini-cart-header-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'heading_cart_sidebar_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .thim-ekits-mini-cart__content-header'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'cart_type' => 'side-cart',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_product() {
		$this->start_controls_section(
			'section_product_tabs_style',
			array(
				'label' => esc_html__( 'Products', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'product_max_height',
			array(
				'label'     => esc_html__( 'Max Height', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'size' => 350,
				],
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .woocommerce-mini-cart' => 'max-height: {{SIZE}}{{UNIT}}; overflow: auto;',
				),
			)
		);
		$this->add_control(
			'heading_product_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Product Title', 'thim-elementor-kit' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'product_title_typography',

				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content a',
			)
		);

		$this->start_controls_tabs( 'product_title_colors' );

		$this->start_controls_tab( 'product_title_normal_colors', array( 'label' => esc_html__( 'Normal', 'thim-elementor-kit' ) ) );

		$this->add_control(
			'product_title_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_title_hover_colors', array( 'label' => esc_html__( 'Hover', 'thim-elementor-kit' ) ) );

		$this->add_control(
			'product_title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_product_price_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Product Price', 'thim-elementor-kit' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'product_price_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'product_price_typography',

				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'heading_quantity_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Quantity', 'thim-elementor-kit' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'product_quantity_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .quantity' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_quantity_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .quantity',
			)
		);
		$this->add_control(
			'heading_remove_item_style',
			array(
				'label'     => esc_html__( 'Remove Item', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'remove_item_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content a.remove' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'cart_remove_item_style'
		);

		$this->start_controls_tab(
			'remove_item_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'remove_item_text_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content a.remove' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'remove_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'remove_item_text_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content a.remove:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_product_divider_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Divider', 'thim-elementor-kit' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => esc_html__( 'Style', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html__( 'Solid', 'thim-elementor-kit' ),
					'double' => esc_html__( 'Double', 'thim-elementor-kit' ),
					'dotted' => esc_html__( 'Dotted', 'thim-elementor-kit' ),
					'dashed' => esc_html__( 'Dashed', 'thim-elementor-kit' ),
					'groove' => esc_html__( 'Groove', 'thim-elementor-kit' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .cart_list.product_list_widget li' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .cart_list.product_list_widget li' => 'border-top-color: {{VALUE}};',
				),
				'condition' => array(
					'divider_style!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'divider_height',
			array(
				'label'     => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'condition' => array(
					'divider_style!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .cart_list.product_list_widget li' => 'border-top-width: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'divider_gap',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--product-divider-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_sub_total() {
		$this->start_controls_section(
			'section_style_sub_total',
			array(
				'label' => esc_html__( 'Subtotal', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'total_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .total' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sub_total_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .total' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_sub_total_divider_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Divider', 'thim-elementor-kit' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'divider_sub_total_style',
			array(
				'label'     => esc_html__( 'Style', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html__( 'Solid', 'thim-elementor-kit' ),
					'double' => esc_html__( 'Double', 'thim-elementor-kit' ),
					'dotted' => esc_html__( 'Dotted', 'thim-elementor-kit' ),
					'dashed' => esc_html__( 'Dashed', 'thim-elementor-kit' ),
					'groove' => esc_html__( 'Groove', 'thim-elementor-kit' ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--subtotal-divider-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_sub_total_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'divider_sub_total_style!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--subtotal-divider-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'divider_sub_total_height',
			array(
				'label'     => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'condition' => array(
					'divider_sub_total_style!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--subtotal-divider-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'divider_sub_total_gap',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--subtotal-divider-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_button() {
		$this->start_controls_section(
			'section_style_buttons',
			array(
				'label' => esc_html__( 'Buttons', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'buttons_layout',
			array(
				'label'                => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					'inline'  => esc_html__( 'Inline', 'thim-elementor-kit' ),
					'stacked' => esc_html__( 'Stacked', 'thim-elementor-kit' ),
				),
				'default'              => 'inline',
				'devices'              => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'inline'  => '--cart-button-layout: 1fr 1fr;',
					'stacked' => '--cart-button-layout: 1fr;',
				),
			)
		);

		$this->add_responsive_control(
			'space_between_buttons',
			array(
				'label'     => esc_html__( 'Space Between', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--cart-space-between-buttons: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'product_buttons_typography',

				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a',
			)
		);
		$this->add_responsive_control(
			'product_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--product-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --view-cart-button-height: fit-content; --view-cart-button-width: fit-content;',
				),

			)
		);
		$this->add_responsive_control(
			'product_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'heading_view_cart_button_style',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'View Cart', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'view_cart_border_style',
			array(
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				),
				'separator' => 'before',
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a:not(.checkout)' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'view_cart_border_dimensions',
			array(
				'label'     => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array(
					'view_cart_border_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a:not(.checkout)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'view_cart_button_text_colors'
		);

		$this->start_controls_tab(
			'heading_view_cart_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'view_cart_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--view-cart-button-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--view-cart-button-background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'view_cart_button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a:not(.checkout)' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'view_cart_border_style!' => 'none',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'heading_view_cart_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'view_cart_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a:not(.checkout):hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_button_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a:not(.checkout):hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_button_border_hover_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons a:not(.checkout):hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'view_cart_border_style!' => 'none',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_checkout_button_style',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkout', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'checkout_border_style',
			array(
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				),
				'separator' => 'before',
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons .checkout' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'checkout_border_dimensions',
			array(
				'label'     => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array(
					'checkout_border_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons .checkout' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'cart_checkout_button_text_colors'
		);

		$this->start_controls_tab(
			'heading_cart_checkout_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'checkout_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'checkout_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'checkout_button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'checkout_border_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons .checkout' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'heading_cart_checkout_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'checkout_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons .checkout:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'checkout_button_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons .checkout:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'checkout_button_border_hover_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'checkout_border_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .buttons .checkout:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function register_stye_message() {
		$this->start_controls_section(
			'section_style_messages',
			array(
				'label' => esc_html__( 'Messages', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cart_empty_message_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .empty',
			)
		);

		$this->add_control(
			'empty_message_color',
			array(
				'label'     => esc_html__( 'Empty Cart Message Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .empty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_message_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-mini-cart .widget_shopping_cart_content .empty' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->render_menu_cart();
	}

	/**
	 * Render menu cart.
	 * The `widget_shopping_cart_content`
	 */
	public function render_menu_cart() {
		if ( ! function_exists( 'WC' ) || null === \WC()->cart  || is_checkout() || is_cart()) { 
			return;
		}
		$settings              = $this->get_settings_for_display();
		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', $settings['hide_empty'] );

		if ( ! $widget_cart_is_hidden ) :
			?>
			<div class="thim-ekits-mini-cart <?php echo esc_attr( $settings['cart_type'] ); ?>">
				<div class="minicart-icon">
					<?php if($settings['title_cart'] && $settings['cart_type'] === 'mini-cart'){?> 
						<<?php echo $settings['title_tag'];?> class="ekits-mini-cart-header-title"><?php echo $settings['title_cart'];?></<?php echo $settings['title_tag'];?>>
					<?php		 
						}
					?>
					<div class="minicart-icon-indicator">
						<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
						<?php
						if ( $settings['items_indicator'] ) {
							\Thim_EL_Kit\Modules\WooCommerce::instance()->render_cart_subtotal();
						}
						?>
					</div>
				</div>

				<div class="thim-ekits-mini-cart__inner">
					<div class="thim-ekits-mini-cart__content">
						<?php if($settings['title_cart'] &&  $settings['cart_type'] === 'side-cart'){?>
							<div class="thim-ekits-mini-cart__content-header">
							<<?php echo $settings['title_tag'];?> class="ekits-mini-cart-header-title"><?php echo $settings['title_cart'];?></<?php echo $settings['title_tag'];?>>
						<?php		 
							}
						?>
						<?php if ( $settings['cart_type'] === 'side-cart' ) : ?>
							<span class="thim-ekits-mini-cart__close">
								<?php 
								esc_html_e('Close','thim-elementor-kit');
								Icons_Manager::render_icon( $settings['close_cart_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php 
						endif;
						if($settings['title_cart'] &&  $settings['cart_type'] === 'side-cart'){?>
							</div>
						<?php } ?>
						<div class="widget_shopping_cart_content">
							<?php woocommerce_mini_cart(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
		endif;

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['cart_type'] === 'side-cart' ) {
			?>
			<script>
				document.body.dispatchEvent(new CustomEvent("thimEkitsEditor:miniCart"));
			</script>
			<?php
		}
	}
}
 