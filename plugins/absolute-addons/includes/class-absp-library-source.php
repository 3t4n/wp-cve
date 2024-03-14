<?php
/**
 * Template Library Data Source.
 *
 * @package AbsoluteAddons
 * @version 1.0.0
 * @since 1.0.0
 */

namespace AbsoluteAddons;

use Elementor\TemplateLibrary\Source_Base;
use \Elementor\Plugin as ElementorPlug;
use Exception;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "ABSOLUTE_ADDONS_WIDGETS_PATH" "./../widgets/" */
/** @define "ABSOLUTE_ADDONS_PRO_WIDGETS_PATH" "./../../absolute-addons-pro/widgets/" */

/**
 * Class Absp_Library
 *
 * @todo category and tags fetch and store.
 *
 * @package AbsoluteAddons
 */
class Absp_Library_Source extends Source_Base {

	/**
	 * Template library data cache
	 */
	const LIBRARY_CACHE_KEY = 'absp_library_cache';
	const LIBRARY_CACHE_TTL = 604800; // 7 Days = 7*24*60*60

	protected static $favorites;

	const LIBRARY_FAVORITE_KEY = 'absp_library_favorites';

	const LIBRARY_CATS_KEY = 'absp_library_categories_cache';
	const LIBRARY_TAGS_KEY = 'absp_library_tags_cache';

	// API Path Data. (don't include leading or trailing slash

	/**
	 * API URL
	 */
	const LIBRARY_API_URL  = 'https://lib.absoluteplugins.com/wp-json';

	/**
	 * API Namespace (with version)
	 */
	const LIBRARY_API_NS   = 'absolute-addons/v2';

	/**
	 * API Base
	 */
	const LIBRARY_API_BASE = 'templates';

	public function get_id() {
		return 'absp-library';
	}

	public function get_title() {
		return __( 'Absolute Library', 'absolute-addons' );
	}

	public function register_data() {}

	public static function get_endpoint( $route, $id = '' ) {

		$api_base = self::LIBRARY_API_URL. '/' . self::LIBRARY_API_NS . '/' . self::LIBRARY_API_BASE . '/';

		$route = ltrim( rtrim( $route, '/\\' ), '/\\' );

		switch ( $route ) {
			case 'info':
			case 'templates':
				return $api_base;
			case 'template':
				$id = absint( $id );
				return $id ? $api_base . $id : false;
			case 'download':
				$id = absint( $id );
				return $id ? $api_base . $id . '/' . $route : false;
			case 'tags':
			case 'categories':
				return $api_base . $route;
			default:
				return false;
		}
	}

	public function save_item( $template_data ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		return new WP_Error( 'invalid_request', 'Cannot save template to library' );
	}

	public function update_item( $new_data ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		return new WP_Error( 'invalid_request', 'Cannot update template to library' );
	}

	public function delete_template( $template_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		return new WP_Error( 'invalid_request', 'Cannot delete template from library' );
	}

	public function export_template( $template_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		return new WP_Error( 'invalid_request', 'Cannot export template from library' );
	}

	public function get_favorites() {
		if ( null === self::$favorites ) {
			self::$favorites = get_option( self::LIBRARY_FAVORITE_KEY, [] );
			if ( ! empty( self::$favorites ) ) {
				self::$favorites = array_map( 'absint', self::$favorites );
			}
		}

		return self::$favorites;
	}

	public function update_favorites( $template_id, $status ) {

		$template_id = absint( $template_id );
		$favorites   = $this->get_favorites();

		if ( $status ) {
			$favorites[] = $template_id;
		}

		if ( ! $status ) {
			$favorites = array_flip( $favorites );

			if ( isset( $favorites[ $template_id ] ) ) {
				unset( $favorites[ $template_id ] );
			}

			$favorites = array_flip( $favorites );
		}

		self::$favorites = array_unique( $favorites );

		update_option( self::LIBRARY_FAVORITE_KEY, self::$favorites, false );

		return self::$favorites;
	}

	public function get_items( $args = [], $force_update = false ) {

		$library_data = self::get_library_data( $force_update );
		$favorites    = $this->get_favorites();

		foreach ( $library_data as &$data ) {
			$data['favorite'] = in_array( (int) $data['template_id'], $favorites );
			if ( ! $data['thumbnail'] ) {
				$data['thumbnail'] = '<img src="' . esc_url( ELEMENTOR_ASSETS_URL . 'images/placeholder.png' ) . '" alt="' . esc_attr__( 'Preview Placeholder', 'absolute-addons'  ) . '">';
			}
		}

		return $library_data;
	}

	public function get_tags() {
		$library_data = self::get_library_data();

		return ( ! empty( $library_data['tags'] ) ? $library_data['tags'] : [] );
	}

	/**
	 * Get library data from remote source and cache
	 *
	 * @param boolean $force_update
	 *
	 * @return array
	 */
	private static function request_library_data( $force_update = false ) {

		/**
		 * Do not use WP transient API, set cache ttl separately.
		 * We should keep the data until new data received, transient will remove old data upon expire.
		 */

		$data = get_option( self::LIBRARY_CACHE_KEY, false );

		if ( $force_update || false === $data ) {

			$response = self::request( self::get_endpoint( 'info' ) );

			if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
			}


			if ( ! is_array( $data ) ) {
				$data = [];
			}

			if ( ! empty( $data ) ) {
				update_option( self::LIBRARY_CACHE_KEY, $data, 'no' );
				update_option( self::LIBRARY_CACHE_KEY . '_updated_at', self::LIBRARY_CACHE_TTL, 'no' );
			}
		}

		return $data;
	}

	public static function request_template_data( $template_id ) {

		if ( empty( $template_id ) ) {
			return new WP_Error( 'template-id-missing', __( 'Template id is missing', 'absolute-addons' ) );
		}

		$response = self::request( self::get_endpoint( 'download', $template_id ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$data = wp_remote_retrieve_body( $response );
		$data = $data ? json_decode( $data, true ) : false;

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( $data && isset( $data['code'], $data['message'] ) ) {
				return new WP_Error(
					$data['code'],
					$data['message'],
					( isset( $data['data'] ) ? $data['data'] : '' )
				);
			}
		}

		return $data;
	}

	private static function request( $url, $payload = [], $args = [] ) {

		$payload = wp_parse_args( $payload, self::prepare_common_payload() );

		$args = wp_parse_args(
			$args,
			[
				'body'    => $payload,
				'timeout' => 25, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				'headers' => [
					'Accept' => 'application/json',
				],
			]
		);

		return absp_remote_get( $url, $args );
	}

	private static function prepare_common_payload() {
		$payload = [
			'home_url' => trailingslashit( home_url() ),
			'version'  => ABSOLUTE_ADDONS_VERSION,
		];
		if ( absp_has_pro() ) {
			$payload['has_pro']     = 1;
			$payload['pro_version'] = ABSOLUTE_ADDONS_PRO_VERSION;
			$payload['license']     = absolute_addons_pro_license_data();
		}

		return $payload;
	}

	/**
	 * Get library data
	 *
	 * @param boolean $force_update
	 *
	 * @return array
	 */
	public static function get_library_data( $force_update = false ) {

		self::request_library_data( $force_update );

		$data = get_option( self::LIBRARY_CACHE_KEY, [] );

		$updated_at = get_option( self::LIBRARY_CACHE_KEY . '_updated_at' );

		// Check if cache expired.
		if ( ! $updated_at || $updated_at + self::LIBRARY_CACHE_TTL >= time() ) {
			$_data = self::request_library_data( true );
			if ( ! empty( $_data ) ) {
				return $_data;
			}
		}

		// Return cached data
		return $data;
	}

	/**
	 * Get remote template.
	 *
	 * Retrieve a single remote template from Elementor.com servers.
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return array Remote template.
	 */
	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from Elementor.com servers.
	 *
	 * @return array|WP_Error Remote Template data.
	 * @throws Exception
	 */
	public function get_data( array $args, $context = 'display' ) {

		// Download Template.
		$data = self::request_template_data( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			throw new Exception( $data->get_error_message() );
		}

		if ( ! is_array( $data ) || empty( $data ) || empty( $data['content'] ) ) {
			throw new Exception( __( 'Template does not have any content', 'absolute-addons' ) );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];

		$document = ElementorPlug::instance()->documents->get( $post_id );

		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}
}

// End of file class-base-widget.php.
