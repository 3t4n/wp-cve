<?php

namespace MyCustomizer\WooCommerce\Connector\Install;

class MczrInstall {

	public function __construct() {
		$this->pluginDir  = realpath( __DIR__ . '/../../' );
		$this->statusFile = $this->pluginDir . '/status';
	}

	public function getStatus() {
		$current = file_get_contents( $this->statusFile );
		return trim( $current );
	}

	public function isInstalled() {
		return $this->getStatus() === 'installed';
	}

	public function setStatus( $status ) {
		file_put_contents( $this->statusFile, $status );
	}

	public function init() {
		$errors       = array();
		$vendor       = "{$this->pluginDir}/vendor";
		$composerHome = "{$this->pluginDir}/bin/composer/";

		// Status file must be present and writable
		if ( ! is_file( $this->statusFile ) ) {
			$errors[] = ( "Could not find '{$this->statusFile}'" );
		}
		if ( ! is_writable( $this->statusFile ) ) {
			$errors[] = ( "File '{$this->statusFile}' must be writable" );
		}
		// Plugin dir must be writable to add vendor dir
		if ( ! is_writable( "{$this->pluginDir}" ) ) {
			$errors[] = ( "Directory '{$this->pluginDir}/' must be writable." );
		}
		// Composer home dir creation
		if ( ! is_dir( $composerHome ) ) {
			if ( ! mkdir( "$composerHome", 0777, true ) ) {
				$errors[] = ( "Could not create '$composerHome', please check recursive permissions for this dir." );
			}
		} else {
			if ( ! is_writable( $composerHome ) ) {
				$errors[] = ( "'$composerHome' is not writeable, please check recursive permissions for this dir." );
			}
		}
		// Vendor dir creation
		if ( ! is_dir( $vendor ) ) {
			if ( ! mkdir( "$vendor", 0777, true ) ) {
				$errors[] = ( "Could not create '$vendor', please check recursive permissions for this dir." );
			}
		} else {
			if ( ! is_writable( $vendor ) ) {
				$errors[] = ( "'$vendor' is not writeable, please check recursive permissions for this dir." );
			}
		}

		// Any error, display and exit
		if ( ! empty( $errors ) ) {
			echo 'Please correct following errors, then try to activate plugin again ';
			foreach ( $errors as $k => $msg ) {
				echo esc_attr( $msg ) . ' ';
			}
			exit;
		}
		return;
	}
}
