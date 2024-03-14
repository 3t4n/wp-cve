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
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require 'class-mo-saml-assertion.php';

/**
 * Class for SAML2 Response messages.
 */
class Mo_SAML_Response {

	/**
	 * The assertions in this response.
	 *
	 * @var array $assertion
	 */
	private $assertions;

	/**
	 * The destination URL in this response.
	 *
	 * @var string $destination
	 */
	private $destination;

	/**
	 * The certificate in this response.
	 *
	 * @var string $certificates
	 */
	private $certificates;

	/**
	 * The signature data in this response.
	 *
	 * @var array $signature_data
	 */
	private $signature_data;

	/**
	 * Constructor for SAML 2 response messages.
	 *
	 * @param DOMElement|NULL $xml The input message.
	 */
	public function __construct( DOMElement $xml = null ) {

		$this->assertions   = array();
		$this->certificates = array();

		if ( null === $xml ) {
			return;
		}

		$sig = Mo_SAML_Utilities::mo_saml_validate_element( $xml );
		if ( false !== $sig ) {
			$this->certificates   = $sig['Certificates'];
			$this->signature_data = $sig;
		}

		/* set the destination from saml response */
		if ( $xml->hasAttribute( 'Destination' ) ) {
			$this->destination = $xml->getAttribute( 'Destination' );
		}

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- $xml is the object of PHP's predefined class
		for ( $node = $xml->firstChild; null !== $node; $node = $node->nextSibling ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- $xml is the object of PHP's predefined class
			if ( 'urn:oasis:names:tc:SAML:2.0:assertion' !== $node->namespaceURI ) {
				continue;
			}

			try {
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- $xml is the object of PHP's predefined class
				if ( 'Assertion' === $node->localName || 'EncryptedAssertion' === $node->localName ) {
					$this->assertions[] = new Mo_SAML_Assertion( $node );
				}
			} catch ( Exception $exception ) {
				wp_die( 'We could not sign you in. Please contact your administrator.', 'Missing Issuer in Assertion' );
			}
		}
	}

	/**
	 * Retrieve the assertions in this response.
	 *
	 * @return Mo_SAML_Assertion[]|SAML2_EncryptedAssertion[]
	 */
	public function mo_saml_get_assertions() {
		return $this->assertions;
	}

	/**
	 * Set the assertions that should be included in this response.
	 *
	 * @param array $assertions The assertions.
	 */
	public function mo_saml_set_assertions( array $assertions ) {
		$this->assertions = $assertions;
	}

	/**
	 * Get the destination for the message in response.
	 *
	 * @return string destination
	 */
	public function mo_saml_get_destination() {
		return $this->destination;
	}

	/**
	 * Get the certificates in response.
	 *
	 * @return string certificates
	 */
	public function mo_saml_get_certificates() {
		return $this->certificates;
	}

	/**
	 * Get the signature data in response.
	 *
	 * @return array signature_data
	 */
	public function mo_saml_get_signature_data() {
		return $this->signature_data;
	}
}
