<?php
namespace FlexMLS\Admin;

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

class Formatter {

	static function build_address_url( $record, $params = array(), $type = 'fmc_tag' ){
		$address = self::format_listing_street_address( $record );
		$return = $address[ 0 ] . '-' . $address[ 1 ] . '-mls_' . $record[ 'StandardFields' ][ 'ListingId' ];
		//$return = preg_replace( '/[^\w]/', '-', $return );
		$return = sanitize_title_with_dashes( $return );
		while( preg_match( '/\-\-/', $return ) ){
			$return = preg_replace( '/\-\-/', '-', $return );
		}
		$return = preg_replace( '/^\-/', '', $return );
		$return = preg_replace( '/\-$/', '', $return );

		$fmc_settings = get_option( 'fmc_settings' );
		if( get_option( 'permalink_structure' ) ){
			$permabase = $fmc_settings[ 'permabase' ];
			$url = '';

			if( 'fmc_vow_tag' == $type ){
				$url = home_url( 'portal/' . $return );
			} else {
				$url = home_url( $permabase . '/' . $return );
			}

			return add_query_arg( $params, $url );
		} else {
			$destlink = $fmc_settings[ 'destlink' ];
			$params[ 'page_id' ] = $destlink;
			$params[ 'address' ] = $return;
			return add_query_arg( $params, home_url() );
		}
	}

	static function build_url( $params = array( 'path' => null, 'params' => array(), 'type' => 'fmc_tag' ) ){
		if( !isset( $params[ 'type' ] ) ){
			$params[ 'type' ] = 'fmc_tag';
		}
		if( !isset( $params[ 'params' ] ) ){
			$params[ 'params' ] = array();
		}
		if( !isset( $params[ 'path' ] ) ){
			$params[ 'path' ] = null;
		}
		$fmc_settings = get_option( 'fmc_settings' );
		if( get_option( 'permalink_structure' ) ){
			$permabase = $fmc_settings[ 'permabase' ];
			$url = home_url();
			if( !array_key_exists( 'url', $params[ 'params' ] ) ){
				$url = home_url( $permabase );
			}
			if( !empty( $params[ 'path' ] ) ){
				$url .= '/' . ltrim( $params[ 'path' ], '/' );
			}
			return add_query_arg( $params[ 'params' ], $url );
		} else {
			$destlink = $fmc_settings[ 'destlink' ];
			$params[ 'params' ][ 'page_id' ] = $destlink;
			if( !array_key_exists( 'url', $params[ 'params' ] ) ){
				$params[ 'params' ][ $params[ 'type' ] ] = $params[ 'path' ];
			}
			return add_query_arg( $params[ 'params' ], home_url() );
		}
	}

	static function clean_comma_list( $var ){
		$return = '';
		if( false !== strpos( $var, ',' ) ){
			// $var contains a comma so break it apart into a list...
			$list = explode( ',', $var );
			$list = array_map( 'sanitize_text_field', $list );
			$return = implode( ',', $list );
		} else {
			$return = sanitize_text_field( $var );
		}
		return $return;
	}

	static function format_listing_street_address( $record ){
		$first_line_address = self::is_not_blank_or_restricted( $record[ 'StandardFields' ][ 'UnparsedFirstLineAddress' ] ) ? sanitize_text_field( $record[ 'StandardFields' ][ 'UnparsedFirstLineAddress' ] ) : '';
		$second_line_address = array();

		if( self::is_not_blank_or_restricted( $record[ 'StandardFields' ][ 'City' ] ) ){
			$second_line_address[] = sanitize_text_field( $record[ 'StandardFields' ][ 'City' ] );
		}
		if( self::is_not_blank_or_restricted( $record[ 'StandardFields' ][ 'StateOrProvince' ] ) ){
			$second_line_address[] = sanitize_text_field( $record[ 'StandardFields' ][ 'StateOrProvince' ] );
		}
		$second_line_address = implode( ', ', $second_line_address );
		$second_line_address = array( $second_line_address );
		if( self::is_not_blank_or_restricted( $record[ 'StandardFields' ][ 'StateOrProvince' ] ) ){
			$second_line_address[] = sanitize_text_field( $record[ 'StandardFields' ][ 'PostalCode' ] );
		}
		$second_line_address = implode( ' ', $second_line_address );

		// Needed if we check it in Formatter::is_not_blank_or_restricted?
		//$second_line_address = str_replace("********", "", $second_line_address);

		$one_line_address = array();
		if( !empty( $first_line_address ) ){
			$one_line_address[] = $first_line_address;
		}
		if( !empty( $second_line_address ) ){
			$one_line_address[] = $second_line_address;
		}
		$one_line_address = implode( ', ', $one_line_address );
		return array( $first_line_address, $second_line_address, $one_line_address );
	}

	static function format_value_for_api( $value, $type ){
		$formatted_value = null;
		switch( $type ){
			case 'Character':
				$formatted_value = (string) "'" . addslashes( trim( trim($value) ,"'") ) ."'";
				break;
			case 'Integer':
				$formatted_value = (int) $value;
				break;
			case 'Decimal':
				$formatted_value = number_format( $value, 2, '.', '' );
				break;
			case 'Date':
			case 'Datetime':
				$formatted_value = trim( $value );
				break;
			default:
		}
		return $formatted_value;
	}

	static function gentle_price_rounding( $price ){
		$price = preg_replace( '/[^0-9\.]/', '', $price );
		if( empty( $price ) ){
			return;
		}
		if( strpos( $price, '.' ) ){
			$price_pieces = explode( '.', $price );
			if( '00' != $price_pieces[ 1 ] ){
				return number_format( $price, 2 );
			}
		}
		return number_format( $price, 0 );
	}

	static function is_not_blank_or_restricted( $val ){
		$result = true;
		if( !is_array( $val ) ){
			$val = sanitize_text_field( $val );
			if( empty( $val ) || false !== strpos( $val, '********' ) ){
				return false;
			}
		} else {
			foreach ( $val as $v ){
				if( !self::is_not_blank_or_restricted( $v ) ){
					$result = false;
				}
			}
		}
		return $result;
	}

	static function parse_cache_time( $time_value = 900 ){
		$tag = preg_replace( '/[^a-z]/', '', strtolower( $time_value ) );
		$time = preg_replace( '/[^0-9]/', '', $time_value );
		if( empty( $time ) ){
			$time = 15 * MINUTE_IN_SECONDS;
		}
		switch( $tag ){
			case 'w':
				$time = $time * WEEK_IN_SECONDS;
				break;
			case 'd':
				$time = $time * DAY_IN_SECONDS;
				break;
			case 'h':
				$time = $time * HOUR_IN_SECONDS;
				break;
			case 'm':
				$time = $time * MINUTE_IN_SECONDS;
				break;
		}
		return $time;
	}

	static function parse_location_search_string( $location ){
		$locations = array();
		if( !empty( $location ) ){
			if( false !== strpos( $location, '|' ) ){
				$locations = explode( '|', $location );
			} else {
				$locations[] = $location;
			}
		}
		$return = array();
		foreach( $locations as $loc ){
			list( $loc_name, $loc_value ) = explode( '=', $loc, 2 );
			list( $loc_value, $loc_display ) = explode( '&', $loc_value );
			$loc_value_nice = preg_replace( '/^\'(.*)\'$/', "$1", $loc_value );
			if( empty( $loc_value_nice ) ){
				$loc_value_nice = $loc_value;
			}
			$loc_value_nice = ltrim( $loc_value_nice, '=' );
			$return[] = array(
				'r' => $loc,
				'f' => $loc_name,
				'v' => $loc_value_nice,
				'l' => $loc_display
			);
		}
		return $return;
	}

	static function wp_mail_content_type(){
		return 'text/html';
	}

}