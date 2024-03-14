<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Internals;

use Glossary\Engine;

/**
 * Shortcodes of this plugin
 */
class Shortcode extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		\add_shortcode( 'glossary-terms', array( $this, 'terms' ) );
		\add_shortcode( 'glossary-cats', array( $this, 'cats' ) );
		\add_shortcode( 'glossary-categories', array( $this, 'cats' ) );

		return true;
	}

	/**
	 * Remap old shortcode proprierty on new
	 *
	 * @param array  $atts    An array with all the parameters.
	 * @param array  $attributes An array with all the new parameters.
	 * @param string $old_key The old parameter.
	 * @param string $new_key The new parameter.
	 * @param bool   $revert  Revert the bool parameter.
	 * @return array
	 */
	public function remap_old_proprierty(
		array $atts,
		array $attributes,
		string $old_key,
		string $new_key,
		bool $revert = false
	) {
		if ( isset( $atts[ $old_key ] ) ) {
			$attributes[ $new_key ] = $atts[ $old_key ];

			if ( $revert ) {
				$attributes[ $new_key ] = 'true';

				if ( $atts[ $old_key ] === 'true' ) {
					$attributes[ $new_key ] = 'false';
				}
			}
		}

		return $attributes;
	}

	/**
	 * Shortcode for generate list of glossary terms
	 *
	 * @param array|string $atts An array with all the parameters.
	 * @since 1.1.0
	 * @return string
	 */
	public function terms( $atts ) {
		$attributes = array(
			'order'    => 'asc',
			'num'      => '100',
			'taxonomy' => '',
			'theme'    => '',
		);

		if ( \is_array( $atts ) ) {
			$attributes = \shortcode_atts( $attributes, $atts );
			$attributes = $this->remap_old_proprierty( $atts, $attributes, 'tax', 'taxonomy' );
		}

		$key  = 'glossary_terms_list-' . \get_locale() . '-' . \md5( (string) \wp_json_encode( $attributes ) );
		$html = \get_transient( $key );

		if ( false === $html || empty( $html ) ) {
			$html = \get_glossary_terms_list( $attributes[ 'order' ], $attributes[ 'num' ], $attributes[ 'taxonomy' ], $attributes[ 'theme' ] );
			\set_transient( $key, $html, DAY_IN_SECONDS );
		}

		return \strval( $html );
	}

	/**
	 * Shortcode for generate list of glossary cat
	 *
	 * @param array|string $atts An array with all the parameters.
	 * @since 1.1.0
	 * @return string
	 */
	public function cats( $atts ) {
		$attributes = array(
			'order' => 'ASC',
			'num'   => '100',
			'theme' => '',
			);

		if ( \is_array( $atts ) ) {
			$attributes = \shortcode_atts( $attributes, $atts );
		}

		return \get_glossary_cats_list( $attributes[ 'order' ], $attributes[ 'num' ], $attributes[ 'theme' ] );
	}

}
