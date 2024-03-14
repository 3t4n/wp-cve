<?php
/**
 * Plugin configurations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$GLOBALS['uxgallery_aliases'] = array(
	'UXGallery_Install'          => 'includes/class-gallery-img-install',
	'UXGallery_Template_Loader'  => 'includes/class-gallery-img-template-loader',
	'UXGallery_Ajax'             => 'includes/class-gallery-img-ajax',
	'UXGallery_Widgets'          => 'includes/class-gallery-img-widgets',
	'UXGallery_Widget'           => 'includes/class-gallery-img-ux-gallery-widget',
	'UXGallery_Shortcode'        => 'includes/class-gallery-img-shortcode',
	'UXGallery_Frontend_Scripts' => 'includes/class-gallery-img-frontend-scripts',
	'UXGallery_Admin'            => 'includes/admin/class-gallery-img-admin',
	'UXGallery_Admin_Assets'     => 'includes/admin/class-gallery-img-admin-assets',
	'UXGallery_General_Options'  => 'includes/admin/class-gallery-img-general-options',
	'UXGallery_Galleries'        => 'includes/admin/class-gallery-img-galleries',
    'UXGallery_Albums' => 'includes/admin/class-gallery-img-albums',
	'UXGallery_Lightbox_Options' => 'includes/admin/class-gallery-img-lightbox-options',
	'UXGallery_Gallery_Elementor_Widget' => 'includes/class-gallery-elementor-widget',
	'UXGallery_Album_Elementor_Widget' => 'includes/class-album-elementor-widget',
);

/**
 * @param $classname
 *
 * @throws Exception
 */
function uxgallery_autoload( $classname ) {
	global $uxgallery_aliases;

	/**
	 * We do not touch classes that are not related to us
	 */
	if ( ! strstr( $classname, 'UXGallery_' ) ) {
		return;
	}

	if ( ! key_exists( $classname, $uxgallery_aliases ) ) {
		throw new Exception( 'trying to load "' . $classname . '" class that is not registered in config file.' );
	}

	$path = UXGallery()->plugin_path() . '/' . $uxgallery_aliases[ $classname ] . '.php';

	if ( ! file_exists( $path ) ) {

		throw new Exception( 'the given path for class "' . $classname . '" is wrong, trying to load from ' . $path );

	}

	require_once $path;

	if ( ! interface_exists( $classname ) && ! class_exists( $classname ) ) {

		throw new Exception( 'The class "' . $classname . '" is not declared in "' . $path . '" file.' );

	}
}

/**
 * Autoloader check
 *
 */
    if ( function_exists( 'spl_autoload_register' ) ){

        spl_autoload_register( 'uxgallery_autoload' );

    } elseif ( isset( $GLOBALS['_wp_spl_autoloaders'] ) ){

        array_push ($GLOBALS['_wp_spl_autoloaders'], 'uxgallery_autoload');

    } else {

        throw new Exception ( 'We recommend you to update your php version that appears to be a really old one which is not compatible with this version of the Gallery.' );
    }