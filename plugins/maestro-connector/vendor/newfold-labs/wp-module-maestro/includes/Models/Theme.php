<?php

namespace NewfoldLabs\WP\Module\Maestro\Models;

use Exception;
use WP_User_Query;

/**
 * Class for getting the theme data
 */
class Theme {

	/**
	 * The id for the theme
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $id;

	/**
	 * The theme name, usually same as the id
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The theme's title (or Name in pure WP sense)
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Theme's status, will either be active or inactive
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $status;

	/**
	 * Theme's version
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Whether there is an update available for the theme or not
	 * will be either none or available
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $update;

	/**
	 * Theme's update version, will be either (undef) or the version
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $update_version;

	/**
	 * Theme's screenshot, an associative array of file and url
	 *
	 * @since 0.0.1
	 *
	 * @var array
	 */
	public $screenshot;

	/**
	 * If Auto updates have been enabled for this theme
	 *
	 * @since 0.0.1
	 *
	 * @var bool
	 */
	public $auto_updates_enabled;

	/**
	 * Constructor
	 *
	 * @since 0.0.1
	 *
	 * @param string   $stylesheet          The theme id
	 * @param WP_Theme $theme               object to initialize our theme
	 * @param array    $theme_update        The updates for the theme
	 * @param WP_Theme $current_theme       current theme's object
	 * @param array    $auto_update_enabled array of auto update options
	 */
	public function __construct( $stylesheet, $theme = null, $theme_update = null, $current_theme = null, $auto_update_enabled = null ) {

		// Include theme functions
		if ( ! function_exists( 'wp_get_themes' ) ) {
			require_once ABSPATH . 'wp-admin/includes/theme.php';
		}

		if ( empty( $theme ) ) {
			$theme = wp_get_theme( $stylesheet );
		}

		if ( is_null( $theme_update ) ) {
			$theme_updates = get_site_transient( 'update_themes' );
			if ( ! empty( $theme_updates->response[ $stylesheet ] ) ) {
				$theme_update = $theme_updates->response[ $stylesheet ];
			}
		}

		if ( empty( $auto_update_enabled ) ) {
			$auto_update_themes  = (array) get_site_option( 'auto_update_themes', array() );
			$auto_update_enabled = in_array( $stylesheet, $auto_update_themes, true );
		}

		$screenshot_url       = $theme->get_screenshot() ? $theme->get_screenshot() : 'none';
		$screenshot_url_array = 'none' !== $screenshot_url ? explode( '/', $screenshot_url ) : array( 'none' );
		$filename             = end( $screenshot_url_array );

		$screenshot = array(
			'url'  => $screenshot_url,
			'file' => $filename,
		);

		// Assign all the required values
		$this->id                   = $stylesheet;
		$this->name                 = $stylesheet;
		$this->title                = $theme->display( 'Name' );
		$this->status               = $stylesheet === $current_theme->get_stylesheet() ? 'active' : 'inactive';
		$this->version              = $theme->get( 'Version' );
		$this->update               = ! empty( $theme_update );
		$this->update_version       = ! empty( $theme_update ) ? $themes_update['new_version'] : null;
		$this->screenshot           = $screenshot;
		$this->auto_updates_enabled = $auto_update_enabled;
	}
}
