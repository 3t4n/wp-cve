<?php


class LPageryUtils {

	public static function lpagery_extract_post_settings( $post_settings ): array {
		$parentId   = $post_settings["parent"];
		$categories = $post_settings["categories"];
		$tags       = $post_settings["tags"];
		$slug       = $post_settings["slug"];
		$status     = $post_settings["status"];

		return array( $parentId, $categories, $tags, $slug, $status );
	}

	public static function lpagery_addslashes_to_strings_only( $value ) {
		return \is_string( $value ) ? \addslashes( $value ) : $value;
	}

	public static function lpagery_recursively_slash_strings( $value ) {
		return \map_deep( $value, [ self::class, 'lpagery_addslashes_to_strings_only' ] );
	}

	public static function lpagery_get_default_filtered_meta_names() {
		return [
			'_edit_lock',
			'_edit_last',
			'_dp_original',
			'_dp_is_rewrite_republish_copy',
			'_dp_has_rewrite_republish_copy',
			'_dp_has_been_republished',
			'_dp_creation_date_gmt',
		];
	}

	public static function lpagery_sanitize_object($input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		// Loop through the input and sanitize each of the values
		foreach ( $input as $key => $val ) {

			$input_value = $input[ $key ];
			if ( ( isset( $input_value ) ) ) {
				if(is_array($input_value)) {
					$new_input[ $key ]  = array_map( 'sanitize_text_field', $input_value );
				} else {
					$new_input[ $key ] = sanitize_text_field( $val );
				}
			} else {
				$new_input[ $key ] = '';
			}

		}

		return $new_input;

	}

}