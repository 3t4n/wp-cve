<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Shield;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Delete extends Base {

	public function process() :ApiResponse {
		$success = true;

		if ( $this->isInstalled() ) {

			$con = $this->getShieldController();
			add_filter( $con->prefix( 'bypass_is_plugin_admin' ), '__return_true', PHP_INT_MAX );

			$baseFile = $con->base_file;
			deactivate_plugins( $baseFile ) ;
			uninstall_plugin( $baseFile );

			$success = $this->loadWpPlugins()->getIsInstalled( $baseFile );
		}

		return $this->success( [
			'success' => $success
		] );
	}
}