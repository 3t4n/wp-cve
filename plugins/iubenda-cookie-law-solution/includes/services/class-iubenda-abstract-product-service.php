<?php
/**
 * Iubenda abstract product service.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Iubenda_Abstract_Product_Service
 */
abstract class Iubenda_Abstract_Product_Service {

	/**
	 * Get only valid values depend on accepted values
	 * and return default if not exist in accepted values.
	 *
	 * @param   mixed $value The value that will check if acceptable.
	 * @param   array $accepted_values The accepted values.
	 * @param   mixed $default_value The default value is returned if the value is not in the accepted values.
	 *
	 * @return mixed
	 */
	protected function get_only_valid_values( $value, array $accepted_values, $default_value ) {
		if ( ! in_array( $value, $accepted_values, true ) ) {
			return $default_value;
		}

		return $value;
	}


	/**
	 * Get language code keys for validate only accepted keys passed.
	 *
	 * @param   bool $append_manual  Add manual code also.
	 *
	 * @return array
	 */
	protected function get_languages_code_keys( $append_manual = true ): array {
		$array = array();
		foreach ( ( new Product_Helper() )->get_languages() as $lang_id => $lang_name ) {
			$array[] = "code_{$lang_id}";
			if ( $append_manual ) {
				$array[] = "manual_code_{$lang_id}";
			}
		}

		return $array;
	}

	/**
	 * Sanitize options
	 *
	 * @param   string|array $options           Options that is will sanitized.
	 * @param   array        $exception         Key that contains that exception will escaped from sanitize.
	 * @param   string       $using_function    Sanitize using this.
	 *
	 * @return array|string
	 */
	protected function sanitize_options( $options, array $exception = array( 'code_', 'manual_code_' ), string $using_function = 'sanitize_text_field' ) {
		foreach ( $options as $key => $value ) {
			if ( $this->iub_strpos_array( $key, $exception ) !== false ) {
				continue;
			}

			if ( is_array( $value ) ) {
				$options[ $key ] = $this->sanitize_options( $value, $exception, $using_function );
			} else {
				$options[ $key ] = $using_function( $value );
			}
		}

		return $options;
	}

	/**
	 * Str pos by array or needles.
	 *
	 * @param      string $haystack haystack.
	 * @param   array  $needles needles.
	 *
	 * @return false|mixed
	 */
	private function iub_strpos_array( $haystack, $needles = array() ): bool {
		foreach ( $needles as $needle ) {
			if ( strpos( $haystack, $needle ) !== false ) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Iubenda StripSlashes Deep
	 *
	 * @param   array|string $value  array|string.
	 *
	 * @return array|string
	 */
	protected function iub_strip_slashes_deep( $value ) {
		$value = is_array( $value ) ?
			array_map( 'stripslashes_deep', $value ) :
			stripslashes( $value );

		return $value;
	}



	/**.
	 * Update (TC / PP) button style in options
	 *
	 * @param   array $options      Options.
	 *
	 * @return array
	 */
	protected function update_button_style( $options ) {
		$new_options  = array();
		$button_style = iub_array_get( $options, 'button_style' );

		foreach ( $options as $key => $index ) {
			$new_options[ $key ] = $index;

			if ( strpos( $index, 'code_' ) !== false ) {
				$new_code            = str_replace(
					array(
						'iubenda-black',
						'iubenda-white',
					),
					"iubenda-{$button_style}",
					$index
				);
				$new_options[ $key ] = $new_code;
			}
		}

		return $new_options;
	}
}
