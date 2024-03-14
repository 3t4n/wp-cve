<?php
/**
 * Library_Source class
 *
 * @package XproElementorAddons
 * @author Xpro
 */

namespace XproElementorAddons;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Base;
use Exception;
use WP_Error;
use XproElementorAddonsPro\Libs\Xpro_Elementor_License;

defined( 'ABSPATH' ) || die();

if ( ! did_action( 'elementor/loaded' ) ) {
	return;
}

class Library_Source extends Source_Base {


	/**
	 * Template library data cache
	 */
	const LIBRARY_CACHE_KEY = 'xpro_elementor_library_cache';

	/**
	 * Template info api url
	 *
	 * Updated api to v2 in version 2.15.0
	 */
	const API_TEMPLATES_INFO_URL = 'https://templates.wpxpro.com/wp-json/wp/v2/templates';

	/**
	 * Template data api url
	 */
	const API_TEMPLATE_DATA_URL = 'https://templates.wpxpro.com/wp-json/wp/v2/template/';

	public function get_id() {
		return 'xpro-library';
	}

	public function get_title() {
		return __( 'Xpro Library', 'xpro-elementor-addons' );
	}

	public function register_data() {
	}

	public function save_item( $template_data ) {
		return new WP_Error( 'invalid_request', 'Cannot save template to a xpro library' );
	}

	public function update_item( $new_data ) {
		return new WP_Error( 'invalid_request', 'Cannot update template to a xpro library' );
	}

	public function delete_template( $template_id ) {
		return new WP_Error( 'invalid_request', 'Cannot delete template from a xpro library' );
	}

	public function export_template( $template_id ) {
		return new WP_Error( 'invalid_request', 'Cannot export template from a xpro library' );
	}

	public function get_tags() {
		$library_data = self::get_library_data();

		return ( ! empty( $library_data['tags'] ) ? $library_data['tags'] : array() );
	}

	/**
	 * Get library data
	 *
	 * @param boolean $force_update
	 *
	 * @return array
	 */
	public static function get_library_data( $force_update = true ) {
		self::request_library_data( $force_update );

		$data = get_option( self::LIBRARY_CACHE_KEY );

		if ( empty( $data ) ) {
			return array();
		}

		return $data;
	}

	/**
	 * Get library data from remote source and cache
	 *
	 * @param boolean $force_update
	 *
	 * @return array
	 */
	private static function request_library_data( $force_update = true ) {
		$data = get_option( self::LIBRARY_CACHE_KEY );

		if ( $force_update || false === $data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get(
				self::API_TEMPLATES_INFO_URL,
				array(
					'timeout' => $timeout,
				)
			);

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				update_option( self::LIBRARY_CACHE_KEY, array() );

				return null;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $data ) || ! is_array( $data ) ) {
				update_option( self::LIBRARY_CACHE_KEY, array() );

				return null;
			}

			update_option( self::LIBRARY_CACHE_KEY, $data, 'no' );
		}

		return $data;
	}

	public function get_type_tags() {
		$library_data = self::get_library_data();

		return ( ! empty( $library_data['type_tags'] ) ? $library_data['type_tags'] : array() );
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

	public function get_items( $args = array() ) {
		$library_data = self::get_library_data();

		$templates = array();

		if ( ! empty( $library_data['templates'] ) ) {
			foreach ( $library_data['templates'] as $template_data ) {
				$templates[] = $this->prepare_template( $template_data );
			}
		}

		return $templates;
	}

	/**
	 * Prepare template items to match model
	 *
	 * @param array $template_data
	 *
	 * @return array
	 */
	private function prepare_template( array $template_data ) {
		return array(
			'template_id' => $template_data['id'],
			'title'       => $template_data['title'],
			'type'        => $template_data['type'],
			'thumbnail'   => $template_data['thumbnail'],
			'date'        => $template_data['created_at'],
			'tags'        => $template_data['tags'],
			'isPro'       => $template_data['is_pro'],
			'url'         => $template_data['url'],
		);
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from Elementor.com servers.
	 *
	 * @return array|WP_Error Remote Template data.
	 */
	public function get_data( array $args, $context = 'display' ) {
		$data = self::request_template_data( $args['template_id'] );

		$data = json_decode( $data, true );

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new Exception( __( 'Template does not have any content', 'xpro-elementor-addons' ) );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];
		$document = Plugin::instance()->documents->get( $post_id );

		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}

	public static function request_template_data( $template_id ) {
		if ( empty( $template_id ) ) {
			return false;
		}

		$body = array(
			'home_url' => trailingslashit( home_url() ),
			'version'  => XPRO_ELEMENTOR_ADDONS_VERSION,
		);

		if ( did_action( 'xpro_elementor_addons_pro_loaded' ) && class_exists( '\XproElementorAddonsPro\Libs\Xpro_Elementor_License' ) && 'valid' === Xpro_Elementor_License::$license_activate ) {
			$body['has_pro']     = 1;
			$body['pro_version'] = XPRO_ELEMENTOR_ADDONS_PRO_VERSION;
		}

		$response = wp_remote_get(
			self::API_TEMPLATE_DATA_URL . $template_id,
			array(
				'body'    => $body,
				'timeout' => 25,
			)
		);

		return wp_remote_retrieve_body( $response );
	}
}
