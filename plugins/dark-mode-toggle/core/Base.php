<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Base abstract class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Initialize base properties and methods.
 */
abstract class Base {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $plugin_ver;

	/**
	 * Plugin URL.
	 *
	 * @var string
	 */
	protected $plugin_url;

	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	protected $plugin_path;

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	protected $plugin_base;

	/**
	 * Initialize properties.
	 */
	public function __construct() {
		$this->plugin_ver  = DARKMODETG_PLUGIN_VER;
		$this->plugin_url  = DARKMODETG_PLUGIN_URL;
		$this->plugin_path = DARKMODETG_PLUGIN_PATH;
		$this->plugin_base = DARKMODETG_PLUGIN_BASE;
	}
}
