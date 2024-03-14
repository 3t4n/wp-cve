<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Plugin services class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Register and initialize plugin services.
 */
class Plugin {
	use Registry;

	/**
	 * Get services.
	 */
	public static function services() {
		$services = array(
			'utility'     => Utility::class,
			'options'     => Options::class,
			'enqueue'     => Enqueue::class,
			'translation' => Translation::class,
		);

		if ( is_admin() ) {
			$services['admin-menu'] = Admin\Menu::class;
			$services['settings']   = Admin\Settings::class;
		}

		return apply_filters( 'darkmodetg_services', $services );
	}
}
