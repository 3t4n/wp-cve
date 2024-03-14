<?php
/**
 * RobRichards\XMLSecLibs handles the signature verification of SAML response with the public certificate saved.
 *
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
 * Xmlseclibs.php
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
 */
class Mo_SAML_XML_Security_DSig {

	const XMLDSIGNS = 'http://www.w3.org/2000/09/xmldsig#';
	const SHA1      = 'http://www.w3.org/2000/09/xmldsig#sha1';
	const SHA256    = 'http://www.w3.org/2001/04/xmlenc#sha256';
	const SHA384    = 'http://www.w3.org/2001/04/xmldsig-more#sha384';
	const SHA512    = 'http://www.w3.org/2001/04/xmlenc#sha512';
	const RIPEMD160 = 'http://www.w3.org/2001/04/xmlenc#ripemd160';

	const C14N              = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
	const C14N_COMMENTS     = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments';
	const EXC_C14N          = 'http://www.w3.org/2001/10/xml-exc-c14n#';
	const EXC_C14N_COMMENTS = 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments';

	const TEMPLATE = '<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
  <ds:SignedInfo>
    <ds:SignatureMethod />
  </ds:SignedInfo>
</ds:Signature>';

	const BASE_TEMPLATE = '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
  <SignedInfo>
    <SignatureMethod />
  </SignedInfo>
</Signature>';

	/**
	 * Signature Node of the XML.
	 *
	 * @var DOMElement|null $sig_node signature node from the SAML response.
	 */
	public $sig_node = null;

	/**
	 * Array of Id keys.
	 *
	 * @var array $id_keys signature ID.
	 */
	public $id_keys = array();

	/**
	 * Array of node ids.
	 *
	 *  @var array node ids.
	 */
	public $id_ns = array();

	/**
	 * Signed Info.
	 *
	 * @var string|null signed info
	 */
	private $signed_info = null;

	/**
	 * Dom xpath.
	 *
	 * @var DomXPath|null
	 */
	private $x_path_ctx = null;

	/**
	 * Canonical Method.
	 *
	 * @var string|null
	 */
	private $canonical_method = null;

	/**
	 * Prefix for the xml node
	 *
	 *  @var string
	 */
	private $prefix = '';

	/**
	 * Search prefix.
	 *
	 * @var string
	 */
	private $search_pfx = 'secdsig';

	/**
	 * This variable contains an associative array of validated nodes.
	 *
	 * @var array|null
	 */
	private $validated_nodes = null;

	/**
	 * Initialize class.
	 *
	 * @param string $prefix xml prefix for digital signature node.
	 */
	public function __construct( $prefix = 'ds' ) {
		$template = self::BASE_TEMPLATE;
		if ( ! empty( $prefix ) ) {
			$this->prefix = $prefix . ':';
			$search       = array( '<S', '</S', 'xmlns=' );
			$replace      = array( "<$prefix:S", "</$prefix:S", "xmlns:$prefix=" );
			$template     = str_replace( $search, $replace, $template );
		}
		$sigdoc = new DOMDocument();
		$sigdoc->loadXML( $template );
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- documentElement is XML DOM property.
		$this->sig_node = $sigdoc->documentElement;
	}

	/**
	 * Reset the XPathObj to null
	 *
	 * @return void
	 */
	private function reset_x_path_obj() {
		$this->x_path_ctx = null;
	}

	/**
	 * Returns the XPathObj or null if xPathCtx is set and sig_node is empty.
	 *
	 * @return DOMXPath|null
	 */
	private function get_x_path_obj() {
		if ( empty( $this->x_path_ctx ) && ! empty( $this->sig_node ) ) {
			$xpath = new DOMXPath( $this->sig_node->ownerDocument );
			$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
			$this->x_path_ctx = $xpath;
		}
		return $this->x_path_ctx;
	}

	/**
	 * Generate guid
	 *
	 * @param string $prefix Prefix to use for guid. defaults to pfx.
	 *
	 * @return string The generated guid.
	 */
	public static function generate_guid( $prefix = 'pfx' ) {
		$uuid = md5( uniqid( wp_rand(), true ) );
		$guid = $prefix . substr( $uuid, 0, 8 ) . '-' .
				substr( $uuid, 8, 4 ) . '-' .
				substr( $uuid, 12, 4 ) . '-' .
				substr( $uuid, 16, 4 ) . '-' .
				substr( $uuid, 20, 12 );
		return $guid;
	}

	/**
	 * Locate signature in dom document.
	 *
	 * @param DOMDocument $obj_doc $object of dom document.
	 * @param int         $pos position of singedInfo Node.
	 *
	 * @return DOMNode|null
	 * @throws Exception Throws Exception for multiple signed-info nodes.
	 */
	public function locate_signature( $obj_doc, $pos = 0 ) {
		if ( $obj_doc instanceof DOMDocument ) {
			$doc = $obj_doc;
		} else {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
			$doc = $obj_doc->ownerDocument;
		}
		if ( $doc ) {
			$xpath = new DOMXPath( $doc );
			$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
			$query          = './/secdsig:Signature';
			$nodeset        = $xpath->query( $query, $obj_doc );
			$this->sig_node = $nodeset->item( $pos );
			$query          = './secdsig:SignedInfo';
			$nodeset        = $xpath->query( $query, $this->sig_node );
			if ( $nodeset->length > 1 ) {
				throw new Exception( 'Invalid structure - Too many SignedInfo elements found' );
			}
			return $this->sig_node;
		}
		return null;
	}

	/**
	 * Creates new Sign Node.
	 *
	 * @param string      $name name of the Node.
	 *
	 * @param null|string $value value of Node.
	 *
	 * @return DOMElement
	 */
	public function create_new_sign_node( $name, $value = null ) {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$doc = $this->sig_node->ownerDocument;
		if ( ! is_null( $value ) ) {
			$node = $doc->createElementNS( self::XMLDSIGNS, $this->prefix . $name, $value );
		} else {
			$node = $doc->createElementNS( self::XMLDSIGNS, $this->prefix . $name );
		}
		return $node;
	}

	/**
	 * Set canonical method.
	 *
	 * @param string $method canonical method.
	 *
	 * @throws Exception If canonical method is not valid.
	 */
	public function set_canonical_method( $method ) {
		switch ( $method ) {
			case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315':
			case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments':
			case 'http://www.w3.org/2001/10/xml-exc-c14n#':
			case 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments':
				$this->canonical_method = $method;
				break;
			default:
				throw new Exception( 'Invalid Canonical Method' );
		}
		$xpath = $this->get_x_path_obj();
		if ( $xpath ) {
			$query   = './' . $this->searchpfx . ':SignedInfo';
			$nodeset = $xpath->query( $query, $this->sig_node );
			$sinfo   = $nodeset->item( 0 );
			if ( $sinfo ) {
				$query      = './' . $this->searchpfx . 'CanonicalizationMethod';
				$nodeset    = $xpath->query( $query, $sinfo );
				$canon_node = $nodeset->item( 0 );
				if ( ! $canon_node ) {
					$canon_node = $this->create_new_sign_node( 'CanonicalizationMethod' );
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- firstChild is XML DOM property.
					$sinfo->insertBefore( $canon_node, $sinfo->firstChild );
				}
				$canon_node->setAttribute( 'Algorithm', $this->canonical_method );
			}
		}
	}

	/**
	 * Canonicalize Data.
	 *
	 * @param DOMNode    $node Node DOM Document.
	 *
	 * @param string     $canonical_method canonical method to produce canonicalize data.
	 *
	 * @param null|array $ar_x_path Xpath.
	 *
	 * @param null|array $prefix_list array of prefix to create canonicalize data.
	 *
	 * @return string
	 */
	private function canonicalize_data( $node, $canonical_method, $ar_x_path = null, $prefix_list = null ) {
		$exclusive     = false;
		$with_comments = false;
		switch ( $canonical_method ) {
			case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315':
				$exclusive     = false;
				$with_comments = false;
				break;
			case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments':
				$with_comments = true;
				break;
			case 'http://www.w3.org/2001/10/xml-exc-c14n#':
				$exclusive = true;
				break;
			case 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments':
				$exclusive     = true;
				$with_comments = true;
				break;
		}
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument & documentElement are XML DOM properties.
		if ( is_null( $ar_x_path ) && ( $node instanceof DOMNode ) && ( null !== $node->ownerDocument ) && $node->isSameNode( $node->ownerDocument->documentElement ) ) {
			// Check for any PI or comments as they would have been excluded.
			$element = $node;
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- previousSibling is XML DOM property.
			$refnode = $element->previousSibling;
			while ( $refnode ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- nodeType is XML DOM property.
				if ( XML_PI_NODE === $refnode->nodeType || ( ( XML_COMMENT_NODE === $refnode->nodeType ) && $with_comments ) ) {
					break;
				}
				$element = $refnode;
			}
			if ( null === $refnode ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
				$node = $node->ownerDocument;
			}
		}
		return $node->C14N( $exclusive, $with_comments, $ar_x_path, $prefix_list );
	}

	/**
	 * Canonicalize signed Info.
	 *
	 * @return null|string
	 * @throws Exception Throws Exception for multiple signed-info nodes.
	 */
	public function canonicalize_signed_info() {
		$doc             = $this->sig_node->ownerDocument;
		$canonicalmethod = null;
		if ( $doc ) {
			$xpath   = $this->get_x_path_obj();
			$query   = './secdsig:SignedInfo';
			$nodeset = $xpath->query( $query, $this->sig_node );
			if ( $nodeset->length > 1 ) {
				throw new Exception( 'Invalid structure - Too many SignedInfo elements found' );
			}
			$sign_info_node = $nodeset->item( 0 );
			if ( $sign_info_node ) {
				$query       = './secdsig:CanonicalizationMethod';
				$nodeset     = $xpath->query( $query, $sign_info_node );
				$prefix_list = null;
				$canon_node  = $nodeset->item( 0 );
				if ( $canon_node ) {
					$canonicalmethod = $canon_node->getAttribute( 'Algorithm' );
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- childNodes is XML DOM property.
					foreach ( $canon_node->childNodes as $node ) {
						//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- localName is XML DOM property.
						if ( 'InclusiveNamespaces' === $node->localName ) {
							$pfx = $node->getAttribute( 'PrefixList' );
							if ( $pfx ) {
								$arpfx = array_filter( explode( ' ', $pfx ) );
								if ( count( $arpfx ) > 0 ) {
									$prefix_list = array_merge( $prefix_list ? $prefix_list : array(), $arpfx );
								}
							}
						}
					}
				}
				$this->signed_info = $this->canonicalize_data( $sign_info_node, $canonicalmethod, null, $prefix_list );
				return $this->signed_info;
			}
		}
		return null;
	}

	/**
	 * Calculate digest from data.
	 *
	 * @param string $digest_algorithm algorithm to create digest.
	 *
	 * @param string $data data to create digest from.
	 *
	 * @param bool   $encode true if we need to return base_64 encoded digest.
	 *
	 * @return string
	 * @throws Exception Throws exception is digest algorithm is not valid.
	 */
	public function calculate_digest( $digest_algorithm, $data, $encode = true ) {
		switch ( $digest_algorithm ) {
			case self::SHA1:
				$alg = 'sha1';
				break;
			case self::SHA256:
				$alg = 'sha256';
				break;
			case self::SHA384:
				$alg = 'sha384';
				break;
			case self::SHA512:
				$alg = 'sha512';
				break;
			case self::RIPEMD160:
				$alg = 'ripemd160';
				break;
			default:
				throw new Exception( "Cannot validate digest: Unsupported Algorithm <$digest_algorithm>" );
		}

		$digest = hash( $alg, $data, true );
		if ( $encode ) {
			//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- base_64 encoding is necessary as per standards of SAML.
			$digest = base64_encode( $digest );
		}
		return $digest;

	}

	/**
	 * Validate the digest from XML node and digest created by plugin.
	 *
	 * @param DOMDocument $ref_node Node from which digest will be picked.
	 *
	 * @param string      $data Data for which digest will be validated.
	 *
	 * @return bool
	 */
	public function validate_digest( $ref_node, $data ) {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$xpath = new DOMXPath( $ref_node->ownerDocument );
		$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
		$query            = 'string(./secdsig:DigestMethod/@Algorithm)';
		$digest_algorithm = $xpath->evaluate( $query, $ref_node );
		try {
			$dig_value = $this->calculate_digest( $digest_algorithm, $data, false );
		} catch ( Exception $exception ) {
			wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid Algorithm' );
		}
		$query        = 'string(./secdsig:DigestValue)';
		$digest_value = $xpath->evaluate( $query, $ref_node );
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- base_64 encoding is necessary as per standards of SAML.
		return ( base64_decode( $digest_value ) === $dig_value );
	}

	/**
	 * Process Transforms.
	 *
	 * @param mixed   $ref_node reference node of the XML.
	 *
	 * @param DOMNode $obj_data object data.
	 *
	 * @param bool    $include_comment_nodes false if comment node is included.
	 *
	 * @return string
	 */
	public function process_transforms( $ref_node, $obj_data, $include_comment_nodes = true ) {
		$data = $obj_data;
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$xpath = new DOMXPath( $ref_node->ownerDocument );
		$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
		$query            = './secdsig:Transforms/secdsig:Transform';
		$nodelist         = $xpath->query( $query, $ref_node );
		$canonical_method = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
		$ar_x_path        = null;
		$prefix_list      = null;
		foreach ( $nodelist as $transform ) {
			$algorithm = $transform->getAttribute( 'Algorithm' );
			switch ( $algorithm ) {
				case 'http://www.w3.org/2001/10/xml-exc-c14n#':
				case 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments':
					if ( ! $include_comment_nodes ) {
						/*
						 * We remove comment nodes by forcing it to use a canonicalization
						 * without comments.
						 */
						$canonical_method = 'http://www.w3.org/2001/10/xml-exc-c14n#';
					} else {
						$canonical_method = $algorithm;
					}
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- firstChild is XML DOM property.
					$node = $transform->firstChild;
					while ( $node ) {
						//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- localName is XML DOM property.
						if ( 'InclusiveNamespaces' === $node->localName ) {
							$pfx = $node->getAttribute( 'PrefixList' );
							if ( $pfx ) {
								$arpfx   = array();
								$pfxlist = explode( ' ', $pfx );
								foreach ( $pfxlist as $pfx ) {
									$val = trim( $pfx );
									if ( ! empty( $val ) ) {
										$arpfx[] = $val;
									}
								}
								if ( count( $arpfx ) > 0 ) {
									$prefix_list = $arpfx;
								}
							}
							break;
						}
						//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- nextSibling is XML DOM property.
						$node = $node->nextSibling;
					}
					break;
				case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315':
				case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments':
					if ( ! $include_comment_nodes ) {
						/*
						 * We remove comment nodes by forcing it to use a canonicalization
						 * without comments.
						 */
						$canonical_method = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
					} else {
						$canonical_method = $algorithm;
					}

					break;
				case 'http://www.w3.org/TR/1999/REC-xpath-19991116':
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- firstChild is XML DOM property.
					$node = $transform->firstChild;
					while ( $node ) {
						//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- localName is XML DOM property.
						if ( 'Mo_SAML_XPath' === $node->localName ) {
							$ar_x_path = array();
							//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- nodeValue is XML DOM property.
							$ar_x_path['query']      = '(.//. | .//@* | .//namespace::*)[' . $node->nodeValue . ']';
							$ar_x_path['namespaces'] = array();
							$nslist                  = $xpath->query( './namespace::*', $node );
							foreach ( $nslist as $nsnode ) {
								//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- localName is XML DOM property.
								if ( 'xml' !== $nsnode->localName ) {
									//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- localName & nodeValue are XML DOM properties.
									$ar_x_path['namespaces'][ $nsnode->localName ] = $nsnode->nodeValue;
								}
							}
							break;
						}
						//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- nextSibling is XML DOM property.
						$node = $node->nextSibling;
					}
					break;
			}
		}
		if ( $data instanceof DOMNode ) {
			$data = $this->canonicalize_data( $obj_data, $canonical_method, $ar_x_path, $prefix_list );
		}
		return $data;
	}

	/**
	 * Process reference Node of XML.
	 *
	 * @param DOMNode $ref_node reference node of XML.
	 *
	 * @return bool
	 */
	public function process_ref_node( $ref_node ) {
		$data_object = null;

		/*
		 * Depending on the URI, we may not want to include comments in the result
		 * See: http://www.w3.org/TR/xmldsig-core/#sec-ReferenceProcessingModel
		 */
		$include_comment_nodes = true;
		$uri                   = $ref_node->getAttribute( 'URI' );
		if ( $uri ) {
			$ar_url = wp_parse_url( $uri );
			if ( empty( $ar_url['path'] ) ) {
				$identifier = $ar_url['fragment'];
				if ( $identifier ) {
					/*
					 * This reference identifies a node with the given id by using
					 * a URI on the form "#identifier". This should not include comments.
					 */
					$include_comment_nodes = false;
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
					$x_path = new DOMXPath( $ref_node->ownerDocument );
					if ( $this->id_ns && is_array( $this->id_ns ) ) {
						foreach ( $this->id_ns as $nspf => $ns ) {
							$x_path->registerNamespace( $nspf, $ns );
						}
					}
					$id_list = '@Id="' . Mo_SAML_XPath::mo_saml_filter_attr_value( $identifier, Mo_SAML_XPath::DOUBLE_QUOTE ) . '"';
					if ( is_array( $this->id_keys ) ) {
						foreach ( $this->id_keys as $id_key ) {
							$id_list .= ' or @' . Mo_SAML_XPath::mo_saml_filter_attr_name( $id_key ) . '="' .
							Mo_SAML_XPath::mo_saml_filter_attr_value( $identifier, Mo_SAML_XPath::DOUBLE_QUOTE ) . '"';
						}
					}
					$query       = '//*[' . $id_list . ']';
					$data_object = $x_path->query( $query )->item( 0 );
				} else {
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
					$data_object = $ref_node->ownerDocument;
				}
			}
		} else {
			/*
			 * This reference identifies the root node with an empty URI. This should
			 * not include comments.
			 */
			$include_comment_nodes = false;
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
			$data_object = $ref_node->ownerDocument;
		}
		$data = $this->process_transforms( $ref_node, $data_object, $include_comment_nodes );
		if ( ! $this->validate_digest( $ref_node, $data ) ) {
			return false;
		}

		if ( $data_object instanceof DOMNode ) {
			/* Add this node to the list of validated nodes. */
			if ( ! empty( $identifier ) ) {
				$this->validated_nodes[ $identifier ] = $data_object;
			} else {
				$this->validated_nodes[] = $data_object;
			}
		}

		return true;
	}

	/**
	 * Get Reference Node ID.
	 *
	 * @param DOMNode $ref_node reference node of XML.
	 *
	 * @return null
	 */
	public function get_ref_node_id( $ref_node ) {
		$uri = $ref_node->getAttribute( 'URI' );
		if ( $uri ) {
			$ar_url = wp_parse_url( $uri );
			// error for parse_url.
			if ( empty( $ar_url['path'] ) ) {
				$identifier = $ar_url['fragment'];
				if ( $identifier ) {
					return $identifier;
				}
			}
		}
		return null;
	}

	/**
	 * Get reference IDs.
	 *
	 * @return array
	 * @throws Exception For zero nodes.
	 */
	public function get_ref_ids() {
		$refids = array();

		$xpath   = $this->get_x_path_obj();
		$query   = './secdsig:SignedInfo[1]/secdsig:Reference';
		$nodeset = $xpath->query( $query, $this->sig_node );
		if ( 0 === $nodeset->length ) {
			throw new Exception( 'Reference nodes not found' );
		}
		foreach ( $nodeset as $ref_node ) {
			$refids[] = $this->get_ref_node_id( $ref_node );
		}
		return $refids;
	}

	/**
	 * Validate reference of XML.
	 *
	 * @return bool
	 * @throws Exception Throws if Signature node is empty.
	 */
	public function validate_reference() {
		$doc_elem = $this->sig_node->ownerDocument->documentElement;
		if ( ! $doc_elem->isSameNode( $this->sig_node ) ) {
			if ( null !== $this->sig_node->parentNode ) {
				$this->sig_node->parentNode->removeChild( $this->sig_node );
			}
		}
		$xpath   = $this->get_x_path_obj();
		$query   = './secdsig:SignedInfo[1]/secdsig:Reference';
		$nodeset = $xpath->query( $query, $this->sig_node );
		if ( 0 === $nodeset->length ) {
			throw new Exception( 'Reference nodes not found' );
		}

		/* Initialize/reset the list of validated nodes. */
		$this->validated_nodes = array();

		foreach ( $nodeset as $ref_node ) {
			if ( ! $this->process_ref_node( $ref_node ) ) {
				/* Clear the list of validated nodes. */
				$this->validated_nodes = null;
				throw new Exception( 'Reference validation failed' );
			}
		}
		return true;
	}

	/**
	 * Add Internal Reference.
	 *
	 * @param DOMNode     $sinfo_node node of signature info.
	 *
	 * @param DOMDocument $node DOMDocument node.
	 *
	 * @param string      $algorithm algorithm of signature.
	 *
	 * @param null|array  $ar_transforms argument to transform.
	 *
	 * @param null|array  $options options.
	 * @return void
	 */
	private function add_ref_internal( $sinfo_node, $node, $algorithm, $ar_transforms = null, $options = null ) {
		$prefix       = null;
		$prefix_ns    = null;
		$id_name      = 'Id';
		$overwrite_id = true;
		$force_uri    = false;

		if ( is_array( $options ) ) {
			$prefix       = empty( $options['prefix'] ) ? null : $options['prefix'];
			$prefix_ns    = empty( $options['prefix_ns'] ) ? null : $options['prefix_ns'];
			$id_name      = empty( $options['id_name'] ) ? 'Id' : $options['id_name'];
			$overwrite_id = ! isset( $options['overwrite'] ) ? true : (bool) $options['overwrite'];
			$force_uri    = ! isset( $options['force_uri'] ) ? false : (bool) $options['force_uri'];
		}

		$attname = $id_name;
		if ( ! empty( $prefix ) ) {
			$attname = $prefix . ':' . $attname;
		}

		$ref_node = $this->create_new_sign_node( 'Reference' );
		$sinfo_node->appendChild( $ref_node );

		if ( ! $node instanceof DOMDocument ) {
			$uri = null;
			if ( ! $overwrite_id ) {
				$uri = $prefix_ns ? $node->getAttributeNS( $prefix_ns, $id_name ) : $node->getAttribute( $id_name );
			}
			if ( empty( $uri ) ) {
				$uri = self::generate_guid();
				$node->setAttributeNS( $prefix_ns, $attname, $uri );
			}
			$ref_node->setAttribute( 'URI', '#' . $uri );
		} elseif ( $force_uri ) {
			$ref_node->setAttribute( 'URI', '' );
		}

		$trans_nodes = $this->create_new_sign_node( 'Transforms' );
		$ref_node->appendChild( $trans_nodes );

		if ( is_array( $ar_transforms ) ) {
			foreach ( $ar_transforms as $transform ) {
				$trans_node = $this->create_new_sign_node( 'Transform' );
				$trans_nodes->appendChild( $trans_node );
				if ( is_array( $transform ) &&
					( ! empty( $transform['http://www.w3.org/TR/1999/REC-xpath-19991116'] ) ) &&
					( ! empty( $transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['query'] ) ) ) {
					$trans_node->setAttribute( 'Algorithm', 'http://www.w3.org/TR/1999/REC-xpath-19991116' );
					$x_path_node = $this->create_new_sign_node( 'XPath', $transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['query'] );
					$trans_node->appendChild( $x_path_node );
					if ( ! empty( $transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['namespaces'] ) ) {
						foreach ( $transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['namespaces'] as $prefix => $namespace ) {
							$x_path_node->setAttributeNS( 'http://www.w3.org/2000/xmlns/', "xmlns:$prefix", $namespace );
						}
					}
				} else {
					$trans_node->setAttribute( 'Algorithm', $transform );
				}
			}
		} elseif ( ! empty( $this->canonical_method ) ) {
			$trans_node = $this->create_new_sign_node( 'Transform' );
			$trans_nodes->appendChild( $trans_node );
			$trans_node->setAttribute( 'Algorithm', $this->canonical_method );
		}

		$canonical_data = $this->process_transforms( $ref_node, $node );
		try {
			$dig_value = $this->calculate_digest( $algorithm, $canonical_data );
		} catch ( Exception $exception ) {
			wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid Algorithm' );
		}

		$digest_method = $this->create_new_sign_node( 'DigestMethod' );
		$ref_node->appendChild( $digest_method );
		$digest_method->setAttribute( 'Algorithm', $algorithm );

		$digest_value = $this->create_new_sign_node( 'DigestValue', $dig_value );
		$ref_node->appendChild( $digest_value );
	}

	/**
	 * Add Reference.
	 *
	 * @param DOMDocument $node node of XML.
	 *
	 * @param string      $algorithm algorithm for signature.
	 *
	 * @param null|array  $ar_transforms argument to transform.
	 *
	 * @param null|array  $options options.
	 * @return void
	 */
	public function add_reference( $node, $algorithm, $ar_transforms = null, $options = null ) {
		$xpath = $this->get_x_path_obj();
		if ( $xpath ) {
			$query   = './secdsig:SignedInfo';
			$nodeset = $xpath->query( $query, $this->sig_node );
			$s_info  = $nodeset->item( 0 );
			if ( $s_info ) {
				$this->add_ref_internal( $s_info, $node, $algorithm, $ar_transforms, $options );
			}
		}
	}

	/**
	 * Add Reference list.
	 *
	 * @param array      $ar_nodes node of XML.
	 *
	 * @param string     $algorithm algorithm for signature.
	 *
	 * @param null|array $ar_transforms argument to transform.
	 *
	 * @param null|array $options options.
	 */
	public function add_reference_list( $ar_nodes, $algorithm, $ar_transforms = null, $options = null ) {
		$xpath = $this->get_x_path_obj();
		if ( $xpath ) {
			$query   = './secdsig:SignedInfo';
			$nodeset = $xpath->query( $query, $this->sig_node );
			$s_info  = $nodeset->item( 0 );
			if ( $s_info ) {
				foreach ( $ar_nodes as $node ) {
					$this->add_ref_internal( $s_info, $node, $algorithm, $ar_transforms, $options );
				}
			}
		}
	}

	/**
	 * Add object.
	 *
	 * @param DOMElement|string $data data of object.
	 *
	 * @param null|string       $mimetype mimetype of data.
	 *
	 * @param null|string       $encoding encoding of data.
	 * @return DOMElement
	 */
	public function add_object( $data, $mimetype = null, $encoding = null ) {
		$obj_node = $this->create_new_sign_node( 'Object' );
		$this->sig_node->appendChild( $obj_node );
		if ( ! empty( $mimetype ) ) {
			$obj_node->setAttribute( 'MimeType', $mimetype );
		}
		if ( ! empty( $encoding ) ) {
			$obj_node->setAttribute( 'Encoding', $encoding );
		}

		if ( $data instanceof DOMElement ) {
			$new_data = $this->sig_node->ownerDocument->importNode( $data, true );
		} else {
			$new_data = $this->sig_node->ownerDocument->createTextNode( $data );
		}
		$obj_node->appendChild( $new_data );

		return $obj_node;
	}

	/**
	 * Function to locate key.
	 *
	 * @param null|DOMNode $node Node of the XML.
	 *
	 * @return null|Mo_SAML_XML_Security_Key.
	 */
	public function locate_key( $node = null ) {
		if ( empty( $node ) ) {
			$node = $this->sig_node;
		}
		if ( ! $node instanceof DOMNode ) {
			return null;
		}
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$doc = $node->ownerDocument;
		if ( $doc ) {
			$xpath = new DOMXPath( $doc );
			$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
			$query     = 'string(./secdsig:SignedInfo/secdsig:SignatureMethod/@Algorithm)';
			$algorithm = $xpath->evaluate( $query, $node );
			if ( $algorithm ) {
				try {
					$obj_key = new Mo_SAML_XML_Security_Key( $algorithm, array( 'type' => 'public' ) );
				} catch ( Exception $e ) {
					return null;
				}
				return $obj_key;
			}
		}
		return null;
	}

	/**
	 * Returns:
	 *  Bool when verifying HMAC_SHA1;
	 *  Int otherwise, with following meanings:
	 *    1 on successful signature verification,
	 *    0 when signature verification failed,
	 *   -1 if an error occurred during processing.
	 *
	 * NOTE: be very careful when checking the int return value, because in
	 * PHP, -1 will be cast to True when in boolean context. Always check the
	 * return value in a strictly typed way, e.g. "$obj->verify(...) === 1".
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key instance of Mo_SAML_XML_Security_Key.
	 *
	 * @return bool|int
	 * @throws Exception Throws if signature value node is empty.
	 */
	public function verify( $obj_key ) {
		$doc   = $this->sig_node->ownerDocument;
		$xpath = new DOMXPath( $doc );
		$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
		$query     = 'string(./secdsig:SignatureValue)';
		$sig_value = $xpath->evaluate( $query, $this->sig_node );
		if ( empty( $sig_value ) ) {
			throw new Exception( 'Unable to locate SignatureValue' );
		}
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- base_64 encode/decode is necessary as per standards of SAML.
		return $obj_key->mo_saml_verify_signature( $this->signed_info, base64_decode( $sig_value ) );
	}

	/**
	 * Sign data.
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key instance of Mo_SAML_XML_Security_Key.
	 *
	 * @param string                   $data data to signed.
	 *
	 * @return mixed|string
	 */
	public function sign_data( $obj_key, $data ) {
		return $obj_key->mo_saml_sign_data( $data );
	}

	/**
	 * Signs a node.
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key instance of Mo_SAML_XML_Security_Key.
	 *
	 * @param null|DOMNode             $append_to_node instance of DOMDocument to add signature.
	 * @return void
	 */
	public function sign( $obj_key, $append_to_node = null ) {
		// If we have a parent node append it now so C14N properly works.
		if ( null !== $append_to_node ) {
			$this->reset_x_path_obj();
			$this->append_signature( $append_to_node );
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- lastChild is XML DOM property.
			$this->sig_node = $append_to_node->lastChild;
		}
		$xpath = $this->get_x_path_obj();
		if ( $xpath ) {
			$query   = './secdsig:SignedInfo';
			$nodeset = $xpath->query( $query, $this->sig_node );
			$s_info  = $nodeset->item( 0 );
			if ( $s_info ) {
				$query    = './secdsig:SignatureMethod';
				$nodeset  = $xpath->query( $query, $s_info );
				$s_method = $nodeset->item( 0 );
				$s_method->setAttribute( 'Algorithm', $obj_key->type );
				$data = $this->canonicalize_data( $s_info, $this->canonical_method );
				//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- base_64 encoding is necessary as per standards of SAML.
				$sig_value      = base64_encode( $this->sign_data( $obj_key, $data ) );
				$sig_value_node = $this->create_new_sign_node( 'SignatureValue', $sig_value );
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- nextSibling is XML DOM property.
				$info_sibling = $s_info->nextSibling;
				if ( $info_sibling ) {
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- parentNode is XML DOM property.
					$info_sibling->parentNode->insertBefore( $sig_value_node, $info_sibling );
				} else {
					$this->sig_node->appendChild( $sig_value_node );
				}
			}
		}
	}

	/**
	 * Append Key.
	 *
	 * @param Mo_SAML_XML_Security_Key $obj_key instance of Mo_SAML_XML_Security_Key.
	 *
	 * @param null|DOMNode             $parent parent node.
	 * @return void
	 */
	public function append_key( $obj_key, $parent = null ) {
		$obj_key->mo_saml_serialize_key( $parent );
	}


	/**
	 * This function inserts the signature element.
	 *
	 * The signature element will be appended to the element, unless $beforeNode is specified. If $beforeNode
	 * is specified, the signature element will be inserted as the last element before $beforeNode.
	 *
	 * @param DOMNode $node       The node the signature element should be inserted into.
	 * @param DOMNode $before_node The node the signature element should be located before.
	 *
	 * @return DOMNode The signature element node
	 */
	public function insert_signature( $node, $before_node = null ) {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$document          = $node->ownerDocument;
		$signature_element = $document->importNode( $this->sig_node, true );

		if ( null === $before_node ) {
			return $node->insertBefore( $signature_element );
		} else {
			return $node->insertBefore( $signature_element, $before_node );
		}
	}

	/**
	 * Append signature.
	 *
	 * @param DOMNode $parent_node parent node in which we need to insert digital signature node.
	 *
	 * @param bool    $insert_before node before which we need to insert digital signature node.
	 *
	 * @return DOMNode
	 */
	public function append_signature( $parent_node, $insert_before = false ) {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- firstChild is XML DOM property.
		$before_node = $insert_before ? $parent_node->firstChild : null;
		return $this->insert_signature( $parent_node, $before_node );
	}

	/**
	 * Get X509 cert.
	 *
	 * @param string $cert public certificate to verify signature.
	 *
	 * @param bool   $is_pem_format if certificate in PEM format.
	 *
	 * @return string
	 */
	public static function get509_xcert( $cert, $is_pem_format = true ) {
		$certs = self::static_get_509_xcerts( $cert, $is_pem_format );
		if ( ! empty( $certs ) ) {
			return $certs[0];
		}
		return '';
	}

	/**
	 * Get cert from the X509 data.
	 *
	 * @param string $certs public certs.
	 *
	 * @param bool   $is_pem_format bool to check if public certificate is in form of the PEM.
	 *
	 * @return array
	 */
	public static function static_get_509_xcerts( $certs, $is_pem_format = true ) {
		if ( $is_pem_format ) {
			$data     = '';
			$certlist = array();
			$ar_cert  = explode( "\n", $certs );
			$in_data  = false;
			foreach ( $ar_cert as $cur_data ) {
				if ( ! $in_data ) {
					if ( strncmp( $cur_data, '-----BEGIN CERTIFICATE', 22 ) === 0 ) {
						$in_data = true;
					}
				} else {
					if ( strncmp( $cur_data, '-----END CERTIFICATE', 20 ) === 0 ) {
						$in_data    = false;
						$certlist[] = $data;
						$data       = '';
						continue;
					}
					$data .= trim( $cur_data );
				}
			}
			return $certlist;
		} else {
			return array( $certs );
		}
	}

	/**
	 * Add static X509 certificate.
	 *
	 * @param DOMElement    $parent_ref Parent reference node.
	 *
	 * @param string        $cert public certificate.
	 *
	 * @param bool          $is_pem_format bool to check if public certificate is in form of the PEM.
	 *
	 * @param bool          $is_url if URL.
	 *
	 * @param null|DOMXPath $xpath Xpath.
	 *
	 * @param null|array    $options options.
	 *
	 * @return void
	 * @throws Exception Throws if parent node is not valid.
	 */
	public static function static_add509_cert( $parent_ref, $cert, $is_pem_format = true,
		$is_url = false, $xpath = null, $options = null ) {
		if ( $is_url ) {
			//phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- file_get_contents can be used to load local files.
			$cert = file_get_contents( $cert );
		}
		if ( ! $parent_ref instanceof DOMElement ) {
			throw new Exception( 'Invalid parent Node parameter' );
		}
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$base_doc = $parent_ref->ownerDocument;

		if ( empty( $xpath ) ) {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
			$xpath = new DOMXPath( $parent_ref->ownerDocument );
			$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
		}

		$query    = './secdsig:KeyInfo';
		$nodeset  = $xpath->query( $query, $parent_ref );
		$key_info = $nodeset->item( 0 );
		$dsig_pfx = '';
		if ( ! $key_info ) {
			$pfx = $parent_ref->lookupPrefix( self::XMLDSIGNS );
			if ( ! empty( $pfx ) ) {
				$dsig_pfx = $pfx . ':';
			}
			$inserted = false;
			$key_info = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'KeyInfo' );
			$query    = './secdsig:Object';
			$nodeset  = $xpath->query( $query, $parent_ref );
			$s_object = $nodeset->item( 0 );
			if ( $s_object ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- parentNode is XML DOM property.
				$s_object->parentNode->insertBefore( $key_info, $s_object );
				$inserted = true;
			}

			if ( ! $inserted ) {
				$parent_ref->appendChild( $key_info );
			}
		} else {
			$pfx = $key_info->lookupPrefix( self::XMLDSIGNS );
			if ( ! empty( $pfx ) ) {
				$dsig_pfx = $pfx . ':';
			}
		}

		// Add all certs if there are more than one.
		$certs = self::static_get_509_xcerts( $cert, $is_pem_format );

		// Attach X509 data node.
		$x509_data_node = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'X509Data' );
		$key_info->appendChild( $x509_data_node );

		$issuer_serial = false;
		$subject_name  = false;
		if ( is_array( $options ) ) {
			if ( ! empty( $options['issuerSerial'] ) ) {
				$issuer_serial = true;
			}
			if ( ! empty( $options['subjectName'] ) ) {
				$subject_name = true;
			}
		}

		// Attach all certificate nodes and any additional data.
		foreach ( $certs as $x509_cert ) {
			if ( $issuer_serial || $subject_name ) {
				$cert_data = openssl_x509_parse( "-----BEGIN CERTIFICATE-----\n" . chunk_split( $x509_cert, 64, "\n" ) . "-----END CERTIFICATE-----\n" );
				if ( $cert_data ) {
					if ( $subject_name && ! empty( $cert_data['subject'] ) ) {
						if ( is_array( $cert_data['subject'] ) ) {
							$parts = array();
							foreach ( $cert_data['subject'] as $key => $value ) {
								if ( is_array( $value ) ) {
									foreach ( $value as $value_element ) {
										array_unshift( $parts, "$key=$value_element" );
									}
								} else {
									array_unshift( $parts, "$key=$value" );
								}
							}
							$subject_name_value = implode( ',', $parts );
						} else {
							$subject_name_value = $cert_data['issuer'];
						}
						$x509_subject_node = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'X509SubjectName', $subject_name_value );
						$x509_data_node->appendChild( $x509_subject_node );
					}
					if ( $issuer_serial && ! empty( $cert_data['issuer'] ) && ! empty( $cert_data['serialNumber'] ) ) {
						if ( is_array( $cert_data['issuer'] ) ) {
							$parts = array();
							foreach ( $cert_data['issuer'] as $key => $value ) {
								array_unshift( $parts, "$key=$value" );
							}
							$issuer_name = implode( ',', $parts );
						} else {
							$issuer_name = $cert_data['issuer'];
						}

						$x509_issuer_node = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'X509IssuerSerial' );
						$x509_data_node->appendChild( $x509_issuer_node );

						$x509_node = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'X509IssuerName', $issuer_name );
						$x509_issuer_node->appendChild( $x509_node );
						$x509_node = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'X509SerialNumber', $cert_data['serialNumber'] );
						$x509_issuer_node->appendChild( $x509_node );
					}
				}
			}
			$x509_cert_node = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'X509Certificate', $x509_cert );
			$x509_data_node->appendChild( $x509_cert_node );
		}
	}

	/**
	 * Add x509 cert.
	 *
	 * @param string     $cert public certificate.
	 *
	 * @param bool       $is_pem_format bool to check if public certificate is in form of the PEM.
	 *
	 * @param bool       $is_url is url.
	 *
	 * @param null|array $options options.
	 */
	public function add509_cert( $cert, $is_pem_format = true, $is_url = false, $options = null ) {
		$xpath = $this->get_x_path_obj();
		if ( $xpath ) {
			try {
				self::static_add509_cert( $this->sig_node, $cert, $is_pem_format, $is_url, $xpath, $options );
			} catch ( Exception $exception ) {
				wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid Node' );
			}
		}
	}

	/**
	 * This function appends a node to the KeyInfo.
	 *
	 * The KeyInfo element will be created if one does not exist in the document.
	 *
	 * @param DOMNode $node The node to append to the KeyInfo.
	 *
	 * @return DOMNode The KeyInfo element node
	 */
	public function append_to_key_info( $node ) {
		$parent_ref = $this->sig_node;
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
		$base_doc = $parent_ref->ownerDocument;
		$xpath    = $this->get_x_path_obj();
		if ( empty( $xpath ) ) {
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- ownerDocument is XML DOM property.
			$xpath = new DOMXPath( $parent_ref->ownerDocument );
			$xpath->registerNamespace( 'secdsig', self::XMLDSIGNS );
		}

		$query    = './secdsig:KeyInfo';
		$nodeset  = $xpath->query( $query, $parent_ref );
		$key_info = $nodeset->item( 0 );
		if ( ! $key_info ) {
			$dsig_pfx = '';
			$pfx      = $parent_ref->lookupPrefix( self::XMLDSIGNS );
			if ( ! empty( $pfx ) ) {
				$dsig_pfx = $pfx . ':';
			}
			$inserted = false;
			$key_info = $base_doc->createElementNS( self::XMLDSIGNS, $dsig_pfx . 'KeyInfo' );

			$query    = './secdsig:Object';
			$nodeset  = $xpath->query( $query, $parent_ref );
			$s_object = $nodeset->item( 0 );
			if ( $s_object ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- parentNode is XML DOM property.
				$s_object->parentNode->insertBefore( $key_info, $s_object );
				$inserted = true;
			}

			if ( ! $inserted ) {
				$parent_ref->appendChild( $key_info );
			}
		}

		$key_info->appendChild( $node );

		return $key_info;
	}

	/**
	 * This function retrieves an associative array of the validated nodes.
	 *
	 * The array will contain the id of the referenced node as the key and the node itself
	 * as the value.
	 *
	 * Returns:
	 *  An associative array of validated nodes or null if no nodes have been validated.
	 *
	 *  @return array Associative array of validated nodes
	 */
	public function get_validated_nodes() {
		return $this->validated_nodes;
	}
}
