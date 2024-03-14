<?php

class ACF_Helper {

	/**
	 * Utility which alters the output based on the ACF key
	 * @param string $field_key acf field key
	 */
	public static function acf_field_type($field_key, $post_id) {

		$field = get_field_object( $field_key, $post_id );
		if ( get_field( $field_key, $post_id ) ) {
			// return different type based on different field return value
			if ( $field['type'] == 'image' ) {
				// if the field has been saved as an object, let's get it's value
				if ( $field['return_format'] == 'array' ) {
					return wp_get_attachment_image( $field['value']['id'], 'full', 1, array( 'class' => 'acf-rpw-thumb acf-img' ) );
				} elseif ( $field['return_format'] == 'url' ) {
					return '<image src="' . $field['value'] . '" class="acf-rpw-thumb acf-img" />';
				} else {
					return wp_get_attachment_image( get_field( $field_key, $post_id ), 'full', 1, array( 'class' => 'acf-rpw-thumb acf-img' ) );
				}
			} else if ( $field['type'] == 'file' ) {
				// if the field has been saved as an object, let's get it's value
				if ( $field['return_format'] == 'array' ) {
					return '<a href="' . wp_get_attachment_url( $field['value']['id'] ) . '"/>' . $field['label'] . '</a>';
				} elseif ( $field['return_format'] == 'url' ) {
					return '<a href="' . $field['value'] . '"/>' . $field['label'] . '</a>';
				} else {
					return '<a href="' . wp_get_attachment_url( get_field( $field_key, $post_id ) ) . '"/>' . $field['label'] . '</a>';
				}
			} else {
				return get_field( $field_key, $post_id );
			}
		}
	}

	/**
	 * 
	 * @param string $content
	 * @hook acp_rwp_before
	 * @hook acp_rwp_after
	 */
	public static function af_bf_content_filter($content) {
		// run these filters only if ACF is active
		if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) or is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			$content = preg_replace_callback( '/\{acf(.*?)\}/s', array( __CLASS__, 'regex_acf_filter_callback' ), $content );
			$content = preg_replace_callback( '/\[acf(.*?)\]/s', array( __CLASS__, 'regex_acf_filter_callback' ), $content );
		}
		$content = preg_replace_callback( '/\{meta(.*?)\}/s', array( __CLASS__, 'regex_filter_callback' ), $content );
		$content = preg_replace_callback( '/\[meta(.*?)\]/s', array( __CLASS__, 'regex_filter_callback' ), $content );
		return $content;
	}

	/**
	 * Regex callback function for the PHP date. Returns the output of the date function.
	 * @param ARRAY_A $matches
	 */
	public static function date_filter($content) {
		$content = preg_replace_callback( '/\{date(.*?)\}/s', array( __CLASS__, 'regex_date_filter_callback' ), $content );
		$content = preg_replace_callback( '/\[date(.*?)\]/s', array( __CLASS__, 'regex_date_filter_callback' ), $content );
		return $content;
	}

	public static function regex_date_filter_callback($matches) {
		$match = trim( $matches[1] );
		return date( 'Ymd', strtotime( $match ) );
	}

	/**
	 * Regex callback function for the ACF. Returns the corresponding ACF field value.
	 * @param ARRAY_A $matches
	 */
	public static function regex_acf_filter_callback($matches) {
		// iterate over the fields trime them and create ACF
		$match = trim( $matches[1] );
		return ACF_Helper::acf_field_type( $match, get_the_ID() );
	}

	/**
	 * Regex callback function for the meta key. Returnes the corresponding meta key value.
	 * @param ARRAY_A $matches
	 */
	public static function regex_filter_callback($matches) {
		// iterate over the fields trime them and create ACF
		$match = trim( $matches[1] );
		return get_post_meta( get_the_ID(), $match, true );
	}

}
