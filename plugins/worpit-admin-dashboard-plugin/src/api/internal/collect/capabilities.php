<?php

use FernleafSystems\Wordpress\Plugin\iControlWP;

class ICWP_APP_Api_Internal_Collect_Capabilities extends ICWP_APP_Api_Internal_Collect_Base {

	/**
	 * @var bool
	 */
	private $bCanWrite;

	/**
	 * @inheritDoc
	 */
	public function process() {
		return $this->success( [ 'capabilities' => $this->collect() ] );
	}

	/**
	 * @return array
	 */
	public function collect() {
		$DP = $this->loadDP();
		$canExtensionLoaded = function_exists( 'extension_loaded' ) && is_callable( 'extension_loaded' );
		return [
			'php_version'                => $DP->getPhpVersion(), //TODO DELETE
			'version_php'                => $DP->getPhpVersion(),
			'is_force_ssl_admin'         => ( function_exists( 'force_ssl_admin' ) && force_ssl_admin() ) ? 1 : 0,
			'can_handshake'              => $this->isHandshakeEnabled() ? 1 : 0,
			'can_handshake_openssl'      => $this->loadEncryptProcessor()->getSupportsOpenSslSign() ? 1 : 0,
			'can_wordpress_write'        => $this->checkCanWordpressWrite( $sWriteToDiskNotice ) ? 1 : 0, //TODO: DELETE
			'can_wordpress_write_notice' => $sWriteToDiskNotice,
			'ext_pdo'                    => class_exists( 'PDO' ) || ( $canExtensionLoaded && extension_loaded( 'pdo' ) ),
			'ext_mysqli'                 => ( $canExtensionLoaded && extension_loaded( 'mysqli' ) ) ? 1 : 0,
			'can_zip'                    => iControlWP\Utilities\File\ZipDir::IsSupported() ? 1 : 0,
		];
	}

	/**
	 * @return bool
	 */
	protected function checkCanWrite() {
		$FS = $this->loadFS();

		$sWorkingTestDir = dirname( __FILE__ ).'/icwp_test/';
		$sWorkingTestFile = $sWorkingTestDir.'test_write';
		$sTestContent = '#FINDME-'.uniqid();

		$bGoodSoFar = true;
		$outsMessage = '';

		if ( !$FS->mkdir( $sWorkingTestDir ) || !$FS->isDir( $sWorkingTestDir ) ) {
			$outsMessage = sprintf( 'Failed to create directory: %s', $sWorkingTestDir );
			$bGoodSoFar = false;
		}
		if ( $bGoodSoFar && !is_writable( $sWorkingTestDir ) ) {
			$outsMessage = sprintf( 'The test directory is not writable: %s', $sWorkingTestDir );
			$bGoodSoFar = false;
		}
		if ( $bGoodSoFar && !$FS->touch( $sWorkingTestFile ) ) {
			$outsMessage = sprintf( 'Failed to touch "%s"', $sWorkingTestFile );
			$bGoodSoFar = false;
		}
		if ( $bGoodSoFar && !file_put_contents( $sWorkingTestFile, $sTestContent ) ) {
			$outsMessage = sprintf( 'Failed to write content "%s" to "%s"', $sWorkingTestFile, $sTestContent );
			$bGoodSoFar = false;
		}
		if ( $bGoodSoFar && !@is_file( $sWorkingTestFile ) ) {
			$outsMessage = sprintf( 'Failed to find file "%s"', $sWorkingTestFile );
			$bGoodSoFar = false;
		}
		$sContents = $FS->getFileContent( $sWorkingTestFile );
		if ( $bGoodSoFar && ( $sContents != $sTestContent ) ) {
			$outsMessage = sprintf( 'The content "%s" does not match what we wrote "%s"', $sContents, $sTestContent );
			$bGoodSoFar = false;
		}

		if ( !$bGoodSoFar ) {
			$this->getStandardResponse()
				 ->setErrorMessage( $outsMessage );

			return false;
		}

		$FS->deleteDir( $sWorkingTestDir );

		return true;
	}

	/**
	 * @param string &$outsMessage
	 * @return boolean
	 */
	protected function checkCanWordpressWrite( &$outsMessage = '' ) {
		$url = '';
		$url = wp_nonce_url( $url, '' );

		ob_start();
		$aCredentials = request_filesystem_credentials( $url, '', false, false, null );
		ob_end_clean();

		if ( $aCredentials === false ) {
			$outsMessage = 'Could not obtain filesystem credentials';
			return false;
		}

		if ( !WP_Filesystem( $aCredentials ) ) {
			global $wp_filesystem;

			$oWpError = null;
			if ( is_object( $wp_filesystem ) && $wp_filesystem->errors->get_error_code() ) {
				$oWpError = $wp_filesystem->errors;
				/** @var WP_Error $oWpError */
			}
			$outsMessage = sprintf( 'Cannot connect to filesystem. Error: "%s"',
				is_wp_error( $oWpError ) ? $oWpError->get_error_message() : ''
			);

			return false;
		}

		$outsMessage = 'WordPress disk write successful.';
		return true;
	}

	/**
	 * @return bool
	 */
	protected function isHandshakeEnabled() {
		return apply_filters(
			'icwp-app-CanHandshake',
			ICWP_Plugin::getController()->loadCorePluginFeatureHandler()->getCanHandshake()
		);
	}

	/**
	 * @return bool
	 */
	public function canWrite() {
		if ( !isset( $this->bCanWrite ) ) {
			$this->bCanWrite = $this->checkCanWordpressWrite();
		}
		return $this->bCanWrite;
	}
}