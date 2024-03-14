<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Service interface.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Interface for service instance.
 */
interface Service {
	/**
	 * Initialize service.
	 */
	public function init();
}
