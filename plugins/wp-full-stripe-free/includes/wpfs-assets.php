<?php

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
class MM_WPFS_Assets {

	const PATH_ASSETS = 'assets';
	const PATH_CSS = 'css';
	const PATH_IMAGES = 'images';
	const PATH_FONTS = 'fonts';
	const PATH_SCRIPTS = 'js';
	const PATH_INCLUDES = 'includes';
	const PATH_TEMPLATES = 'templates';

	public static function css( $assetName = null ) {
		$path = DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_ASSETS . DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_CSS;

		return self::getAssetUrl( $path, $assetName );
	}

	public static function getAssetUrl( $path, $file ) {
		$asset = $path;
		if ( ! is_null( $file ) ) {
			$asset .= DIRECTORY_SEPARATOR . $file;
		}
		$assetUrl = plugins_url( $asset, dirname( __FILE__ ) );

		return $assetUrl;
	}

	public static function images( $assetName = null ) {
		$path = DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_ASSETS . DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_IMAGES;

		return self::getAssetUrl( $path, $assetName );
	}

	public static function fonts( $assetName = null ) {
		$path = DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_ASSETS . DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_FONTS;

		return self::getAssetUrl( $path, $assetName );
	}

	public static function scripts( $assetName = null ) {
		$path = DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_ASSETS . DIRECTORY_SEPARATOR . MM_WPFS_Assets::PATH_SCRIPTS;

		return self::getAssetUrl( $path, $assetName );
	}

	public static function includes( $assetName = null ) {
		return self::getAssetPath( MM_WPFS_Assets::PATH_INCLUDES, $assetName );
	}

	public static function getAssetPath( $path, $file ) {
		$assetPath = plugin_dir_path( dirname( __FILE__ ) ) . $path;
		if ( ! is_null( $file ) ) {
			$assetPath .= DIRECTORY_SEPARATOR . $file;
		}

		return $assetPath;
	}

	public static function templates( $assetName = null ) {
		return self::getAssetPath( MM_WPFS_Assets::PATH_TEMPLATES, $assetName );
	}
}