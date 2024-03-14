<?php
namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Wrapper_Link {

	public static function init() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( __CLASS__, 'add_controls_section' ), 1 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( __CLASS__, 'add_controls_section' ), 1 );
		add_action( 'elementor/element/container/section_layout/after_section_end', array( __CLASS__, 'add_controls_section' ), 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( __CLASS__, 'add_controls_section' ), 1 );

		add_action( 'elementor/frontend/before_render', array( __CLASS__, 'before_section_render' ), 1 );
	}

	public static function add_controls_section( Element_Base $element ) {

		$element->start_controls_section(
			'section_xpro_elementor_wrapper_link',
			array(
				'label' => __( 'Wrapper Link', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'xpro_elementor_element_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://example.com',
			)
		);

		$element->end_controls_section();
	}

	public static function before_section_render( Element_Base $element ) {
		$link_settings = $element->get_settings_for_display( 'xpro_elementor_element_link' );

		if ( $link_settings && ! empty( $link_settings['url'] ) ) {
			$element->add_render_attribute(
				'_wrapper',
				array(
					'data-xpro-element-link' => wp_json_encode( $link_settings ),
					'style'                  => 'cursor: pointer',
				)
			);
		}
	}
}

Wrapper_Link::init();
