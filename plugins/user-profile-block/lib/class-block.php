<?php

namespace HAMWORKS\User_Profile_Block;

/**
 * Abstract Block
 *
 * @package HAMWORKS\User_Profile_Block
 */
abstract class Block {

	/**
	 * Block name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Block constructor.
	 *
	 * @param string $name Block name.
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Render callback
	 *
	 * @param array $attributes block attributes.
	 *
	 * @return false|string
	 */
	public function render( $attributes ) {
		return $this->get_content_from_template( $attributes );
	}

	/**
	 * Get html class names.
	 *
	 * @param array $attributes block attributes.
	 *
	 * @return array
	 */
	public function get_class_names( $attributes ): array {
		$class_names = array();
		if ( ! empty( $attributes['className'] ) ) {
			$class_names = explode( ' ', $attributes['className'] );
		}
		if ( ! empty( $attributes['align'] ) ) {
			$class_names[] = 'align' . $attributes['align'];
		}

		return $class_names;
	}

	/**
	 * Get template part directory.
	 *
	 * @return string
	 */
	public function get_template_part_dir() {
		$template_part_dir = 'template-parts/blocks';

		return trim( $template_part_dir, '/\\' );
	}

	/**
	 * Loads a template part into a template.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 *
	 * @return string
	 */
	public function get_template_part( $slug, $name = null ) {
		ob_start();
		get_template_part( $slug, $name );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Get content from template.
	 *
	 * Examples:
	 *
	 *   1. template-parts/blocks/{namespace}/{name}-{style}.php
	 *   2. template-parts/blocks/{namespace}/{name}.php
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return false|string
	 */
	protected function get_content_from_template( $attributes ) {
		$class_name = join( ' ', $this->get_class_names( $attributes ) );
		set_query_var( 'class_name', $class_name );
		$path   = array(
			$this->get_template_part_dir(),
			$this->name,
		);
		$output = $this->get_template_part( join( '/', $path ), $this->get_style_name( $class_name ) );

		if ( $output ) {
			return $output;
		}

		ob_start();
		load_template( __DIR__ . '/template/' . $this->name . '.php', false );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Get component style name.
	 *
	 * @param string $class_name class strings.
	 *
	 * @return string
	 */
	protected function get_style_name( $class_name ) {
		$classes = explode( ' ', $class_name );
		$styles  = array_filter(
			$classes,
			function ( $class ) {
				return strpos( $class, 'is-style-' ) !== false;
			}
		);

		if ( ! empty( $styles ) && is_array( $styles ) ) {
			$style = reset( $styles );

			return str_replace( 'is-style-', '', $style );
		}

		return '';
	}
}
