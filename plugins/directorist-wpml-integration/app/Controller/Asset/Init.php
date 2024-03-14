<?php

namespace Directorist_WPML_Integration\Controller\Asset;

use Directorist_WPML_Integration\Helper;

class Init {
	
	/**
	 * Constuctor
	 * 
     * @return void
	 */
	function __construct() {

		// Register Enqueuers
        $enqueuers = $this->get_assets_enqueuers();
        Helper\Serve::register_services( $enqueuers );

	}

	/**
	 * Get assets enqueuers
	 * 
     * @return array $enqueuers
	 */
	protected function get_assets_enqueuers() {
        return [
            AdminAsset::class,
            PublicAsset::class,
        ];
    }
}