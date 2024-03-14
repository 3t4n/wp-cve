<?php

if ( class_exists( 'ICWP_APP_Processor_Autoupdates', false ) ) {
	return;
}

require_once( dirname(__FILE__).'/base_app.php' );

class ICWP_APP_Processor_Autoupdates extends ICWP_APP_Processor_BaseApp {

	public function run() {
		$nFilterPriority = $this->getHookPriority();
		if ( !$this->loadWP()->getWordpressIsAtLeastVersion( '5.5' ) ) {
			add_filter( 'auto_update_plugin', array( $this, 'autoupdate_plugins' ), $nFilterPriority, 2 );
			add_filter( 'auto_update_theme', array( $this, 'autoupdate_themes' ), $nFilterPriority, 2 );
		}
	}

	/**
	 * @return boolean
	 */
	public function getIfForceRunAutoupdates() {
		return false;
	}

	public function force_run_autoupdates() {
	}

	/**
	 * This is a filter method designed to say whether a major core WordPress upgrade should be permitted,
	 * based on the plugin settings.
	 *
	 * @param boolean $bUpdate
	 * @return boolean
	 */
	public function autoupdate_core_major( $bUpdate ) {
		return $bUpdate;
	}

	/**
	 * This is a filter method designed to say whether a minor core WordPress upgrade should be permitted,
	 * based on the plugin settings.
	 *
	 * @param boolean $bUpdate
	 * @return boolean
	 */
	public function autoupdate_core_minor( $bUpdate ) {
		return $bUpdate;
	}

	/**
	 * @param bool   $bUpdate
	 * @param string $sSlug
	 * @return bool
	 */
	public function autoupdate_translations( $bUpdate, $sSlug ) {
		return $bUpdate;
	}

	/**
	 * @param bool            $bDoAutoUpdate
	 * @param stdClass|string $mItem
	 * @return bool
	 */
	public function autoupdate_plugins( $bDoAutoUpdate, $mItem ) {

		// first, is global auto updates for plugins set
		if ( $this->getIsOption( 'enable_autoupdate_plugins', 'Y' ) ) {
			return true;
		}

		if ( is_object( $mItem ) && isset( $mItem->plugin ) )  { // WP 3.8.2+
			$sItemFile = $mItem->plugin;
		}
		else if ( is_string( $mItem ) ) { // WP pre-3.8.2
			$sItemFile = $mItem;
		}
		// at this point we don't have a slug to use so we just return the current update setting
		else {
			return $bDoAutoUpdate;
		}

		/** @var \ICWP_APP_FeatureHandler_Autoupdates $oFO */
		$oFO = $this->getFeatureOptions();
		$aAutoupdateFiles = $oFO->getAutoUpdates( 'plugins' );
		if ( !empty( $aAutoupdateFiles ) && is_array( $aAutoupdateFiles ) && in_array( $sItemFile, $aAutoupdateFiles ) ) {
			$bDoAutoUpdate = true;
		}
		return $bDoAutoUpdate;
	}

	/**
	 * This is a filter method designed to say whether WordPress theme upgrades should be permitted,
	 * based on the plugin settings.
	 *
	 * @param boolean $bDoAutoUpdate
	 * @param stdClass|string $mItem
	 * @return bool
	 */
	public function autoupdate_themes( $bDoAutoUpdate, $mItem ) {

		// first, is global auto updates for themes set
		if ( $this->getIsOption( 'enable_autoupdate_themes', 'Y' ) ) {
			return true;
		}

		if ( is_object( $mItem ) && isset( $mItem->theme ) ) { // WP 3.8.2+
			$sItemFile = $mItem->theme;
		}
		else if ( is_string( $mItem ) ) { // WP pre-3.8.2
			$sItemFile = $mItem;
		}
		// at this point we don't have a slug to use so we just return the current update setting
		else {
			return $bDoAutoUpdate;
		}

		$aAutoupdateFiles = $this->getFeatureOptions()->getAutoUpdates( 'themes' );
		if ( !empty( $aAutoupdateFiles ) && is_array( $aAutoupdateFiles ) && in_array( $sItemFile, $aAutoupdateFiles ) ) {
			$bDoAutoUpdate = true;
		}
		return $bDoAutoUpdate;
	}

	/**
	 * This is a filter method designed to say whether WordPress automatic upgrades should be permitted
	 * if a version control system is detected.
	 *
	 * @param $checkout
	 * @param $context
	 * @return boolean
	 */
	public function disable_for_vcs( $checkout, $context ) {
		return false;
	}

	/**
	 * A filter on whether or not a notification email is send after core upgrades are attempted.
	 *
	 * @param boolean $bSendEmail
	 * @return boolean
	 */
	public function autoupdate_send_email( $bSendEmail ) {
		return $this->getIsOption( 'enable_upgrade_notification_email', 'Y' );
	}

	/**
	 * A filter on the target email address to which to send upgrade notification emails.
	 *
	 * @param array $aEmailParams
	 * @return array
	 */
	public function autoupdate_email_override( $aEmailParams ) {
		return $aEmailParams;
	}

	/**
	 * Removes all filters that have been added from auto-update related WordPress filters
	 */
	protected function removeAllAutoupdateFilters() {
		$aFilters = array(
			'allow_minor_auto_core_updates',
			'allow_major_auto_core_updates',
			'auto_update_translation',
			'auto_update_plugin',
			'auto_update_theme',
			'automatic_updates_is_vcs_checkout',
			'automatic_updater_disabled'
		);
		foreach( $aFilters as $sFilter ) {
			remove_all_filters( $sFilter );
		}
	}

	/**
	 * @return int
	 */
	protected function getHookPriority() {
		return $this->getOption( 'action_hook_priority', 1001 );
	}
}