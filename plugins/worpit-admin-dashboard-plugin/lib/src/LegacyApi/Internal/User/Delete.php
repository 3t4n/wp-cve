<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\User;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Delete extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {
		try {
			return $this->success( [
				'result' => $this->loadWpUsers()->deleteUser(
					(int)$this->getActionParam( 'user_id' ),
					false,
					$this->getActionParam( 'reassign_id' )
				)
			] );
		}
		catch ( \Exception $e ) {
			return $this->fail( $e->getMessage() );
		}
	}
}