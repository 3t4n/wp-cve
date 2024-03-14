<?php

namespace NewfoldLabs\WP\Module\Maestro\Models;

use NewfoldLabs\WP\Module\Maestro\Util;

/**
 * Class for getting general info about the website and corresponding WordPress installation
 */
class SiteDetails {

	/**
	 * Last updated timestamp
	 *
	 * @since 0.0.1
	 *
	 * @var date
	 */
	public $last_updated;

	/**
	 * Version of WordPress installed
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $wordpress_version;

	/**
	 * Latest available version of WordPress
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $wordpress_version_latest;

	/**
	 * Latest available version of WordPress
	 *
	 * @since 0.0.1
	 *
	 * @var UpdatesAvailable
	 */
	public $updates_available;

	/**
	 * If WordPress major updates have been enabled
	 *
	 * @since 0.0.1
	 *
	 * @var boolean $allow_major_auto_core_updates
	 */
	public $allow_major_auto_core_updates;

	/**
	 * If WordPress minor updates have been enabled
	 *
	 * @since 0.0.1
	 *
	 * @var boolean $allow_minor_auto_core_updates
	 */
	public $allow_minor_auto_core_updates;

	/**
	 * Constructor
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		// Trigger Update check
		wp_update_plugins();
		wp_update_themes();

		if ( ! function_exists( 'get_site_option' ) ) {
			require_once ABSPATH . 'wp-admin/includes/options.php';
		}

		$allow_major_auto_core_updates = null;
		$allow_minor_auto_core_updates = null;

		$util = new Util();
		if ( $util->is_bluehost() ) {
			$allow_major_auto_core_updates = get_option( 'allow_major_auto_core_updates' ) === 'true' ? true : false;
			$allow_minor_auto_core_updates = get_option( 'allow_minor_auto_core_updates' ) === 'true' ? true : false;
		}

		$core_update              = get_site_transient( 'update_core' );
		$last_updated             = $core_update->last_checked;
		$wordpress_version        = $core_update->version_checked;
		$wordpress_latest_version = $core_update->updates[0]->current;
		$themes_update_count      = count( get_site_transient( 'update_themes' )->response );
		$plugins_update_count     = count( get_site_transient( 'update_plugins' )->response );
		$core_update_count        = count( $core_update->updates ) - 1;

		$updates_available                   = array(
			'core'    => $core_update_count,
			'themes'  => $themes_update_count,
			'plugins' => $plugins_update_count,
			'total'   => $core_update_count + $themes_update_count + $plugins_update_count,
		);
		$this->last_updated                  = $last_updated;
		$this->wordpress_version             = $wordpress_version;
		$this->wordpress_version_latest      = $wordpress_latest_version;
		$this->updates_available             = $updates_available;
		$this->allow_major_auto_core_updates = $allow_major_auto_core_updates;
		$this->allow_minor_auto_core_updates = $allow_minor_auto_core_updates;
	}
}
