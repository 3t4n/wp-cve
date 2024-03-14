<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Download;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;
use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Plugin\Base;
use FernleafSystems\Wordpress\Plugin\iControlWP\Ops\ZipDownload\Create;

class Plugin extends Base {

	public function process() :ApiResponse {
		try {
			return $this->success( [
				'success'  => true,
				'zip_data' => ( new Create() )
					->setCon( $this->getCon() )
					->plugin( $this->getFile() ),
			] );
		}
		catch ( \Exception $e ) {
			return $this->fail( $e->getMessage() );
		}
	}
}