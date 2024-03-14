<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * EmptyServiceInit trait.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Trait for service classes when init() method is empty.
 */
trait EmptyServiceInit {
	/**
	 * Initialize service.
	 *
	 * This service doesn't require initialization.
	 */
	public function init() {}
}
