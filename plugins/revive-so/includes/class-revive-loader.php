<?php
/**
 * Register all classes
 *
 */

/**
 * Main Class.
 */
final class REVIVESO_Loader {

	/**
	 * Store all the classes inside an array
	 *
	 * @return array Full list of classes
	 */
	public static function get_services() {
		$services = apply_filters( 'reviveso_get_services', array(
			REVIVESO_Dashboard::class,
			REVIVESO_Enqueue::class,
			REVIVESO_Admin::class,
			REVIVESO_Actions::class,
			REVIVESO_Localization::class,
			REVIVESO_RatingNotice::class,
			REVIVESO_FetchPosts::class,
			REVIVESO_PostRepublish::class,
			REVIVESO_RewritePermainks::class,
			REVIVESO_SiteCache::class,
			REVIVESO_RepublishInfo::class,
			REVIVESO_Database::class,
			REVIVESO_Upsells::class,
			REVIVESO_Extensions::class,
			REVIVESO_Pro_Settings_Importer::class,
		) );

		return $services;
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 */
	public static function register_services() {
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 *
	 * @param  class  $class  class from the services array
	 *
	 * @return class instance  new instance of the class
	 */
	private static function instantiate( $class ) {
		if ( method_exists( $class, 'get_instance' ) ) {
			$service = $class::get_instance();
		} else {
			$service = new $class();
		}

		return $service;
	}
}
