<?php

namespace SG_Email_Marketing\Integrations\ThirdParty;

/**
 * Field Guesser class.
 *
 * Since 1.1.0
 */
class Form_Parser {
	public static function extract_data( $data = array() ) {

		if ( empty( $data ) ) {
			return array();
		}

		$decoded_data = array();

		foreach( $data as $key => $value ) {
			if ( self::is_first_name( $key ) ) {
				$decoded_data['first_name'] = $value;
			}
			else if ( self::is_last_name( $key ) ) {
				$decoded_data['last_name'] = $value;
			}
			else if( self::is_full_name( $key ) ) {
				$decoded_data['full_name'] = $value;
			}
			else if( ( strpos( $key, 'email' ) !== false ) ) {
				$decoded_data['email'] = $value;
			}

		}

		if ( ! empty( $decoded_data['full_name'] ) && empty( $decoded_data['last_name'] ) ) {
			$full_name = explode( ' ', $decoded_data['full_name'] );
			$decoded_data['last_name'] = end( $full_name );
		}

		if ( ! empty( $decoded_data['full_name'] ) && empty( $decoded_data['first_name'] ) ) {
			$full_name = explode( ' ', $decoded_data['full_name'] );
			$decoded_data['first_name'] = reset( $full_name );
		}

		return $decoded_data;

	}

	public static function is_first_name( $key ) {
		return
		( strpos( strtolower( $key ), 'first_name' ) !== false ) ||
		( strpos( strtolower( $key ), 'firstname' ) !== false ) ||
		( strpos( strtolower( $key ), 'givenname' ) !== false ) ||
		( strpos( strtolower( $key ), 'forename' ) !== false ) ||
		( strpos( strtolower( $key ), 'fname' ) !== false ) ||
		( strpos( strtolower( $key ), 'f_name' ) !== false ) ||
		( strpos( strtolower( $key ), 'first' ) !== false );
	}

	public static function is_last_name( $key ) {
		return
		( strpos( strtolower( $key ), 'last_name' ) !== false ) ||
		( strpos( strtolower( $key ), 'lastname' ) !== false ) ||
		( strpos( strtolower( $key ), 'surname' ) !== false ) ||
		( strpos( strtolower( $key ), 'familyname' ) !== false ) ||
		( strpos( strtolower( $key ), 'family_name' ) !== false ) ||
		( strpos( strtolower( $key ), 'lname' ) !== false ) ||
		( strpos( strtolower( $key ), 'l_name' ) !== false ) ||
		( strpos( strtolower( $key ), 'last' ) !== false );
	}
	public static function is_full_name( $key ) {
		return
		( strpos( strtolower( $key ), 'name' ) !== false ) ||
		( strpos( strtolower( $key ), 'full_name' ) !== false ) ||
		( strpos( strtolower( $key ), 'fullname' ) !== false );
	}
}