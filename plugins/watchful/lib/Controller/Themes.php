<?php
/**
 * Controller for managing WP themes.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

use Watchful\Helpers\Authentification;
use Watchful\Helpers\Files as FilesHelper;
use Watchful\Skins\SkinThemeUpgrader;
use Watchful\Exception;
use WP_REST_Request;
use WP_REST_Server;
use Theme_Upgrader;
use WP_Theme;

/**
 * WP REST API Menu routes
 *
 * @package WP_API_Menus
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful Themes controller class.
 */
class Themes implements BaseControllerInterface {

	/**
	 * Register watchful routes for WP API v2.
	 *
	 * @since  1.2.0
	 */
	public function register_routes() {
		register_rest_route(
			'watchful/v1',
			'/theme/install',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'install_theme' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'slug' => array(
								'default'           => null,
								'sanitize_callback' => 'esc_attr',
							),
							'zip'  => array(
								'default'           => null,
								'sanitize_callback' => 'esc_url',
							),
						)
					),
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/theme/update',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_theme' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'slug' => array(
								'default'           => null,
								'sanitize_callback' => 'esc_attr',
							),
						)
					),
				),
			)
		);
	}

	/**
	 * Update a theme from his slug.
	 *
	 * @param WP_REST_Request $request The WP request object.
	 *
	 * @return mixed
	 *
	 * @throws Exception If the slug is not valid.
	 */
	public function update_theme( WP_REST_Request $request ) {
		require_once ABSPATH . 'wp-admin/includes/theme.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . WPINC . '/theme.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$slug = $request->get_param( 'slug' );

		if ( ! $slug ) {
			throw new Exception( 'slug parameter is missing', 400 );
		}

		$theme = wp_get_theme( $slug );

		if ( ! $theme->exists() ) {
			throw new Exception( 'requested theme is not installed', 404 );
		}

		$api_args = array(
			'slug'   => $slug,
			'fields' => array( 'sections' => false ),
		);
		$api      = themes_api( 'theme_information', $api_args );

		if ( is_wp_error( $api ) ) {
			throw new Exception( 'could not get theme data : ' . $api->get_error_message(), 400 );
		}

		$skin     = new SkinThemeUpgrader();
		$upgrader = new Theme_Upgrader( $skin );

		$result = $upgrader->upgrade( $slug );

		if ( is_wp_error( $result ) ) {
			throw new Exception( 'theme could not be updated : ' . $result->get_error_message(), 400 );
		}

		if ( ! $result ) {
			throw new Exception( 'unknown error', 500 );
		}

		return $result;
	}

	/**
	 * Install a theme from his slug or url to zip.
	 *
	 * @param WP_REST_Request $request The WP request object.
	 *
	 * @return mixed
	 *
	 * @throws Exception If the slug and zip are missing.
	 */
	public function install_theme( WP_REST_Request $request ) {
        require_once ABSPATH . 'wp-admin/includes/admin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/theme.php';
		require_once ABSPATH . WPINC . '/theme.php';

		$slug = $request->get_param( 'slug' );
		$zip  = $request->get_param( 'zip' );

		if ( ! $slug && ! $zip ) {
			throw new Exception( 'parameter is missing. slug or zip required', 400 );
		}

		// Install from name.
		if ( $slug ) {
			$install_path = $this->download_link_from_slug( $slug );
		}

		// Install from url.
		if ( $zip ) {
			$install_path = $this->download_link_from_zip( $zip );
		}

		$skin     = new SkinThemeUpgrader();
		$upgrader = new Theme_Upgrader( $skin );

        $result = $upgrader->install( $install_path );

        if ( is_wp_error( $result ) ) {
			throw new Exception( 'installation of the theme failed : ' . $result->get_error_message(), 400 );
		}

		if ( ! $result ) {
			throw new Exception( 'unknown error', 500 );
		}

		return $result;
	}

	/**
	 * Check that the slug is valid and get the download link.
	 *
	 * @param string $slug The theme slug.
	 *
	 * @return mixed|string
	 *
	 * @throws Exception If the theme is already installed.
	 */
	private function download_link_from_slug( $slug ) {
		if ( wp_get_theme( $slug )->exists() ) {
			throw new Exception( 'theme is already installed', 400 );
		}

		$api_args = array(
			'slug'   => $slug,
			'fields' => array( 'sections' => false ),
		);

		$api = themes_api( 'theme_information', $api_args );

		// Usually because slug is wrong.
		if ( is_wp_error( $api ) ) {
			throw new Exception( 'theme not found on wordpress.org : ' . $api->get_error_message(), 400 );
		}

		return $api->download_link;
	}

	/**
	 * Check that the zip is valid.
	 *
	 * @param string $zip The theme zip file.
	 *
	 * @return mixed|string
	 *
	 * @throws Exception If the theme is already installed.
	 */
	private function download_link_from_zip( $zip ) {
		$helper = new FilesHelper();

		$potential_slugs = $helper->get_zip_directories( $zip );
		$is_installed    = false;

		foreach ( $potential_slugs as $slug ) {
			$is_installed = wp_get_theme( $slug )->exists();

			if ( $is_installed ) {
				break;
			}
		}

		if ( $is_installed ) {
			throw new Exception( 'theme is already installed', 400 );
		}

		return $zip;
	}

	/**
	 * Get Themes.
	 *
	 * @return array
	 */
	public function get_themes() {

		require_once ABSPATH . 'wp-admin/includes/theme.php';
		require_once ABSPATH . WPINC . '/theme.php';
		require_once ABSPATH . WPINC . '/update.php';

		// Get all themes.
		$themes = wp_get_themes();

		// Get the active theme.
		$active = get_template();

		// Delete the transient so wp_update_themes can get fresh data.
		delete_site_transient( 'update_themes' );

		// Force a theme update check.
		wp_update_themes();

		// Different versions of wp store the updates in different places.
		$current = get_site_transient( 'update_themes' );

        $themes = array_filter( $themes, function( $theme ) use ( $current ) {
            return is_object($theme) && is_a( $theme, 'WP_Theme' );
        });

        $parsed_themes = [];

		foreach ($themes as $key => $theme ) {
            /**
             * The WP_Theme object.
             *
             * @var WP_Theme $theme
             */
            $new_version = isset( $current->response[ $theme->get_stylesheet() ] ) ? $current->response[ $theme->get_stylesheet() ]['new_version'] : $theme->get('Version' );
            $current_version = $theme->get( 'Version' );

            $parsed_theme = array(
                'name'          => $theme->get( 'Name' ),
                'realname'      => $key,
                'active'        => $active === $key,
                'authorurl'     => $theme->get( 'AuthorURI' ),
                'version'       => $current_version,
                'updateVersion' => $new_version,
                'vUpdate'       => $current_version !== $new_version,
                'type'          => 'theme',
                'creationdate'  => null,
                'updateServer'  => null,
                'extId'         => 0,
                'variant'       => null,
            );

            $parsed_themes[] = $parsed_theme;
		}

		return $parsed_themes;
	}

}
