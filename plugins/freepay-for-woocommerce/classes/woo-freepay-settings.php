<?php

/**
 * WC_FreePay_Settings class
 */
class WC_FreePay_Settings {

	/**
	 * get_fields function.
	 *
	 * Returns an array of available admin settings fields
	 *
	 * @access public static
	 * @return array
	 */
	public static function get_fields() {
		$fields =
			[
				'enabled' => [
					'title'   => __( 'Enable', 'freepay-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable FreePay Payment', 'freepay-for-woocommerce' ),
					'default' => 'yes'
				],

				'_Account_setup' => [
					'type'  => 'title',
					'title' => __( 'API - Integration', 'freepay-for-woocommerce' ),
				],
				'freepay_apikey' => [
					'title'       => __( 'Communication key', 'freepay-for-woocommerce' ) . self::get_required_symbol(),
					'type'        => 'text',
					'description' => __( 'Communication key (API key) is obtained from your merchant portal (Indstillinger -> API nøgler). Fetch your key from https://freepay.dk/portal/tools/apikeys', 'freepay-for-woocommerce' ),
					'desc_tip'    => true,
				],
				'_Currency_settings' => [
					'type'  => 'title',
					'title' => __( 'Currency settings', 'freepay-for-woocommerce' )
				],
				'freepay_currency' => [
					'title'       => __( 'Fixed Currency', 'freepay-for-woocommerce' ),
					'description' => __( 'Choose a fixed currency. Please make sure to use the same currency as in your WooCommerce currency settings.', 'freepay-for-woocommerce' ),
					'desc_tip'    => true,
					'type'        => 'select',
					'options'     => [
						'AUTO' => 'Autodetect',
						'DKK' => 'DKK',
						'EUR' => 'EUR',
						'GBP' => 'GBP',
						'NOK' => 'NOK',
						'SEK' => 'SEK',
						'USD' => 'USD'
					]
				],
				
				'_Extra_gateway_settings' => [
					'type'  => 'title',
					'title' => __( 'Extra gateway settings', 'freepay-for-woocommerce' )
				],
				'freepay_language'       => [
					'title'       => __( 'Language', 'freepay-for-woocommerce' ),
					'description' => __( 'Payment Window Language', 'freepay-for-woocommerce' ),
					'desc_tip'    => true,
					'type'        => 'select',
					'options'     => [
						'da-DK' => 'Danish',
						'en-US' => 'English',
						'sv-SE' => 'Swedish',
						'auto'	=> 'Autodetect',
					]
				],
				'freepay_captureoncomplete' => [
					'title'       => __( 'Capture on complete', 'freepay-for-woocommerce' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable', 'freepay-for-woocommerce' ),
					'description' => __( 'When enabled freepay payments will automatically be captured when order state is set to "Complete".', 'freepay-for-woocommerce' ),
					'default'     => 'yes',
					'desc_tip'    => true,
				],

				'_Shop_setup'                           => [
					'type'  => 'title',
					'title' => __( 'Shop setup', 'freepay-for-woocommerce' ),
				],
				'title'                                 => [
					'title'       => __( 'Title', 'freepay-for-woocommerce' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'freepay-for-woocommerce' ),
					'default'     => __( 'Betalingskort', 'freepay-for-woocommerce' ),
					'desc_tip'    => true,
				],
				'description'                           => [
					'title'       => __( 'Customer Message', 'freepay-for-woocommerce' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'freepay-for-woocommerce' ),
					'default'     => __( 'Pay via FreePay. Allows you to pay with your credit card via FreePay.', 'freepay-for-woocommerce' ),
					'desc_tip'    => true,
				],
				'checkout_button_text'                  => [
					'title'       => __( 'Order button text', 'freepay-for-woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Text shown on the submit button when choosing payment method.', 'freepay-for-woocommerce' ),
					'default'     => __( 'Go to payment', 'freepay-for-woocommerce' ),
					'desc_tip'    => true,
				],
				'freepay_icons'                        => [
					'title'             => __( 'Credit card icons', 'freepay-for-woocommerce' ),
					'type'              => 'multiselect',
					'description'       => __( 'Choose the card icons you wish to show next to the FreePay payment option in your shop.', 'freepay-for-woocommerce' ),
					'desc_tip'          => true,
					'class'             => 'wc-enhanced-select',
					'css'               => 'width: 450px;',
					'custom_attributes' => [
						'data-placeholder' => __( 'Select icons', 'freepay-for-woocommerce' )
					],
					'default'           => '',
					'options'           => self::get_card_icons(),
				],
				'freepay_icons_maxheight'              => [
					'title'       => __( 'Credit card icons maximum height', 'freepay-for-woocommerce' ),
					'type'        => 'number',
					'description' => __( 'Set the maximum pixel height of the credit card icons shown on the frontend.', 'freepay-for-woocommerce' ),
					'default'     => 20,
					'desc_tip'    => true,
				],
				'freepay_test_mode'		              => [
					'title'       => __( 'Enable test mode', 'freepay-for-woocommerce' ),
					'type'        => 'checkbox',
					'description' => __( 'Enable use of test acquirer and test payments.', 'freepay-for-woocommerce' ),
					'default'     => 'no',
					'desc_tip'    => true,
				],
				'freepay_decline_url'	              => [
					'title'       => __( 'Declined payment URL (optional)', 'freepay-for-woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Here you can provide URL to a static page that you want to show to the client if the payment was unsuccessful and declined by the payment gateway.', 'freepay-for-woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				],
				'freepay_caching_expiration' => [
					'title'       => __( 'Cache Expiration', 'freepay-for-woocommerce' ),
					'label'       => __( 'Cache Expiration', 'freepay-for-woocommerce' ),
					'type'        => 'number',
					'description' => __( '<strong>Time in seconds</strong> for how long a transaction should be cached. <strong>Default: 604800 (7 days).</strong>', 'freepay-for-woocommerce' ),
					'default'     => 7 * DAY_IN_SECONDS,
					'desc_tip'    => false,
				],
				'freepay_payment_decline_message'	 => [
					'title'       => __( 'Declined payment message (optional)', 'freepay-for-woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Here you can provide the message shown to the customer when an order payment is declined.', 'freepay-for-woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				],
			];

		if(has_filter('wpml_object_id')) {
			$fields['freepay_currency']['options']['WPML'] = 'WPML plugin currency';
		}

		if ( WC_FreePay_Subscription_Utils::wcs_plugin_is_active() || WC_FreePay_Subscription_Utils::sfw_plugin_is_active() ) {
			$fields['woocommerce-subscriptions'] = [
				'type'  => 'title',
				'title' => 'Subscriptions'
			];

			$fields['subscription_autocomplete_renewal_orders'] = [
				'title'       => __( 'Complete renewal orders', 'freepay-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'freepay-for-woocommerce' ),
				'description' => __( 'Automatically mark a renewal order as complete on successful recurring payments.', 'freepay-for-woocommerce' ),
				'default'     => 'no',
				'desc_tip'    => true,
			];
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	public static function get_card_icons() {
		return [
			'dankort'               => 'Dankort',
			'visa'                  => 'Visa',
			'mastercard'            => 'Mastercard',
			'mobilepay'             => 'MobilePay',
			'gpay'             		=> 'GooglePay',
			'apay'             		=> 'ApplePay',
			'visadankort'      		=> 'Visa/Dankort',
			'anyday'      			=> 'Anyday',
			'forbrugsforening'		=> 'Forbrugsforening',
		];
	}


	/**
	 * Clears the log file.
	 *
	 * @return void
	 */
	public static function clear_logs_section() {
		printf( '<h3 class="wc-settings-sub-title">%s</h3>', __( 'Debug', 'freepay-for-woocommerce' ) );
		printf( '<a id="wcfp_wiki" class="wcfp-debug-button button button-primary" href="%s" target="_blank">%s</a>', self::get_wiki_link(), __( 'Got problems? Check out the Wiki.', 'freepay-for-woocommerce' ) );
		printf( '<a id="wcfp_logs" class="wcfp-debug-button button" href="%s">%s</a>', WC_FP_MAIN()->log->get_admin_link(), __( 'View debug logs', 'freepay-for-woocommerce' ) );

		if ( WC_FreePay_Helper::can_user_empty_logs() ) {
			printf( '<button role="button" id="wcfp_logs_clear" class="wcfp-debug-button button">%s</button>', __( 'Empty debug logs', 'freepay-for-woocommerce' ) );
		}

		if ( WC_FreePay_Helper::can_user_flush_cache() ) {
			printf( '<button role="button" id="wcfp_flush_cache" class="wcfp-debug-button button">%s</button>', __( 'Empty transaction cache', 'freepay-for-woocommerce' ) );
		}

		printf( '<br/>' );
		printf( '<h3 class="wc-settings-sub-title">%s</h3>', __( 'Enable', 'freepay-for-woocommerce' ) );
	}

	/**
	 * Returns the link to the gateway settings page.
	 *
	 * @return mixed
	 */
	public static function get_settings_page_url() {
		return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_freepay' );
	}

	/**
	 * Shows an admin notice if the setup is not complete.
	 *
	 * @return void
	 */
	public static function show_admin_setup_notices() {
		$error_fields = [];

		$mandatory_fields = [
			'freepay_apikey'     => __( 'Api User key', 'freepay-for-woocommerce' )
		];

		foreach ( $mandatory_fields as $mandatory_field_setting => $mandatory_field_label ) {
			if ( self::has_empty_mandatory_post_fields( $mandatory_field_setting ) ) {
				$error_fields[] = $mandatory_field_label;
			}
		}

		if ( ! empty( $error_fields ) ) {
			$message = sprintf( '<h2>%s</h2>', __( "Woo FreePay", 'freepay-for-woocommerce' ) );
			$message .= sprintf( '<p>%s</p>', sprintf( __( 'You have missing or incorrect settings. Go to the <a href="%s">settings page</a>.', 'freepay-for-woocommerce' ), self::get_settings_page_url() ) );
			$message .= '<ul>';
			foreach ( $error_fields as $error_field ) {
				$message .= "<li>" . sprintf( __( '<strong>%s</strong> is mandatory.', 'freepay-for-woocommerce' ), $error_field ) . "</li>";
			}
			$message .= '</ul>';

			printf( '<div class="%s">%s</div>', 'notice notice-error', $message );
		}

	}

	/**
	 * @return string
	 */
	public static function get_wiki_link() {
		return 'https://mw.freepay.dk/Content/Api.html';
	}

	/**
	 * Logic wrapper to check if some of the mandatory fields are empty on post request.
	 *
	 * @return bool
	 */
	private static function has_empty_mandatory_post_fields( $settings_field ) {
		$post_key    = 'woo_freepay_' . $settings_field;
		$setting_key = WC_FP_MAIN()->s( $settings_field );

		return empty( $_POST[ $post_key ] ) && empty( $setting_key );

	}

	/**
	 * @return string
	 */
	private static function get_required_symbol() {
		return '<span style="color: red;">*</span>';
	}
}


?>