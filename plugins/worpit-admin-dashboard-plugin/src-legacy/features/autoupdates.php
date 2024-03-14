<?php

if ( class_exists( 'ICWP_APP_FeatureHandler_Autoupdates', false ) ) {
	return;
}

require_once( dirname(__FILE__).'/base_app.php' );

class ICWP_APP_FeatureHandler_Autoupdates extends ICWP_APP_FeatureHandler_BaseApp {

	/**
	 * @param string $sContext
	 * @return array
	 */
	public function getAutoUpdates( $sContext = 'plugins' ) {
		$WP = $this->loadWP();

		$items = $this->getOpt( 'auto_update_'.$sContext, array() );
		if ( !is_array( $items ) ) {
			$items = array();
		}

		if ( $sContext == 'plugins' ) {
			$items = array_intersect( array_keys( $this->loadWpPlugins()->getPlugins() ), $items );
		}

		// handover storage to WordPress itself
		if ( $WP->getWordpressIsAtLeastVersion( '5.5' ) ) {
			$wpItems = $this->loadWP()->getOption( 'auto_update_'.$sContext, array() );
			if ( !is_array( $wpItems ) ) {
				$wpItems = array();
			}
			$aAutoUpdateItems = array_unique( array_merge( $items, $wpItems ) );

			if ( count( $aAutoUpdateItems ) != count( $items )
				 || count( array_diff( $aAutoUpdateItems, $items ) ) > 0
				 || count( array_diff( $items, $aAutoUpdateItems ) ) > 0 ) {
				$WP->updateOption( 'auto_update_'.$sContext, $aAutoUpdateItems );
			}
			$this->setOpt( 'auto_update_'.$sContext, array() );
		}
		else {
			$aAutoUpdateItems = $items;
		}

		return $aAutoUpdateItems;
	}

	/**
	 * @param array  $items
	 * @param string $context
	 * @deprecated
	 */
	public function setAutoUpdates( $items, $context = 'plugins' ) {
		$this->storeAutoUpdates( $items, $context );
	}

	/**
	 * @param string $sSlug
	 * @param bool $bSetOn
	 * @param string $sContext
	 */
	public function setAutoUpdate( $sSlug, $bSetOn = false, $sContext = 'plugins' ) {
		$items = $this->getAutoUpdates( $sContext );

		if ( $bSetOn ) {
			$items[] = $sSlug;
		}
		else {
			$items = array_diff( $items, array( $sSlug ) );
		}
		$this->storeAutoUpdates( $items, $sContext );
	}

	/**
	 * @param array  $items
	 * @param string $context
	 * @return $this
	 */
	private function storeAutoUpdates( $items, $context = 'plugins' ) {
		$WP = $this->loadWP();

		if ( is_array( $items ) ) {
			if ( $WP->getWordpressIsAtLeastVersion( '5.5' ) ) {
				$WP->updateOption( 'auto_update_'.$context, array_unique( $items ) );
			}
			else {
				$this->setOpt( 'auto_update_'.$context, array_unique( $items ) );
			}
		}
		return $this;
	}
}