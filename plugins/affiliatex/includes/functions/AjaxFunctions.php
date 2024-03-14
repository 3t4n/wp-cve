<?php

/**
 * Ajax of AffiliateX.
 *
 * @package AffiliateX
 */

namespace AffiliateX;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package AffiliateX
 */
class AffiliateX_Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init() {
		// Initialize hooks.
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init_hooks() {
		// AJAX for changelog query
		add_action( 'wp_ajax_affx_get_latest_changelog', array( $this, 'get_latest_changelog' ) );

		add_action( 'wp_ajax_get_block_settings', array( $this, 'get_block_settings' ) );
		add_action( 'wp_ajax_save_block_settings', array( $this, 'save_block_settings' ) );
		add_action( 'wp_ajax_get_customization_settings', array( $this, 'get_customization_settings' ) );
		add_action( 'wp_ajax_save_customization_settings', array( $this, 'save_customization_settings' ) );

	}

	/**
	 * Get Latest Changelog
	 *
	 * @return void
	 */
	public function get_latest_changelog() {
		$changelog     = null;
		$pro_changelog = null;
		$access_type   = get_filesystem_method();

		if ( $access_type === 'direct' ) {
			$creds = request_filesystem_credentials(
				site_url() . '/wp-admin/',
				'',
				false,
				false,
				array()
			);

			if ( WP_Filesystem( $creds ) ) {
				global $wp_filesystem;

				$changelog = $wp_filesystem->get_contents(
					plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/changelog.txt'
				);

				if ( affx_is_pro_activated() ) {
					$pro_changelog = $wp_filesystem->get_contents(
						plugin_dir_path( AFFILIATEX_PRO_PLUGIN_FILE ) . '/changelog.txt'
					);
				}
			}
		}

		wp_send_json_success(
			array(
				'changelog' => apply_filters(
					'affiliateX_changelogs_list',
					array(
						array(
							'title'     => __( 'Free', 'affiliatex' ),
							'changelog' => $changelog,
						),
						array(
							'title'     => __( 'Pro', 'affiliatex' ),
							'changelog' => $pro_changelog,
						),
					)
				),
			)
		);
	}

	/**
	 * Get Block Settings values.
	 */
	public function get_block_settings() {
		check_ajax_referer( 'affiliatex_ajax_nonce', 'security' );

		$data = affx_get_block_settings( true );
		wp_send_json_success( $data );
	}

	/**
	 * Save Block Settings values.
	 */
	public function save_block_settings() {
		check_ajax_referer( 'affiliatex_ajax_nonce', 'security' );

		$data = isset( $_POST['data'] ) ? affx_clean_vars( json_decode( stripslashes_deep( $_POST['data'] ) ), true, 512, JSON_OBJECT_AS_ARRAY ) : array();
		update_option( 'affiliatex_block_settings', json_encode( $data ) );

		wp_send_json_success( __( 'Saved successfully.', 'affiliatex' ) );
	}

	/**
	 * Get Customization Settings values.
	 */
	public function get_customization_settings() {
		check_ajax_referer( 'affiliatex_ajax_nonce', 'security' );

		$data = affx_get_customization_settings( true );
		wp_send_json_success( $data );
	}

	/**
	 * Save Customization Settings values.
	 */
	public function save_customization_settings() {
		check_ajax_referer( 'affiliatex_ajax_nonce', 'security' );

		$data = isset( $_POST['data'] ) ? affx_clean_vars( json_decode( stripslashes_deep( $_POST['data'] ) ), true, 512, JSON_OBJECT_AS_ARRAY ) : array();

		update_option( 'affiliatex_customization_settings', json_encode( $data ) );

		wp_send_json_success( __( 'Saved successfully.', 'affiliatex' ) );
	}
}

new AffiliateX_Ajax();
