<?php

/**
 * WP Feed Queries Class.
 *
 * @package WP Product Feed Manager/Application/Classes
 * @version 2.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPFM_Feed_Queries' ) ) :


	/**
	 * Feed Queries Class
	 */
	class WPPFM_Feed_Queries {

		public function includes_query( $query, $value ) {
			return ! ( $query[3] && strpos( strtolower( $value ), strtolower( trim( $query[3] ) ) ) !== false );
		}

		public function does_not_include_query( $query, $value ) {
			return ! ( $query[3] && strpos( strtolower( $value ), strtolower( trim( $query[3] ) ) ) === false );
		}

		public function is_equal_to_query( $query, $value ) {
			return ! ( strtolower( $value ) === strtolower( trim( $query[3] ) ) );
		}

		public function is_not_equal_to_query( $query, $value ) {
			return ! ( strtolower( $value ) !== strtolower( trim( $query[3] ) ) );
		}

		public function is_empty( $value ) {
			if ( ! is_array( $value ) ) {
				$value = trim( $value );
			}

			return ! empty( $value );
		}

		public function is_not_empty_query( $value ) {
			if ( ! is_array( $value ) ) {
				$value = trim( $value );
			}

			return empty( $value );
		}

		public function starts_with_query( $query, $value ) {
			if ( ! empty( $value ) && strrpos( strtolower( $value ), strtolower( trim( $query[3] ) ), - strlen( $value ) ) !== false ) {
				return false;
			} else {
				return true;
			}
		}

		public function does_not_start_with_query( $query, $value ) {
			if ( empty( $value ) || strrpos( strtolower( $value ), strtolower( trim( $query[3] ) ), - strlen( $value ) ) === false ) {
				return false;
			} else {
				return true;
			}
		}

		public function ends_with_query( $query, $value ) {
			$search_string = trim( $query[3] );
			$value_length  = strlen( $value );

			if ( ! empty( $value ) && ( $value_length - strlen( $search_string ) ) >= 0 && strpos( $value, $search_string, $value_length ) !== false ) {
				return false;
			} else {
				return true;
			}
		}

		public function does_not_end_with_query( $query, $value ) {
			$search_string = trim( $query[3] );
			$value_length  = strlen( $value );

			if ( ! empty( $value ) && ( $value_length - strlen( $search_string ) ) >= 0 && strpos( $value, $search_string, $value_length ) !== false ) {
				return true;
			} else {
				return false;
			}
		}

		public function is_greater_than_query( $query, $value ) {
			$data_nr      = $this->convert_to_us_notation( trim( $value ) );
			$condition_nr = $this->convert_to_us_notation( trim( $query[3] ) );

			if ( is_numeric( $data_nr ) && is_numeric( $condition_nr ) ) {
				return ! ( (float) $data_nr > (float) $condition_nr );
			} else {
				return true;
			}
		}

		public function is_greater_or_equal_to_query( $query, $value ) {
			$data_nr      = $this->convert_to_us_notation( trim( $value ) );
			$condition_nr = $this->convert_to_us_notation( trim( $query[3] ) );

			if ( is_numeric( $data_nr ) && is_numeric( trim( $condition_nr ) ) ) {
				return ! ( (float) $data_nr >= (float) $condition_nr );
			} else {
				return true;
			}
		}

		public function is_smaller_than_query( $query, $value ) {
			$data_nr      = $this->convert_to_us_notation( trim( $value ) );
			$condition_nr = $this->convert_to_us_notation( trim( $query[3] ) );

			if ( is_numeric( $data_nr ) && is_numeric( $condition_nr ) ) {
				return ! ( (float) $data_nr < (float) $condition_nr );
			} else {
				return true;
			}
		}

		public function is_smaller_or_equal_to_query( $query, $value ) {
			$data_nr      = $this->convert_to_us_notation( trim( $value ) );
			$condition_nr = $this->convert_to_us_notation( trim( $query[3] ) );

			if ( is_numeric( $data_nr ) && is_numeric( $condition_nr ) ) {
				return ! ( (float) $data_nr <= (float) $condition_nr );
			} else {
				return true;
			}
		}

		public function is_between_query( $query, $value ) {
			$data_nr           = $this->convert_to_us_notation( trim( $value ) );
			$condition_nr_low  = $this->convert_to_us_notation( trim( $query[3] ) );
			$condition_nr_high = $this->convert_to_us_notation( trim( $query[5] ) );

			if ( is_numeric( $data_nr ) && is_numeric( $condition_nr_low ) && is_numeric( $condition_nr_high ) ) {
				if ( (float) $data_nr > (float) $condition_nr_low && (float) $data_nr < (float) $condition_nr_high ) {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}

		private function convert_to_us_notation( $current_value ) {
			// @since 2.28.0 Switched to the formal wc functions to get the separator and number of decimals values.
			$decimal_sep   = wc_get_price_decimal_separator();
			$thousands_sep = wc_get_price_thousand_separator();

			if ( ! preg_match( '/[a-zA-Z]/', $current_value ) ) { // only remove the commas if the current value has no letters
				if ( $this->already_us_notation( $current_value ) ) {
					// Some values like the Weight can already be in the US notation, so don't change them.
					return $current_value;
				}

				$no_thousands_sep = str_replace( $thousands_sep, '', $current_value );

				return ',' === $decimal_sep ? str_replace( ',', '.', $no_thousands_sep ) : $no_thousands_sep;
			} else {
				return $current_value;
			}
		}

		/**
		 * Checks if a numeric value is already in the US notation.
		 *
		 * @param $value
		 * @since 2.25.0
		 *
		 * @return bool
		 */
		private function already_us_notation( $value ) {
			return strpos( $value, '.' ) && ! strpos( $value, ',' );
		}
	}


	// end of WPPFM_Feed_Queries_Class

endif;
