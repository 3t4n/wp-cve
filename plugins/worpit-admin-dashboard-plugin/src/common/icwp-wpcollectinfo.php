<?php

class ICWP_APP_WpCollectInfo extends ICWP_APP_Foundation {

	/**
	 * @var ICWP_APP_WpCollectInfo
	 */
	protected static $oInstance = null;

	/**
	 * @return ICWP_APP_WpCollectInfo
	 */
	public static function GetInstance() {
		if ( is_null( self::$oInstance ) ) {
			self::$oInstance = new self();
		}
		return self::$oInstance;
	}

	public function __construct() {
	}

	/**
	 * @param string  $sPluginFile       if null, collect all plugins
	 * @param boolean $bForceUpdateCheck (optional)
	 * @return array[]                                associative: PluginFile => PluginData
	 * @see plugins.php
	 *
	 * @see class-wp-plugins-list-table.php
	 */
	public function collectWordpressPlugins( $sPluginFile = null, $bForceUpdateCheck = false ) {

		$oWpPlugins = $this->loadWpPlugins();

//			$this->prepThirdPartyPlugins(); //TODO

		$aPlugins = empty( $sPluginFile ) ? $oWpPlugins->getPlugins() : [ $sPluginFile => $oWpPlugins->getPlugin( $sPluginFile ) ];
		$oCurrentUpdates = $oWpPlugins->getUpdates( $bForceUpdateCheck );
		$aAutoUpdatesList = $this->getAutoUpdates( 'plugins' );

		foreach ( $aPlugins as $sPluginFile => $aData ) {

			$aPlugins[ $sPluginFile ][ 'file' ] = $sPluginFile;

			// is it active ?
			$aPlugins[ $sPluginFile ][ 'active' ] = is_plugin_active( $sPluginFile );
			$aPlugins[ $sPluginFile ][ 'network_active' ] = is_plugin_active_for_network( $sPluginFile );

			// is it set to autoupdate ?
			$aPlugins[ $sPluginFile ][ 'auto_update' ] = in_array( $sPluginFile, $aAutoUpdatesList );

			// is there an update ?
			$aPlugins[ $sPluginFile ][ 'update_available' ] = isset( $oCurrentUpdates->response[ $sPluginFile ] ) ? 1 : 0;

			$aPlugins[ $sPluginFile ][ 'update_info' ] = '';
			if ( $aPlugins[ $sPluginFile ][ 'update_available' ] ) {
				$aPlugins[ $sPluginFile ][ 'update_info' ] = wp_json_encode( $oCurrentUpdates->response[ $sPluginFile ] );
			}
		}

		$sServicePluginBaseFile = ICWP_Plugin::getController()->getPluginBaseFile();
		if ( isset( $aPlugins[ $sServicePluginBaseFile ] ) ) {
			$aPlugins[ $sServicePluginBaseFile ][ 'is_service_plugin' ] = 1;
		}

		return $aPlugins;
	}

	/**
	 * @param string  $themeFile        (optional)
	 * @param boolean $forceUpdateCheck (optional)
	 * @return array[]                                associative: ThemeStylesheet => ThemeData
	 */
	public function collectWordpressThemes( $themeFile = null, $forceUpdateCheck = false ) {

		$WPT = $this->loadWpFunctionsThemes();

//			$this->prepThirdPartyThemes(); //TODO
		$themes = empty( $themeFile ) ? $WPT->getThemes() : [ $themeFile => $WPT->getTheme( $themeFile ) ];
		$themes = $this->normaliseThemeData( $themes );

		$updates = $WPT->getUpdates( $forceUpdateCheck );
		$aAutoUpdatesList = $this->getAutoUpdates( 'themes' );

		$bIsMultisite = is_multisite();
		$aNetworkAllowedThemes = $this->loadWpFunctionsThemes()->wpmsGetSiteAllowedThemes();

		$oActiveTheme = $this->loadWpFunctionsThemes()->getCurrent();
		$sActiveThemeStylesheet = $oActiveTheme->get_stylesheet();

		foreach ( $themes as $nIndex => $aData ) {

			$themes[ $nIndex ][ 'active' ] = ( $sActiveThemeStylesheet == $aData[ 'Stylesheet' ] ) ? 1 : 0;
			if ( !isset( $aData[ 'network_active' ] ) ) {
				$themes[ $nIndex ][ 'network_active' ] = ( $bIsMultisite && isset( $aNetworkAllowedThemes[ $aData[ 'Stylesheet' ] ] ) );
			}

			// is it set to autoupdate ?
			$themes[ $nIndex ][ 'auto_update' ] = in_array( $aData[ 'Stylesheet' ], $aAutoUpdatesList );

			$themes[ $nIndex ][ 'update_available' ] = isset( $updates->response[ $aData[ 'Stylesheet' ] ] ) ? 1 : 0;

			$themes[ $nIndex ][ 'update_info' ] = '';
			if ( $themes[ $nIndex ][ 'update_available' ] ) {
				$themes[ $nIndex ][ 'update_info' ] = json_encode( $updates->response[ $aData[ 'Stylesheet' ] ] );
			}
		}
		return $themes;
	}

	/**
	 * @param array $aThemes
	 * @return array[]
	 */
	protected function normaliseThemeData( $aThemes ) {

		$aNormalizedThemes = [];

		if ( $this->loadWP()->getWordpressIsAtLeastVersion( '3.4' ) ) {

			/** @var WP_Theme[] $aThemes */
			foreach ( $aThemes as $sStylesheet => $oTheme ) {
				$aNormalizedThemes[ $sStylesheet ] = [
					'Name'        => $oTheme->display( 'Name' ),
					'Title'       => $oTheme->offsetGet( 'Title' ),
					'Description' => $oTheme->offsetGet( 'Description' ),
					'Author'      => $oTheme->offsetGet( 'Author' ),
					'Author Name' => $oTheme->offsetGet( 'Author Name' ),
					'Author URI'  => $oTheme->offsetGet( 'Author URI' ),
					'Version'     => $oTheme->offsetGet( 'Version' ),

					'Template'       => $oTheme->offsetGet( 'Template' ),
					'Stylesheet'     => $oTheme->offsetGet( 'Stylesheet' ),
					//'Template Dir'		=> $oTheme->offsetGet( 'Template Dir' ),
					//'Stylesheet Dir'	=> $oTheme->offsetGet( 'Stylesheet Dir' ),
					'Theme Root'     => $oTheme->offsetGet( 'Theme Root' ),
					'Theme Root URI' => $oTheme->offsetGet( 'Theme Root URI' ),

					'Status'         => $oTheme->offsetGet( 'Status' ),

					// We add our own data here because it's easier while it's an object
					'network_active' => $oTheme->is_allowed( 'network' )
				];
			}
		}
		else {
			$aNormalizedThemes = $aThemes;
		}

		return $aNormalizedThemes;
	}

	/**
	 * @param string $sContext
	 * @return mixed
	 */
	protected function getAutoUpdates( $sContext = 'plugins' ) {
		$oAutoupdatesSystem = ICWP_Plugin::GetAutoUpdatesSystem();
		return $oAutoupdatesSystem->getAutoUpdates( $sContext );
	}
}