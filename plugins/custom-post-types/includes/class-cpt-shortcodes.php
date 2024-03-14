<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Shortcodes extends CPT_Component {
	/**
	 * @return void
	 */
	public function init_hooks() {
		add_shortcode( 'cpt-field', array( $this, 'get_post_field' ) );
		add_shortcode( 'cpt-terms', array( $this, 'get_post_terms' ) );
		add_shortcode( 'cpt-term-field', array( $this, 'get_term_field' ) );
		add_shortcode( 'cpt-option-field', array( $this, 'get_option_field' ) );
	}

	/**
	 * @param $atts
	 *
	 * @return mixed|string|null
	 */
	public function get_post_field( $atts ) {
		$a      = shortcode_atts(
			array(
				'key'           => false,
				'post-id'       => false,
				'output-filter' => true,
			),
			$atts
		);
		$errors = false;
		if ( ! $a['key'] ) {
			$errors[] = __( 'Missing field "key".', 'custom-post-types' );
		}
		if ( $errors ) {
			return current_user_can( 'edit_posts' ) ? '<pre>' . implode( '</pre><pre>', $errors ) . '</pre>' : '';
		}
		$output = cpt_get_post_meta( $a['key'], $a['post-id'], $a['output-filter'] );
		return apply_filters( 'cpt_shortcode_field_output', $output, $a );
	}

	/**
	 * @param $atts
	 *
	 * @return mixed|string|null
	 */
	public function get_post_terms( $atts ) {
		$a      = shortcode_atts(
			array(
				'key'         => false,
				'post-id'     => false,
				'output-type' => 'links',
				'separator'   => ', ',
			),
			$atts
		);
		$errors = false;
		if ( ! $a['key'] ) {
			$errors[] = __( 'Missing field "key".', 'custom-post-types' );
		}
		if ( $errors ) {
			return current_user_can( 'edit_posts' ) ? '<pre>' . implode( '</pre><pre>', $errors ) . '</pre>' : '';
		}
		$output = cpt_get_post_terms( $a['key'], $a['post-id'], $a['output-type'], $a['separator'] );
		return apply_filters( 'cpt_shortcode_terms_output', $output, $a );
	}

	/**
	 * @param $atts
	 *
	 * @return mixed|string|null
	 */
	public function get_term_field( $atts ) {
		$a      = shortcode_atts(
			array(
				'key'           => false,
				'term-id'       => false,
				'output-filter' => true,
			),
			$atts
		);
		$errors = false;
		if ( ! $a['key'] ) {
			$errors[] = __( 'Missing field "key".', 'custom-post-types' );
		}
		if ( ! $a['term-id'] ) {
			$errors[] = __( 'Missing field "term-id".', 'custom-post-types' );
		}
		if ( $errors ) {
			return current_user_can( 'edit_posts' ) ? '<pre>' . implode( '</pre><pre>', $errors ) . '</pre>' : '';
		}
		$output = cpt_get_term_meta( $a['key'], $a['term-id'], $a['output-filter'] );
		return apply_filters( 'cpt_shortcode_term_field_output', $output, $a );
	}

	/**
	 * @param $atts
	 *
	 * @return mixed|string|null
	 */
	public function get_option_field( $atts ) {
		$a      = shortcode_atts(
			array(
				'key'           => false,
				'option-id'     => false,
				'output-filter' => true,
			),
			$atts
		);
		$errors = false;
		if ( ! $a['key'] ) {
			$errors[] = __( 'Missing field "key".', 'custom-post-types' );
		}
		if ( ! $a['option-id'] ) {
			$errors[] = __( 'Missing field "option-id".', 'custom-post-types' );
		}
		if ( $errors ) {
			return current_user_can( 'edit_posts' ) ? '<pre>' . implode( '</pre><pre>', $errors ) . '</pre>' : '';
		}
		$output = cpt_get_option_meta( $a['key'], $a['option-id'], $a['output-filter'] );
		return apply_filters( 'cpt_shortcode_option_field_output', $output, $a );
	}
}
