<?php

class ICWP_APP_Encrypt extends ICWP_APP_Foundation {

	/**
	 * @var ICWP_APP_Encrypt
	 */
	protected static $oInstance = null;

	/**
	 * @return ICWP_APP_Encrypt
	 */
	public static function GetInstance() {
		if ( is_null( self::$oInstance ) ) {
			self::$oInstance = new self();
		}
		return self::$oInstance;
	}

	protected function __construct() {
	}

	/**
	 * @return bool
	 */
	public function getSupportsOpenSslSign() {
		return function_exists( 'base64_decode' )
			   && function_exists( 'openssl_sign' )
			   && function_exists( 'openssl_verify' )
			   && defined( 'OPENSSL_ALGO_SHA1' );
	}

	/**
	 * @param        $mDataToEncrypt
	 * @param        $publicKey
	 * @param string $cipher
	 * @return stdClass
	 * @deprecated 4.2.0
	 */
	public function encryptDataPublicKey( $mDataToEncrypt, $publicKey, $cipher = 'rc4' ) {

		$isPHP8 = $this->loadDP()->getPhpVersionIsAtLeast( '8.0' );

		$encryptResponse = $this->getStandardEncryptResponse();

		if ( empty( $mDataToEncrypt ) ) {
			$encryptResponse->success = false;
			$encryptResponse->message = 'Data to encrypt was empty';
			return $encryptResponse;
		}
		elseif ( !$this->isSupportedOpenSslDataEncryption() ) {
			$encryptResponse->success = false;
			$encryptResponse->message = 'Does not support OpenSSL data encryption';
		}
		elseif ( $isPHP8 && !$this->hasCipherAlgo( 'rc4' ) ) {
			$encryptResponse->success = false;
			$encryptResponse->message = 'Cipher Algo RC4 is not available';
		}
		elseif ( !$this->hasCipherAlgo( $cipher ) ) {
			$encryptResponse->message = sprintf( 'Defaulting to RC4 as cipher %s is not available', $cipher );
			$cipher = 'rc4';
		}

		// If at this stage we're not 'success' we return it.
		if ( !$encryptResponse->success ) {
			return $encryptResponse;
		}

		$encryptResponse->cipher = $cipher;

		if ( is_string( $mDataToEncrypt ) ) {
			$encryptResponse->serialized = false;
		}
		else {
			$mDataToEncrypt = $this->loadDP()->encodeJson( $mDataToEncrypt );
			$encryptResponse->serialized = true;
		}

		$passwordKeys = [];

		if ( $isPHP8 ) {
			$nResult = openssl_seal(
				$mDataToEncrypt,
				$encryptedData,
				$passwordKeys,
				[ $publicKey ],
				$cipher
			);
		}
		else {
			// PHP <8.0 doesn't require the Cipher parameter so for now we don't specify it (rc4) until we're sure
			// we can handle it if it's not available. We just leave default action.
			$nResult = openssl_seal(
				$mDataToEncrypt,
				$encryptedData,
				$passwordKeys,
				[ $publicKey ]
			);
		}

		$encryptResponse->result = $nResult;
		$encryptResponse->success = is_int( $nResult ) && $nResult > 0 && !is_null( $encryptedData );
		if ( $encryptResponse->success ) {
			$encryptResponse->encrypted_data = base64_encode( $encryptedData );
			$encryptResponse->encrypted_password = base64_encode( $passwordKeys[ 0 ] );
		}

		if ( $cipher !== 'rc4' ) {
			// we do a backup seal as rc4 while we determine availability of other cipers
			$encryptResponse->rc4_fallback = $this->sealData( $mDataToEncrypt, $publicKey, 'rc4' );
		}

		return $encryptResponse;
	}

	/**
	 * @return bool
	 */
	public function isSupportedOpenSslDataEncryption() {
		$supported = $this->isSupportedOpenSsl();
		$funcs = [
			'openssl_seal',
			'openssl_open',
			'openssl_pkey_new',
			'openssl_pkey_export',
			'openssl_pkey_get_details',
			'openssl_pkey_get_private',
			'openssl_get_cipher_methods',
		];
		foreach ( $funcs as $func ) {
			$supported = $supported && function_exists( $func );
		}
		return $supported;
	}

	public function isSupportedOpenSsl() :bool {
		return extension_loaded( 'openssl' );
	}

	/**
	 * @param mixed  $mDataToEncrypt
	 * @param string $publicKey
	 * @return \stdClass                    3 members: result, encrypted, password
	 */
	public function sealData( $mDataToEncrypt, $publicKey, $cipher = 'rc4' ) {

		$isPHP8 = $this->loadDP()->getPhpVersionIsAtLeast( '8.0' );

		$encryptResponse = $this->getStandardEncryptResponse();

		if ( empty( $mDataToEncrypt ) ) {
			$encryptResponse->success = false;
			$encryptResponse->message = 'Data to encrypt was empty';
			return $encryptResponse;
		}
		elseif ( !$this->isSupportedOpenSslDataEncryption() ) {
			$encryptResponse->success = false;
			$encryptResponse->message = 'Does not support OpenSSL data encryption';
		}
		elseif ( $isPHP8 && !$this->hasCipherAlgo( 'rc4' ) ) {
			$encryptResponse->success = false;
			$encryptResponse->message = 'Cipher Algo RC4 is not available';
		}
		elseif ( !$this->hasCipherAlgo( $cipher ) ) {
			$encryptResponse->message = sprintf( 'Defaulting to RC4 as cipher %s is not available', $cipher );
			$cipher = 'rc4';
		}

		// If at this stage we're not 'success' we return it.
		if ( !$encryptResponse->success ) {
			return $encryptResponse;
		}

		$encryptResponse->cipher = $cipher;

		if ( is_string( $mDataToEncrypt ) ) {
			$encryptResponse->serialized = false;
		}
		else {
			$mDataToEncrypt = $this->loadDP()->encodeJson( $mDataToEncrypt );
			$encryptResponse->serialized = true;
		}

		$passwordKeys = [];

		if ( $isPHP8 ) {
			$nResult = openssl_seal(
				$mDataToEncrypt,
				$encryptedData,
				$passwordKeys,
				[ $publicKey ],
				$cipher
			);
		}
		else {
			// PHP <8.0 doesn't require the Cipher parameter so for now we don't specify it (rc4) until we're sure
			// we can handle it if it's not available. We just leave default action.
			$nResult = openssl_seal(
				$mDataToEncrypt,
				$encryptedData,
				$passwordKeys,
				[ $publicKey ]
			);
		}

		$encryptResponse->result = $nResult;
		$encryptResponse->success = is_int( $nResult ) && $nResult > 0 && !is_null( $encryptedData );
		if ( $encryptResponse->success ) {
			$encryptResponse->encrypted_data = base64_encode( $encryptedData );
			$encryptResponse->encrypted_password = base64_encode( $passwordKeys[ 0 ] );
		}

		if ( $cipher !== 'rc4' ) {
			// we do a backup seal as rc4 while we determine availability of other cipers
			$encryptResponse->rc4_fallback = $this->sealData( $mDataToEncrypt, $publicKey, 'rc4' );
		}

		return $encryptResponse;
	}

	/**
	 * @param string $sVerificationCode
	 * @param string $sSignature
	 * @param string $sPublicKey
	 * @return int                    1: Success; 0: Failure; -1: Error; -2: Not supported
	 */
	public function verifySslSignature( $sVerificationCode, $sSignature, $sPublicKey ) {
		$nResult = -2;
		if ( $this->getSupportsOpenSslSign() ) {
			$nResult = openssl_verify( $sVerificationCode, $sSignature, $sPublicKey );
		}
		return $nResult;
	}

	/**
	 * @return stdClass
	 */
	protected function getStandardEncryptResponse() {
		$oEncryptResponse = new stdClass();
		$oEncryptResponse->success = true;
		$oEncryptResponse->result = null;
		$oEncryptResponse->message = '';
		$oEncryptResponse->serialized = false;
		$oEncryptResponse->encrypted_data = null;
		$oEncryptResponse->encrypted_password = null;
		$oEncryptResponse->cipher = 'rc4';
		return $oEncryptResponse;
	}

	public function hasCipherAlgo( string $cipher ) :bool {
		return in_array( strtolower( $cipher ), array_map( 'strtolower', openssl_get_cipher_methods( true ) ) );
	}
}