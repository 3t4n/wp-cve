<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Plugin;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Activate extends Base {

	public function process() :ApiResponse {
		$file = $this->getFile();
		return $this->success( [
			'result'        => $this->loadWpPlugins()->activate( $file, $this->getActionParam( 'site_is_wpms' ) ),
			'single-plugin' => $this->getWpCollector()->collectWordpressPlugins( $file )[ $file ]
		] );
	}
}