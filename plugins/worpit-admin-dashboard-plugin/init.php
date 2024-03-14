<?php

use FernleafSystems\Wordpress\Plugin\iControlWP\Controller;

/** @var string $sIcwpPluginRootFile */

if ( \version_compare( PHP_VERSION, '7.0', '<' ) ) {
	require_once( \dirname( __FILE__ ).'/src-legacy/common/icwp-foundation.php' );
	require_once( \dirname( __FILE__ ).'/icwp-plugin-controller.php' );
	$oICWP_App_Controller = ICWP_APP_Plugin_Controller::GetInstance( $sIcwpPluginRootFile );
}
else {
	require_once( \dirname( __FILE__ ).'/lib/vendor/autoload.php' );
	$oICWP_App_Controller = Controller::GetInstance( $sIcwpPluginRootFile );
}

class ICWP_Plugin {

	/**
	 * @var ICWP_APP_Plugin_Controller
	 */
	protected static $oPluginController;

	/**
	 * @param \ICWP_APP_Plugin_Controller|Controller $con
	 */
	public function __construct( $con ) {
		self::$oPluginController = $con;
		$this->getController()->loadAllFeatures();
	}

	/**
	 * @return \ICWP_APP_Plugin_Controller|Controller
	 */
	public static function getController() {
		return self::$oPluginController;
	}

	/**
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 * @deprecated 4.3.0
	 */
	public static function getOption( $key, $default = false ) {
		return self::getController()->loadCorePluginFeatureHandler()->getOpt( $key, $default );
	}

	/**
	 * @param string $key
	 * @param bool   $mValue
	 * @return mixed
	 * @deprecated 4.3.0
	 */
	public static function updateOption( $key, $mValue ) {
		$oCorePluginFeature = self::getController()->loadCorePluginFeatureHandler();
		$oCorePluginFeature->setOpt( $key, $mValue );
		$oCorePluginFeature->savePluginOptions();
		return true;
	}

	/**
	 * @return string
	 * @deprecated 4.3.0
	 */
	public static function GetAssignedToEmail() {
		return self::getController()->loadCorePluginFeatureHandler()->getAssignedTo();
	}

	/**
	 * @return bool
	 * @deprecated 4.3.0
	 */
	public static function GetHandshakingEnabled() {
		return self::getController()->loadCorePluginFeatureHandler()->getCanHandshake();
	}

	/**
	 * @return bool
	 * @deprecated 4.3.0
	 */
	public static function IsLinked() {
		return self::getController()->loadCorePluginFeatureHandler()->getIsSiteLinked();
	}

	/**
	 * @return int
	 */
	public static function GetVersion() {
		return self::getController()->getVersion();
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_AutoUpdates
	 */
	public static function GetAutoUpdatesSystem() {
		return self::getController()->loadFeatureHandler( [ 'slug' => 'autoupdates' ] );
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_GoogleAnalytics
	 */
	public static function GetGoogleAnalyticsSystem() {
		return self::getController()->loadFeatureHandler( [ 'slug' => 'google_analytics' ] );
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_Plugin
	 */
	public static function GetPluginSystem() {
		return self::getController()->loadCorePluginFeatureHandler();
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_WhiteLabel
	 */
	public static function GetWhiteLabelSystem() {
		return self::getController()->loadFeatureHandler( [ 'slug' => 'whitelabel' ] );
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_Security
	 */
	public static function GetSecuritySystem() {
		return self::getController()->loadFeatureHandler( [ 'slug' => 'security' ] );
	}
}

if ( !class_exists( 'Worpit_Plugin' ) ) {
	class Worpit_Plugin extends ICWP_Plugin {
	}
}

$g_oWorpit = new ICWP_Plugin( $oICWP_App_Controller );