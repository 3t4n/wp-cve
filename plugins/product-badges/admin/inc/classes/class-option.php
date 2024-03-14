<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_Option {

	const OPTIONS_NAME = 'badge';

	/**
	 * Echo input ID
	 * 
	 * @param string $group 
	 * @param string $var 
	 * @param type|bool $array 
	 */
	public function input_id( $group, $var, $array = false ) {
		$name = self::OPTIONS_NAME . '[' . $group . '][' . $var . ']';

		if ( $array ) {
			$name .= '[]';
		}

		echo esc_attr( $name );
	}

	/**
	 * Echo input name
	 * 
	 * @param string $group 
	 * @param string $var 
	 * @param type|bool $array 
	 */
	public function input_name( $group, $var, $array = false ) {
		$name = self::OPTIONS_NAME . '[' . $group . '][' . $var . ']';

		if ( $array ) {
			$name .= '[]';
		}

		echo esc_attr( $name );
	}

	/**
	 * Echo input value
	 * 
	 * @param type $group 
	 * @param type $var 
	 */
	public function input_value( $group, $var ) {
		echo esc_attr( $this->get_input_value( $group, $var ) );
	}

	/**
	 * Get input value
	 * 
	 * @param string $group 
	 * @param string $var 
	 * @return string
	 */
	public function get_input_value( $group, $var ) {
		global $post;

		$meta_key = '_' . self::OPTIONS_NAME . '_' . $group . '_' . $var;

		return get_post_meta( $post->ID, $meta_key, true );
	}

	/**
	 * Get select field value
	 * 
	 * @param string $group 
	 * @param string $var 
	 * @return string
	 */
	public function get_select_value( $group, $var ) {
		global $post;

		$meta_key = '_' . self::OPTIONS_NAME . '_' . $group . '_' . $var;

		return maybe_unserialize( get_post_meta( $post->ID, $meta_key, true ) );
	}

	/**
	 * Echo product select options in dropdown
	 * 
	 * @param string $group 
	 * @param string $var 
	 */
	public function select_wc_product_value( $group, $var ) {
		$raw_value = $this->get_select_value( $group, $var );

		$output = '';
		if ( is_array( $raw_value ) ) {
			foreach ( $raw_value as $key => $product_id ) {
				$output .= '<option value="' . esc_attr( $product_id ) . '" selected>' . esc_attr( get_the_title( $product_id ) ) . '</option>';
			}
		}

		echo $output;
	}

	/**
	 * Echo category select options in dropdown
	 * 
	 * @param string $group 
	 * @param string $var 
	 */
	public function select_wc_product_cat_value( $group, $var ) {
		$raw_value = $this->get_select_value( $group, $var );

		$output = '';
		if ( is_array( $raw_value ) ) {
			foreach ( $raw_value as $key => $category_id ) {
				$output .= '<option value="' . esc_attr( $category_id ) . '" selected>' . esc_attr( get_term( $category_id )->name ) . '</option>';
			}
		}

		echo $output;
	}
}
