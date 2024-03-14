<?php
/**
 * Class: Soft_Template_Mini_Cart
 * Name: Mini Cart
 * Slug: soft-template-mini-cart
 */
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Mini_Cart extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-mini-cart';
	}

	public function get_title() {
		return esc_html__( 'Mini Cart', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-cart';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}


	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'soft-template-mini-cart' );
	}

	

    protected function register_controls() {
        // Widget main
        $this->widget_main_options();
        $this->widget_style_options();
    }

    public function widget_main_options() {
        $this->start_controls_section(
			'mini_cart_content_section',
			[
				'label' => __( 'Mini Cart', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'mini_cart_icons',
			[
				'label' => __( 'Icon', 'soft-template-core' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'ha_woo_mini_cart_icon',
                'default' => [
                    'value' => 'fas fa-shopping-basket',
					'library' => 'fa-solid',
                ],
			]
        );

        $this->add_control(
            'mini_cart_show',
            [
                'label' => __( 'Cart Popup Show', 'soft-template-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
				'options' => [
					'none'     => __( 'None', 'soft-template-core' ),
					'click'     => __( 'Click', 'soft-template-core' ),
					'hover'     => __( 'Hover', 'soft-template-core' ),
				],

            ]
        );

		$this->add_control(
			'mini_cart_subtotal_show',
			[
				'label'                 => __( 'Show Subtotal', 'soft-template-core' ),
				'type'                  => Controls_Manager::SWITCHER,
				'label_on'              => __( 'Show', 'soft-template-core' ),
				'label_off'             => __( 'Hide', 'soft-template-core' ),
				'return_value'          => 'yes',
				'default'               => 'yes',
			]
		);

        $this->add_control(
            'mini_cart_subtotal_position',
            [
                'label' =>__( 'Subtotal Position', 'soft-template-core' ),
                'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'soft-template-core'),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __('Right', 'soft-template-core'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'toggle' => false,
                'style_transfer' => true,
				'selectors_dictionary' => [
                    'left' => 'order: -1;',
                    'right' => 'order: 1;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .soft-template-cart-wrapper .soft-template-cart-button .soft-template-cart-total' => '{{VALUE}};'
				],
				'prefix_class' => 'soft-template-cart-subtotal-position-',
				'condition'             => [
					'mini_cart_subtotal_show' => 'yes',
				],
            ]
        );

        $this->add_responsive_control(
            'mini_cart_alignment',
            [
                'label' =>__( 'Alignment', 'soft-template-core' ),
                'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'soft-template-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'soft-template-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'soft-template-core' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'selectors_dictionary' => [
                    'left' => 'text-align: left;',
                    'center' => 'text-align: center;',
                    'right' => 'text-align: right;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .soft-template-cart-wrapper' => '{{VALUE}};'
				],
				'prefix_class' => 'soft-template-cart%s-align-',
                'default' => 'left',
            ]
        );

        $this->end_controls_section();
    }

	public function widget_style_options() {
		$this->__cart_btn_style_controls();
		$this->__body_style_controls();
		$this->__header_style_controls();
		$this->__item_style_controls();
		$this->__subtotal_style_controls();
		$this->__btn_style_controls();
	}

	// Mini Cart Style
	protected function __cart_btn_style_controls() {

		$this->start_controls_section(
			'mini_cart_button_section',
			[
				'label' => __( 'Mini Cart', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'mini_cart_button_padding',
			[
				'label' => __( 'Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mini_cart_icon_size',
			[
				'label' => __( 'Icon Size', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .soft-template-cart-button svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mini_cart_subtotal_space',
			[
				'label' => __( 'Space Between', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}}.soft-template-cart-subtotal-position-right .soft-template-cart-button .soft-template-cart-total' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.soft-template-cart-subtotal-position-left .soft-template-cart-button .soft-template-cart-total' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'mini_cart_subtotal_show' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_button_typo',
				'label'    => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .soft-template-cart-button',
			]
		);

		$this->add_responsive_control(
			'mini_cart_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'mini_cart_button_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-button',
			]
		);

		$this->start_controls_tabs('mini_cart_button_color_tabs');
		$this->start_controls_tab(
			'mini_cart_button_color_normal_tab',
			[
				'label' => __('Normal', 'soft-template-core')
			]
		);

		$this->add_control(
			'mini_cart_button_normal_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .soft-template-cart-button svg'  => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'mini_cart_button_normal_bg_color',
				'exclude' => [
					'classic' => 'image' // remove image bg option
				],
				'selector' => '{{WRAPPER}} .soft-template-cart-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'mini_cart_button_border',
				'label'     => __( 'Border', 'soft-template-core' ),
				'selector'  => '{{WRAPPER}} .soft-template-cart-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mini_cart_button_color_hover_tab',
			[
				'label' => __('Hover', 'soft-template-core')
			]
		);

		$this->add_control(
			'mini_cart_button_hover_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .soft-template-cart-button:hover svg'  => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'mini_cart_button_hover_bg_color',
				'exclude' => [
					'classic' => 'image' // remove image bg option
				],
				'selector' => '{{WRAPPER}} .soft-template-cart-button:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'mini_cart_hover_button_border',
				'label'     => __( 'Border', 'soft-template-core' ),
				'selector'  => '{{WRAPPER}} .soft-template-cart-button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'mini_cart_button_count_heading',
			[
				'label' => __( 'Count:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mini_cart_button_count_height',
			[
				'label' => __( 'Height', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 250,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-count' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'mini_cart_button_count_width',
			[
				'label' => __( 'Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 250,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-count' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// $this->add_control(
		// 	'mini_cart_button_count_toggle',
		// 	[
		// 		'label' => __( 'Position', 'soft-template-core' ),
		// 		'type' => Controls_Manager::POPOVER_TOGGLE,
		// 		'return_value' => 'yes',
		// 	]
		// );

		$this->start_popover();

		$this->add_responsive_control(
			'mini_cart_button_count_x',
			[
				'label' => __( 'Horizontal', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					]
				],
				'style_transfer' => true,
				'render_type' => 'ui',
				'condition' => [
					'mini_cart_button_count_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-count' => 'top: {{SIZE}}px;'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_button_count_y',
			[
				'label' => __( 'Vertical', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					]
				],
				'style_transfer' => true,
				'render_type' => 'ui',
				'condition' => [
					'mini_cart_button_count_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-count' => 'right: {{SIZE}}px;'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'mini_cart_button_count_font_size',
			[
				'label' => __( 'Font Size', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-count' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mini_cart_button_count_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'mini_cart_button_count_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-count',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'mini_cart_button_count_border',
				'label'       => __( 'Border', 'soft-template-core' ),
				'selector'    => '{{WRAPPER}} .soft-template-cart-count',
			]
		);

		$this->start_controls_tabs('mini_cart_button_count_tabs');
		$this->start_controls_tab(
			'mini_cart_button_count_normal_tab',
			[
				'label' => __( 'Normal', 'soft-template-core' )
			]
		);

		$this->add_control(
			'mini_cart_button_count_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mini_cart_button_count_background_color',
			[
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mini_cart_button_count_hover_tab',
			[
				'label' => __( 'Hover', 'soft-template-core' )
			]
		);

		$this->add_control(
			'mini_cart_button_count_hover_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-button:hover .soft-template-cart-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mini_cart_button_count_hover_background_color',
			[
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-button:hover .soft-template-cart-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mini_cart_button_count_hover_border_color',
			[
				'label'     => __( 'Border Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-button:hover .soft-template-cart-count' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	// Body Style
	protected function __body_style_controls(){

		$this->start_controls_section(
			'popup_body_section',
			[
				'label' => __( 'Popup:- Body', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'mini_cart_show!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'popup_body_width',
			[
				'label' => __( 'Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_offset_x',
			[
				'label' => __( 'Offset X', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1200,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup' => 'left: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'popup_body_padding',
			[
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'popup_body_margin',
			[
				'label'      => __( 'Margin', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'popup_body_bg_color',
			[
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'popup_body_border',
				'label'       => __( 'Border', 'soft-template-core' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .soft-template-cart-popup',
			]
		);

		$this->add_responsive_control(
			'popup_body_border_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'popup_body_border_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-popup',
			]
		);

		$this->end_controls_section();
	}

	// Header Style
	protected function __header_style_controls(){

		$this->start_controls_section(
			'popup_header_section',
			[
				'label' => __( 'Popup:- Header', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'mini_cart_show!' => 'none',
				],
			]
		);

		$this->add_control(
			'popup_header_content_padding',
			[
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'popup_header_content_margin',
			[
				'label'      => __( 'Margin', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'		 => 'popup_header_content_typo',
				'selector'	 => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header .soft-template-cart-popup-count-text-area, {{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header .soft-template-cart-popup-count-text-area a',
			]
		);



		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'popup_header_side_border',
				'label'       => __( 'Border', 'soft-template-core' ),
				'selector'    => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header .soft-template-cart-popup-count-text-area:before,{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header .soft-template-cart-popup-count-text-area:after',
			]
		);

		$this->add_responsive_control(
			'popup_header_side_border_space',
			[
				'label' => __( 'Border Space', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 5,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header .soft-template-cart-popup-count-text-area:before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-header .soft-template-cart-popup-count-text-area:after' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Item Style
	protected function __item_style_controls(){

		$this->start_controls_section(
			'popup_item_section',
			[
				'label' => __( 'Popup:- Item', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'mini_cart_show!' => 'none',
				],
			]
		);

		$this->add_control(
			'popup_item_border_color',
			[
				'label'     => __( 'Border Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popup_item_padding',
			[
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_item_border_width',
			[
				'label' => __( 'Border Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_item_title_heading',
			[
				'label' => __( 'Title:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'		 => 'popup_item_title_typo',
				'selector'	 => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a:not(.remove)',
			]
		);

		$this->add_control(
			'popup_item_title_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a:not(.remove)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_item_quantity_heading',
			[
				'label' => __( 'Quantity:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), [
				'name'		 => 'popup_item_quantity_typo',
				'selector'	 => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li .quantity',
			]
		);

		$this->add_control(
			'popup_item_quantity_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li .quantity' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_item_image_heading',
			[
				'label' => __( 'Image:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'popup_item_image_border',
				'label'       => __( 'Border', 'soft-template-core' ),
				'selector'    => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a > img',
			]
		);

		$this->add_control(
			'popup_item_image_border_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'popup_item_image_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a > img',
			]
		);

		$this->add_responsive_control(
			'popup_item_remove_heading',
			[
				'label' => __( 'Remove:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_item_remove_height',
			[
				'label' => __( 'Height', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'popup_item_remove_width',
			[
				'label' => __( 'Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popup_item_remove_typo',
				'label'    => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove',
			]
		);

		$this->add_control(
			'popup_item_remove_border_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'popup_item_remove_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'popup_item_remove_border',
				'label'       => __( 'Border', 'soft-template-core' ),
				'selector'    => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove',
			]
		);

		$this->start_controls_tabs('popup_item_remove_color_tabs');
		$this->start_controls_tab(
			'popup_item_remove_color_normal_tab',
			[
				'label' => __( 'Normal', 'soft-template-core' )
			]
		);

		$this->add_control(
			'popup_item_remove_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popup_item_remove_background_color',
			[
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'popup_item_remove_color_hover_tab',
			[
				'label' => __( 'Hover', 'soft-template-core' )
			]
		);

		$this->add_control(
			'popup_item_remove_hover_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popup_item_remove_hover_background_color',
			[
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popup_item_remove_hover_border_color',
			[
				'label'     => __( 'Border Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body ul li a.remove:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	// Subtotal Style
	protected function __subtotal_style_controls(){

		$this->start_controls_section(
			'popup_subtotal_section',
			[
				'label' => __( 'Popup:- Subtotal', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'mini_cart_show!' => 'none',
				],
			]
		);

		$this->add_control(
			'popup_subtotal_padding',
			[
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'popup_subtotal_margin',
			[
				'label'      => __( 'Margin', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_subtotal_title_heading',
			[
				'label' => __( 'Title:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popup_subtotal_title_typo',
				'label'    => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__total strong',
			]
		);

		$this->add_control(
			'popup_subtotal_title_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__total strong' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'popup_subtotal_price_heading',
			[
				'label' => __( 'Price:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popup_subtotal_price_typo',
				'label'    => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__total .amount',
			]
		);

		$this->add_control(
			'popup_subtotal_price_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__total .amount' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Button Style
	protected function __btn_style_controls(){

		$this->start_controls_section(
			'popup_button_section',
			[
				'label' => __( 'Popup:- Button', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'mini_cart_show!' => 'none',
				],
			]
		);

		$this->view_cart_btn_style();

		$this->checkout_btn_style();

		$this->end_controls_section();

	}

	/**
	 * View Cart Button Style controls
	 */
	protected function view_cart_btn_style(){

		$this->add_responsive_control(
			'view_cart_button_heading',
			[
				'label' => __( 'View Cart:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'view_cart_button_padding',
			[
				'label' => __( 'Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'view_cart_button_typo',
				'label'    => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)',
			]
		);

		$this->add_responsive_control(
			'view_cart_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'view_cart_button_border',
				'label'     => __( 'Border', 'soft-template-core' ),
				'selector'  => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'view_cart_button_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)',
			]
		);

		$this->start_controls_tabs('view_cart_button_color_tabs');
		$this->start_controls_tab(
			'view_cart_button_color_normal_tab',
			[
				'label' => __('Normal', 'soft-template-core')
			]
		);

		$this->add_control(
			'view_cart_button_normal_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'view_cart_button_normal_bg_color',
				'exclude' => [
					'classic' => 'image' // remove image bg option
				],
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1)',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_cart_button_color_hover_tab',
			[
				'label' => __('Hover', 'soft-template-core')
			]
		);

		$this->add_control(
			'view_cart_button_hover_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'view_cart_button_hover_bg_color',
				'exclude' => [
					'classic' => 'image' // remove image bg option
				],
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1):hover',
			]
		);

		$this->add_control(
			'view_cart_button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(1):hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	/**
	 * Checkout Button Style controls
	 */
	protected function checkout_btn_style(){

		$this->add_responsive_control(
			'checkout_button_heading',
			[
				'label' => __( 'Checkout:', 'soft-template-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'checkout_button_padding',
			[
				'label' => __( 'Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_button_typo',
				'label'    => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)',
			]
		);

		$this->add_responsive_control(
			'checkout_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'checkout_button_border',
				'label'     => __( 'Border', 'soft-template-core' ),
				'selector'  => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'checkout_button_shadow',
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)',
			]
		);

		$this->start_controls_tabs('checkout_button_color_tabs');
		$this->start_controls_tab(
			'checkout_button_color_normal_tab',
			[
				'label' => __('Normal', 'soft-template-core')
			]
		);

		$this->add_control(
			'checkout_button_normal_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'checkout_button_normal_bg_color',
				'exclude' => [
					'classic' => 'image' // remove image bg option
				],
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2)',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkout_button_color_hover_tab',
			[
				'label' => __('Hover', 'soft-template-core')
			]
		);

		$this->add_control(
			'checkout_button_hover_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'checkout_button_hover_bg_color',
				'exclude' => [
					'classic' => 'image' // remove image bg option
				],
				'selector' => '{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2):hover',
			]
		);

		$this->add_control(
			'checkout_button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soft-template-cart-popup .soft-template-cart-popup-body .woocommerce-mini-cart__buttons .button:nth-child(2):hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

    protected function render(){

		if ( ! function_exists( 'WC' ) ) {
			$this->show_wc_missing_alert();
			return;
		}

		$settings = $this->get_settings();

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'soft-template-cart-wrapper',
			]
		);

		$this->add_render_attribute(
			'inner',
			[
				'class' => [
					'soft-template-cart-inner',
					$settings['mini_cart_show'] !== 'none' ? 'soft-template-cart-on-' . esc_attr( $settings['mini_cart_show'] ) : '',
				]
			]
		);
        ?>

        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

			<div <?php $this->print_render_attribute_string( 'inner' ); ?>>

                <div class="soft-template-cart-button">

                    <div class="soft-template-cart-count-area">
						<span class="soft-template-cart-icon">
							<?php
								if ( $settings['mini_cart_icons']['value'] ) {
									Icons_Manager::render_icon( $settings['mini_cart_icons'], [ 'aria-hidden' => 'true' ] );
								} else { ?>
									<i class="fas fa-shopping-basket" aria-hidden="true"></i>
									<?php
								}
							?>
						</span>
						<span class="soft-template-cart-count">
	                        <?php echo (( WC()->cart != '' ) ? WC()->cart->get_cart_contents_count()." " : '' ); ?>
						</span>
                    </div>

					<?php if( 'yes' == $settings['mini_cart_subtotal_show'] ): ?>
						<div class="soft-template-cart-total">
							<?php echo (( WC()->cart != '' ) ? WC()->cart->get_cart_total() : '' ); ?>
						</div>
					<?php endif; ?>

                </div>

                <?php if( $settings['mini_cart_show'] !== 'none' ): ?>
					<div class="soft-template-cart-popup">
						<div class="soft-template-cart-popup-header">
								<div class="soft-template-cart-popup-count-text-area">
									<span class="soft-template-cart-popup-count"><?php echo (( WC()->cart != '' ) )?  WC()->cart->get_cart_contents_count() : '' ; ?></span>
									<span class="soft-template-cart-popup-count-text"><?php esc_html_e( 'items', 'soft-template-core' ); ?></span>
								</div>
						</div>
						<div class="soft-template-cart-popup-body">
							<div class="widget_shopping_cart_content">
								<?php (( WC()->cart != '' ) ? woocommerce_mini_cart() : '' ); ?>
							</div>
						</div>
					</div>
                <?php endif; ?>
            </div>
        </div>

    <?php

	}

	public function show_wc_missing_alert() {
		if ( current_user_can( 'activate_plugins' ) ) {
			printf(
				'<div %s>%s</div>',
				'style="margin: 1rem;padding: 1rem 1.25rem;border-left: 5px solid #f5c848;color: #856404;background-color: #fff3cd;"',
				__( 'WooCommerce is missing! Please install and activate WooCommerce.', 'soft-template-core' )
				);
		}
	}
}