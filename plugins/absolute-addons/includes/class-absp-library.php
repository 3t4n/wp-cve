<?php
/**
 *
 *
 * @package AbsoluteAddons
 * @version 1.0.0
 * @since 1.0.0
 */

namespace AbsoluteAddons;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/** @define "ABSOLUTE_ADDONS_PATH" "./../" */
/** @define "ABSOLUTE_ADDONS_WIDGETS_PATH" "./../widgets/" */
/** @define "ABSOLUTE_ADDONS_PRO_WIDGETS_PATH" "./../../absolute-addons-pro/widgets/" */

/**
 * Class Absp_Library
 * @package AbsoluteAddons
 */
class Absp_Library {

	protected static $source = null;

	public static function init() {

		add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
		add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
	}

	public static function print_template_views() {
		include_once ABSOLUTE_ADDONS_PATH . 'templates/template-library/templates.php';
	}

	public static function enqueue_assets() {
	}

	/**
	 * Undocumented function
	 *
	 * @return Absp_Library_Source
	 */
	public static function get_source() {
		if ( is_null( self::$source ) ) {
			self::$source = new Absp_Library_Source();
		}

		return self::$source;
	}

	public static function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'absp_library_data', function ( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new Exception( __( 'Post not found.', 'absolute-addons' ) );
				}

				\Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
			}

			return self::get_library_data( $data );
		} );

		$ajax->register_ajax_action( 'absp_template_data', function ( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new Exception( __( 'Post not found', 'absolute-addons' ) );
				}

				\Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
			}

			if ( empty( $data['template_id'] ) ) {
				throw new Exception( __( 'Template id missing', 'absolute-addons' ) );
			}

			return self::get_template_data( $data );
		} );

		$ajax->register_ajax_action( 'absp_toggle_favorite', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new Exception( 'Access Denied' );
			}

			if ( isset( $data['template_id'], $data['favorite'] ) ) {

				$source = self::get_source();

				$source->update_favorites( $data['template_id'], $data['favorite'] );

				return [ 'success' => true ];
			}


			throw new Exception( 'Invalid Request' );
		} );
	}

	public static function get_template_data( array $args ) {
		$source = self::get_source();

		return $source->get_data( $args );
	}

	public static function get_library_data( array $args ) {
		$source = self::get_source();

		if ( ! empty( $args['sync'] ) ) {
			Absp_Library_Source::get_library_data( true );
		}

		return [
			'templates' => $source->get_items(),
			'tags'      => $source->get_tags(),
		];
	}
}

// End of file class-base-widget.php.
