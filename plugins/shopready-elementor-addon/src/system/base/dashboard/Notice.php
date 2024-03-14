<?php

namespace Shop_Ready\system\base\dashboard;

class Notice {


	private $notice_url = 'https://plugins.quomodosoft.com/templates/wp-json/quomodo-notice/v1/remote?type=quomodo-notice-shop-ready';
	// The constructor is private
	// to prevent initiation with outer code.
	public function register() {
		/*
		----------------------------------
		Check for required PHP version
		-----------------------------------*/
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_woocommerce_install' ) );
			return;
		}

		if ( ! did_action( 'elementor/loaded' ) ) {

			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		add_action( 'admin_notices', array( $this, 'add_admin_remote_notice' ) );

	}

	public function add_admin_remote_notice() {

		$data = wp_remote_retrieve_body( wp_remote_get( $this->notice_url ) );

		$_data = json_decode( $data, true );

		if ( ! isset( $_data['show'] ) ) {
			return;
		}
		if ( $_data['show'] == false ) {
			return;
		}

		if ( is_wp_error( $_data ) ) {
			return false;
		}

		if ( $_data['msg'] == '""' ) {
			return;
		}

		if ( false === get_transient( 'shop_ready_remote_notice_time_elaps' ) ) {

			set_transient( 'shop_ready_remote_notice_time_elaps', time(), 300 * MINUTE_IN_SECONDS );
			require_once __DIR__ . '/views/notice.php';
		}

	} // end method

	/**************************
	 *  MISSING NOTICE
	 ***************************/
	public function admin_notice_missing_main_plugin() {

		$product_name = shop_ready_app_config()->all()['app']['product_name'];
		$con          = esc_html__( 'Click to Install', 'shopready-elementor-addon' );

		if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {

			$er_url = shop_ready_plugin_activation_link_url( 'elementor/elementor.php' );
			$con    = esc_html__( 'Click to Activate', 'shopready-elementor-addon' );

		} else {

			$con    = esc_html__( 'Click to Install ', 'shopready-elementor-addon' );
			$action = 'install-plugin';
			$slug   = 'elementor';

			$er_url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $slug,
					),
					admin_url( 'update.php' )
				),
				$action . '_' . $slug
			);
		}

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$message = sprintf(
				__( '"%1$s" requires "%2$s"', 'shopready-elementor-addon' ),
				'<strong>' . $product_name . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'shopready-elementor-addon' ) . '</strong>'
			);
		} else {

			$message = sprintf(
				__( '"%1$s" requires "%2$s" %3$s', 'shopready-elementor-addon' ),
				'<strong>' . $product_name . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'shopready-elementor-addon' ) . '</strong>',
				'<strong> <a href="' . esc_url( $er_url ) . '">' . $con . '</a></strong>'
			);
		}

		printf( '<div class="notice shop-ready-notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

		unset( $product_name );
		unset( $er_url );
		unset( $con );
		unset( $message );
	}
	public function admin_notice_woocommerce_install() {

		$product_name = shop_ready_app_config()->all()['app']['product_name'];
		$con          = esc_html__( 'Click to Install', 'shopready-elementor-addon' );

		if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {

			$er_url = shop_ready_plugin_activation_link_url();
			$con    = esc_html__( 'Click to Activate', 'shopready-elementor-addon' );

		} else {

			$con    = esc_html__( ' Click to Install ', 'shopready-elementor-addon' );
			$action = 'install-plugin';
			$slug   = 'woocommerce';

			$er_url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $slug,
					),
					admin_url( 'update.php' )
				),
				$action . '_' . $slug
			);
		}

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$message = sprintf(
				__( '"%1$s" requires "%2$s"', 'shopready-elementor-addon' ),
				'<strong>' . $product_name . '</strong>',
				'<strong>' . esc_html__( 'WooCommerce', 'shopready-elementor-addon' ) . '</strong>'
			);
		} else {

			$message = sprintf(
				__( '"%1$s" requires "%2$s" %3$s', 'shopready-elementor-addon' ),
				'<strong>' . $product_name . '</strong>',
				'<strong>' . esc_html__( 'WooCommerce', 'shopready-elementor-addon' ) . '</strong>',
				'<strong> <a href="' . esc_url( $er_url ) . '">' . $con . '</a></strong>'
			);
		}

		printf( '<div class="notice shop-ready-notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

		unset( $product_name );
		unset( $er_url );
		unset( $con );
		unset( $message );

	}

	public function admin_notice_unyson_install() {

		$product_name = shop_ready_app_config()->all()['app']['product_name'];
		$con          = esc_html__( 'Click to Install', 'shopready-elementor-addon' );

		if ( file_exists( WP_PLUGIN_DIR . '/unyson/unyson.php' ) ) {

			$er_url = shop_ready_plugin_activation_link_url();
			$con    = esc_html__( 'Click to Activate', 'shopready-elementor-addon' );

		} else {

			$con    = esc_html__( ' Click to Install ', 'shopready-elementor-addon' );
			$action = 'install-plugin';
			$slug   = 'unyson';

			$er_url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $slug,
					),
					admin_url( 'update.php' )
				),
				$action . '_' . $slug
			);
		}

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		if ( in_array( 'unyson/unyson.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$message = sprintf(
				__( '"%1$s" requires "%2$s"', 'shopready-elementor-addon' ),
				'<strong>' . $product_name . '</strong>',
				'<strong>' . esc_html__( 'Demo Importer unyson', 'shopready-elementor-addon' ) . '</strong>'
			);
		} else {

			$message = sprintf(
				__( '"%1$s" requires "%2$s" %3$s', 'shopready-elementor-addon' ),
				'<strong>' . $product_name . '</strong>',
				'<strong>' . esc_html__( 'Demo Importer unyson', 'shopready-elementor-addon' ) . '</strong>',
				'<strong> <a href="' . esc_url( $er_url ) . '">' . $con . '</a></strong>'
			);
		}

		printf( '<div class="notice shop-ready-notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

		unset( $product_name );
		unset( $er_url );
		unset( $con );
		unset( $message );

	}

}