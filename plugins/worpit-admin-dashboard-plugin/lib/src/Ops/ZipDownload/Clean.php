<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Ops\ZipDownload;

class Clean extends Base {

	public function run() {
		try {
			$dir = $this->getZipsDir( false );
			if ( !empty( $dir ) && $this->loadFS()->isDir( $dir ) ) {
				$this->loadFS()->deleteDir( $dir );
			}
		}
		catch ( \Exception $e ) {
		}
	}
}