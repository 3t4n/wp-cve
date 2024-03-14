<?php
namespace Skt_Addons_Elementor\Elementor\Extension;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Grid_Layer {

	public static function init() {
		add_action('elementor/documents/register_controls', [__CLASS__, 'add_controls_section'] , 1 , 1 );
	}

	public static function add_controls_section( $element ) {
		$element->start_controls_section(
			'_section_skt_addons_elementor_grid_layer',
			[
				'label' => __( 'Grid Layer', 'skt-addons-elementor' ) . skt_addons_elementor_get_section_icon(),
				'tab' => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'skt_addons_elementor_grid',
			[
				'label'     => __( 'Grid Layer', 'skt-addons-elementor' ),
				'description'     => __( 'Use "Cmd/Ctrl + Shift + G" to On/Off Grid Layer', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'On', 'skt-addons-elementor' ),
				'label_off' => __( 'Off', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$element->add_control(
			'skt_addons_elementor_grid_layer_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'This feature is avilable only in editor and only for design purpose.', 'skt-addons-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'skt_addons_elementor_grid_number',
			[
				'label'     => __( 'Columns', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => 12,
				'tablet_default' => 12,
				'mobile_default' => 12,
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_responsive_control(
			'skt_addons_elementor_grid_max_width',
			[
				'label'      => __( 'Max Width', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 3000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 0.1,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => self::get_default_grid_value('desktop'),
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => self::get_default_grid_value('tablet'),
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => self::get_default_grid_value('mobile'),
					'unit' => 'px',
				],
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_responsive_control(
			'skt_addons_elementor_grid_offset',
			[
				'label'      => __( 'Offset', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_responsive_control(
			'skt_addons_elementor_grid_gutter',
			[
				'label'      => __( 'Gutter', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 0.1,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'skt_addons_elementor_grid_zindex',
			[
				'label'      => __( 'Z-Index', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => '1000',
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'skt_addons_elementor_grid_color',
			[
				'label'     => __( 'Grid Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(226, 73, 138, 0.5)',
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'skt_addons_elementor_grid_on',
			[
				'label' => __( 'Grid Layer On', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'grid-on',
				'condition' => [
					'skt_addons_elementor_grid' => 'yes',
				],
				'selectors' => [
					'html.elementor-html' => 'position: relative;',
					'html.elementor-html::before' => 'content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; margin-right: auto; margin-left: auto; pointer-events: none; z-index: {{skt_addons_elementor_grid_zindex.VALUE || 1000}}; min-height: 100vh;',
					//Desktop view
					'(desktop) html.elementor-html::before' => '
					width: calc(100% - (2 * {{skt_addons_elementor_grid_offset.SIZE}}{{skt_addons_elementor_grid_offset.UNIT}}));
					max-width: {{skt_addons_elementor_grid_max_width.SIZE}}{{skt_addons_elementor_grid_max_width.UNIT}};
					background-size: calc(100% + {{skt_addons_elementor_grid_gutter.SIZE}}{{skt_addons_elementor_grid_gutter.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{skt_addons_elementor_grid_color.VALUE}}, {{skt_addons_elementor_grid_color.VALUE}} calc((100% / {{skt_addons_elementor_grid_number.VALUE}}) - {{skt_addons_elementor_grid_gutter.SIZE}}{{skt_addons_elementor_grid_gutter.UNIT}}), transparent calc((100% / {{skt_addons_elementor_grid_number.VALUE}}) - {{skt_addons_elementor_grid_gutter.SIZE}}{{skt_addons_elementor_grid_gutter.UNIT}}), transparent calc(100% / {{skt_addons_elementor_grid_number.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{skt_addons_elementor_grid_color.VALUE}}, {{skt_addons_elementor_grid_color.VALUE}} calc((100% / {{skt_addons_elementor_grid_number.VALUE}}) - {{skt_addons_elementor_grid_gutter.SIZE}}{{skt_addons_elementor_grid_gutter.UNIT}}), transparent calc((100% / {{skt_addons_elementor_grid_number.VALUE}}) - {{skt_addons_elementor_grid_gutter.SIZE}}{{skt_addons_elementor_grid_gutter.UNIT}}), transparent calc(100% / {{skt_addons_elementor_grid_number.VALUE}}) );',
					//Tablet view
					'(tablet) html.elementor-html::before' => '
					width: calc(100% - (2 * {{skt_addons_elementor_grid_offset_tablet.SIZE}}{{skt_addons_elementor_grid_offset_tablet.UNIT}}));
					max-width: {{skt_addons_elementor_grid_max_width_tablet.SIZE}}{{skt_addons_elementor_grid_max_width_tablet.UNIT}};
					background-size: calc(100% + {{skt_addons_elementor_grid_gutter_tablet.SIZE}}{{skt_addons_elementor_grid_gutter_tablet.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{skt_addons_elementor_grid_color.VALUE}}, {{skt_addons_elementor_grid_color.VALUE}} calc((100% / {{skt_addons_elementor_grid_number_tablet.VALUE}}) - {{skt_addons_elementor_grid_gutter_tablet.SIZE}}{{skt_addons_elementor_grid_gutter_tablet.UNIT}}), transparent calc((100% / {{skt_addons_elementor_grid_number_tablet.VALUE}}) - {{skt_addons_elementor_grid_gutter_tablet.SIZE}}{{skt_addons_elementor_grid_gutter_tablet.UNIT}}), transparent calc(100% / {{skt_addons_elementor_grid_number_tablet.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{skt_addons_elementor_grid_color.VALUE}}, {{skt_addons_elementor_grid_color.VALUE}} calc((100% / {{skt_addons_elementor_grid_number_tablet.VALUE}}) - {{skt_addons_elementor_grid_gutter_tablet.SIZE}}{{skt_addons_elementor_grid_gutter_tablet.UNIT}}), transparent calc((100% / {{skt_addons_elementor_grid_number_tablet.VALUE}}) - {{skt_addons_elementor_grid_gutter_tablet.SIZE}}{{skt_addons_elementor_grid_gutter_tablet.UNIT}}), transparent calc(100% / {{skt_addons_elementor_grid_number_tablet.VALUE}}) );',
					//Mobile view
					'(mobile) html.elementor-html::before' => '
					width: calc(100% - (2 * {{skt_addons_elementor_grid_offset_mobile.SIZE}}{{skt_addons_elementor_grid_offset_mobile.UNIT}}));
					max-width: {{skt_addons_elementor_grid_max_width_mobile.SIZE}}{{skt_addons_elementor_grid_max_width_mobile.UNIT}};
					background-size: calc(100% + {{skt_addons_elementor_grid_gutter_mobile.SIZE}}{{skt_addons_elementor_grid_gutter_mobile.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{skt_addons_elementor_grid_color.VALUE}}, {{skt_addons_elementor_grid_color.VALUE}} calc((100% / {{skt_addons_elementor_grid_number_mobile.VALUE}}) - {{skt_addons_elementor_grid_gutter_mobile.SIZE}}{{skt_addons_elementor_grid_gutter_mobile.UNIT}}), transparent calc((100% / {{skt_addons_elementor_grid_number_mobile.VALUE}}) - {{skt_addons_elementor_grid_gutter_mobile.SIZE}}{{skt_addons_elementor_grid_gutter_mobile.UNIT}}), transparent calc(100% / {{skt_addons_elementor_grid_number_mobile.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{skt_addons_elementor_grid_color.VALUE}}, {{skt_addons_elementor_grid_color.VALUE}} calc((100% / {{skt_addons_elementor_grid_number_mobile.VALUE}}) - {{skt_addons_elementor_grid_gutter_mobile.SIZE}}{{skt_addons_elementor_grid_gutter_mobile.UNIT}}), transparent calc((100% / {{skt_addons_elementor_grid_number_mobile.VALUE}}) - {{skt_addons_elementor_grid_gutter_mobile.SIZE}}{{skt_addons_elementor_grid_gutter_mobile.UNIT}}), transparent calc(100% / {{skt_addons_elementor_grid_number_mobile.VALUE}}) );',
				],
			]
		);

		$element->end_controls_section();
	}

	public static function get_default_grid_value( $value = '' ) {
		$default = [
			'desktop' => get_option( 'elementor_container_width', 1140 ),
			'tablet' => get_option( 'elementor_viewport_lg', 1025 ),
			'mobile' => get_option( 'elementor_viewport_md', 768 ),
		];

		return $default[$value];
	}
}

Grid_Layer::init();