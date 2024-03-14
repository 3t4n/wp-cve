<?php
/**
 * Grid Column extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Grid_Column {

	public static function init() {

		add_action( 'elementor/documents/register_controls', array( __CLASS__, 'register' ), 1, 1 );

	}

	public static function register( $element ) {

		$element->start_controls_section(
			'section_xpro_elementor_grid_column',
			array(
				'label' => __( 'Grid Column', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$element->add_control(
			'xpro_grid',
			array(
				'label'        => __( 'Enable', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Off', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$element->add_control(
			'xpro_grid_color',
			array(
				'label'       => __( 'Grid Color', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#DADADA47',
				'condition'   => array(
					'xpro_grid' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_responsive_control(
			'xpro_grid_number',
			array(
				'label'       => __( 'Columns', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 12,
				'step'        => 1,
				'default'     => 12,
				'condition'   => array(
					'xpro_grid' => 'yes',
				),
				'devices'     => array( 'desktop', 'tablet', 'mobile' ),
				'render_type' => 'none',
			)
		);

		$element->add_responsive_control(
			'xpro_grid_max_width',
			array(
				'label'          => __( 'Max Width', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'vw', '%' ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2600,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 1140,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 1025,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 768,
					'unit' => 'px',
				),
				'condition'      => array(
					'xpro_grid' => 'yes',
				),
				'devices'        => array( 'desktop', 'tablet', 'mobile' ),
				'render_type'    => 'none',
			)
		);

		$element->add_responsive_control(
			'xpro_grid_offset',
			array(
				'label'          => __( 'Offset', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', '%' ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					),
				),
				'default'        => array(
					'unit' => 'px',
					'size' => 0,
				),
				'tablet_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'condition'      => array(
					'xpro_grid' => 'yes',
				),
				'devices'        => array( 'desktop', 'tablet', 'mobile' ),
				'render_type'    => 'none',
			)
		);

		$element->add_responsive_control(
			'xpro_grid_gutter',
			array(
				'label'          => __( 'Gutter', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', '%' ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					),
				),
				'default'        => array(
					'size' => 15,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 8,
					'unit' => 'px',
				),
				'condition'      => array(
					'xpro_grid' => 'yes',
				),
				'devices'        => array( 'desktop', 'tablet', 'mobile' ),
				'render_type'    => 'none',
			)
		);

		$element->add_control(
			'xpro_grid_zindex',
			array(
				'label'       => __( 'Z-Index', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 999,
				'condition'   => array(
					'xpro_grid' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'xpro_grid_style',
			array(
				'label'     => __( 'Grid Style', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'grid-on',
				'condition' => array(
					'xpro_grid' => 'yes',
				),
				'selectors' => array(
					'html.elementor-html'                  => 'position: relative;',
					'html.elementor-html::before'          => 'content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; margin-right: auto; margin-left: auto; pointer-events: none; z-index: {{xpro_grid_zindex.VALUE || 1000}}; min-height: 100vh;',
					//Desktop view
					'(desktop) html.elementor-html::before' => '
					width: calc(100% - (2 * {{xpro_grid_offset.SIZE}}{{xpro_grid_offset.UNIT}}));
					max-width: {{xpro_grid_max_width.SIZE}}{{xpro_grid_max_width.UNIT}};
					background-size: calc(100% + {{xpro_grid_gutter.SIZE}}{{xpro_grid_gutter.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{xpro_grid_color.VALUE}}, {{xpro_grid_color.VALUE}} calc((100% / {{xpro_grid_number.VALUE}}) - {{xpro_grid_gutter.SIZE}}{{xpro_grid_gutter.UNIT}}), transparent calc((100% / {{xpro_grid_number.VALUE}}) - {{xpro_grid_gutter.SIZE}}{{xpro_grid_gutter.UNIT}}), transparent calc(100% / {{xpro_grid_number.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{xpro_grid_color.VALUE}}, {{xpro_grid_color.VALUE}} calc((100% / {{xpro_grid_number.VALUE}}) - {{xpro_grid_gutter.SIZE}}{{xpro_grid_gutter.UNIT}}), transparent calc((100% / {{xpro_grid_number.VALUE}}) - {{xpro_grid_gutter.SIZE}}{{xpro_grid_gutter.UNIT}}), transparent calc(100% / {{xpro_grid_number.VALUE}}) );',
					//Tablet view
					'(tablet) html.elementor-html::before' => '
					width: calc(100% - (2 * {{xpro_grid_offset_tablet.SIZE}}{{xpro_grid_offset_tablet.UNIT}}));
					max-width: {{xpro_grid_max_width_tablet.SIZE}}{{xpro_grid_max_width_tablet.UNIT}};
					background-size: calc(100% + {{xpro_grid_gutter_tablet.SIZE}}{{xpro_grid_gutter_tablet.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{xpro_grid_color.VALUE}}, {{xpro_grid_color.VALUE}} calc((100% / {{xpro_grid_number_tablet.VALUE}}) - {{xpro_grid_gutter_tablet.SIZE}}{{xpro_grid_gutter_tablet.UNIT}}), transparent calc((100% / {{xpro_grid_number_tablet.VALUE}}) - {{xpro_grid_gutter_tablet.SIZE}}{{xpro_grid_gutter_tablet.UNIT}}), transparent calc(100% / {{xpro_grid_number_tablet.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{xpro_grid_color.VALUE}}, {{xpro_grid_color.VALUE}} calc((100% / {{xpro_grid_number_tablet.VALUE}}) - {{xpro_grid_gutter_tablet.SIZE}}{{xpro_grid_gutter_tablet.UNIT}}), transparent calc((100% / {{xpro_grid_number_tablet.VALUE}}) - {{xpro_grid_gutter_tablet.SIZE}}{{xpro_grid_gutter_tablet.UNIT}}), transparent calc(100% / {{xpro_grid_number_tablet.VALUE}}) );',
					//Mobile view
					'(mobile) html.elementor-html::before' => '
					width: calc(100% - (2 * {{xpro_grid_offset_mobile.SIZE}}{{xpro_grid_offset_mobile.UNIT}}));
					max-width: {{xpro_grid_max_width_mobile.SIZE}}{{xpro_grid_max_width_mobile.UNIT}};
					background-size: calc(100% + {{xpro_grid_gutter_mobile.SIZE}}{{xpro_grid_gutter_mobile.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{xpro_grid_color.VALUE}}, {{xpro_grid_color.VALUE}} calc((100% / {{xpro_grid_number_mobile.VALUE}}) - {{xpro_grid_gutter_mobile.SIZE}}{{xpro_grid_gutter_mobile.UNIT}}), transparent calc((100% / {{xpro_grid_number_mobile.VALUE}}) - {{xpro_grid_gutter_mobile.SIZE}}{{xpro_grid_gutter_mobile.UNIT}}), transparent calc(100% / {{xpro_grid_number_mobile.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{xpro_grid_color.VALUE}}, {{xpro_grid_color.VALUE}} calc((100% / {{xpro_grid_number_mobile.VALUE}}) - {{xpro_grid_gutter_mobile.SIZE}}{{xpro_grid_gutter_mobile.UNIT}}), transparent calc((100% / {{xpro_grid_number_mobile.VALUE}}) - {{xpro_grid_gutter_mobile.SIZE}}{{xpro_grid_gutter_mobile.UNIT}}), transparent calc(100% / {{xpro_grid_number_mobile.VALUE}}) );',
				),
			)
		);

		$element->end_controls_section();
	}
}

Xpro_Elementor_Grid_Column::init();
