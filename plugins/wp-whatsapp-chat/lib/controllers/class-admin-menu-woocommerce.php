<?php

namespace QuadLayers\QLWAPP\Controllers;

class Admin_Menu_WooCommerce extends Admin_Menu {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	public function register_scripts() {

		$woocommerce = include QLWAPP_PLUGIN_DIR . 'build/admin-menu-woocommerce/js/index.asset.php';

		wp_register_script(
			'qlwapp-admin-menu-woocommerce',
			plugins_url( '/build/admin-menu-woocommerce/js/index.js', QLWAPP_PLUGIN_FILE ),
			$woocommerce['dependencies'],
			$woocommerce['version'],
			true
		);
	}

	public function enqueue_scripts() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		if ( ! isset( $_GET['page'] ) || self::get_menu_slug() !== $_GET['page'] ) {
			return;
		}

		wp_enqueue_script( 'qlwapp-admin-menu-woocommerce' );
	}

	public function add_menu() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$menu_slug = self::get_menu_slug();

		add_submenu_page(
			$menu_slug,
			esc_html__( 'WooCommerce', 'wp-whatsapp-chat' ),
			esc_html__( 'WooCommerce', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=woocommerce",
			'__return_null'
		);
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
