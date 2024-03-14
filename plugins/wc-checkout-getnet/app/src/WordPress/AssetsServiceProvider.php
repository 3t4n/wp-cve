<?php

namespace WcGetnet\WordPress;

use WcGetnet\Services\WcGetnetAuth;
use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;
use WcGetnet\Services\WcGetnetApi as Service_Api;

/**
 * Register and enqueues assets.
 */
class AssetsServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		add_action( 'wp_enqueue_scripts', [$this, 'enqueueFrontendAssets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueueAdminAssets'] );
		add_action( 'wp_footer', [$this, 'loadSvgSprite'] );
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @return void
	 */
	public function enqueueFrontendAssets() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		// Enqueue the built-in comment-reply script for singular pages.
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( false === ( is_checkout() || is_account_page() ) ) {
			return;
		}

		// Enqueue scripts.
		\WcGetnet::core()->assets()->enqueueScript(
			'wc-getnet-js-bundle',
			\WcGetnet::core()->assets()->getBundleUrl( 'frontend', '.js' ),
			[ 'jquery' ],
			true
		);


		wp_localize_script(
			'wc-getnet-js-bundle',
			'wpParams',
			[
				'baseUrl'   => Service_Api::get_environment_url(),
				'basicAuth' => WcGetnetAuth::create_auth_base_64(),
				'sellerId'  => WcGetnetAuth::get_seller_id()
			]
		);

		// Enqueue styles.
		$style = \WcGetnet::core()->assets()->getBundleUrl( 'frontend', '.css' );

		if ( $style ) {
			\WcGetnet::core()->assets()->enqueueStyle(
				'wc-getnet-css-bundle',
				$style
			);
		}
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @return void
	 */
	public function enqueueAdminAssets() {
		$current_screen = get_current_screen();

		if ( 'woocommerce_page_wc-settings' !== $current_screen->id && 'woocommerce_page_getnet-settings' !== $current_screen->id ) {
			return;
		}

		// Enqueue scripts.
		\WcGetnet::core()->assets()->enqueueScript(
			'wc-getnet-admin-js-bundle',
			\WcGetnet::core()->assets()->getBundleUrl( 'admin', '.js' ),
			[ 'jquery' ],
			true
		);

		wp_localize_script(
			'wc-getnet-admin-js-bundle',
			'wpParams',
			[
				'ajax_url' 	=> admin_url( 'admin-ajax.php' )
			]
		);

		// Enqueue styles.
		$style = \WcGetnet::core()->assets()->getBundleUrl( 'admin', '.css' );

		if ( $style ) {
			\WcGetnet::core()->assets()->enqueueStyle(
				'wc-getnet-admin-css-bundle',
				$style
			);
		}
	}

	/**
	 * Load SVG sprite.
	 *
	 * @return void
	 */
	public function loadSvgSprite() {
		$file_path = implode(
			DIRECTORY_SEPARATOR,
			array_filter(
				[
					plugin_dir_url( WC_GETNET_PLUGIN_FILE ),
					'dist',
					'images',
					'sprite.svg'
				]
			)
		);

		if ( ! file_exists( $file_path ) ) {
			return;
		}

		readfile( $file_path );
	}

}