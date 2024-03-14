<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Theme;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Activate extends Base {

	public function process() :ApiResponse {
		return $this->success( [
			'result'           => $this->loadWpFunctionsThemes()->activate( $this->getFile() ),
			'wordpress-themes' => $this->getWpCollector()->collectWordpressThemes(),
			//Need to send back all themes, so we can update the one that got deactivated
		] );
	}
}