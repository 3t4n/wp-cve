<?php
/**
 * File mo-saml-xmlseclibs.php
 *
 * Copyright (c) 2007-2020, Robert Richards <rrichards@cdatazone.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Robert Richards nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author    Robert Richards <rrichards@cdatazone.org>
 * @copyright 2007-2020 Robert Richards <rrichards@cdatazone.org>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @package miniorange-saml-20-single-sign-on\includes\lib\SAML2Core
 */

namespace RobRichards\XMLSecLibs;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Exception;
use RobRichards\XMLSecLibs\Utils\Mo_SAML_XPath as Mo_SAML_XPath;
/**
 * Encrypt the XML data.
 */
class Mo_SAML_XML_Sec_Enc {

	const TEMPLATE = "<xenc:EncryptedData xmlns:xenc='http://www.w3.org/2001/04/xmlenc#'>
   <xenc:CipherData>
      <xenc:CipherValue></xenc:CipherValue>
   </xenc:CipherData>
</xenc:EncryptedData>";

	const ELEMENT  = 'http://www.w3.org/2001/04/xmlenc#Element';
	const CONTENT  = 'http://www.w3.org/2001/04/xmlenc#Content';
	const URI      = 3;
	const XMLENCNS = 'http://www.w3.org/2001/04/xmlenc#';

	/**
	 * Encryption doc.
	 *
	 * @var null|DOMDocument
	 */
	private $encdoc = null;


	/**
	 * Stores raw node.
	 *
	 * @var null|DOMNode
	 */
	private $raw_node = null;

	/**
	 * Stores type of node .
	 *
	 * @var null|string
	 */
	public $type = null;

	/**
	 * Encryption key.
	 *
	 * @var undefined
	 */
	public $enc_key = null;

	/**
	 * Array of references.
	 *
	 * @var array
	 */
	private $references = array();
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		$this->mo_saml_reset_template();
	}
	/**
	 * Reset the template.
	 *
	 * @return void
	 */
	private function mo_saml_reset_template() {
		$this->encdoc = new DOMDocument();
		$this->encdoc->loadXML( self::TEMPLATE );
	}

	/**
	 * Add reference to node.
	 *
	 * @param string  $name Name of the Node.
	 * @param DOMNode $node Node DOM Document.
	 * @param string  $type Type of the Node.
	 * @throws Exception Throws expections if the type of node is not DOMNode.
	 */
	public function mo_saml_add_reference( $name, $node, $type ) {
		if ( ! $node instanceof DOMNode ) {
			throw new Exception( '$node is not of type DOMNode' );
		}
		$curencdoc = $this->encdoc;
		$this->mo_saml_reset_template();
		$encdoc       = $this->encdoc;
		$this->encdoc = $curencdoc;
		$refuri       = Mo_SAML_XML_Security_DSig::generate_guid();
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMDocument Attributes.
		$element = $encdoc->documentElement;
		$element->setAttribute( 'Id', $refuri );
		$this->references[ $name ] = array(
			'node'    => $node,
			'type'    => $type,
			'encnode' => $encdoc,
			'refuri'  => $refuri,
		);
	}

	/**
	 * Funtion to set the raw node.
	 *
	 * @param DOMNode $node Node DOM Document.
	 */
	public function mo_saml_set_node( $node ) {
		$this->raw_node = $node;
	}

	/**
	 * Encrypt the selected node with the given key.
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key  The encryption key and algorithm.
	 * @param bool                     $replace Whether the encrypted node should be replaced in the original tree. Default is true.
	 * @return DOMElement  The <xenc:EncryptedData>-element.
	 * @throws Exception Throws expections if the node to be encrypted is not set or the key is not valid.
	 */
	public function mo_saml_encrypt_node( $obj_key, $replace = true ) {
		$data = '';
		if ( empty( $this->raw_node ) ) {
			throw new Exception( 'Node to encrypt has not been set' );
		}
		if ( ! $obj_key instanceof Mo_SAML_XML_Security_Key ) {
			throw new Exception( 'Invalid Key' );
		}
		$doc          = $this->raw_node->ownerDocument;
		$x_path       = new DOMXPath( $this->encdoc );
		$obj_list     = $x_path->query( '/xenc:EncryptedData/xenc:CipherData/xenc:CipherValue' );
		$cipher_value = $obj_list->item( 0 );
		if ( null === $cipher_value ) {
			throw new Exception( 'Error locating CipherValue element within template' );
		}
		switch ( $this->type ) {
			case ( self::ELEMENT ):
				$data = $doc->saveXML( $this->raw_node );
				$this->encdoc->documentElement->setAttribute( 'Type', self::ELEMENT );
				break;
			case ( self::CONTENT ):
				$children = $this->raw_node->childNodes;
				foreach ( $children as $child ) {
					$data .= $doc->saveXML( $child );
				}
				$this->encdoc->documentElement->setAttribute( 'Type', self::CONTENT );
				break;
			default:
				throw new Exception( 'Type is currently not supported' );
		}

		$enc_method = $this->encdoc->documentElement->appendChild( $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:EncryptionMethod' ) );
		$enc_method->setAttribute( 'Algorithm', $obj_key->mo_saml_get_algorithm() );
        // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMElement Attributes.
		$cipher_value->parentNode->parentNode->insertBefore( $enc_method, $cipher_value->parentNode->parentNode->firstChild );
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Working on the object key which is supposed to be encoded.
		$str_encrypt = base64_encode( $obj_key->mo_saml_encrypt_data( $data ) );
		$value       = $this->encdoc->createTextNode( $str_encrypt );
		$cipher_value->appendChild( $value );

		if ( $replace ) {
			switch ( $this->type ) {
				case ( self::ELEMENT ):
					if ( XML_DOCUMENT_NODE === $this->raw_node->nodeType ) {
						return $this->encdoc;
					}
					$import_enc = $this->raw_node->ownerDocument->importNode( $this->encdoc->documentElement, true );
					$this->raw_node->parentNode->replaceChild( $import_enc, $this->raw_node );
					return $import_enc;
				case ( self::CONTENT ):
					$import_enc = $this->raw_node->ownerDocument->importNode( $this->encdoc->documentElement, true );
					while ( $this->raw_node->firstChild ) {
						$this->raw_node->removeChild( $this->raw_node->firstChild );
					}
					$this->raw_node->appendChild( $import_enc );
					return $import_enc;
			}
		} else {
			return $this->encdoc->documentElement;
		}
	}

	/**
	 * Encrypts the references.
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key instance of MOXMLSecurityKey.
	 * @throws Exception $e exection.
	 */
	public function mo_saml_encrypt_references( $obj_key ) {
		$cur_raw_node = $this->raw_node;
		$cur_type     = $this->type;
		foreach ( $this->references as $name => $reference ) {
			$this->encdoc   = $reference['encnode'];
			$this->raw_node = $reference['node'];
			$this->type     = $reference['type'];
			try {
				$enc_node                             = $this->mo_saml_encrypt_node( $obj_key );
				$this->references[ $name ]['encnode'] = $enc_node;
			} catch ( Exception $e ) {
				$this->raw_node = $cur_raw_node;
				$this->type     = $cur_type;
				throw $e;
			}
		}
		$this->raw_node = $cur_raw_node;
		$this->type     = $cur_type;
	}

	/**
	 * Retrieve the CipherValue text from this encrypted node.
	 *
	 * @return string|null  The Ciphervalue text, or null if no CipherValue is found.
	 * @throws Exception Throws expections if the node is not set.
	 */
	public function mo_saml_get_cipher_value() {
		if ( empty( $this->raw_node ) ) {
			throw new Exception( 'Node to decrypt has not been set' );
		}

		$doc    = $this->raw_node->ownerDocument;
		$x_path = new DOMXPath( $doc );
		$x_path->registerNamespace( 'xmlencr', self::XMLENCNS );
		/* Only handles embedded content right now and not a reference */
		$query   = './xmlencr:CipherData/xmlencr:CipherValue';
		$nodeset = $x_path->query( $query, $this->raw_node );
		$node    = $nodeset->item( 0 );

		if ( ! $node ) {
			return null;
		}
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Working with PHP DOMElement Attributes.
		return base64_decode( $node->nodeValue );
	}

	/**
	 * Decrypt this encrypted node.
	 *
	 * The behaviour of this function depends on the value of $replace.
	 * If $replace is false, we will return the decrypted data as a string.
	 * If $replace is true, we will insert the decrypted element(s) into the
	 * document, and return the decrypted element(s).
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key  The decryption key that should be used when decrypting the node.
	 * @param boolean                  $replace Whether we should replace the encrypted node in the XML document with the decrypted data. The default is true.
	 * @throws Exception Throws expections if encrypted data is not located.
	 * @return string|DOMElement  The decrypted data.
	 */
	public function mo_saml_decrypt_node( $obj_key, $replace = true ) {
		if ( ! $obj_key instanceof Mo_SAML_XML_Security_Key ) {
			throw new Exception( 'Invalid Key' );
		}

		$encrypted_data = $this->mo_saml_get_cipher_value();
		if ( $encrypted_data ) {
			$decrypted = $obj_key->mo_saml_decrypt_data( $encrypted_data );
			if ( $replace ) {
				switch ( $this->type ) {
					case ( self::ELEMENT ):
						$newdoc = new DOMDocument();
						$newdoc->loadXML( $decrypted );
						if ( XML_DOCUMENT_NODE === $this->raw_node->nodeType ) {
							return $newdoc;
						}
						// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMDocument Attributes.
						$import_enc = $this->raw_node->ownerDocument->importNode( $newdoc->documentElement, true );
						$this->raw_node->parentNode->replaceChild( $import_enc, $this->raw_node );
						return $import_enc;
					case ( self::CONTENT ):
						if ( XML_DOCUMENT_NODE === $this->raw_node->nodeType ) {
							$doc = $this->raw_node;
						} else {
							$doc = $this->raw_node->ownerDocument;
						}
						$new_frag = $doc->createDocumentFragment();
						$new_frag->appendXML( $decrypted );
						$parent = $this->raw_node->parentNode;
						$parent->replaceChild( $new_frag, $this->raw_node );
						return $parent;
					default:
						return $decrypted;
				}
			} else {
				return $decrypted;
			}
		} else {
			throw new Exception( 'Cannot locate encrypted data' );
		}
	}

	/**
	 * Encrypt the XMLSecurityKey
	 *
	 * @param Mo_SAML_XML_Security_Key $src_key source key.
	 * @param Mo_SAML_XML_Security_Key $raw_key raw key.
	 * @param bool                     $append append.
	 * @throws Exception Throws expections if the key is invalid.
	 */
	public function mo_saml_encrypt_key( $src_key, $raw_key, $append = true ) {
		if ( ( ! $src_key instanceof Mo_SAML_XML_Security_Key ) || ( ! $raw_key instanceof Mo_SAML_XML_Security_Key ) ) {
			throw new Exception( 'Invalid Key' );
		}
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Working on the source key which is supposed to be encoded.
		$str_enc_key = base64_encode( $src_key->mo_saml_encrypt_data( $raw_key->key ) );
		$root        = $this->encdoc->documentElement;
		$enc_key     = $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:EncryptedKey' );
		if ( $append ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMDocument Attributes.
			$key_info = $root->insertBefore( $this->encdoc->createElementNS( 'http://www.w3.org/2000/09/xmldsig#', 'dsig:KeyInfo' ), $root->firstChild );
			$key_info->appendChild( $enc_key );
		} else {
			$this->enc_key = $enc_key;
		}
		$enc_method = $enc_key->appendChild( $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:EncryptionMethod' ) );
		$enc_method->setAttribute( 'Algorithm', $src_key->mo_saml_get_algorith() );
		if ( ! empty( $src_key->name ) ) {
			$key_info = $enc_key->appendChild( $this->encdoc->createElementNS( 'http://www.w3.org/2000/09/xmldsig#', 'dsig:KeyInfo' ) );
			$key_info->appendChild( $this->encdoc->createElementNS( 'http://www.w3.org/2000/09/xmldsig#', 'dsig:KeyName', $src_key->name ) );
		}
		$cipher_data = $enc_key->appendChild( $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:CipherData' ) );
		$cipher_data->appendChild( $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:CipherValue', $str_enc_key ) );
		if ( is_array( $this->references ) && count( $this->references ) > 0 ) {
			$ref_list = $enc_key->appendChild( $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:ReferenceList' ) );
			foreach ( $this->references as $name => $reference ) {
				$refuri   = $reference['refuri'];
				$data_ref = $ref_list->appendChild( $this->encdoc->createElementNS( self::XMLENCNS, 'xenc:DataReference' ) );
				$data_ref->setAttribute( 'URI', '#' . $refuri );
			}
		}
	}

	/**
	 * Decrypt the XMLSecurityKey.
	 *
	 * @param Mo_SAML_XML_Security_Key $enc_key Encryption key.
	 * @return DOMElement|string
	 * @throws Exception Throws expections if key is not encrypted and if key has some missing data.
	 */
	public function mo_saml_decrypt_key( $enc_key ) {
		if ( ! $enc_key->is_encrypted ) {
			throw new Exception( 'Key is not Encrypted' );
		}
		if ( empty( $enc_key->key ) ) {
			throw new Exception( 'Key is missing data to perform the decryption' );
		}
		return $this->mo_saml_decrypt_node( $enc_key, false );
	}

	/**
	 * Locate the encrypted data.
	 *
	 * @param DOMDocument $element element.
	 * @return DOMNode|null
	 */
	public function mo_saml_locate_encrypted_data( $element ) {
		if ( $element instanceof DOMDocument ) {
			$doc = $element;
		} else {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMDocument Attributes.
			$doc = $element->ownerDocument;
		}
		if ( $doc ) {
			$xpath   = new DOMXPath( $doc );
			$query   = "//*[local-name()='EncryptedData' and namespace-uri()='" . self::XMLENCNS . "']";
			$nodeset = $xpath->query( $query );
			return $nodeset->item( 0 );
		}
		return null;
	}

	/**
	 * Returns the key from the DOM.
	 *
	 * @param null|DOMNode $node Node DOM Document.
	 * @return null|Mo_SAML_XML_Security_Key
	 */
	public function mo_saml_locate_key( $node = null ) {
		if ( empty( $node ) ) {
			$node = $this->raw_node;
		}
		if ( ! $node instanceof DOMNode ) {
			return null;
		}
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMDocument Attributes.
		$doc = $node->ownerDocument;
		if ( $doc ) {
			$xpath = new DOMXPath( $doc );
			$xpath->registerNamespace( 'xmlsecenc', self::XMLENCNS );
			$query   = './/xmlsecenc:EncryptionMethod';
			$nodeset = $xpath->query( $query, $node );
			$encmeth = $nodeset->item( 0 );
			if ( $encmeth ) {
				$attr_algorithm = $encmeth->getAttribute( 'Algorithm' );
				try {
					$obj_key = new Mo_SAML_XML_Security_Key( $attr_algorithm, array( 'type' => 'private' ) );
				} catch ( Exception $e ) {
					return null;
				}
				return $obj_key;
			}
		}
		return null;
	}

	/**
	 * Static locatation of key.
	 *
	 * @param null|Mo_SAML_XML_Security_Key $obj_base_key object base key.
	 * @param null|DOMNode                  $node Node DOM Document.
	 * @return null|Mo_SAML_XML_Security_Key intance of the MOXMLSecurityKey.
	 * @throws Exception Throws expections if the encrypted key is not located by ID.
	 */
	public static function mo_saml_staticlocate_key_info( $obj_base_key = null, $node = null ) {
		if ( empty( $node ) || ( ! $node instanceof DOMNode ) ) {
			return null;
		}
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase  -- Working with PHP DOMDocument Attributes.
		$doc = $node->ownerDocument;
		if ( ! $doc ) {
			return null;
		}

		$xpath = new DOMXPath( $doc );
		$xpath->registerNamespace( 'xmlsecenc', self::XMLENCNS );
		$xpath->registerNamespace( 'xmlsecdsig', Mo_SAML_XML_Security_DSig::XMLDSIGNS );
		$query   = './xmlsecdsig:KeyInfo';
		$nodeset = $xpath->query( $query, $node );
		$encmeth = $nodeset->item( 0 );
		if ( ! $encmeth ) {
			/* No KeyInfo in EncryptedData / EncryptedKey. */
			return $obj_base_key;
		}
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMElement Attrbutes.
		foreach ( $encmeth->childNodes as $child ) {
			try {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMElement Attributes.
				switch ( $child->localName ) {
					case 'KeyName':
						if ( ! empty( $obj_base_key ) ) {
							// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMElement Attributes.
							$obj_base_key->name = $child->nodeValue;
						}
						break;
					case 'KeyValue':
						// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMElement Attributes.
						foreach ( $child->childNodes as $keyval ) {
							// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with PHP DOMElement Attributes.
							switch ( $keyval->localName ) {
								case 'DSAKeyValue':
									throw new Exception( 'DSAKeyValue currently not supported' );
								case 'RSAKeyValue':
									$modulus      = null;
									$exponent     = null;
									$modulus_node = $keyval->getElementsByTagName( 'Modulus' )->item( 0 );
									if ( $modulus_node ) {
										// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Working with PHP DOMElement Attributes.
										$modulus = base64_decode( $modulus_node->nodeValue );
									}
									$exponent_node = $keyval->getElementsByTagName( 'Exponent' )->item( 0 );
									if ( $exponent_node ) {
										// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Working with PHP DOMElement Attributes.
										$exponent = base64_decode( $exponent_node->nodeValue );
									}
									if ( empty( $modulus ) || empty( $exponent ) ) {
										throw new Exception( 'Missing Modulus or Exponent' );
									}
									$public_key = Mo_SAML_XML_Security_Key::mo_saml_convert_rsa( $modulus, $exponent );
									$obj_base_key->mo_saml_load_key( $public_key );
									break;
							}
						}
						break;
					case 'RetrievalMethod':
						$type = $child->getAttribute( 'Type' );
						if ( 'http://www.w3.org/2001/04/xmlenc#EncryptedKey' !== $type ) {
							/* Unsupported key type. */
							break;
						}
						$uri = $child->getAttribute( 'URI' );
						if ( '#' !== $uri[0] ) {
							/* URI not a reference - unsupported. */
							break;
						}
						$id = substr( $uri, 1 );

						$query       = '//xmlsecenc:EncryptedKey[@Id="' . Mo_SAML_XPath::mo_saml_filter_attr_value( $id, Mo_SAML_XPath::DOUBLE_QUOTE ) . '"]';
						$key_element = $xpath->query( $query )->item( 0 );
						if ( ! $key_element ) {
							throw new Exception( "Unable to locate EncryptedKey with @Id='$id'." );
						}

						return Mo_SAML_XML_Security_Key::mo_saml_from_encrypted_key_element( $key_element );
					// phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment -- Show error instead of return.
					case 'EncryptedKey':
						return Mo_SAML_XML_Security_Key::mo_saml_from_encrypted_key_element( $child );
					case 'X509Data':
						$x509cert_nodes = $child->getElementsByTagName( 'X509Certificate' );
						if ( $x509cert_nodes ) {
							if ( $x509cert_nodes->length > 0 ) {
								$x509cert = $x509cert_nodes->item( 0 )->textContent;
								$x509cert = str_replace( array( "\r", "\n", ' ' ), '', $x509cert );
								$x509cert = "-----BEGIN CERTIFICATE-----\n" . chunk_split( $x509cert, 64, "\n" ) . "-----END CERTIFICATE-----\n";
								$obj_base_key->mo_saml_load_key( $x509cert, false, true );
							}
						}
						break;
				}
			} catch ( Exception $exception ) {
				wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid Key' );
			}
		}
		return $obj_base_key;
	}

	/**
	 * Locate the key information.
	 *
	 * @param null|Mo_SAML_XML_Security_Key $obj_base_key Object base key.
	 * @param null|DOMNode                  $node Node DOM Document.
	 * @return null|Mo_SAML_XML_Security_Key
	 */
	public function mo_saml_locate_key_info( $obj_base_key = null, $node = null ) {
		if ( empty( $node ) ) {
			$node = $this->raw_node;
		}
		return self::mo_saml_staticlocate_key_info( $obj_base_key, $node );
	}
}
