<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Core;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Dbupgrade extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {
		$this->loadWP()->doWpUpgrade();
		return $this->success();
	}
}