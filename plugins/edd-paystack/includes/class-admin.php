<?php

namespace Tubiz\EDD_Paystack;

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
			'plugin_action_links_' . plugin_basename( TBZ_EDD_PAYSTACK_PLUGIN_FILE ),
			array(
				$this,
				'plugin_action_links',
			)
		);
	}

	/**
	 * @param $sections
	 *
	 * @return array
	 */
	public function settings_section( $sections ) {
		$sections['paystack-settings'] = __( 'Paystack', 'edd-paystack' );

		return $sections;
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function settings( $settings ) {

		$paystack_settings = array(
			array(
				'id'   => 'edd_paystack_settings',
				'name' => '<strong>' . __( 'Paystack Settings', 'edd-paystack' ) . '</strong>',
				'desc' => __( 'Configure the gateway settings', 'edd-paystack' ),
				'type' => 'header',
			),
			array(
				'id'   => 'edd_paystack_test_mode',
				'name' => __( 'Enable Test Mode', 'edd-paystack' ),
				'desc' => __( 'Test mode enables you to test payments before going live. Once the LIVE MODE is enabled on your Paystack account uncheck this', 'edd-paystack' ),
				'type' => 'checkbox',
				'std'  => 0,
			),
			array(
				'id'   => 'edd_paystack_test_secret_key',
				'name' => __( 'Test Secret Key', 'edd-paystack' ),
				'desc' => __( 'Enter your Test Secret Key here', 'edd-paystack' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_paystack_test_public_key',
				'name' => __( 'Test Public Key', 'edd-paystack' ),
				'desc' => __( 'Enter your Test Public Key here', 'edd-paystack' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_paystack_live_secret_key',
				'name' => __( 'Live Secret Key', 'edd-paystack' ),
				'desc' => __( 'Enter your Live Secret Key here', 'edd-paystack' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_paystack_live_public_key',
				'name' => __( 'Live Public Key', 'edd-paystack' ),
				'desc' => __( 'Enter your Live Public Key here', 'edd-paystack' ),
				'type' => 'text',
				'size' => 'regular',
			),
			array(
				'id'   => 'edd_paystack_webhook',
				'type' => 'descriptive_text',
				'name' => __( 'Webhook URL', 'edd-paystack' ),
				'desc' => '<p><strong>Important:</strong> To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="https://dashboard.paystack.com/#/settings/developer" target="_blank">here</a> in your Paystack account to the URL below.</p>' . '<p><strong><pre>' . home_url( 'index.php?edd-listener=paystackipn' ) . '</pre></strong></p>',
			),
		);

		if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
			$paystack_settings = array( 'paystack-settings' => $paystack_settings );
		}

		return array_merge( $settings, $paystack_settings );
	}

	/**
	 *
	 */
	public function test_mode_notice() {

		if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

			$allowed_html = array(
				'a' => array(
					'href' => array(),
				),
			);

			$paystack_settings_url = admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=paystack-settings' );

			/* translators: 1: Paystack settings URL. */
			$dashboard_notice = __( 'Paystack test mode is still enabled for Easy Digital Downloads, click <a href="%s">here</a> to disable it when you want to start accepting live payment on your site.', 'edd-paystack' );

			?>
			<div class="error">
				<p><?php printf( wp_kses( $dashboard_notice, $allowed_html ), esc_url( $paystack_settings_url ) ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * @param $links
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {

		$paystack_settings_url = admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=paystack-settings' );

		$settings_link = '<a href="' . esc_url( $paystack_settings_url ) . '">' . __( 'Settings', 'edd-paystack' ) . '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}
}

new namespace\Admin();
