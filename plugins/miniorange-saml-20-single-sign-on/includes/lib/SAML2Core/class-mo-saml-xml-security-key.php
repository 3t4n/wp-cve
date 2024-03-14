<?php
/**
 * File: mo-saml-xmlseclibs.php
 * This file takes care of processing the security key for SAML message.
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
 * @package   miniorange-saml-20-single-sign-on\includes\lib\SAML2Core
 */

namespace RobRichards\XMLSecLibs;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DOMElement;
use Exception;

/**
 * This class contains all methods required to operate on the security key for SAML.
 */
class Mo_SAML_XML_Security_Key {

	const TRIPLEDES_CBC  = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
	const AES128_CBC     = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
	const AES192_CBC     = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
	const AES256_CBC     = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
	const AES128_GCM     = 'http://www.w3.org/2009/xmlenc11#aes128-gcm';
	const AES192_GCM     = 'http://www.w3.org/2009/xmlenc11#aes192-gcm';
	const AES256_GCM     = 'http://www.w3.org/2009/xmlenc11#aes256-gcm';
	const RSA_1_5        = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
	const RSA_OAEP_MGF1P = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
	const DSA_SHA1       = 'http://www.w3.org/2000/09/xmldsig#dsa-sha1';
	const RSA_SHA1       = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
	const RSA_SHA256     = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
	const RSA_SHA384     = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384';
	const RSA_SHA512     = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512';
	const HMAC_SHA1      = 'http://www.w3.org/2000/09/xmldsig#hmac-sha1';
	const AUTHTAG_LENGTH = 16;

	/**
	 * Array to store all crypto parameters.
	 *
	 * @var array
	 * */
	private $crypt_params = array();

	/**
	 * Key type.
	 *
	 * @var int|string
	 */
	public $type = 0;

	/**
	 * Stores Key, Public/Private.
	 *
	 *  @var mixed|null
	 */
	public $key = null;

	/**
	 * Passphrase for encrypted key.
	 *
	 * @var string
	 */
	public $passphrase = '';

	/**
	 * Initialization vector.
	 *
	 * @var string|null
	 */
	public $iv = null;

	/**
	 * Name.
	 *
	 * @var string|null
	 */
	public $name = null;

	/**
	 * Key Chain.
	 *
	 * @var mixed|null
	 */
	public $key_chain = null;

	/**
	 * Flag is encrypted.
	 *
	 * @var bool
	 */
	public $is_encrypted = false;

	/**
	 * Encrypted ctx.
	 *
	 * @var Mo_SAML_XML_Sec_Enc|null
	 */
	public $encrypted_ctx = null;

	/**
	 * Guid.
	 *
	 * @var mixed|null
	 */
	public $guid = null;

	/**
	 * This variable contains the certificate as a string if this key represents an X509-certificate.
	 * If this key doesn't represent a certificate, this will be null.
	 *
	 * @var string|null
	 */
	private $x509_certificate = null;

	/**
	 * This variable contains the certificate thumbprint if we have loaded an X509-certificate.
	 *
	 * @var string|null
	 */
	private $x509_thumbprint = null;

	/**
	 * Initializes the $crypt_params variable with all data for the Key.
	 *
	 * @param string     $type Key Type.
	 * @param null|array $params Additional information for the Certificate.
	 * @throws Exception If passed key type is invalid or if type is required but not provided for certificate.
	 */
	public function __construct( $type, $params = null ) {
		switch ( $type ) {
			case ( self::TRIPLEDES_CBC ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'des-ede3-cbc';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
				$this->crypt_params['keysize']   = 24;
				$this->crypt_params['blocksize'] = 8;
				break;
			case ( self::AES128_CBC ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'aes-128-cbc';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
				$this->crypt_params['keysize']   = 16;
				$this->crypt_params['blocksize'] = 16;
				break;
			case ( self::AES192_CBC ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'aes-192-cbc';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
				$this->crypt_params['keysize']   = 24;
				$this->crypt_params['blocksize'] = 16;
				break;
			case ( self::AES256_CBC ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'aes-256-cbc';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
				$this->crypt_params['keysize']   = 32;
				$this->crypt_params['blocksize'] = 16;
				break;
			case ( self::AES128_GCM ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'aes-128-gcm';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2009/xmlenc11#aes128-gcm';
				$this->crypt_params['keysize']   = 32;
				$this->crypt_params['blocksize'] = 16;
				break;
			case ( self::AES192_GCM ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'aes-192-gcm';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2009/xmlenc11#aes192-gcm';
				$this->crypt_params['keysize']   = 32;
				$this->crypt_params['blocksize'] = 16;
				break;
			case ( self::AES256_GCM ):
				$this->crypt_params['library']   = 'openssl';
				$this->crypt_params['cipher']    = 'aes-256-gcm';
				$this->crypt_params['type']      = 'symmetric';
				$this->crypt_params['method']    = 'http://www.w3.org/2009/xmlenc11#aes256-gcm';
				$this->crypt_params['keysize']   = 32;
				$this->crypt_params['blocksize'] = 16;
				break;
			case ( self::RSA_1_5 ):
				$this->crypt_params['library'] = 'openssl';
				$this->crypt_params['padding'] = OPENSSL_PKCS1_PADDING;
				$this->crypt_params['method']  = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
				if ( is_array( $params ) && ! empty( $params['type'] ) ) {
					if ( 'public' === $params['type'] || 'private' === $params['type'] ) {
						$this->crypt_params['type'] = $params['type'];
						break;
					}
				}
				throw new Exception( 'Certificate "type" (private/public) must be passed via parameters' );
			case ( self::RSA_OAEP_MGF1P ):
				$this->crypt_params['library'] = 'openssl';
				$this->crypt_params['padding'] = OPENSSL_PKCS1_OAEP_PADDING;
				$this->crypt_params['method']  = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
				$this->crypt_params['hash']    = null;
				if ( is_array( $params ) && ! empty( $params['type'] ) ) {
					if ( 'public' === $params['type'] || 'private' === $params['type'] ) {
						$this->crypt_params['type'] = $params['type'];
						break;
					}
				}
				throw new Exception( 'Certificate "type" (private/public) must be passed via parameters' );
			case ( self::RSA_SHA1 ):
				$this->crypt_params['library'] = 'openssl';
				$this->crypt_params['method']  = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
				$this->crypt_params['padding'] = OPENSSL_PKCS1_PADDING;
				if ( is_array( $params ) && ! empty( $params['type'] ) ) {
					if ( 'public' === $params['type'] || 'private' === $params['type'] ) {
						$this->crypt_params['type'] = $params['type'];
						break;
					}
				}
				throw new Exception( 'Certificate "type" (private/public) must be passed via parameters' );
			case ( self::RSA_SHA256 ):
				$this->crypt_params['library'] = 'openssl';
				$this->crypt_params['method']  = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
				$this->crypt_params['padding'] = OPENSSL_PKCS1_PADDING;
				$this->crypt_params['digest']  = 'SHA256';
				if ( is_array( $params ) && ! empty( $params['type'] ) ) {
					if ( 'public' === $params['type'] || 'private' === $params['type'] ) {
						$this->crypt_params['type'] = $params['type'];
						break;
					}
				}
				throw new Exception( 'Certificate "type" (private/public) must be passed via parameters' );
			case ( self::RSA_SHA384 ):
				$this->crypt_params['library'] = 'openssl';
				$this->crypt_params['method']  = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384';
				$this->crypt_params['padding'] = OPENSSL_PKCS1_PADDING;
				$this->crypt_params['digest']  = 'SHA384';
				if ( is_array( $params ) && ! empty( $params['type'] ) ) {
					if ( 'public' === $params['type'] || 'private' === $params['type'] ) {
						$this->crypt_params['type'] = $params['type'];
						break;
					}
				}
				throw new Exception( 'Certificate "type" (private/public) must be passed via parameters' );
			case ( self::RSA_SHA512 ):
				$this->crypt_params['library'] = 'openssl';
				$this->crypt_params['method']  = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512';
				$this->crypt_params['padding'] = OPENSSL_PKCS1_PADDING;
				$this->crypt_params['digest']  = 'SHA512';
				if ( is_array( $params ) && ! empty( $params['type'] ) ) {
					if ( 'public' === $params['type'] || 'private' === $params['type'] ) {
						$this->crypt_params['type'] = $params['type'];
						break;
					}
				}
				throw new Exception( 'Certificate "type" (private/public) must be passed via parameters' );
			case ( self::HMAC_SHA1 ):
				$this->crypt_params['library'] = $type;
				$this->crypt_params['method']  = 'http://www.w3.org/2000/09/xmldsig#hmac-sha1';
				break;
			default:
				throw new Exception( 'Invalid Key Type' );
		}
		$this->type = $type;
	}

	/**
	 * Retrieve the key size for the symmetric encryption algorithm.
	 *
	 * If the key size is unknown, or this isn't a symmetric encryption algorithm null is returned.
	 *
	 * @return int|null  The number of bytes in the key.
	 */
	public function mo_saml_get_symmetric_key_size() {
		if ( ! isset( $this->crypt_params['keysize'] ) ) {
			return null;
		}
		return $this->crypt_params['keysize'];
	}

	/**
	 * Generates a session key using the openssl-extension.
	 * In case of using DES3-CBC the key is checked for a proper parity bits set.
	 *
	 * @return string
	 * @throws Exception For unknown key size.
	 */
	public function mo_saml_generate_session_key() {
		if ( ! isset( $this->crypt_params['keysize'] ) ) {
			throw new Exception( 'Unknown key size for type "' . $this->type . '".' );
		}
		$key_size = $this->crypt_params['keysize'];

		$key = openssl_random_pseudo_bytes( $key_size );

		if ( self::TRIPLEDES_CBC === $this->type ) {
			/*
			 * Make sure that the generated key has the proper parity bits set.
			 * Mcrypt doesn't care about the parity bits, but others may care.
			*/
			$key_length = strlen( $key );
			for ( $i = 0; $i < $key_length; $i++ ) {
				$byte   = ord( $key[ $i ] ) & 0xfe;
				$parity = 1;
				for ( $j = 1; $j < 8; $j++ ) {
					$parity ^= ( $byte >> $j ) & 1;
				}
				$byte     |= $parity;
				$key[ $i ] = chr( $byte );
			}
		}

		$this->key = $key;
		return $key;
	}

	/**
	 * Get the raw thumbprint of a certificate.
	 *
	 * @param string $cert Certificate.
	 * @return null|string
	 */
	public static function mo_saml_get_raw_thumbprint( $cert ) {
		$ar_cert = explode( "\n", $cert );
		$data    = '';
		$in_data = false;

		foreach ( $ar_cert as $cur_data ) {
			if ( ! $in_data ) {
				if ( strncmp( $cur_data, '-----BEGIN CERTIFICATE', 22 ) === 0 ) {
					$in_data = true;
				}
			} else {
				if ( strncmp( $cur_data, '-----END CERTIFICATE', 20 ) === 0 ) {
					break;
				}
				$data .= trim( $cur_data );
			}
		}

		if ( ! empty( $data ) ) {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Working on the X509 certificate.
			return strtolower( sha1( base64_decode( $data ) ) );
		}

		return null;
	}

	/**
	 * Loads the given key, or - with isFile set true - the key from the keyfile.
	 *
	 * @param string $key Public Key.
	 * @param bool   $is_file True if the key is a file.
	 * @param bool   $is_cert True if the key passed is x509 certificate.
	 * @throws Exception When the key is invalid.
	 */
	public function mo_saml_load_key( $key, $is_file = false, $is_cert = false ) {
		if ( $is_file ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading certificate from file.
			$this->key = file_get_contents( $key );
		} else {
			$this->key = $key;
		}
		if ( $is_cert ) {
			$this->key = openssl_x509_read( $this->key );
			openssl_x509_export( $this->key, $str_cert );
			$this->x509_certificate = $str_cert;
			$this->key              = $str_cert;
		} else {
			$this->x509_certificate = null;
		}
		if ( 'openssl' === $this->crypt_params['library'] ) {
			switch ( $this->crypt_params['type'] ) {
				case 'public':
					if ( $is_cert ) {
						/* Load the thumbprint if this is an X509 certificate. */
						$this->x509_thumbprint = self::mo_saml_get_raw_thumbprint( $this->key );
					}
					$this->key = openssl_get_publickey( $this->key );
					if ( ! $this->key ) {
						throw new Exception( 'Unable to extract public key' );
					}
					break;

				case 'private':
					$this->key = openssl_get_privatekey( $this->key, $this->passphrase );
					break;

				case 'symmetric':
					if ( strlen( $this->key ) < $this->crypt_params['keysize'] ) {
						throw new Exception( 'Key must contain at least 25 characters for this cipher' );
					}
					break;

				default:
					throw new Exception( 'Unknown type' );
			}
		}
	}

	/**
	 * ISO 10126 Padding
	 *
	 * @param string  $data Data to be padded.
	 * @param integer $block_size Blocksize.
	 * @throws Exception For Block size greater than 256.
	 * @return string
	 */
	private function mo_saml_pad_iso_10126( $data, $block_size ) {
		if ( $block_size > 256 ) {
			throw new Exception( 'Block size higher than 256 not allowed' );
		}
		$pad_chr = $block_size - ( strlen( $data ) % $block_size );
		$pattern = chr( $pad_chr );
		return $data . str_repeat( $pattern, $pad_chr );
	}

	/**
	 * Remove ISO 10126 Padding.
	 *
	 * @param string $data Data to be unpadded.
	 * @return string
	 */
	private function mo_saml_unpad_iso_10126( $data ) {
		$pad_chr = substr( $data, -1 );
		$pad_len = ord( $pad_chr );
		return substr( $data, 0, -$pad_len );
	}

	/**
	 * Encrypts the given data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be encrypted.
	 * @return string
	 * @throws Exception For encryption failures.
	 */
	private function mo_saml_encrypt_symmetric( $data ) {
		$this->iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( $this->crypt_params['cipher'] ) );
		$auth_tag = null;
		if ( in_array( $this->crypt_params['cipher'], array( 'aes-128-gcm', 'aes-192-gcm', 'aes-256-gcm' ), true ) ) {
			if ( version_compare( PHP_VERSION, '7.1.0' ) < 0 ) {
				throw new Exception( 'PHP 7.1.0 is required to use AES GCM algorithms' );
			}
			$auth_tag  = openssl_random_pseudo_bytes( self::AUTHTAG_LENGTH );
			$encrypted = openssl_encrypt( $data, $this->crypt_params['cipher'], $this->key, OPENSSL_RAW_DATA, $this->iv, $auth_tag );
		} else {
			try {
				$data = $this->mo_saml_pad_iso_10126( $data, $this->crypt_params['blocksize'] );
			} catch ( Exception $exception ) {
				wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid block size' );
			}
			$encrypted = openssl_encrypt( $data, $this->crypt_params['cipher'], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv );
		}

		if ( false === $encrypted ) {
			throw new Exception( 'Failure encrypting Data (openssl symmetric) - ' . openssl_error_string() );
		}
		return $this->iv . $encrypted . $auth_tag;
	}

	/**
	 * Decrypts the given data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be decrypted.
	 * @return string
	 * @throws Exception For decryption errors.
	 */
	private function mo_saml_decrypt_symmetric( $data ) {
		$iv_length = openssl_cipher_iv_length( $this->crypt_params['cipher'] );
		$this->iv  = substr( $data, 0, $iv_length );
		$data      = substr( $data, $iv_length );
		$auth_tag  = null;
		if ( in_array( $this->crypt_params['cipher'], array( 'aes-128-gcm', 'aes-192-gcm', 'aes-256-gcm' ), true ) ) {
			if ( version_compare( PHP_VERSION, '7.1.0' ) < 0 ) {
				throw new Exception( 'PHP 7.1.0 is required to use AES GCM algorithms' );
			}
			// obtain and remove the authentication tag.
			$offset    = 0 - self::AUTHTAG_LENGTH;
			$auth_tag  = substr( $data, $offset );
			$data      = substr( $data, 0, $offset );
			$decrypted = openssl_decrypt( $data, $this->crypt_params['cipher'], $this->key, OPENSSL_RAW_DATA, $this->iv, $auth_tag );
		} else {
			$decrypted = openssl_decrypt( $data, $this->crypt_params['cipher'], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv );
		}

		if ( false === $decrypted ) {
			throw new Exception( 'Failure decrypting Data (openssl symmetric) - ' . openssl_error_string() );
		}
		return null !== $auth_tag ? $decrypted : $this->mo_saml_unpad_iso_10126( $decrypted );
	}

	/**
	 * Encrypts the given public data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be encrypted.
	 * @return string
	 * @throws Exception For encryption failure.
	 */
	private function mo_saml_encrypt_public( $data ) {
		if ( ! openssl_public_encrypt( $data, $encrypted, $this->key, $this->crypt_params['padding'] ) ) {
			throw new Exception( 'Failure encrypting Data (openssl public) - ' . openssl_error_string() );
		}
		return $encrypted;
	}

	/**
	 * Decrypts the given public data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be decrypted.
	 * @return string
	 * @throws Exception For decryption failure.
	 */
	private function mo_saml_decrypt_public( $data ) {
		if ( ! openssl_public_decrypt( $data, $decrypted, $this->key, $this->crypt_params['padding'] ) ) {
			throw new Exception( 'Failure decrypting Data (openssl public) - ' . openssl_error_string() );
		}
		return $decrypted;
	}

	/**
	 * Encrypts the given private data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be encrypted.
	 * @return string
	 * @throws Exception For encryption failure.
	 */
	private function mo_saml_encrypt_private( $data ) {
		if ( ! openssl_private_encrypt( $data, $encrypted, $this->key, $this->crypt_params['padding'] ) ) {
			throw new Exception( 'Failure encrypting Data (openssl private) - ' . openssl_error_string() );
		}
		return $encrypted;
	}

	/**
	 * Decrypts the given private data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be decrypted.
	 * @return string
	 * @throws Exception For decryption failure.
	 */
	private function mo_saml_decrypt_private( $data ) {
		if ( ! openssl_private_decrypt( $data, $decrypted, $this->key, $this->crypt_params['padding'] ) ) {
			throw new Exception( 'Failure decrypting Data (openssl private) - ' . openssl_error_string() );
		}
		return $decrypted;
	}

	/**
	 * Signs the given data (string) using the openssl-extension.
	 *
	 * @param string $data Data to be signed.
	 * @return string
	 * @throws Exception For signing failure.
	 */
	private function mo_saml_sign_open_ssl( $data ) {
		$algo = OPENSSL_ALGO_SHA1;
		if ( ! empty( $this->crypt_params['digest'] ) ) {
			$algo = $this->crypt_params['digest'];
		}
		if ( ! openssl_sign( $data, $signature, $this->key, $algo ) ) {
			throw new Exception( 'Failure Signing Data: ' . openssl_error_string() . ' - ' . $algo );
		}
		return $signature;
	}

	/**
	 * Verifies the given data (string) belonging to the given signature using the openssl-extension.
	 *
	 * Returns:
	 *  1 on succesful signature verification,
	 *  0 when signature verification failed,
	 *  -1 if an error occurred during processing.
	 *
	 * NOTE: be very careful when checking the return value, because in PHP,
	 * -1 will be cast to True when in boolean context. So always check the
	 * return value in a strictly typed way, e.g. "$obj->verify(...) === 1".
	 *
	 * @param string $data Data to be verified.
	 * @param string $signature Signature to verify the data.
	 * @return int
	 */
	private function mo_saml_verify_open_ssl( $data, $signature ) {
		$algo = OPENSSL_ALGO_SHA1;
		if ( ! empty( $this->crypt_params['digest'] ) ) {
			$algo = $this->crypt_params['digest'];
		}
		return openssl_verify( $data, $signature, $this->key, $algo );
	}

	/**
	 * Encrypts the given data (string) using the regarding php-extension, depending on the library assigned to algorithm in the contructor.
	 *
	 * @param string $data Data to be encrypted.
	 * @return mixed|string
	 */
	public function mo_saml_encrypt_data( $data ) {
		if ( 'openssl' === $this->crypt_params['library'] ) {
			try {
				switch ( $this->crypt_params['type'] ) {
					case 'symmetric':
						return $this->mo_saml_encrypt_symmetric( $data );
					// phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment -- Show error instead of return.
					case 'public':
						return $this->mo_saml_encrypt_public( $data );
					case 'private':
						return $this->mo_saml_encrypt_private( $data );
				}
			} catch ( Exception $exception ) {
				wp_die( 'We could not sign you in. Please contact your administrator.', 'Encryption failure' );
			}
		}
	}

	/**
	 * Decrypts the given data (string) using the regarding php-extension, depending on the library assigned to algorithm in the contructor.
	 *
	 * @param string $data Data to be decrypted.
	 * @return mixed|string
	 */
	public function mo_saml_decrypt_data( $data ) {
		if ( 'openssl' === $this->crypt_params['library'] ) {
			try {
				switch ( $this->crypt_params['type'] ) {
					// phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment -- Show error instead of return.
					case 'symmetric':
						return $this->mo_saml_decrypt_symmetric( $data );
					// phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment -- Show error instead of return.
					case 'public':
						return $this->mo_saml_decrypt_public( $data );
					case 'private':
						return $this->mo_saml_decrypt_private( $data );
				}
			} catch ( Exception $exception ) {
				wp_die( 'We could not sign you in. Please contact your administrator.', 'Decryption failure' );

			}
		}
	}

	/**
	 * Signs the data (string) using the extension assigned to the type in the constructor.
	 *
	 * @param string $data Data to be signed.
	 * @return mixed|string
	 */
	public function mo_saml_sign_data( $data ) {
		switch ( $this->crypt_params['library'] ) {
			// phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment -- Show error instead of return.
			case 'openssl':
				try {
					return $this->mo_saml_sign_open_ssl( $data );
				} catch ( Exception $exception ) {
					wp_die( 'We could not sign you in. Please contact your administrator.', 'Invalid Signing Data' );
				}
			case ( self::HMAC_SHA1 ):
				return hash_hmac( 'sha1', $data, $this->key, true );
		}
	}

	/**
	 * Verifies the data (string) against the given signature using the extension assigned to the type in the constructor.
	 *
	 * Returns in case of openSSL:
	 *  1 on succesful signature verification,
	 *  0 when signature verification failed,
	 *  -1 if an error occurred during processing.
	 *
	 * NOTE: be very careful when checking the return value, because in PHP,
	 * -1 will be cast to True when in boolean context. So always check the
	 * return value in a strictly typed way, e.g. "$obj->verify(...) === 1".
	 *
	 * @param string $data Data to be verified.
	 * @param string $signature Signature to verify data with.
	 * @return bool|int
	 */
	public function mo_saml_verify_signature( $data, $signature ) {
		switch ( $this->crypt_params['library'] ) {
			case 'openssl':
				return $this->mo_saml_verify_open_ssl( $data, $signature );
			case ( self::HMAC_SHA1 ):
				$expected_signature = hash_hmac( 'sha1', $data, $this->key, true );
				return strcmp( $signature, $expected_signature ) === 0;
		}
	}

	/**
	 * Wrapper for mo_saml_get_algorithm().
	 *
	 * @deprecated
	 * @see mo_saml_get_algorithm()
	 * @return mixed
	 */
	public function mo_saml_get_algorith() {
		return $this->mo_saml_get_algorithm();
	}

	/** Returns algorithm name.
	 *
	 * @return mixed
	 */
	public function mo_saml_get_algorithm() {
		return $this->crypt_params['method'];
	}

	/**
	 * Make an ASN segment.
	 *
	 * @param int    $type Segment type.
	 * @param string $string Data.
	 * @return null|string
	 */
	public static function mo_saml_make_asn_segment( $type, $string ) {
		switch ( $type ) {
			case 0x02:
				if ( ord( $string ) > 0x7f ) {
					$string = chr( 0 ) . $string;
				}
				break;
			case 0x03:
				$string = chr( 0 ) . $string;
				break;
		}

		$length = strlen( $string );

		if ( $length < 128 ) {
			$output = sprintf( '%c%c%s', $type, $length, $string );
		} elseif ( $length < 0x0100 ) {
			$output = sprintf( '%c%c%c%s', $type, 0x81, $length, $string );
		} elseif ( $length < 0x010000 ) {
			$output = sprintf( '%c%c%c%c%s', $type, 0x82, $length / 0x0100, $length % 0x0100, $string );
		} else {
			$output = null;
		}
		return $output;
	}

	/**
	 * Convert to RSA format.
	 * Hint: Modulus and Exponent must already be base64 decoded.
	 *
	 * @param string $modulus Modulus.
	 * @param string $exponent Exponent.
	 * @return string
	 */
	public static function mo_saml_convert_rsa( $modulus, $exponent ) {
		/* make an ASN publicKeyInfo */
		$exponent_encoding        = self::mo_saml_make_asn_segment( 0x02, $exponent );
		$modulus_encoding         = self::mo_saml_make_asn_segment( 0x02, $modulus );
		$sequence_encoding        = self::mo_saml_make_asn_segment( 0x30, $modulus_encoding . $exponent_encoding );
		$bitstring_encoding       = self::mo_saml_make_asn_segment( 0x03, $sequence_encoding );
		$rsa_algorithm_identifier = pack( 'H*', '300D06092A864886F70D0101010500' );
		$public_key_info          = self::mo_saml_make_asn_segment( 0x30, $rsa_algorithm_identifier . $bitstring_encoding );

		// encode the publicKeyInfo in base64 and add PEM brackets.
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Working on the public key which is supposed to be encoded.
		$public_key_info_base64 = base64_encode( $public_key_info );
		$encoding               = "-----BEGIN PUBLIC KEY-----\n";
		$offset                 = 0;
		$segment                = substr( $public_key_info_base64, $offset, 64 );
		while ( $segment ) {
			$encoding = $encoding . $segment . "\n";
			$offset  += 64;
		}
		return $encoding . "-----END PUBLIC KEY-----\n";
	}

	/**
	 * Serializes the key.
	 *
	 * @param mixed $parent Key.
	 */
	public function mo_saml_serialize_key( $parent ) {
	}

	/**
	 * Retrieve the X509 certificate this key represents.
	 *
	 * Will return the X509 certificate in PEM-format if this key represents an X509 certificate.
	 *
	 * @return string The X509 certificate or null if this key doesn't represent an X509-certificate.
	 */
	public function mo_saml_get_x509_certificate() {
		return $this->x509_certificate;
	}

	/**
	 * Get the thumbprint of this X509 certificate.
	 *
	 * Returns:
	 *  The thumbprint as a lowercase 40-character hexadecimal number, or null
	 *  if this isn't a X509 certificate.
	 *
	 *  @return string Lowercase 40-character hexadecimal number of thumbprint
	 */
	public function mo_saml_get_x509_thumbprint() {
		return $this->x509_thumbprint;
	}

	/**
	 * Create key from an EncryptedKey-element.
	 *
	 * @param DOMElement $element The EncryptedKey-element.
	 * @return Mo_SAML_XML_Security_Key The new key.
	 * @throws Exception If no algorithm is found for the encrypted key.
	 */
	public static function mo_saml_from_encrypted_key_element( DOMElement $element ) {
		$objenc = new Mo_SAML_XML_Sec_Enc();
		$objenc->setNode( $element );
		$obj_key = $objenc->locateKey();
		if ( ! $obj_key ) {
			throw new Exception( 'Unable to locate algorithm for this Encrypted Key' );
		}
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with DOMElement Attribute
		$obj_key->is_encrypted = true;
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Working with DOMElement Attribute
		$obj_key->encryptedCtx = $objenc;
		Mo_SAML_XML_Sec_Enc::staticLocateKeyInfo( $obj_key, $element );
		return $obj_key;
	}

}
