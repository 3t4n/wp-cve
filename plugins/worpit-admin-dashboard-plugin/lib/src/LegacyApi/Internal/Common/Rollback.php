<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Common;

trait Rollback {

	/**
	 * @param string $itemSlug
	 * @param string $context
	 */
	public function prepRollbackData( $itemSlug, $context = 'plugins' ) :bool {
		$success = false;
		if ( $context === 'themes' || strpos( $itemSlug, '/' ) > 0 ) {

			if ( $context === 'plugins' ) {
				$dirname = dirname( $itemSlug );
				$fullPathToAsset = path_join( WP_PLUGIN_DIR, $dirname );
			}
			else {
				$dirname = $itemSlug;
				$fullPathToAsset = $this->loadWpFunctionsThemes()->getTheme( $itemSlug )->get_stylesheet_directory();
			}

			$destinationPath = path_join( $this->getRollbackBaseDir(), $context.DIRECTORY_SEPARATOR.$dirname );
			if ( is_dir( $destinationPath ) ) {
				global $wp_filesystem;
				$wp_filesystem->rmdir( $destinationPath, true );
			}

			wp_mkdir_p( $destinationPath );
			$success = copy_dir( $fullPathToAsset, $destinationPath ) === true;
		}

		return $success;
	}

	protected function getRollbackBaseDir() :string {
		return path_join( WP_CONTENT_DIR, 'icwp/rollback/' );
	}
}