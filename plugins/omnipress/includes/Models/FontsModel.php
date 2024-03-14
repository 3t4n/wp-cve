<?php
/**
 * Patterns model class.
 *
 * @package Omnipress\Models
 */

namespace Omnipress\Models;

use Omnipress\Abstracts\ModelsBase;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Patterns model class.
 *
 * @since 1.1.0
 */
class FontsModel extends ModelsBase {

	protected $model_type = 'fonts';

	protected $formated_fonts = array();

	protected function format_raw_font( $font ) {
		if ( empty( $font->family ) ) {
			return array();
		}

		$font_weights = array();
		$font_styles  = array( 'normal' );
		$font_files   = array(
			'normal' => array(),
			'italic' => array(),
		);

		if ( ! empty( $font->variants ) && is_array( $font->variants )) {
			foreach ( $font->variants as $variant ) {
				if ( false !== strpos( $variant, 'italic' ) ) {
					$font_styles[] = 'italic';
				} else {
					$font_weights[] = 'regular' === $variant ? '400' : $variant;
				}
			}
		}

		if ( ! empty( $font->files ) && is_object( $font->files ) ) {
			foreach ( $font->files as $file_key => $file_url ) {
				$key = ( 'regular' === $file_key ) || ( 'italic' === $file_key ) ? '400' : $file_key;
				if ( false !== strpos( $file_key, 'italic' ) ) {
					$font_files['italic'][ str_replace( 'italic', '', $key ) ] = $file_url;
				} else {
					$font_files['normal'][ $key ] = $file_url;
				}
			}
		}

		return array(
			'family'  => $font->family,
			'weights' => $font_weights,
			'styles'  => array_values( array_unique( $font_styles ) ),
			'files'    => $font_files
		);
	}

	public function get() {

		if ( $this->formated_fonts ) {
			return $this->formated_fonts;
		}

		$fonts = $this->get_raw();

		if ( ! empty( $fonts ) && is_array( $fonts ) ) {
			foreach ( $fonts as $font ) {
				$font_attrs = $this->format_raw_font( $font );

				if ( ! $font_attrs ) {
					continue;
				}

				$this->formated_fonts[ $font_attrs['family'] ] = $font_attrs;
			}
		}

		return $this->formated_fonts;
	}

	public function get_raw() {
		if ( ! $this->data ) {
			$this->sync();
		}

		return $this->data;
	}

	public function get_font_attrs( $font_family ) {
		$fonts = $this->get();

		return isset( $fonts[ $font_family ] ) ? $fonts[ $font_family ] : array();
	}

	public function get_font_fileurl( $font_family, $font_weight, $font_style ) {
		$font_attrs = $this->get_font_attrs( $font_family );

		if ( empty( $font_attrs['files'] ) ) {
			return;
		}

		$font_weight = absint( $font_weight );
		$font_style  = strtolower( $font_style );

		return ! empty( $font_attrs['files'][ $font_style ][ $font_weight ] ) ? $font_attrs['files'][ $font_style ][ $font_weight ] : '';

	}
}
