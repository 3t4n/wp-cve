<?php
/**
 * UpStream_Template_Loader
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UpStream_Template_Loader
 */
class UpStream_Template_Loader {

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_loader' ) );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. upstream looks for theme.
	 * overrides in /theme/upstream/ by default.
	 *
	 * @param mixed $template Template data.
	 *
	 * @return string
	 */
	public function template_loader( $template ) {
		$get_data    = isset( $_GET ) ? wp_unslash( $_GET ) : array();
		$server_data = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();
		$file        = '';

		if ( get_post_type() === false ) {
			if ( ! upstream_is_project_base_uri( sanitize_text_field( $server_data['REQUEST_URI'] ) ) ) {
				return $template;
			}
		} elseif ( 'project' !== get_post_type() ) {
			return $template;
		}

		if ( is_single() ) {
			require_once UPSTREAM_PLUGIN_DIR . 'includes/admin/metaboxes/metabox-functions.php';

			$file = 'single-project.php';

			$user_id    = get_current_user_id();
			$project_id = upstream_post_id();

			if ( ! upstream_user_can_access_project( $user_id, $project_id ) ) {
				wp_redirect( get_post_type_archive_link( 'project' ) );
				exit;
			}
		}

		if ( is_archive() ) {
			$file = 'archive-project.php';
		}

		if ( isset( $get_data['action'] ) && sanitize_text_field( $get_data['action'] ) === 'logout' ) {
			UpStream_Login::do_destroy_session();
			$url_base = upstream_get_project_base();

			if ( preg_match( '/^\/' . $url_base . '/i', sanitize_text_field( $server_data['REQUEST_URI'] ) ) ) {
				$redirect_to = wp_login_url( get_post_type_archive_link( 'project' ) );
			} else {
				$redirect_to = get_permalink();
			}

			wp_save_redirect( $redirect_to );
			exit;
		}

		/*
		 * Login page if not logged in
		 */
		if ( ! apply_filters( 'upstream_permissions_filter_page_access', false ) && ! upstream_is_user_logged_in() ) {
			$file                      = 'login.php';
			$GLOBALS['login_template'] = true;
		}

		if ( $file ) {
			$check_dirs = array(
				trailingslashit( get_stylesheet_directory() ) . upstream_template_path(),
				trailingslashit( get_template_directory() ) . upstream_template_path(),
				UPSTREAM_PLUGIN_DIR . 'templates/',
			);

			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . $file ) ) {
					load_template( $dir . $file );

					return;
				}
			}
		}

		return $template;
	}
}

new UpStream_Template_Loader();
