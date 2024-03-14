<?php

namespace QuadLayers\QLWAPP\Controllers;

use QuadLayers\QLWAPP\Api\Admin_Menu_Routes_Library;
use QuadLayers\QLWAPP\Services\Entity_Options;


class Admin_Menu {

	protected static $instance;
	protected static $menu_slug = 'wp-whatsapp-chat';

	private function __construct() {
		/**
		 * Admin Menu
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	public function register_scripts() {

		$menu           = include QLWAPP_PLUGIN_DIR . 'build/admin-menu/js/index.asset.php';
		$entity_options = Entity_Options::instance();

		wp_register_script(
			'qlwapp-admin-menu',
			plugins_url( '/build/admin-menu/js/index.js', QLWAPP_PLUGIN_FILE ),
			$menu['dependencies'],
			$menu['version'],
			true
		);

		wp_register_style(
			'qlwapp-admin-menu',
			plugins_url( '/build/admin-menu/css/style.css', QLWAPP_PLUGIN_FILE ),
			array(
				'qlwapp-components',
				'media-views',
				'wp-components',
			),
			QLWAPP_PLUGIN_VERSION
		);

		wp_localize_script(
			'qlwapp-admin-menu',
			'qlwappApiAdminMenu',
			array(
				'QLWAPP_API_REST_ROUTES'    => $this->get_endpoints(),
				'QLWAPP_DISPLAY_POST_TYPES' => $entity_options->get_entries(),
				'QLWAPP_DISPLAY_TAXONOMIES' => $entity_options->get_taxonomies(),
			)
		);

		wp_enqueue_editor();
	}

	private function get_endpoints() {
		$route_library   = Admin_Menu_Routes_Library::instance();
		$endpoints       = $route_library->get_routes();
		$endpoints_array = array();

		foreach ( $endpoints as $endpoint ) {

			$endpoint_key = str_replace( '/', '_', $endpoint::get_rest_route() );

			if ( ! isset( $endpoints_array[ $endpoint_key ] ) ) {

				$endpoints_array[ $endpoint_key ] = $endpoint::get_rest_path();

			}
		}

		return $endpoints_array;
	}

	public function enqueue_scripts() {

		if ( ! isset( $_GET['page'] ) || self::get_menu_slug() !== $_GET['page'] ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( 'qlwapp-admin-menu' );
		wp_enqueue_style( 'qlwapp-admin-menu' );
	}

	public function add_menu() {

		$menu_slug = self::get_menu_slug();

		add_menu_page(
			QLWAPP_PLUGIN_NAME,
			QLWAPP_PLUGIN_NAME,
			'edit_posts',
			$menu_slug,
			'__return_null',
			'dashicons-whatsapp'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Welcome', 'wp-whatsapp-chat' ),
			esc_html__( 'Welcome', 'wp-whatsapp-chat' ),
			'edit_posts',
			$menu_slug,
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Premium', 'wp-whatsapp-chat' ),
			esc_html__( 'Premium', 'wp-whatsapp-chat' ),
			'edit_posts',
			"$menu_slug&tab=premium",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Button', 'wp-whatsapp-chat' ),
			esc_html__( 'Button', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=button",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Box', 'wp-whatsapp-chat' ),
			esc_html__( 'Box', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=box",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Contacts', 'wp-whatsapp-chat' ),
			esc_html__( 'Contacts', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=contacts",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Display', 'wp-whatsapp-chat' ),
			esc_html__( 'Display', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=display",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Settings', 'wp-whatsapp-chat' ),
			esc_html__( 'Settings', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=settings",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Scheme', 'wp-whatsapp-chat' ),
			esc_html__( 'Scheme', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=scheme",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Suggestions', 'wp-whatsapp-chat' ),
			esc_html__( 'Suggestions', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=suggestions",
			'__return_null'
		);
	}

	public static function get_menu_slug() {
		return self::$menu_slug;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
