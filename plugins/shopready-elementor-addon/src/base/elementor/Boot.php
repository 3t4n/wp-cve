<?php

namespace Shop_Ready\base\elementor;

/******************* **********
 * Register all elementor boot
 * register widget control type
 *
 * @since 1.0
 * ************************ ***************/
abstract class Boot {

	use \Shop_Ready\base\config\App;
	const VERSION                   = SHOP_READY_VERSION;
	const MINIMUM_ELEMENTOR_VERSION = '3.3';
	const MINIMUM_PHP_VERSION       = '5.6';

	abstract protected function init_widgets( $widgets_manager);

	/***************************
	 *  VERSION CHECK
	 * *************************/
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$product_name = self::get_app_config()->all()['app']['product_name'];
		$message      = sprintf(
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'shopready-elementor-addon' ),
			'<strong>' . $product_name . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'shopready-elementor-addon' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice shop-ready-notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
		unset( $product_name );
	}



	/****************************
	 *  PHP VERSION NOTICE
	 ****************************/
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$product_name = self::get_app_config()->all()['app']['product_name'];
		$message      = sprintf(
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'shopready-elementor-addon' ),
			'<strong>' . $product_name . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'shopready-elementor-addon' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice shop-ready-notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

	}



	public function is_compatible() {

		/*
		---------------------------------
			Check if Elementor installed and activated
		-----------------------------------*/
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}
		/*
		----------------------------------
			Check for required PHP version
		-----------------------------------*/
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return false;
		}
		/*
		---------------------------------
			Check for required Elementor version
		----------------------------------*/
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {

			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return false;
		}

		return true;

	}

	function plugin_notice_assets() {
		wp_enqueue_style( 'shop-ready-admin-notice' );
	}

	public function init_controls() {

		\Elementor\Plugin::$instance->controls_manager->register_control( 'wrradioimage', new controls\Radio_Choose() );

	}


}