<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\User;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Logout extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {
		if ( $this->loadWpUsers()->isUserLoggedIn() ) {
			$this->loadWpUsers()->logoutUser();
		}
		return $this->success();
	}
}