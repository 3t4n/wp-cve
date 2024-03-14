<?php
/**
 * The trait for singleton instance.
 *
 * @package WP_Team_free
 * @since 2.1.0
 */

namespace ShapedPlugin\WPTeam\Traits;

/**
 * Singleton trait
 */
trait Singleton {

	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Make a class instance.
	 *
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
