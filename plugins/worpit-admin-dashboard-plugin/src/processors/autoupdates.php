<?php

class ICWP_APP_Processor_Autoupdates extends ICWP_APP_Processor_BaseApp {

	public function run() {
		$nFilterPriority = $this->getHookPriority();
		if ( !$this->loadWP()->getWordpressIsAtLeastVersion( '5.5' ) ) {
			add_filter( 'auto_update_plugin', [ $this, 'autoupdate_plugins' ], $nFilterPriority, 2 );
			add_filter( 'auto_update_theme', [ $this, 'autoupdate_themes' ], $nFilterPriority, 2 );
		}
	}

	/**
	 * @return bool
	 */
	public function getIfForceRunAutoupdates() {
		return false;
	}

	public function force_run_autoupdates() {
	}

	/**
	 * @param bool             $doUpdate
	 * @param \stdClass|string $mItem
	 * @return boolean
	 */
	public function autoupdate_plugins( $doUpdate, $mItem ) {

		if ( is_object( $mItem ) && isset( $mItem->plugin ) ) { // WP 3.8.2+
			$sItemFile = $mItem->plugin;
		}
		elseif ( is_string( $mItem ) ) { // WP pre-3.8.2
			$sItemFile = $mItem;
		}
		else { // we don't have a slug to use so we just return the current update setting
			return $doUpdate;
		}

		/** @var \ICWP_APP_FeatureHandler_Autoupdates $mod */
		$mod = $this->getFeatureOptions();
		if ( in_array( $sItemFile, $mod->getAutoUpdates( 'plugins' ) ) ) {
			$doUpdate = true;
		}

		return $doUpdate;
	}

	/**
	 * @param bool             $doUpdate
	 * @param \stdClass|string $mItem
	 * @return bool
	 */
	public function autoupdate_themes( $doUpdate, $mItem ) {
		if ( is_object( $mItem ) && isset( $mItem->theme ) ) { // WP 3.8.2+
			$sItemFile = $mItem->theme;
		}
		elseif ( is_string( $mItem ) ) { // WP pre-3.8.2
			$sItemFile = $mItem;
		}
		else {
			return $doUpdate;
		}

		/** @var \ICWP_APP_FeatureHandler_Autoupdates $mod */
		$mod = $this->getFeatureOptions();
		if ( in_array( $sItemFile, $mod->getAutoUpdates( 'themes' ) ) ) {
			$doUpdate = true;
		}
		return $doUpdate;
	}

	/**
	 * @return int
	 */
	protected function getHookPriority() {
		return $this->getOption( 'action_hook_priority', 1001 );
	}
}