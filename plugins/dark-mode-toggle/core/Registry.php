<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Registry trait.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Trait for registry classes.
 */
trait Registry {
	/**
	 * Registry of services.
	 *
	 * @var array
	 */
	protected static $registry = array();

	/**
	 * Set service in the registry.
	 *
	 * @param string  $key Service key.
	 * @param Service $service Service instance.
	 */
	public static function set( $key, Service $service ) {
		static::$registry[ $key ] = $service;
	}

	/**
	 * Get service from the registry.
	 *
	 * @param string $key Service key.
	 *
	 * @return Service|null
	 */
	public static function get( $key ) {
		if ( array_key_exists( $key, static::$registry ) ) {
			return static::$registry[ $key ];
		}

		return null;
	}

	/**
	 * Initialize service.
	 *
	 * Call init() method of a service.
	 *
	 * @param Service $service Service instance.
	 */
	public static function init_service( Service $service ) {
		$service->init();
	}

	/**
	 * Set and initialize services.
	 *
	 * @param string $suffix Action suffix.
	 */
	public static function init_services( $suffix = 'main' ) {
		// Set services in the registry.
		foreach ( static::services() as $key => $class ) {
			static::set( $key, new $class() );
		}

		do_action( 'darkmodetg_after_set_services_' . $suffix );

		// Initialize services.
		foreach ( static::$registry as $instance ) {
			static::init_service( $instance );
		}

		do_action( 'darkmodetg_after_init_services_' . $suffix );
	}
}
