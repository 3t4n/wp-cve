<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Shield\Options;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;
use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Shield;
use FernleafSystems\Wordpress\Plugin\Shield\Controller\Plugin\PluginNavs;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\BaseShield\ModCon;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\HackGuard\Scan\Results\Counts;

class Export extends Shield\Base {

	public function process() :ApiResponse {
		if ( !$this->isInstalled() ) {
			return $this->success( [
				'version' => 'not-installed' // \iControlWP\Shield\ShieldPluginConnectionStatus::REMOTE_NOT_INSTALLED
			] );
		}

		$shield = $this->getShieldController();

		$urls = [];

		if ( \version_compare( $shield->cfg->version(), '18.0', '>=' ) ) {
			$pluginURLs = $shield->plugin_urls;
			foreach ( $shield->modules as $module ) {
				$urls[ 'module_'.$module->cfg->slug ] = $pluginURLs->modCfg( $module );
			}
			$urls = \array_merge( $urls, [
				'overview'      => $pluginURLs->adminHome(),
				'scans_run'     => $pluginURLs->adminTopNav( PluginNavs::NAV_SCANS, PluginNavs::SUBNAV_SCANS_RUN ),
				'scans_results' => $pluginURLs->adminTopNav( PluginNavs::NAV_SCANS, PluginNavs::SUBNAV_SCANS_RESULTS ),
				'audit_trail'   => $pluginURLs->adminTopNav( PluginNavs::NAV_ACTIVITY, PluginNavs::SUBNAV_LOGS ),
				'ips'           => $pluginURLs->adminTopNav( PluginNavs::NAV_IPS, PluginNavs::SUBNAV_IPS_RULES ),
			] );
		}

		// store the Shield Central license
		$this->updateProStatus();

		return $this->success( [
			'version'          => $shield->cfg->version(),
			'is_pro'           => $shield->isPremiumActive() ? 1 : 0,
			'urls'             => $urls,
			'scan_results'     => $this->countItemsForEachScan(),
			'exported_options' => \array_map(
				function ( $mod ) {
					/** @var ModCon $mod */
					return $mod->opts()->getAllOptionsValues();
				},
				$shield->modules
			)
		] );
	}

	private function countItemsForEachScan() :array {
		if ( \version_compare( $this->getShieldController()->cfg->version(), '18.0', '>=' ) ) {
			$counts = ( new Counts() )->all();
			$counts[ 'file_locker' ] = \count( ( new \FernleafSystems\Wordpress\Plugin\Shield\Modules\HackGuard\Lib\FileLocker\Ops\LoadFileLocks() )
				->withProblems() );
		}
		else {
			// file locker is a separate scan
			$counts = [];
		}
		return $counts;
	}

	private function updateProStatus() {
		if ( !$this->getShieldController()->isPremiumActive() ) {
			$license = $this->getShieldController()->getModule_License();
			if ( method_exists( $license, 'getLicenseHandler' ) ) {
				try {
					$license->getLicenseHandler()
							->verify( true );
					$license->getWpHashesTokenManager()
							->setCanRequestOverride( true )
							->getToken();
				}
				catch ( \Exception $e ) {
				}
			}
		}
	}
}