<?php

class ICWP_APP_WpUpgrades extends ICWP_APP_Foundation {

	/**
	 * @var ICWP_APP_WpUpgrades
	 */
	protected static $oInstance = null;

	/**
	 * @return ICWP_APP_WpUpgrades
	 */
	public static function GetInstance() {
		if ( is_null( self::$oInstance ) ) {
			self::$oInstance = new self();
		}
		return self::$oInstance;
	}
}

@include_once( ABSPATH.'wp-admin/includes/class-wp-upgrader.php' );