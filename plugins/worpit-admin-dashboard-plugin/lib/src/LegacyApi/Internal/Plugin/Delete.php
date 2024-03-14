<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Plugin;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Delete extends Base {

	public function process() :ApiResponse {

		$result = $this->loadWpPlugins()->delete(
			$this->getFile(),
			$this->getActionParam( 'site_is_wpms' )
		);

		wp_cache_flush(); // since we've deleted a plugin, we need to ensure our collection is up-to-date rebuild.

		$data = [
			'result'            => $result,
			'wordpress-plugins' => $this->getWpCollector()->collectWordpressPlugins()
		];
		return $result ? $this->success( $data ) : $this->fail( $data );
	}
}