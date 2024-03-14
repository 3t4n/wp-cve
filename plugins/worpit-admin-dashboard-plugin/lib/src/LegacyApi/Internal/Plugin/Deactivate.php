<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Plugin;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Deactivate extends Base {

	public function process() :ApiResponse {
		$WP = $this->loadWpPlugins();

		$file = $this->getFile();
		$WP->deactivate( $file, $this->getActionParam( 'site_is_wpms' ) );
		return $this->success( [
			'result'        => !$WP->getIsActive( $file ),
			'single-plugin' => $this->getWpCollector()->collectWordpressPlugins( $file )[ $file ]
		] );
	}
}