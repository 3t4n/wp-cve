<?php
namespace Thim_EL_Kit;

class Shortcode {
	use SingletonTrait;

	const SHORTCODE_NAME = 'thim_ekit';

	public function __construct() {
		add_shortcode( self::SHORTCODE_NAME, array( $this, 'template_shortcode' ) );
	}

	public function template_shortcode( array $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			self::SHORTCODE_NAME
		);

		$id = ! empty( $atts['id'] ) ? apply_filters( 'thim_ekit/shortcode/id', absint( $atts['id'] ) ) : '';

		if ( empty( $id ) ) {
			return '';
		}

		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_class = new \Elementor\Core\Files\CSS\Post( $id );
		} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
			$css_class = new \Elementor\Post_CSS_File( $id );
		}

		if ( ! empty( $css_class ) ) {
			$css_class->enqueue();
		}

		return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
	}
}

Shortcode::instance();
