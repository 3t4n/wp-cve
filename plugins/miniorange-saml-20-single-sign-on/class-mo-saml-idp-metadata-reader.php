<?php
/**
 * MetadataReader File used to read the metadata.
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound -- Disabling this to define multiple classes in the same file.
require_once 'class-mo-saml-utilities.php';

/**
 * IDP Metadata Reader Class. Provides the functionality to read IDP metadata.
 *
 * @category Class
 */
class Mo_SAML_IDP_Metadata_Reader {

	/**
	 * Identity_providers
	 *
	 * @var array
	 */
	private $identity_providers;

	/**
	 * Service_providers
	 *
	 * @var array
	 */
	private $service_providers;

	/**
	 * Constructor to initialize variables
	 *
	 * @param DOMNode $xml reads metadata descriptors.
	 */
	public function __construct( DOMNode $xml = null ) {

		$this->identity_providers = array();
		$this->service_providers  = array();

		$entities_descriptor = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:EntitiesDescriptor' );

		if ( ! empty( $entities_descriptor ) ) {
			$entity_descriptors = Mo_SAML_Utilities::mo_saml_xp_query( $entities_descriptor[0], './saml_metadata:EntityDescriptor' );
		} else {
			$entity_descriptors = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:EntityDescriptor' );
		}

		foreach ( $entity_descriptors as $entity_descriptor ) {
			$idp_sso_escriptor = Mo_SAML_Utilities::mo_saml_xp_query( $entity_descriptor, './saml_metadata:IDPSSODescriptor' );

			if ( isset( $idp_sso_escriptor ) && ! empty( $idp_sso_escriptor ) ) {
				array_push( $this->identity_providers, new Mo_SAML_Identity_Providers( $entity_descriptor ) );
			}
			// TODO: add sp descriptor.
		}
	}

	/**
	 * Get the identity providers
	 *
	 * @return array
	 */
	public function mo_saml_get_identity_providers() {
		return $this->identity_providers;
	}

	/**
	 * Get the service providers
	 *
	 * @return array
	 */
	public function mo_saml_get_service_providers() {
		return $this->service_providers;
	}

}

/**
 * Identity Providers class
 *
 * @category class
 */
class Mo_SAML_Identity_Providers {

	/**
	 * Idp_name
	 *
	 * @var string $idp_name
	 */
	private $idp_name;
	/**
	 * Entity_id
	 *
	 * @var array $entity_id
	 */
	private $entity_id;
	/**
	 * Login_details
	 *
	 * @var array $login_details
	 */
	private $login_details;
	/**
	 * Logout_details
	 *
	 * @var array $logout_details
	 */
	private $logout_details;
	/**
	 * Signing_certificate
	 *
	 * @var array $signing_certificate
	 */
	private $signing_certificate;
	/**
	 * Encryption_certificate
	 *
	 * @var array $encryption_certificate
	 */
	private $encryption_certificate;
	/**
	 * Signed_request
	 *
	 * @var bool $signed_request
	 */
	private $signed_request;

	/**
	 * Constructor to initialize variables
	 *
	 * @param DOMElement $xml reads metadata.
	 * @throws Exception Missing idpssodescriptor.
	 */
	public function __construct( DOMElement $xml = null ) {

		$this->idp_name               = '';
		$this->login_details          = array();
		$this->logout_details         = array();
		$this->signing_certificate    = array();
		$this->encryption_certificate = array();

		if ( $xml->hasAttribute( 'entityID' ) ) {
			$this->entity_id = $xml->getAttribute( 'entityID' );
		}

		if ( $xml->hasAttribute( 'WantAuthnRequestsSigned' ) ) {
			$this->signed_request = $xml->getAttribute( 'WantAuthnRequestsSigned' );
		}

		$idp_sso_descriptor = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:IDPSSODescriptor' );

		if ( count( $idp_sso_descriptor ) > 1 ) {
			throw new Exception( 'More than one <IDPSSODescriptor> in <EntityDescriptor>.' );
		} elseif ( empty( $idp_sso_descriptor ) ) {
			throw new Exception( 'Missing required <IDPSSODescriptor> in <EntityDescriptor>.' );
		}
		$idp_sso_descriptor_el = $idp_sso_descriptor[0];

		$info = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:Extensions' );

		if ( $info ) {
			$this->mo_saml_parse_info( $idp_sso_descriptor_el );
		}
		$this->mo_saml_parse_sso_service( $idp_sso_descriptor_el );
		$this->mo_saml_parse_slo_service( $idp_sso_descriptor_el );
		$this->mo_saml_parsex509_certificate( $idp_sso_descriptor_el );

	}

	/**
	 * Parse Info function
	 *
	 * @param DOMElement $xml reads display name and language.
	 * @return void
	 */
	private function mo_saml_parse_info( $xml ) {
		$display_names = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './mdui:UIInfo/mdui:DisplayName' );
		foreach ( $display_names as $name ) {
			if ( $name->hasAttribute( 'xml:lang' ) && $name->getAttribute( 'xml:lang' ) === 'en' ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Ignoring this because textContent is of DOMElement type and converting it to snake_case will not provide the desired output.
				$this->idp_name = $name->textContent;
			}
		}
	}

	/**
	 * Parse SSO Service function
	 *
	 * @param DOMElement $xml reads single sign on service from metadata.
	 * @return void
	 */
	private function mo_saml_parse_sso_service( $xml ) {
		$sso_services = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:SingleSignOnService' );
		foreach ( $sso_services as $sso_service ) {
			$binding             = str_replace( 'urn:oasis:names:tc:SAML:2.0:bindings:', '', $sso_service->getAttribute( 'Binding' ) );
			$this->login_details = array_merge(
				$this->login_details,
				array( $binding => $sso_service->getAttribute( 'Location' ) )
			);
		}
	}

	/**
	 * Parse SLO Service
	 *
	 * @param DOMElement $xml reads single logout service from metadata.
	 * @return void
	 */
	private function mo_saml_parse_slo_service( $xml ) {
		$slo_services = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:SingleLogoutService' );
		foreach ( $slo_services as $slo_service ) {
			$binding              = str_replace( 'urn:oasis:names:tc:SAML:2.0:bindings:', '', $slo_service->getAttribute( 'Binding' ) );
			$this->logout_details = array_merge(
				$this->logout_details,
				array( $binding => $slo_service->getAttribute( 'Location' ) )
			);
		}
	}

	/**
	 * Parse the X509 Certificate
	 *
	 * @param DOMElement $xml parses the key descriptor.
	 * @return void
	 */
	private function mo_saml_parsex509_certificate( $xml ) {
		foreach ( Mo_SAML_Utilities::mo_saml_xp_query( $xml, './saml_metadata:KeyDescriptor' ) as $key_descriptor_node ) {
			if ( $key_descriptor_node->hasAttribute( 'use' ) ) {
				if ( $key_descriptor_node->getAttribute( 'use' ) === 'encryption' ) {
					$this->mo_saml_parse_encryption_certificate( $key_descriptor_node );
				} else {
					$this->mo_saml_parse_signing_certificate( $key_descriptor_node );
				}
			} else {
				$this->mo_saml_parse_signing_certificate( $key_descriptor_node );
			}
		}
	}

	/**
	 * Parse the Signing certificate
	 *
	 * @param DOMElement $xml reads the signing certificate.
	 * @return void
	 */
	private function mo_saml_parse_signing_certificate( $xml ) {
		$cert_node = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './ds:KeyInfo/ds:X509Data/ds:X509Certificate' );
		$cert_data = trim( $cert_node[0]->textContent );
		$cert_data = str_replace( array( "\r", "\n", "\t", ' ' ), '', $cert_data );
		if ( ! empty( $cert_node ) ) {
			array_push( $this->signing_certificate, Mo_SAML_Utilities::mo_saml_sanitize_certificate( $cert_data ) );
		}
	}

	/**
	 * Parse the Encryption Certificate
	 *
	 * @param DOMElement $xml reads the encryption certificate.
	 * @return void
	 */
	private function mo_saml_parse_encryption_certificate( $xml ) {
		$cert_node = Mo_SAML_Utilities::mo_saml_xp_query( $xml, './ds:KeyInfo/ds:X509Data/ds:X509Certificate' );
		$cert_data = trim( $cert_node[0]->textContent );
		$cert_data = str_replace( array( "\r", "\n", "\t", ' ' ), '', $cert_data );
		if ( ! empty( $cert_node ) ) {
			array_push( $this->encryption_certificate, $cert_data );
		}
	}

	/**
	 * Get IDP Name
	 *
	 * @return null
	 */
	public function mo_saml_get_idp_name() {
		return '';
	}

	/**
	 * Get Entity ID
	 *
	 * @return array
	 */
	public function mo_saml_get_entity_id() {
		return $this->entity_id;
	}

	/**
	 * Get Login URL
	 *
	 * @param string $binding states the login binding type.
	 * @return array
	 */
	public function mo_saml_get_login_url( $binding ) {
		return $this->login_details[ $binding ];
	}

	/**
	 * Get Logout URL
	 *
	 * @param string $binding states the logout binding type.
	 * @return array
	 */
	public function mo_saml_get_logout_url( $binding ) {
		return $this->logout_details[ $binding ];
	}

	/**
	 * Get Login Details
	 *
	 * @return array
	 */
	public function mo_saml_get_login_details() {
		return $this->login_details;
	}

	/**
	 * Get Logout Details
	 *
	 * @return array
	 */
	public function mo_saml_get_logout_details() {
		return $this->logout_details;
	}

	/**
	 * Get Signing Certificate
	 *
	 * @return array
	 */
	public function mo_saml_get_signing_certificate() {
		return $this->signing_certificate;
	}

	/**
	 * Get Encryption Certificate
	 *
	 * @return array
	 */
	public function mo_saml_get_encryption_certificate() {
		return $this->encryption_certificate[0];
	}

	/**
	 * Checks if Request is Signed
	 *
	 * @return boolean
	 */
	public function mo_saml_is_request_signed() {
		return $this->signed_request;
	}

}

