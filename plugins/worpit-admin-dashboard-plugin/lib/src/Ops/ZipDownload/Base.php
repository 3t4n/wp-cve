<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Ops\ZipDownload;

use FernleafSystems\Wordpress\Plugin\iControlWP\Traits\PluginControllerConsumer;

class Base extends \ICWP_APP_Foundation {

	use PluginControllerConsumer;

	/**
	 * @param bool $makeDir
	 * @return string
	 * @throws \Exception
	 */
	protected function getZipsDir( $makeDir = true ) :string {
		$FS = $this->loadFS();
		$zipsDir = path_join( $this->getCon()->getPath_Temp(), 'zips' );
		if ( $makeDir ) {
			$FS->mkdir( $zipsDir );
			if ( !$FS->isDir( $zipsDir ) ) {
				throw new \Exception( sprintf( 'Could not create temp dir to store zip: %s', $zipsDir ) );
			}
		}
		return $zipsDir;
	}
}
