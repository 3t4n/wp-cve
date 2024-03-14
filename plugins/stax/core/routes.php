<?php
/**
 *  REST API routes.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Routes {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * Routes constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'load' ] );
	}

	/**
	 * @return null|Routes
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *
	 */
	public function load() {
		register_rest_route( STAX_API_NAMESPACE, '/save-data', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Model_Zones::instance(),
				'save'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/save-template', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Model_Templates::instance(),
				'save'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/update-template', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Model_Templates::instance(),
				'update'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/get-default-template/(?P<id>\d+)', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				Templates::instance(),
				'rest_get_by_id'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/save-component', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Model_Components::instance(),
				'save'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/update-editor-theme', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Model_Settings::instance(),
				'update_theme'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/update-preset-colors', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Model_Settings::instance(),
				'update_colors'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/import', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Import::instance(),
				'execute'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/clean-export', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Export::instance(),
				'cleanExport'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/rebuild-element', [
			'methods'             => \WP_REST_SERVER::CREATABLE,
			'callback'            => [
				Composer::instance(),
				'buildTemplate'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/export', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				Export::instance(),
				'execute'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/menus', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				Menus::instance(),
				'getSlugs'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/shortcode', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				ShortCode::instance(),
				'getString'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/menu/(?P<slug>\S+)', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				Menus::instance(),
				'getBySlug'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/render-status', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				RenderStatus::instance(),
				'toggleStatus'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/helpers/find-page-for-zone', [
			'methods'             => \WP_REST_SERVER::READABLE,
			'callback'            => [
				PageSeeker::instance(),
				'find'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/delete-template/(?P<id>\d+)', [
			'methods'             => \WP_REST_SERVER::DELETABLE,
			'callback'            => [
				Model_Templates::instance(),
				'delete'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/delete-component/(?P<id>\d+)', [
			'methods'             => \WP_REST_SERVER::DELETABLE,
			'callback'            => [
				Model_Components::instance(),
				'delete'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );

		register_rest_route( STAX_API_NAMESPACE, '/delete-zone/(?P<uuid>[a-zA-Z0-9]+)', [
			'methods'             => \WP_REST_SERVER::DELETABLE,
			'callback'            => [
				Model_Zones::instance(),
				'delete'
			],
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		] );
	}
}
