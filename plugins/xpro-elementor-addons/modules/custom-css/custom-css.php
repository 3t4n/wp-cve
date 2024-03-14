<?php
/**
 * Custom CSS extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Modules;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

class Xpro_Elementor_Custom_CSS {


	/*
	 * Instance of this class
	 */
	private static $instance = null;


	public function __construct() {

		// Add new controls to advanced tab globally
		add_action( 'elementor/element/after_section_end', array( $this, 'register' ), 25, 3 );

		// Render the custom CSS
		add_action( 'elementor/element/parse_css', array( $this, 'xpro_elementor_add_post_css' ), 10, 2 );

	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register( $element, $section_id ) {

		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		if ( in_array( $element->get_name(), array( 'section', 'column', 'common', 'container' ), true ) ) {

			$element->start_controls_section(
				'section_xpro_elementor_custom_css',
				array(
					'label' => __( ' Custom CSS', 'xpro-elementor-addons' ),
					'tab'   => Controls_Manager::TAB_ADVANCED,
				)
			);

			$element->add_control(
				'xpro_custom_css',
				array(
					'type'        => Controls_Manager::CODE,
					'label'       => __( 'Custom CSS', 'xpro-elementor-addons' ),
					'render_type' => 'ui',
					'show_label'  => false,
					'language'    => 'css',
				)
			);

			$element->add_control(
				'xpro_custom_css_description',
				array(
					'raw'             => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'xpro-elementor-addons' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
				)
			);

			$element->end_controls_section();
		}
	}

	public function xpro_elementor_add_post_css( $post_css, $element ) {

		$element_settings = $element->get_settings();

		if ( empty( $element_settings['xpro_custom_css'] ) ) {
			return;
		}

		$css = trim( $element_settings['xpro_custom_css'] );

		if ( empty( $css ) ) {
			return;
		}
		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		// Add a css comment
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $css );
	}
}

Xpro_Elementor_Custom_CSS::get_instance();
