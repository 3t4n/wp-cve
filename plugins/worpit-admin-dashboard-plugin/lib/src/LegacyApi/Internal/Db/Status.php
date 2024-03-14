<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Db;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Status extends Base {

	public function process() :ApiResponse {
		try {
			return $this->success( $this->getDatabaseTableStatus() );
		}
		catch ( \Exception $e ) {
			return $this->fail( $e->getMessage() );
		}
	}
}