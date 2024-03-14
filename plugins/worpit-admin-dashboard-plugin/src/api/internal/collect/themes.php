<?php

class ICWP_APP_Api_Internal_Collect_Themes extends ICWP_APP_Api_Internal_Collect_Base {

	/**
	 * @inheritDoc
	 */
	public function process() {
		return $this->success( [ 'wordpress-themes' => $this->collect() ] );
	}

	/**
	 * @return array - associative: ThemeStylesheet => ThemeData
	 */
	public function collect() {

//			$this->prepThirdPartyThemes(); TODO
		$themes = $this->getInstalledThemes();
		$oUpdates = $this->loadWP()
						 ->updatesGather( 'themes', $this->isForceUpdateCheck() ); // option to do another update check? force it?
		$aAutoUpdates = $this->getAutoUpdates( 'themes' );

		$sActiveThemeStylesheet = $this->loadWpFunctionsThemes()->getCurrent()->get_stylesheet();

		foreach ( $themes as $stylesheet => &$data ) {
			$data[ 'active' ] = ( $stylesheet == $sActiveThemeStylesheet );
			$data[ 'auto_update' ] = in_array( $stylesheet, $aAutoUpdates );
			$data[ 'update_available' ] = isset( $oUpdates->response[ $data[ 'Stylesheet' ] ] ) ? 1 : 0;
			$data[ 'update_info' ] = '';

			if ( $data[ 'update_available' ] ) {
				$oUpdateInfo = $oUpdates->response[ $data[ 'Stylesheet' ] ];

				if ( isset( $oUpdateInfo[ 'sections' ] ) ) {
					unset( $oUpdateInfo[ 'sections' ] ); // TODO: Filter unwanted data using set array of keys
				}
				$data[ 'update_info' ] = json_encode( $oUpdateInfo );
			}
		}
		return $themes;
	}

	/**
	 * The method for getting installed themes changed in version 3.4+ so this function normalizes everything.
	 * @return array
	 */
	public function getInstalledThemes() {

		$aThemes = [];

		if ( $this->loadWP()->getWordpressIsAtLeastVersion( '3.4' ) ) {

			/** @var WP_Theme[] $aThemeObjects */
			$aThemeObjects = $this->loadWpFunctionsThemes()->getThemes();

			$bHasChildThemes = false;

			foreach ( $aThemeObjects as $oTheme ) {

				$bIsChildTheme = ( $oTheme->offsetGet( 'Template' ) != $oTheme->offsetGet( 'Stylesheet' ) );
				$bHasChildThemes = $bHasChildThemes || $bIsChildTheme;

				$sStylesheet = $oTheme->offsetGet( 'Stylesheet' );
				$aThemes[ $sStylesheet ] = [
					'Name'           => $oTheme->display( 'Name' ),
					'Title'          => $oTheme->offsetGet( 'Title' ),
					'Description'    => $oTheme->offsetGet( 'Description' ),
					'Author'         => $oTheme->offsetGet( 'Author' ),
					'Author Name'    => $oTheme->offsetGet( 'Author Name' ),
					'Author URI'     => $oTheme->offsetGet( 'Author URI' ),
					'Version'        => $oTheme->offsetGet( 'Version' ),
					'Template'       => $oTheme->offsetGet( 'Template' ),
					'Stylesheet'     => $sStylesheet,
					//'Template Dir'		=> $oTheme->offsetGet( 'Template Dir' ),
					//'Stylesheet Dir'	=> $oTheme->offsetGet( 'Stylesheet Dir' ),
					'Theme Root'     => $oTheme->offsetGet( 'Theme Root' ),
					'Theme Root URI' => $oTheme->offsetGet( 'Theme Root URI' ),

					'Status' => $oTheme->offsetGet( 'Status' ),

					'IsChild'        => $bIsChildTheme ? 1 : 0,
					'IsParent'       => 0,

					// We add our own
					'network_active' => $oTheme->is_allowed( 'network' )
				];
				$aThemes[ $sStylesheet ] = array_intersect_key(
					$aThemes[ $sStylesheet ],
					array_flip( $this->getDesiredThemeAttributes() )
				);
			}

			if ( $bHasChildThemes ) {
				foreach ( $aThemes as $aMaybeChildTheme ) {
					if ( $aMaybeChildTheme[ 'IsChild' ] ) {
						foreach ( $aThemes as &$aMaybeParentTheme ) {
							if ( $aMaybeParentTheme[ 'Stylesheet' ] == $aMaybeChildTheme[ 'Template' ] ) {
								$aMaybeParentTheme[ 'IsParent' ] = 1;
							}
						}
					}
				}
			}
		}
		else {
			$aThemes = $this->loadWpFunctionsThemes()->getThemes();
			$fIsMultisite = is_multisite();
			$aNetworkAllowedThemes = function_exists( 'get_site_allowed_themes' ) ? get_site_allowed_themes() : [];

			// We add our own here because it's easier due to WordPress differences
			foreach ( $aThemes as $sName => $aData ) {
				$sStylesheet = $aData[ 'Stylesheet' ];
				$aData[ 'network_active' ] = $fIsMultisite && isset( $aNetworkAllowedThemes[ $sStylesheet ] );
				unset( $aThemes[ $sName ] );
				$aThemes[ $sStylesheet ] = $aData;
			}
		}

		return $aThemes;
	}

	/**
	 * @return array
	 */
	protected function getDesiredThemeAttributes() {
		return [
			'Name',
			'Version',
			'Template',
			'Stylesheet',
			'IsChild',
			'IsParent',
			'Network',
			'active',
			'network_active'
		];
	}
}