<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Ops\ZipDownload;

use FernleafSystems\Wordpress\Plugin\iControlWP\Utilities\File\ZipDir;

class Create extends Base {

	/**
	 * @param string $file
	 * @return array
	 * @throws \Exception
	 */
	public function plugin( string $file ) {
		if ( !$this->loadWpPlugins()->getIsInstalled( $file ) ) {
			throw new \Exception( sprintf( 'Plugin for file is not installed: %s', $file ) );
		}
		return $this->createFrom( dirname( path_join( WP_PLUGIN_DIR, $file ) ) );
	}

	/**
	 * @param string $file - Stylesheet
	 * @return array
	 * @throws \Exception
	 */
	public function theme( string $file ) {
		$theme = $this->loadWpFunctionsThemes()->getTheme( $file );
		if ( empty( $theme ) ) {
			throw new \Exception( sprintf( 'Theme for stylesheet is not installed: %s', $file ) );
		}
		return $this->createFrom( $theme->get_stylesheet_directory() );
	}

	/**
	 * @param string $sourceDir
	 * @return array
	 * @throws \Exception
	 */
	protected function createFrom( string $sourceDir ) {
		if ( !ZipDir::IsSupported() ) {
			throw new \Exception( 'ZipDir is not supported' );
		}

		$FS = $this->loadFS();

		if ( !$FS->isDir( $sourceDir ) ) {
			throw new \Exception( sprintf( 'Plugin directory does not exist: %s', $sourceDir ) );
		}

		$sZipsDir = $this->getZipsDir( true );
		$ID = sanitize_key( uniqid( basename( $sourceDir ).'-' ) );
		$zipFile = path_join( $sZipsDir, $ID.'.zip' );

		if ( !( new ZipDir() )->run( $sourceDir, $zipFile ) ) {
			throw new \Exception( sprintf( 'ZipDir execution failed: %s', $sZipsDir ) );
		}

		$size = $FS->getFileSize( $zipFile );
		if ( empty( $size ) ) {
			throw new \Exception( 'Zip file size is empty' );
		}

		return [
			'id'   => $ID,
			'file' => $zipFile,
			'size' => $size,
		];
	}
}
