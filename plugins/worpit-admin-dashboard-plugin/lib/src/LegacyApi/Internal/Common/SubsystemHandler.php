<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Common;

class SubsystemHandler {

	const SYS_AUTOUPDATES = 0;
	const SYS_GOOGLEANALYTICS = 1;
	const SYS_SITESECURITY = 3;
	const SYS_WHITELABEL = 4;
	const SYS_PLUGIN = 10;

	/**
	 * @var SubsystemHandler
	 */
	protected static $oInstance;

	public function __construct() {
	}

	/**
	 * @return SubsystemHandler
	 */
	public static function & GetInstance() {
		if ( is_null( self::$oInstance ) ) {
			self::$oInstance = new SubsystemHandler();
		}
		return self::$oInstance;
	}

	/**
	 * @return bool[]
	 */
	public function collectSubSystemInfo() :array {
		return [
			'autoupdates_enabled'     => $this->getIsSystemEnabled( self::SYS_AUTOUPDATES ),
			'googleanalytics_enabled' => $this->getIsSystemEnabled( self::SYS_GOOGLEANALYTICS ),
			'sitesecurity_enabled'    => $this->getIsSystemEnabled( self::SYS_SITESECURITY ),
			'whitelabel_enabled'      => $this->getIsSystemEnabled( self::SYS_WHITELABEL ),
		];
	}

	/**
	 * @param $nSystem
	 * @return \ICWP_APP_FeatureHandler_BaseApp|mixed
	 * @throws \Exception
	 */
	public function getPluginSubSystem( int $nSystem ) {
		switch ( $nSystem ) {
			case self::SYS_AUTOUPDATES:
				$oSys = \ICWP_Plugin::GetAutoUpdatesSystem();
				break;
			case self::SYS_GOOGLEANALYTICS:
				$oSys = \ICWP_Plugin::GetGoogleAnalyticsSystem();
				break;
			case self::SYS_SITESECURITY:
				$oSys = \ICWP_Plugin::GetSecuritySystem();
				break;
			case self::SYS_WHITELABEL:
				$oSys = \ICWP_Plugin::GetWhiteLabelSystem();
				break;
			case self::SYS_PLUGIN:
				$oSys = \ICWP_Plugin::GetPluginSystem();
				break;
			default:
				throw new \Exception( 'Plugin subsystem could not be loaded as it is not supported' );
				break;
		}
		return $oSys;
	}

	/**
	 * @param int $nSystem
	 * @return bool
	 */
	public function isPluginSubSystemSupported( $nSystem ) :bool {
		$bSupported = true;
		try {
			$this->getPluginSubSystem( $nSystem );
		}
		catch ( \Exception $oE ) {
			$bSupported = false;
		}
		return $bSupported;
	}

	/**
	 * @param $nSystemId
	 * @return array
	 * @throws \Exception
	 */
	public function getSystemOptions( $nSystemId ) {
		return $this->getPluginSubSystem( $nSystemId )->getOptionsVo()->getStoredOptions();
	}

	/**
	 * @param int   $nSystemId
	 * @param array $aOptions
	 * @return bool
	 */
	public function setSystemOptions( $nSystemId, $aOptions ) {
		try {
			$this->getPluginSubSystem( $nSystemId )->setOptions( $aOptions );
			$bSuccess = true;
		}
		catch ( \Exception $oE ) {
			$bSuccess = false;
		}
		return $bSuccess;
	}

	/**
	 * @param int $nSystemId
	 * @return bool
	 */
	public function getIsSystemEnabled( $nSystemId ) {
		try {
			$bEnabled = $this->getPluginSubSystem( $nSystemId )->getIsMainFeatureEnabled();
		}
		catch ( \Exception $e ) {
			$bEnabled = false;
		}
		return $bEnabled;
	}

	/**
	 * @param int  $nSystemId
	 * @param bool $bEnable
	 * @return bool
	 */
	public function setSystemEnabled( $nSystemId, $bEnable = true ) {
		try {
			$this->getPluginSubSystem( $nSystemId )->setIsMainFeatureEnabled( $bEnable );
		}
		catch ( \Exception $oE ) {
			return false;
		}
		return true;
	}
}