<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_AVFSTW\Core\Core;

/**
 * Base Class.
 */
class Base {
	/**
	 * Core.
	 *
	 * @var Core
	 */
	protected static $core;

	/**
	 * Plugin Info.
	 *
	 * @var array
	 */
	protected static $plugin_info;

	/**
	 * Initialize Base.
	 *
	 * @param Core  $core
	 * @param array $plugin_info
	 * @return void
	 */
	public static function start( $core, $plugin_info ) {
		self::$core        = $core;
		self::$plugin_info = $plugin_info;
	}
}
