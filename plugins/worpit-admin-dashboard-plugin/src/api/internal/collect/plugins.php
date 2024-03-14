<?php

class ICWP_APP_Api_Internal_Collect_Plugins extends ICWP_APP_Api_Internal_Collect_Base {

	/**
	 * @inheritDoc
	 */
	public function process() {
		return $this->success( [ 'wordpress-plugins' => $this->collect() ] );
	}

	/**
	 * @return array                                associative: PluginFile => PluginData
	 * @see plugins.php
	 * @see class-wp-plugins-list-table.php
	 */
	public function collect() {

//			$this->prepThirdPartyPlugins(); TODO
		$aPlugins = $this->getInstalledPlugins( $this->getDesiredPluginAttributes() );

		$oUpdates = $this->loadWP()
						 ->updatesGather( 'plugins', $this->isForceUpdateCheck() ); // option to do another update check? force it?

		$aAutoUpdates = $this->getAutoUpdates( 'plugins' );
		$sServicePluginBaseFile = ICWP_Plugin::getController()->getPluginBaseFile();

		foreach ( $aPlugins as $file => &$data ) {
			$data[ 'active' ] = is_plugin_active( $file );
			$data[ 'auto_update' ] = (int)in_array( $file, $aAutoUpdates );
			$data[ 'file' ] = $file;
			$data[ 'is_service_plugin' ] = ( $file == $sServicePluginBaseFile );
			$data[ 'network_active' ] = is_plugin_active_for_network( $file );
			$data[ 'update_available' ] = isset( $oUpdates->response[ $file ] ) ? 1 : 0;
			$data[ 'update_info' ] = '';

			if ( $data[ 'update_available' ] ) {
				$oUpdateInfo = $oUpdates->response[ $file ];
				if ( isset( $oUpdateInfo->sections ) ) {
					unset( $oUpdateInfo->sections );
				}
				if ( isset( $oUpdateInfo->changelog ) ) {
					unset( $oUpdateInfo->changelog );
				}

				$data[ 'update_info' ] = json_encode( $oUpdateInfo );
				if ( !empty( $oUpdateInfo->slug ) ) {
					$data[ 'slug' ] = $oUpdateInfo->slug;
				}
			}

			// $oCurrentUpdates->no_update seems to be relatively new
			if ( empty( $data[ 'slug' ] ) && !empty( $oUpdates->no_update[ $file ]->slug ) ) {
				$data[ 'slug' ] = $oUpdates->no_update[ $file ]->slug;
			}
		}
		return $aPlugins;
	}

	/**
	 * Gets all the installed plugin and filters
	 * out unnecessary information based on "desired attributes"
	 * @param array $aDesiredAttributes
	 * @return array
	 */
	protected function getInstalledPlugins( $aDesiredAttributes = null ) {
		$aPlugins = $this->loadWpPlugins()->getPlugins();
		if ( !empty( $aDesiredAttributes ) ) {
			foreach ( $aPlugins as $sPluginFile => $aData ) {
				$aPlugins[ $sPluginFile ] = array_intersect_key( $aData, array_flip( $aDesiredAttributes ) );
			}
		}
		return $aPlugins;
	}

	/**
	 * @return array
	 */
	protected function getDesiredPluginAttributes() {
		return [
			'Name',
			'PluginURI',
			'Version',
			'Network',
			'slug',
			'Version'
		];
	}

	/**
	 * Manual preparation for third party plugin update checking that hook into 'init' so we can't "grab" them
	 */
	public function prepThirdPartyPlugins() {
		//Headway Blocks
		$this->doHeadwayBlocks();
		//Soliloquy Slider
		$this->doSoliloquy();
		//WP Migrate DB Pro
		$this->doWpMigrateDbPro();
		//White Label Branding
		$this->doWhiteLabelBranding();
		$this->doMisc();
		//Yoast SEO Plugin
		$this->doYoastSeo();
		//Advanced Custom Fields Pro Plugin
		$this->doAdvancedCustomFieldsPro();
		//Handle Backup Buddy
		$this->doIThemes();
	}
}