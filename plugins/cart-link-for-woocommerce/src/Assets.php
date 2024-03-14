<?php

namespace IC\Plugin\CartLinkWooCommerce;

use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;

/**
 * Register assets.
 */
class Assets {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @var AssetsChecker
	 */
	private $assets_checker;

	/**
	 * @param PluginData    $plugin_data    .
	 * @param AssetsChecker $assets_checker .
	 */
	public function __construct( PluginData $plugin_data, AssetsChecker $assets_checker ) {
		$this->plugin_data    = $plugin_data;
		$this->assets_checker = $assets_checker;
	}

	public function hooks() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ], 100 );
	}

	public function register_scripts() {
		if ( ! $this->assets_checker->should_register_assets() ) {
			return;
		}

		wp_enqueue_script(
			$this->plugin_data->get_plugin_slug(),
			$this->plugin_data->get_plugin_url( 'assets/dist/campaign-manager.js' ),
			[
				'wp-util',
				'wc-enhanced-select',
				'jquery',
			],
			$this->plugin_data->get_script_version(),
			1
		);

		wp_localize_script(
			$this->plugin_data->get_plugin_slug(),
			'__jsVars',
			[
				'post_type' => RegisterPostType::POST_TYPE,
				'url'       => [
					'rest_api' => rest_url(),
				],
				'nonce'     => wp_create_nonce( 'wp_rest' ),
			]
		);

		wp_enqueue_style( $this->plugin_data->get_plugin_slug(), $this->plugin_data->get_plugin_url( 'assets/dist/campaign-manager.css' ), [], $this->plugin_data->get_script_version() );
	}
}
