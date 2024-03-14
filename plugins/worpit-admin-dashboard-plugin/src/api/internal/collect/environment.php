<?php

class ICWP_APP_Api_Internal_Collect_Environment extends ICWP_APP_Api_Internal_Collect_Capabilities {

	/**
	 * @return array
	 */
	public function collect() {
		$DP = $this->loadDP();
		if ( $DP->suhosinFunctionExists( 'set_time_limit' ) ) {
			@set_time_limit( 15 );
		}

		$appsData = [];
		if ( $DP->suhosinFunctionExists( 'exec' ) ) {
			$appVersionCmds = [
				'mysql -V',
				'mysqldump -V',
				'mysqlimport -V',
				'unzip -v',
				'zip -v',
				'tar --version'
			];
			$appsData = $this->collectApplicationVersions( $appVersionCmds );
		}

		return [
			'open_basedir'                 => ini_get( 'open_basedir' ),
			'safe_mode'                    => ini_get( 'safe_mode' ),
			'safe_mode_gid'                => ini_get( 'safe_mode_gid' ),
			'safe_mode_include_dir'        => ini_get( 'safe_mode_include_dir' ),
			'safe_mode_exec_dir'           => ini_get( 'safe_mode_exec_dir' ),
			'safe_mode_allowed_env_vars'   => ini_get( 'safe_mode_allowed_env_vars' ),
			'safe_mode_protected_env_vars' => ini_get( 'safe_mode_protected_env_vars' ),
			'can_exec'                     => $DP->checkCanExec() ? 1 : 0,
			'can_timelimit'                => $DP->checkCanTimeLimit() ? 1 : 0,
			'can_write'                    => $this->checkCanWrite() ? 1 : 0,
			'can_tar'                      => $appsData[ 'tar' ][ 'version-info' ] > 0 ? 1 : 0,
			'can_zip'                      => $appsData[ 'zip' ][ 'version-info' ] > 0 ? 1 : 0,
			'can_unzip'                    => $appsData[ 'unzip' ][ 'version-info' ] > 0 ? 1 : 0,
			'can_mysql'                    => $appsData[ 'mysql' ][ 'version-info' ] > 0 ? 1 : 0,
			'can_mysqldump'                => $appsData[ 'mysqldump' ][ 'version-info' ] > 0 ? 1 : 0,
			'can_mysqlimport'              => $appsData[ 'mysqlimport' ][ 'version-info' ] > 0 ? 1 : 0,
			'applications'                 => $appsData,
		];
	}

	/**
	 * @param array $appVersionCmds
	 * @return array
	 */
	protected function collectApplicationVersions( $appVersionCmds ) {
		$apps = [];

		foreach ( $appVersionCmds as $versionCmd ) {
			list( $exec, $execParams ) = explode( ' ', $versionCmd, 2 );
			@exec( $versionCmd, $output, $nReturnVal );

			$apps[ $exec ] = [
				'exec'         => $exec,
				'version-cmd'  => $versionCmd,
				'version-info' => $this->parseApplicationVersionOutput( $exec, is_array( $output ) ? implode( "\n", $output ) : '' ),
				'found'        => $nReturnVal === 0,
			];
		}
		return $apps;
	}

	/**
	 * @param string $executable
	 * @param string $versionOutput
	 * @return string
	 */
	protected function parseApplicationVersionOutput( $executable, $versionOutput ) {
		$aRegExprs = [
			'mysql'       => '/Distrib\s+([0-9]+\.[0-9]+(\.[0-9]+)?)/i',
			//mysql  Ver 14.14 Distrib 5.1.56, for pc-linux-gnu (i686) using readline 5.1
			'mysqlimport' => '/Distrib\s+([0-9]+\.[0-9]+(\.[0-9]+)?)/i',
			//mysqlimport  Ver 3.7 Distrib 5.1.41, for Win32 (ia32)
			'mysqldump'   => '/Distrib\s+([0-9]+\.[0-9]+(\.[0-9]+)?)/i',
			//mysqldump  Ver 10.13 Distrib 5.1.41, for Win32 (ia32)
			'zip'         => '/Zip\s+([0-9]+\.[0-9]+(\.[0-9]+)?)/i',
			//This is Zip 2.31 (March 8th 2005), by Info-ZIP.
			'unzip'       => '/UnZip\s+([0-9]+\.[0-9]+(\.[0-9]+)?)/i',
			//UnZip 5.52 of 28 February 2005, by Info-ZIP.  Maintained by C. Spieler.  Send
			'tar'         => '/tar\s+\(GNU\s+tar\)\s+([0-9]+\.[0-9]+(\.[0-9]+)?)/i'
			//tar (GNU tar) 1.15.1
		];

		if ( $executable == 'php' ) {
			if ( preg_match( '/X-Pingback/i', $versionOutput ) ) {
				return '-2';
			}
		}
		if ( !preg_match( $aRegExprs[ $executable ], $versionOutput, $matches ) ) {
			return '-3';
		}
		else {
			return $matches[ 1 ];
		}
	}
}