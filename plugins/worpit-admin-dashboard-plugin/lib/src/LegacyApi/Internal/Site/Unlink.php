<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Site;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Unlink extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {
		if ( \class_exists( 'ICWP_Plugin', false ) && \method_exists( 'ICWP_Plugin', 'getController' ) ) {
			$modPlugin = \ICWP_Plugin::getController()->loadCorePluginFeatureHandler();
			$modPlugin->setOpt( 'key', $this->getActionParam( 'auth_key' ) );
			$modPlugin->setOpt( 'pin', '' );
			$modPlugin->setOpt( 'assigned', 'N' );
			$modPlugin->setOpt( 'assigned_to', '' );
			$modPlugin->savePluginOptions();
			deactivate_plugins( \ICWP_Plugin::getController()->getPluginBaseFile(), '', is_multisite() );
		}
		else {
			do_action( 'icwp-app-SiteUnlink' );
		}
		return $this->getStandardResponse();
	}
}