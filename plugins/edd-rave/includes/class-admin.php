<?php

namespace Tubiz\EDD_Rave;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_filter( 'edd_settings_sections_gateways', array( $this, 'settings_section' ) );
		add_filter( 'edd_settings_gateways', array( $this, 'settings' ), 1 );
		add_action( 'admin_notices', array( $this, 'test_mode_notice' ) );
		add_filter(
			'plugin_action_links_' . plugin_basename( TBZ_EDD_RAVE_PLUGIN_FILE ),
			array(
				$this,
				'plugin_action_links',
			)
		);
	}

	public function settings_section( $sections ) {

		$sections['rave-settings'] = __( 'Flutterwave', 'edd-rave' );

		return $sections;
	}

	public function settings( $settings ) {

		$rave_settings = array(
			array(
				'id'   => 'edd_rave_settings',
				'name' => '<strong>' . __( 'Flutterwave Settings', 'edd-rave' ) . '</strong>',
				'desc' => __( 'Configure the gateway settings', 'edd-rave' ),
				'type' => 'header',
			),
			array(
				'id'   => 'edd_rave_test_mode',
				'name' => __( 'Enable Test Mode', 'edd-rave' ),
				'desc' => __( 'Test mode enables you to test payments before going live. Once you are live uncheck this.', 'edd-rave' ),
				'type' => 'checkbox',
				'std'  => 0,
			),
			array(
				'id'   => 'edd_rave_test_public_key',
				'name' => __( 'Test Public Key', 'edd-rave' ),
				'desc' => __( 'Enter your Test Public Key here', 'edd-rave' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_rave_test_secret_key',
				'name' => __( 'Test Secret Key', 'edd-rave' ),
				'desc' => __( 'Enter your Test Secret Key here', 'edd-rave' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_rave_live_public_key',
				'name' => __( 'Live Public Key', 'edd-rave' ),
				'desc' => __( 'Enter your Live Public Key here', 'edd-rave' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_rave_live_secret_key',
				'name' => __( 'Live Secret Key', 'edd-rave' ),
				'desc' => __( 'Enter your Live Secret Key here', 'edd-rave' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_rave_title',
				'name' => __( 'Modal Title', 'edd-rave' ),
				'desc' => __( 'Text to be displayed as the title of the payment modal', 'edd-rave' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_rave_description',
				'name' => __( 'Modal Description', 'edd-rave' ),
				'desc' => __( 'Text to be displayed as a short modal description', 'edd-rave' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_rave_checkout_image',
				'name' => __( 'Checkout Logo', 'edd-rave' ),
				'desc' => __( 'Upload an image to be shown on the Flutterwave Checkout modal window. Recommended minimum size is 128x128px. Leave blank to disable the image.', 'edd-rave' ),
				'type' => 'upload',
			),
			array(
				'id'   => 'edd_rave_webhook',
				'type' => 'descriptive_text',
				'name' => __( 'Webhook URL', 'edd-rave' ),
				'desc' => '<p><strong>Important:</strong> To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="https://dashboard.flutterwave.com/dashboard/settings/webhooks" target="_blank">here</a> in your Flutterwave account to the URL below.</p>' . '<p><strong><pre>' . home_url( 'index.php?edd-listener=raveipn' ) . '</pre></strong></p>',
			),
		);

		if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
			$rave_settings = array( 'rave-settings' => $rave_settings );
		}

		return array_merge( $settings, $rave_settings );
	}

	public function plugin_action_links( $links ) {

		$settings_url = admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=rave-settings' );

		$settings_link = '<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'edd-rave' ) . '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}

	public function test_mode_notice() {

		if ( edd_get_option( 'edd_rave_test_mode' ) ) {

			$allowed_html = array(
				'a' => array(
					'href' => array(),
				),
			);

			$rave_settings_url = admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=rave-settings' );

			/* translators: 1: Rave settings URL. */
			$dashboard_notice = __( 'Flutterwave test mode is still enabled for Easy Digital Downloads, click <a href="%s">here</a> to disable it when you want to start accepting live payment on your site.', 'edd-rave' );

			?>
			<div class="error">
				<p><?php printf( wp_kses( $dashboard_notice, $allowed_html ), esc_url( $rave_settings_url ) ); ?></p>
			</div>
			<?php
		}
	}
}
new namespace\Admin();

