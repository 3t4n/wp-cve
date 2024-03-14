<?php
/**
 * This file is part of miniOrange SAML plugin.
 *
 * The miniOrange SAML plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * miniOrange SAML plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with miniOrange SAML plugin.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    miniorange-saml-20-single-sign-on
 * @author     miniOrange
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'mo-saml-xmlseclibs.php';
use \RobRichards\XMLSecLibs\Mo_SAML_XML_Security_Key;
use \RobRichards\XMLSecLibs\Mo_SAML_XML_Security_DSig;
use \RobRichards\XMLSecLibs\Mo_SAML_XML_Sec_Enc;

/**
 * This class contains collections of various static functions used across the plugin.
 */
class Mo_SAML_Utilities {
	/**
	 * Generates Random ID of 21 characters.
	 *
	 * @return string
	 */
	public static function mo_saml_generate_id() {
		return '_' . self::mo_saml_string_to_hex( self::mo_saml_generate_random_bytes( 21 ) );
	}

	/**
	 * Coverts String to Hex.
	 *
	 * @param  string $bytes Contains bytes.
	 * @return string
	 */
	public static function mo_saml_string_to_hex( $bytes ) {
		$ret    = '';
		$length = strlen( $bytes );
		for ( $i = 0; $i < $length; $i++ ) {
			$ret .= sprintf( '%02x', ord( $bytes[ $i ] ) );
		}
		return $ret;
	}

	/**
	 * Generates Random Bytes.
	 *
	 * @param   int $length Length of characters generating Random Bytes.
	 * @return string
	 */
	public static function mo_saml_generate_random_bytes( $length ) {

		return openssl_random_pseudo_bytes( $length );
	}

	/**
	 * Create SAML Request.
	 *
	 * @param  string $acs_url Endpoint on the SP where the IDP will redirect to with its authentication response.
	 * @param  string $issuer An Entity ID which uniquely identities the SP.
	 * @param  bool   $force_authn It will prompt users to enter their credentials on every login request.
	 * @return string
	 */
	public static function mo_saml_create_authn_request( $acs_url, $issuer, $force_authn = 'false' ) {
		$saml_nameid_format = 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified';
		$request_xml_str    = '<?xml version="1.0" encoding="UTF-8"?>' .
						'<samlp:AuthnRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" ID="' . self::mo_saml_generate_id() .
						'" Version="2.0" IssueInstant="' . self::mo_saml_generate_time_stamp() . '"';
		if ( 'true' === $force_authn ) {
			$request_xml_str .= ' ForceAuthn="true"';
		}
		$request_xml_str .= ' ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="' . $acs_url .
						'" ><saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer .
			'</saml:Issuer><samlp:NameIDPolicy AllowCreate="true" Format="' . $saml_nameid_format . '"/></samlp:AuthnRequest>';

		$deflated_str = gzdeflate( $request_xml_str );
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Require to encode the SAML Request.
		$base64_encoded_str = base64_encode( $deflated_str );
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode -- Require when encoding string to be used in query part of URL.
		$url_encoded = urlencode( $base64_encoded_str );
		update_option( Mo_Saml_Options_Test_Configuration::SAML_REQUEST, $base64_encoded_str );

		return $url_encoded;
	}
	/**
	 * Generates time stamp.
	 *
	 * @param  mixed $instant Store current time.
	 * @return Date.
	 */
	public static function mo_saml_generate_time_stamp( $instant = null ) {
		if ( null === $instant ) {
			$instant = time();
		}
		return gmdate( 'Y-m-d\TH:i:s\Z', $instant );
	}

	/**
	 * Querying the SAML Response.
	 *
	 * @param  DOMNode $node Instance of DOMNode.
	 * @param  string  $query Contains value to be checked in the SAML Response e.g. Issuer.
	 * @return array
	 */
	public static function mo_saml_xp_query( DOMNode $node, $query ) {
		static $xp_cache = null;

		if ( $node instanceof DOMDocument ) {
			$doc = $node;
		} else {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMNode class.
			$doc = $node->ownerDocument;
		}

		if ( null === $xp_cache || ! $xp_cache->document->isSameNode( $doc ) ) {
			$xp_cache = new DOMXPath( $doc );
			$xp_cache->registerNamespace( 'soap-env', 'http://schemas.xmlsoap.org/soap/envelope/' );
			$xp_cache->registerNamespace( 'saml_protocol', 'urn:oasis:names:tc:SAML:2.0:protocol' );
			$xp_cache->registerNamespace( 'saml_assertion', 'urn:oasis:names:tc:SAML:2.0:assertion' );
			$xp_cache->registerNamespace( 'saml_metadata', 'urn:oasis:names:tc:SAML:2.0:metadata' );
			$xp_cache->registerNamespace( 'ds', 'http://www.w3.org/2000/09/xmldsig#' );
			$xp_cache->registerNamespace( 'xenc', 'http://www.w3.org/2001/04/xmlenc#' );
		}

		$results = $xp_cache->query( $query, $node );
		$ret     = array();
		for ( $i = 0; $i < $results->length; $i++ ) {
			$ret[ $i ] = $results->item( $i );
		}

		return $ret;
	}
	/**
	 * Parse the NameID.
	 *
	 * @param  DOMElement $xml Contains an Xml value.
	 * @return string
	 */
	public static function mo_saml_parse_name_id( DOMElement $xml ) {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMElement class.
		$ret = array( 'Value' => trim( $xml->textContent ) );

		foreach ( array( 'NameQualifier', 'SPNameQualifier', 'Format' ) as $attr ) {
			if ( $xml->hasAttribute( $attr ) ) {
				$ret[ $attr ] = $xml->getAttribute( $attr );
			}
		}

		return $ret;
	}
	/**
	 * Converts Date to Timestamp.
	 *
	 * @param  mixed $time Contains time value.
	 * @return string
	 */
	public static function mo_saml_xs_date_time_to_timestamp( $time ) {
		$matches = array();

		// We use a very strict regex to parse the timestamp.
		$regex = '/^(\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)T(\\d\\d):(\\d\\d):(\\d\\d)(?:\\.\\d+)?Z$/D';
		if ( preg_match( $regex, $time, $matches ) === 0 ) {
			echo sprintf( 'Invalid SAML2 timestamp passed to xsDateTimeToTimestamp: ' . esc_html( $time ) );
			exit;
		}

		// Extract the different components of the time from the  matches in the regex.
		// intval will ignore leading zeroes in the string.
		$year   = intval( $matches[1] );
		$month  = intval( $matches[2] );
		$day    = intval( $matches[3] );
		$hour   = intval( $matches[4] );
		$minute = intval( $matches[5] );
		$second = intval( $matches[6] );

		// We use gmmktime because the timestamp will always be given
		// in UTC.
		$ts = gmmktime( $hour, $minute, $second, $month, $day, $year );

		return $ts;
	}
	/**
	 * Extract strings from Assertion.
	 *
	 * @param  DOMElement $parent Instance of DOMElement.
	 * @param  string     $namespace_url Contains namespace value.
	 * @param  string     $local_name Contains AuthenticatingAuthority or Audience Value.
	 * @return array
	 */
	public static function mo_saml_extract_strings( DOMElement $parent, $namespace_url, $local_name ) {
		$ret = array();
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMElement class.	
		for ( $node = $parent->firstChild; null !== $node; $node = $node->nextSibling ) {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMElement class.
			if ( $node->namespaceURI !== $namespace_url || $node->localName !== $local_name ) {
				continue;
			}
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMElement class.
			$ret[] = trim( $node->textContent );
		}

		return $ret;
	}
	/**
	 * Validate the SAML Response.
	 *
	 * @param  DOMElement $root Instance of DOMElement.
	 * @return array|bool
	 */
	public static function mo_saml_validate_element( DOMElement $root ) {
		/* Create an XML security object. */
		$obj_xml_sec_dsig = new Mo_SAML_XML_Security_DSig();

		/* Both SAML messages and SAML assertions use the 'ID' attribute. */
		$obj_xml_sec_dsig->id_keys[] = 'ID';

		/* Locate the XMLDSig Signature element to be used. */
		$signature_element = self::mo_saml_xp_query( $root, './ds:Signature' );

		$signature_length = count( $signature_element );

		if ( 0 === $signature_length ) {
			/* We don't have a signature element to validate. */
			return false;
		} elseif ( $signature_length > 1 ) {
			echo sprintf( 'XMLSec: more than one signature element in root.' );
			exit;
		}

		$signature_element          = $signature_element[0];
		$obj_xml_sec_dsig->sig_node = $signature_element;

		try {
			/* Canonicalize the XMLDSig SignedInfo element in the message. */
			$obj_xml_sec_dsig->canonicalize_signed_info();
			/* Validate referenced xml nodes. */
			if ( ! $obj_xml_sec_dsig->validate_reference() ) {
				echo sprintf( 'XMLSec: digest validation failed' );
				exit;
			}
		} catch ( Exception $exception ) {
			wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid SAML Response' );
		}

		/* Check that $root is one of the signed nodes. */
		$root_signed = false;
		foreach ( $obj_xml_sec_dsig->get_validated_nodes() as $signed_node ) {
			if ( $signed_node->isSameNode( $root ) ) {
				$root_signed = true;
				break;
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMElement class.
			} elseif ( $root->parentNode instanceof DOMDocument && $signed_node->isSameNode( $root->ownerDocument ) ) {
				/* $root is the root element of a signed document. */
				$root_signed = true;
				break;
			}
		}

		if ( ! $root_signed ) {
			echo sprintf( 'XMLSec: The root element is not signed.' );
			exit;
		}

		/* Now we extract all available X509 certificates in the signature element. */
		$certificates = array();
		foreach ( self::mo_saml_xp_query( $signature_element, './ds:KeyInfo/ds:X509Data/ds:X509Certificate' ) as $cert_node ) {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Can not convert into Snakecase, since it is a part of DOMElement class.
			$cert_data      = trim( $cert_node->textContent );
			$cert_data      = str_replace( array( "\r", "\n", "\t", ' ' ), '', $cert_data );
			$certificates[] = $cert_data;
		}

		$ret = array(
			'Signature'    => $obj_xml_sec_dsig,
			'Certificates' => $certificates,
		);

		return $ret;
	}

	/**
	 * Validates the signature in saml response.
	 *
	 * @param  array                    $info Contains the signature Data.
	 * @param  Mo_SAML_XML_Security_Key $key Used to verify the signature.
	 * @return void
	 */
	public static function mo_saml_validate_signature( array $info, Mo_SAML_XML_Security_Key $key ) {
		$obj_xml_sec_dsig = $info['Signature'];

		$sig_method = self::mo_saml_xp_query( $obj_xml_sec_dsig->sig_node, './ds:SignedInfo/ds:SignatureMethod' );
		if ( empty( $sig_method ) ) {
			echo sprintf( 'Missing SignatureMethod element' );
			exit();
		}
		$sig_method = $sig_method[0];
		if ( ! $sig_method->hasAttribute( 'Algorithm' ) ) {
			echo sprintf( 'Missing Algorithm-attribute on SignatureMethod element.' );
			exit;
		}
		$algo = $sig_method->getAttribute( 'Algorithm' );

		if ( Mo_SAML_XML_Security_Key::RSA_SHA1 === $key->type && $algo !== $key->type ) {
			$key = self::mo_saml_cast_key( $key, $algo );
		}

		/* Check the signature. */
		if ( ! $obj_xml_sec_dsig->verify( $key ) ) {
			echo sprintf( 'Unable to validate Signature' );
			exit;
		}
	}

	/**
	 * Return new Key with required algorithm and type, if needed.
	 *
	 * @param  Mo_SAML_XML_Security_Key $key Instance of MoXMLSecurityKey.
	 * @param  string                   $algorithm Contains Algorithm.
	 * @param  string                   $type Algorithm type.
	 * @return Object
	 */
	public static function mo_saml_cast_key( Mo_SAML_XML_Security_Key $key, $algorithm, $type = 'public' ) {
		// do nothing if algorithm is already the type of the key.
		if ( $key->type === $algorithm ) {
			return $key;
		}

		$key_info = openssl_pkey_get_details( $key->key );
		if ( false === $key_info ) {
			echo sprintf( 'Unable to get key details from XMLSecurityKey.' );
			exit;
		}
		if ( ! isset( $key_info['key'] ) ) {
			echo sprintf( 'Missing key in public key details.' );
			exit;
		}

		$new_key = new Mo_SAML_XML_Security_Key( $algorithm, array( 'type' => $type ) );
		try {
			$new_key->mo_saml_load_key( $key_info['key'] );
		} catch ( Exception $exception ) {
			wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid Key' );
		}

		return $new_key;
	}

	/**
	 * Checks if the SAML Assertion is valid or not.
	 *
	 * @param  Mo_SAML_Assertion $assertion Contains SAML Assertion.
	 * @param  string            $not_before It specifies the earliest time instant at which the assertion is valid.
	 * @return void
	 */
	public static function mo_saml_verify_time_window( $assertion, $not_before ) {
		if ( null !== $not_before && $not_before > time() + 60 ) {
			Mo_SAML_Logger::mo_saml_add_log( 'Received an assertion that is valid in the future. Check clock synchronization on IdP and SP.', Mo_SAML_Logger::ERROR );
			$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR007'];
			self::mo_saml_die( $error_code );
		}

		$not_on_or_after = $assertion->mo_saml_get_not_on_or_after();
		if ( null !== $not_on_or_after && $not_on_or_after <= time() - 60 ) {
			Mo_SAML_Logger::mo_saml_add_log( 'Received an assertion that has expired. Check clock synchronization on IdP and SP.', Mo_SAML_Logger::ERROR );
			$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR008'];
			self::mo_saml_die( $error_code );
		}

		$session_not_on_or_after = $assertion->mo_saml_get_session_not_on_or_after();
		if ( null !== $session_not_on_or_after && $session_not_on_or_after <= time() - 60 ) {
			Mo_SAML_Logger::mo_saml_add_log( 'Received an assertion with a session that has expired. Check clock synchronization on IdP and SP.', Mo_SAML_Logger::ERROR );
			$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR008'];
			self::mo_saml_die( $error_code );
		}

	}
	/**
	 * Process the SAML Response.
	 *
	 * @param  string           $current_url              URL or Endpoint on the SP where the IDP will redirect to with its authentication response.
	 * @param  string           $cert_fingerprint         Conatains Certificate from the plugin.
	 * @param  string           $signature_data           Signature Node in SAML Response.
	 * @param  Mo_SAML_Response $response                 Contains SAML Response.
	 * @param  string           $cert_number              Holds Cert. Number.
	 * @param  string           $relay_state              an url where users will be redirected after successful authentication.
	 * @return string
	 */
	public static function mo_saml_process_response( $current_url, $cert_fingerprint, $signature_data, Mo_SAML_Response $response, $cert_number, $relay_state ) {
		$assertion = current( $response->mo_saml_get_assertions() );

		$not_before              = $assertion->mo_saml_get_not_before();
		$assertion_time_validity = get_option( 'mo_saml_assertion_time_validity' );

		if ( isset( $assertion_time_validity ) && 'checked' === $assertion_time_validity ) {
			self::mo_saml_verify_time_window( $assertion, $not_before );
		}

		/* Validate Response-element destination. */
		$msg_destination = $response->mo_saml_get_destination();
		if ( substr( $msg_destination, -1 ) === '/' ) {
			$msg_destination = substr( $msg_destination, 0, -1 );
		}
		if ( substr( $current_url, -1 ) === '/' ) {
			$current_url = substr( $current_url, 0, -1 );
		}

		if ( null !== $msg_destination && $msg_destination !== $current_url ) {
			Mo_SAML_Logger::mo_saml_add_log( 'Destination in response doesn\'t match the current URL. Destination is "' . esc_url( $msg_destination ) . '", current URL is "' . esc_url( $current_url ) . '".', Mo_SAML_Logger::ERROR );
			echo sprintf( 'Destination in response doesn\'t match the current URL. Destination is "' . esc_url( $msg_destination ) . '", current URL is "' . esc_url( $current_url ) . '".' );
			exit;
		}

		$response_signed = self::mo_saml_check_sign( $cert_fingerprint, $signature_data, $cert_number, $relay_state );

		/* Returning boolean $response_signed */
		return $response_signed;
	}
	/**
	 * Checks if SAML Response is signed.
	 *
	 * @param  array $cert_fingerprint certificates in the SAML Response.
	 * @param  array $signature_data Signature Node in SAML Response.
	 * @param  int   $cert_number Holds Cert. Number.
	 * @param  mixed $relay_state an url where users will be redirected after successful authentication.
	 * @throws Exception Throws unable to validate signature error.
	 * @return bool
	 */
	public static function mo_saml_check_sign( $cert_fingerprint, $signature_data, $cert_number, $relay_state ) {
		$certificates = $signature_data['Certificates'];

		if ( count( $certificates ) === 0 ) {

			$stored_certs = maybe_unserialize( get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ) );
			$pem_cert     = $stored_certs[ $cert_number ];
		} else {
			$fp_array   = array();
			$fp_array[] = $cert_fingerprint;
			$pem_cert   = self::mo_saml_find_certificate( $fp_array, $certificates, $relay_state );
			if ( false === $pem_cert ) {
				return false;
			}
		}

		$last_exception = null;

		$key = new Mo_SAML_XML_Security_Key( Mo_SAML_XML_Security_Key::RSA_SHA1, array( 'type' => 'public' ) );
		try {
			$key->mo_saml_load_key( $pem_cert );

			/*
			 * Make sure that we have a valid signature
			 */
			self::mo_saml_validate_signature( $signature_data, $key );
			return true;
		} catch ( Exception $e ) {
			$last_exception = $e;
		}

		/* We were unable to validate the signature with any of our keys. */
		if ( null !== $last_exception ) {
			throw $last_exception;
		} else {
			return false;
		}

	}


	/**
	 * Validates Issuer and audience URI.
	 *
	 * @param  Mo_SAML_Response $saml_response Contains SAML Response.
	 * @param  string           $sp_entity_id Uniquely identitify the SP.
	 * @param  string           $issuer_to_validate_against SP Issuer Value.
	 * @param  string           $relay_state an url where users will be redirected after successful authentication.
	 * @return bool
	 */
	public static function mo_saml_validate_issuer_and_audience( $saml_response, $sp_entity_id, $issuer_to_validate_against, $relay_state ) {
		$issuer    = current( $saml_response->mo_saml_get_assertions() )->mo_saml_get_issuer();
		$assertion = current( $saml_response->mo_saml_get_assertions() );
		$audiences = $assertion->mo_saml_get_valid_audiences();
		if ( strcmp( $issuer_to_validate_against, $issuer ) === 0 ) {
			if ( ! empty( $audiences ) ) {
				if ( in_array( $sp_entity_id, $audiences, true ) ) {
					return true;
				} else {

					Mo_SAML_Logger::mo_saml_add_log(
						Mo_Saml_Error_Log::mo_saml_write_message(
							'UTILITIES_INVALID_AUDIENCE_URI',
							array(
								'spEntityId' => $sp_entity_id,
								'audiences'  => $audiences,
							)
						),
						Mo_SAML_Logger::ERROR
					);
					$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR009'];
					if ( 'testValidate' === $relay_state ) {
						$error_cause   = $error_code['cause'];
						$error_message = $error_code['testConfig_msg'];
						mo_saml_display_test_config_error_page( $error_code['code'], $error_cause, $error_message );
						mo_saml_download_logs( $error_cause, $error_message );
						exit;
					} else {
						self::mo_saml_die( $error_code );
					}
				}
			}
		} else {

			Mo_SAML_Logger::mo_saml_add_log(
				Mo_Saml_Error_Log::mo_saml_write_message(
					'UTILITIES_INVALID_ISSUER',
					array(
						'issuerToValidateAgainst' => $issuer_to_validate_against,
						'issuer'                  => $issuer,
					)
				),
				Mo_SAML_Logger::ERROR
			);
			$error_code = Mo_Saml_Options_Enum_Error_Codes::$error_codes['WPSAMLERR010'];
			if ( 'testValidate' === $relay_state ) {
				$error_cause   = $error_code['cause'];
				$error_message = $error_code['testConfig_msg'];
				update_option( Mo_Saml_Sso_Constants::MO_SAML_REQUIRED_ISSUER, $issuer );
				mo_saml_display_test_config_error_page( $error_code['code'], $error_cause, $error_message );
				mo_saml_download_logs( $error_cause, $error_message );
				exit;
			} else {
					self::mo_saml_die( $error_code );
			}
		}
	}

	/**
	 * Checks if certificate is present or not.
	 *
	 * @param  array $cert_fingerprints certificates in the SAML Response.
	 * @param  array $certificates certificates configured in the plugin.
	 * @return string
	 */
	private static function mo_saml_find_certificate( array $cert_fingerprints, array $certificates ) {
		$candidates = array();
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Required to decode the encoded certificates.
			$fp = strtolower( sha1( base64_decode( $certificates[0] ) ) );
		if ( ! in_array( $fp, $cert_fingerprints, true ) ) {
			$candidates[] = $fp;
			return false;
		}

			/* We have found a matching fingerprint. */
			$pem = "-----BEGIN CERTIFICATE-----\n" .
				chunk_split( $certificates[0], 64 ) .
				"-----END CERTIFICATE-----\n";

			return $pem;

	}
	/**
	 * Santize the Certificate.
	 *
	 * @param  string $certificate Contains value of certificate.
	 * @return string
	 */
	public static function mo_saml_sanitize_certificate( $certificate ) {
		$certificate = preg_replace( "/[\r\n]+/", '', $certificate );
		$certificate = str_replace( '-', '', $certificate );
		$certificate = str_replace( 'BEGIN CERTIFICATE', '', $certificate );
		$certificate = str_replace( 'END CERTIFICATE', '', $certificate );
		$certificate = str_replace( ' ', '', $certificate );
		$certificate = chunk_split( $certificate, 64, "\r\n" );
		$certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . '-----END CERTIFICATE-----';
		return $certificate;
	}
	/**
	 * Desanitize the certificate.
	 *
	 * @param  string $certificate Contains value of certificate.
	 * @return string
	 */
	public static function mo_saml_desanitize_certificate( $certificate ) {
		$certificate = preg_replace( "/[\r\n]+/", '', $certificate );
		$certificate = str_replace( '-----BEGIN CERTIFICATE-----', '', $certificate );
		$certificate = str_replace( '-----END CERTIFICATE-----', '', $certificate );
		$certificate = str_replace( ' ', '', $certificate );
		return $certificate;
	}

	/**
	 * Makes an HTTP request to given url using post method and returns its response.
	 *
	 * @param  string $url endpoint where the HTTP request is made.
	 * @param  array  $args Request arguments.
	 * @return string
	 */
	public static function mo_saml_wp_remote_post( $url, $args = array() ) {
		$response = wp_remote_post( $url, $args );
		if ( ! is_wp_error( $response ) ) {
			return $response['body'];
		} else {
			update_option( Mo_Saml_Options_Enum::SAML_MESSAGE, __( 'Unable to connect to the Internet. Please try again.', 'miniorange-saml-20-single-sign-on' ) );
			( new self() )->mo_saml_show_error_message();
			return null;
		}
	}

	/**
	 * Makes an HTTP Request using GET method and return its response.
	 *
	 * @param  string $url Endpoint where the HTTP request is made.
	 * @param  array  $args Request arguments.
	 */
	public static function mo_saml_wp_remote_get( $url, $args = array() ) {
		$response = wp_remote_get( $url, $args );
		if ( ! is_wp_error( $response ) ) {
			return $response;
		} else {
			update_option( Mo_Saml_Options_Enum::SAML_MESSAGE, __( 'Unable to connect to the Internet. Please try again.', 'miniorange-saml-20-single-sign-on' ) );
			( new self() )->mo_saml_show_error_message();
		}
	}
	/**
	 * Responsible for showing success message.
	 *
	 * @return void
	 */
	public static function mo_saml_show_error_message() {
		remove_action( 'admin_notices', array( self::class, 'mo_saml_error_message' ) );
		add_action( 'admin_notices', array( self::class, 'mo_saml_success_message' ) );
	}
	/**
	 * Responsible for showing error message.
	 *
	 * @return void
	 */
	public static function mo_saml_show_success_message() {
		remove_action( 'admin_notices', array( self::class, 'mo_saml_success_message' ) );
		add_action( 'admin_notices', array( self::class, 'mo_saml_error_message' ) );
	}
	/**
	 * Responsible for showing success message.
	 *
	 * @return void
	 */
	public static function mo_saml_success_message() {
		$class        = 'error';
		$message      = get_option( Mo_Saml_Options_Enum::SAML_MESSAGE );
		$allowed_html = array(
			'a'    => array(
				'href'   => array(),
				'target' => array(),
			),
			'code' => array(),
		);
		echo '<div class="' . esc_html( $class ) . ' error_msg" style="display:none;"> <p>' . wp_kses( $message, $allowed_html ) . '</p></div>';
	}
	/**
	 * Responsible for showing error message.
	 *
	 * @return void
	 */
	public static function mo_saml_error_message() {
		$class        = 'updated';
		$message      = get_option( Mo_Saml_Options_Enum::SAML_MESSAGE );
		$allowed_html = array(
			'a'    => array(
				'href'   => array(),
				'target' => array(),
			),
			'code' => array(),
		);
		echo '<div class="' . esc_html( $class ) . ' success_msg" style="display:none;"> <p>' . wp_kses( $message, $allowed_html ) . '</p></div>';
	}
	/**
	 * Validate the given array.
	 *
	 * @param  array $validate_fields_array contains fields to be validated.
	 * @return boolean
	 */
	public static function mo_saml_check_empty_or_null( $validate_fields_array ) {
		foreach ( $validate_fields_array as $fields ) {
			if ( ! isset( $fields ) || empty( $fields ) ) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Block Access to WP site.
	 *
	 * @param  array $error_code contains error codes.
	 * @return void
	 */
	public static function mo_saml_die( $error_code ) {
		wp_die( 'We could not sign you in. Please contact your administrator with the following error code.<br><br>Error code: <b>' . esc_html( $error_code['code'] ) . '</b>', 'Error: ' . esc_html( $error_code['code'] ) );
	}
	/**
	 * Get the file contents.
	 *
	 * @param  string $file contains metadata file.
	 * @return string
	 */
	public static function mo_safe_file_get_contents( $file ) {
		//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler -- Required for handling runtime error during the file read operation.
		set_error_handler( 'Mo_SAML_Utilities::mo_handle_file_content_error' );
		if ( is_uploaded_file( $file ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Required for reading the file.
			$file = file_get_contents( $file );
		} else {
			$file = '';
		}
		restore_error_handler();
		return $file;
	}
	/**
	 * Checks if Curl Extension is installed or not.
	 *
	 * @return int
	 */
	public static function mo_saml_is_curl_installed() {
		if ( in_array( 'curl', get_loaded_extensions(), true ) ) {
			return 1;
		}
		return 0;
	}
	/**
	 * Checks if iconv Extension is installed or not.
	 *
	 * @return int
	 */
	public static function mo_saml_is_iconv_installed() {

		if ( in_array( 'iconv', get_loaded_extensions(), true ) ) {
			return 1;
		} else {
			return 0;
		}
	}
	/**
	 * Checks if openssl Extension is installed or not.
	 *
	 * @return int
	 */
	public static function mo_saml_is_openssl_installed() {

		if ( in_array( 'openssl', get_loaded_extensions(), true ) ) {
			return 1;
		} else {
			return 0;
		}
	}
	/**
	 * Checks if the DOM Extension is installed or not.
	 *
	 * @return int
	 */
	public static function mo_saml_is_dom_installed() {

		if ( in_array( 'dom', get_loaded_extensions(), true ) ) {
			return 1;
		} else {
			return 0;
		}
	}
	/**
	 * Returns SP Base URL.
	 *
	 * @return string
	 */
	public static function mo_saml_get_sp_base_url() {
		$sp_base_url = get_option( Mo_Saml_Options_Enum_Identity_Provider::SP_BASE_URL );

		if ( empty( $sp_base_url ) ) {
			$sp_base_url = site_url();
		}

		if ( substr( $sp_base_url, -1 ) === '/' ) {
			$sp_base_url = substr( $sp_base_url, 0, - 1 );
		}

		return $sp_base_url;
	}
	/**
	 * Returns SP Entity ID.
	 *
	 * @param  string $sp_base_url Base URL of the Plugin.
	 * @return string
	 */
	public static function mo_saml_get_sp_entity_id( $sp_base_url ) {
		$sp_entity_id = get_option( Mo_Saml_Options_Enum_Identity_Provider::SP_ENTITY_ID );

		if ( empty( $sp_entity_id ) ) {
			$sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
		}

		return $sp_entity_id;
	}
	/**
	 * Checks if the SP is configured or not.
	 *
	 * @return bool
	 */
	public static function mo_saml_is_sp_configured() {
		$saml_login_url = get_option( Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL );

		if ( empty( $saml_login_url ) ) {
			return 0;
		} else {
			return 1;
		}
	}
	/**
	 * Display run time error, which occured during the file reading.
	 *
	 * @param  string $errno contains error message.
	 * @return bool
	 */
	public static function mo_handle_file_content_error( $errno ) {
		if ( E_WARNING === $errno ) {
			update_option( 'mo_saml_message', 'Error: An error occurred while reading file content' );
			self::mo_saml_show_error_message();
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Get the URL directory path for the plugin __FILE__ passed in.
	 *
	 * @return string
	 */
	public static function mo_saml_get_plugin_dir_url() {
		return plugin_dir_url( __FILE__ );
	}
	/**
	 * Checks whether its plugin page or any other page such as feedback page.
	 *
	 * @return bool
	 */
	public static function mo_saml_is_plugin_page() {
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$server_url = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		} else {
			$server_url = '';
		}
		//phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url -- Required to parse the Server URL.
		$query_str = parse_url( $server_url, PHP_URL_QUERY );
		$query_str = is_null( $query_str ) ? '' : $query_str;
		parse_str( $query_str, $query_params );
		//phpcs:ignore WordPress.Security.NonceVerification.Missing -- NonceVerification is not required here.
		if ( ( isset( $_POST['option'] ) && ( 'mo_skip_feedback' === $_POST['option'] || 'mo_feedback' === $_POST['option'] ) ) || ! empty( $query_params['page'] ) && strpos( $query_params['page'], 'mo_saml' ) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Function to sanitize the $_POST array.
	 *
	 * @param array $array Array to sanitize.
	 * @return array Sanitized array values.
	 */
	public static function mo_saml_sanitize_post_array( $array ) {
		foreach ( $array as $key => $value ) {
			if ( 'saml_x509_certificate' === $key ) {
				$array[ $key ] = $value;
			} else {
				$array[ $key ] = sanitize_text_field( $value );
			}
		}
		return $array;
	}

}
