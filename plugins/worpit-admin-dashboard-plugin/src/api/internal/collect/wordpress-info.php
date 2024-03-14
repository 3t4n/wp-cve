<?php

use FernleafSystems\Wordpress\Plugin\iControlWP;

class ICWP_APP_Api_Internal_Collect_Wordpress extends ICWP_APP_Api_Internal_Collect_Base {

	/**
	 * @inheritDoc
	 */
	public function process() {
		return $this->success( [ 'wordpress-info' => $this->collect() ] );
	}

	/**
	 * @return array - associative: ThemeStylesheet => ThemeData
	 */
	public function collect() {
		$DP = $this->loadDP();
		$WP = $this->loadWP();

		$info = [
			'is_multisite'            => (int)is_multisite(),
			'is_classicpress'         => (int)function_exists( 'classicpress_version' ),
			'type'                    => is_multisite() ? 'wpms' : 'wordpress',
			'admin_path'              => network_admin_url(),
			'admin_url'               => network_admin_url(), // TODO: DELETE
			'available_core_upgrades' => $this->getAvailableCoreUpdates(),
			'wordpress_version'       => $WP->getWordPressVersion(),
			'wordpress_title'         => get_bloginfo( 'name' ),
			'wordpress_tagline'       => get_bloginfo( 'description' ),
			// moved from collect_sync
			'platform'                => $DP->isWindows() ? 'Windows' : 'Linux',
			'windows'                 => $DP->isWindows() ? 1 : 0,
			'server_ip'               => $this->getServerAddress(),
			'php_version'             => $DP->getPhpVersion(),
			'can_write'               => $this->getCollector_Capabilities()->canWrite() ? 1 : 0,
			'is_wpe'                  => ( @getenv( 'IS_WPE' ) == '1' ) ? 1 : 0,
			'wordpress_url'           => $WP->getHomeUrl(),
			'wordpress_wpurl'         => get_bloginfo( 'wpurl' ),
			'debug'                   => [
				'url_rewritten'   => $DP->isUrlRewritten() ? 1 : 0,
				'database_server' => isset( $_ENV[ 'DATABASE_SERVER' ] ) ? $_ENV[ 'DATABASE_SERVER' ] : '-1',
				'ds'              => DIRECTORY_SEPARATOR,
			]
		];

		$defines = [
			'FS_METHOD',
			'DISALLOW_FILE_EDIT',
			'FORCE_SSL_LOGIN',
			'FORCE_SSL_ADMIN',
			'DB_PASSWORD',
			'WP_ALLOW_MULTISITE',
			'MULTISITE',
			'DB_HOST',
			'DB_NAME',
			'DB_USER',
			'DB_PASSWORD',
			'DB_CHARSET',
			'DB_COLLATE',
		];

		$wpConfig = [
			'table_prefix' => $this->loadDbProcessor()->getPrefix()
		];
		foreach ( $defines as $defineKey ) {
			if ( defined( $defineKey ) ) {
				$wpConfig[ strtolower( $defineKey ) ] = constant( $defineKey );
			}
		}

		$info[ 'config' ] = $wpConfig; // TODO: delete; backwards compat
		$info[ 'wordpress_config' ] = $wpConfig;

		return $info;
	}

	/**
	 * Attempts to find a valid server IP address whether it's Windows or *nix.
	 * @return string
	 */
	protected function getServerAddress() {
		if ( $this->loadDP()->isWindows() ) {
			if ( empty( $_SERVER[ 'SERVER_ADDR' ] ) ) {
				if ( isset( $_SERVER[ 'LOCAL_ADDR' ] ) && !empty( $_SERVER[ 'LOCAL_ADDR' ] ) ) {
					$addr = $_SERVER[ 'LOCAL_ADDR' ];
				}
				else {
					$addr = '0.0.0.0';
				}
			}
			else {
				$addr = $_SERVER[ 'SERVER_ADDR' ];
			}
		}
		else {
			$addr = $_SERVER[ 'SERVER_ADDR' ];
		}

		if ( $this->isPrivateIp( $addr ) && function_exists( 'gethostbyname' )
			 && isset( $_SERVER[ 'SERVER_NAME' ] ) && !empty( $_SERVER[ 'SERVER_NAME' ] )
		) {
			$addr = gethostbyname( $_SERVER[ 'SERVER_NAME' ] );
		}

		return $addr;
	}

	/**
	 * @param string $sIp
	 * @return bool
	 */
	private function isPrivateIp( $sIp ) {
		return !filter_var( $sIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE );
	}

	/**
	 * @return string[]
	 */
	private function getAvailableCoreUpdates() {
		$aVersions = [];

		$this->loadWP()->updatesCheck( 'core', true );
		$oUpds = get_site_transient( 'update_core' );
		if ( is_object( $oUpds ) && !empty( $oUpds->updates ) && is_array( $oUpds->updates ) ) {
			foreach ( $oUpds->updates as $oUpd ) {
				$aVersions[] = empty( $oUpd->current ) ? $oUpd->version : $oUpd->current;
			}
		}
		return array_unique( $aVersions );
	}
}