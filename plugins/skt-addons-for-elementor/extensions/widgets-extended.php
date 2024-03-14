<?php
/**
 * Elementor default widgets enhancements
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Text_Stroke;

defined('ABSPATH') || die();

class Widgets_Extended {

	public static function init() {
		add_action( 'elementor/element/button/section_style/after_section_start', [ __CLASS__, 'add_button_controls' ] );

		if( ! in_array( 'text-stroke', skt_addons_elementor_get_inactive_features() ) ){
			add_action( 'elementor/element/heading/section_title_style/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
			add_action( 'elementor/element/theme-page-title/section_title_style/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
			add_action( 'elementor/element/theme-site-title/section_title_style/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
			add_action( 'elementor/element/theme-post-title/section_title_style/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
			add_action( 'elementor/element/woocommerce-product-title/section_title_style/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
			add_action( 'elementor/element/animated-headline/section_style_text/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
			add_action( 'elementor/element/skt-gradient-heading/_section_style_title/after_section_end', [ __CLASS__, 'add_text_stroke' ] );
		}
	}

	public static function add_text_stroke( Widget_Base $widget ) {
		$common = [
			'of'     => 'blend_mode',
			'target' => '.elementor-heading-title',
		];

		$map = [
			'heading'                   => $common,
			'theme-page-title'          => $common,
			'theme-site-title'          => $common,
			'theme-post-title'          => $common,
			'woocommerce-product-title' => $common,
			'animated-headline'         => [
				'of'     => 'title_color',
				'target' => '.elementor-headline',
			],
			'skt-gradient-heading'       => [
				'of'     => 'blend_mode',
				'target' => '.skt-gradient-heading',
			],
		];

		$of     = $map[ $widget->get_name() ]['of'];
		$target = $map[ $widget->get_name() ]['target'];

		$widget->start_injection( [
			'at' => 'after',
			'of' => $of,
		] );

		$widget->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'text_stroke',
				'selector' => '{{WRAPPER}} ' . $target,
			]
		);

		$widget->end_injection();
	}

	public static function add_button_controls( Widget_Base $widget ) {
		$widget->add_control(
			'skt_addons_elementor_fixed_size_toggle',
			[
				'label' => __( 'Fixed Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
			]
		);

		$widget->start_popover();

		$widget->add_responsive_control(
			'skt_addons_elementor_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 500,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'height: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'skt_addons_elementor_fixed_size_toggle' => 'yes',
				]
			]
		);

		$widget->add_responsive_control(
			'skt_addons_elementor_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 500,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'skt_addons_elementor_fixed_size_toggle' => 'yes',
				]
			]
		);

		$widget->add_control(
			'skt_addons_elementor_align_x',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Horizontal Align', 'skt-addons-elementor' ),
				'default' => 'center',
				'toggle' => false,
				'options' => [
					'left' => [
						'title' =>  __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' =>  __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => '{{VALUE}}'
				],
				'selectors_dictionary' => [
					'left' => '-webkit-box-pack: start; -ms-flex-pack: start; justify-content: flex-start;',
					'center' => '-webkit-box-pack: center; -ms-flex-pack: center; justify-content: center;',
					'right' => '-webkit-box-pack: end; -ms-flex-pack: end; justify-content: flex-end;',
				],
				'condition' => [
					'skt_addons_elementor_fixed_size_toggle' => 'yes',
				],
			]
		);

		$widget->add_control(
			'skt_addons_elementor_align_y',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Vertical Align', 'skt-addons-elementor' ),
				'default' => 'center',
				'toggle' => false,
				'options' => [
					'top' => [
						'title' =>  __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' =>  __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => '-webkit-box-align: start; -ms-flex-align: start; align-items: flex-start;',
					'center' => '-webkit-box-align: center; -ms-flex-align: center; align-items: center;',
					'bottom' => '-webkit-box-align: end; -ms-flex-align: end; align-items: flex-end;',
				],
				'condition' => [
					'skt_addons_elementor_fixed_size_toggle' => 'yes',
				],
			]
		);

		$widget->add_control(
			'skt_addons_elementor_flex_display',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'inline-flex',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'display: -webkit-inline-box; display: -ms-inline-flexbox; display: inline-flex;',
				],
				'condition' => [
					'skt_addons_elementor_fixed_size_toggle' => 'yes',
					'skt_addons_elementor_align_x!' => '',
					'skt_addons_elementor_align_y!' => '',
				],
			]
		);

		$widget->end_popover();
	}
}

Widgets_Extended::init();