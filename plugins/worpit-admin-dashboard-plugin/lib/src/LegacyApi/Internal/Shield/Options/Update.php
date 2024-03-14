<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Shield\Options;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Update extends LegacyApi\Internal\Shield\Base {

	public function process() :LegacyApi\ApiResponse {
		if ( !$this->isInstalled() ) {
			return $this->success( [
				'version' => 'not-installed' // \iControlWP\Shield\ShieldPluginConnectionStatus::REMOTE_NOT_INSTALLED
			] );
		}

		$con = $this->getShieldController();

		// Get the options to update from the action parameters
		foreach ( $this->getActionParam( 'shield_options' ) as $slug => $options ) {
			if ( isset( $con->modules[ $slug ] ) ) {
				$mod = $con->modules[ $slug ];
				$mod->opts()->setOptionsValues( $options );
			}
		}

		return $this->success();
	}
}