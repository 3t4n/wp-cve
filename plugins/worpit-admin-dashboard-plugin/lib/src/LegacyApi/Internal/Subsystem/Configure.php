<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Subsystem;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;
use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Configure extends LegacyApi\Internal\Base {

	// Copied from iCWP::Icwp_Subsystem_Handler
	const SYS_AUTOUPDATES = 0;
	const SYS_GOOGLEANALYTICS = 1;
	const SYS_SITESECURITY = 3;
	const SYS_WHITELABEL = 4;
	const SYS_PLUGIN = 10;

	public function process() :ApiResponse {

		$sys = $this->getSubSystem();
		if ( \is_null( $sys ) ) {
			$this->fail( sprintf( 'Not a supported subsystem: %s', $this->getActionParam( 'subsystem_id' ) ) );
		}

		$this->applyOptions();

		return $this->success( [
			'options' => $this->getStoredOptions()
		] );
	}

	protected function applyOptions() {
		$opts = $this->getOptionsParams();
		if ( !empty( $opts ) ) {
			$sys = $this->getSubSystem();

			// Only set enable status if the enable key is actually present.
			if ( isset( $opts[ 'enable' ] ) ) {
				$sys->setIsMainFeatureEnabled( $opts[ 'enable' ] );
				unset( $opts[ 'enable' ] );
			}
			$sys->setOptions( $opts );
		}
	}

	protected function getOptionsParams() :array {
		$opts = $this->getActionParam( 'subsystem_options', [] );
		return \is_array( $opts ) ? $opts : [];
	}

	protected function getStoredOptions() :array {
		return $this->getSubSystem()->getOptionsVo()->getStoredOptions();
	}

	/**
	 * @return \ICWP_APP_FeatureHandler_BaseApp|mixed|null
	 */
	protected function getSubSystem() {
		switch ( $this->getActionParam( 'subsystem_id' ) ) {
			case self::SYS_AUTOUPDATES:
				$sys = \ICWP_Plugin::GetAutoUpdatesSystem();
				break;
			case self::SYS_GOOGLEANALYTICS:
				$sys = \ICWP_Plugin::GetGoogleAnalyticsSystem();
				break;
			case self::SYS_SITESECURITY:
				$sys = \ICWP_Plugin::GetSecuritySystem();
				break;
			case self::SYS_WHITELABEL:
				$sys = \ICWP_Plugin::GetWhiteLabelSystem();
				break;
			case self::SYS_PLUGIN:
				$sys = \ICWP_Plugin::GetPluginSystem();
				break;
			default:
				$sys = null;
				break;
		}
		return $sys;
	}
}