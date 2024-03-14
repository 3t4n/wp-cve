<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Frontend\Interceptor;

use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Helper\PluginBag;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;

final class PreSubmitData implements HookProvider, Conditional {

	/** @var string */
	private const SHOPMAGIC_PRESUBMIT = 'shopmagic-presubmit';
	/** @var CurrentCustomer */
	private $customer_interceptor;
	/** @var string */
	private $assets_url;

	public function __construct(
		CurrentCustomer $customer_interceptor,
		PluginBag $plugin_bag
	) {
		$this->customer_interceptor = $customer_interceptor;
		$this->assets_url           = $plugin_bag->get_assets_url();
	}

	public static function is_needed(): bool {
		return WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) &&
		       ! is_user_logged_in() &&
		       filter_var(
			       GeneralSettings::get_option( 'enable_session_tracking', true ),
			       \FILTER_VALIDATE_BOOLEAN
		       );
	}

	public function hooks(): void {
		add_action(
			'wp_enqueue_scripts',
			function (): void {
				if ( ! is_checkout() ) {
					return;
				}

				wp_register_script( self::SHOPMAGIC_PRESUBMIT,
					$this->assets_url . '/js/presubmit.js',
					[ 'jquery' ],
					SHOPMAGIC_VERSION,
					true );
				wp_localize_script( self::SHOPMAGIC_PRESUBMIT,
					'shopmagic_presubmit_params',
					$this->get_js_params() );
				wp_enqueue_script( self::SHOPMAGIC_PRESUBMIT );
			}
		);
		add_action(
			'wp_ajax_nopriv_capture_email_url',
			function () {
				$this->ajax_capture_email();
			}
		);
		add_action(
			'wp_ajax_nopriv_capture_checkout_field_url',
			function () {
				$this->ajax_capture_checkout_field();
			}
		);
	}

	/**
	 * @return array{email_capture_selectors: mixed[], checkout_capture_selectors: mixed[],
	 *                                        capture_email_url: mixed, capture_checkout_field_url:
	 *                                        mixed}
	 */
	private function get_js_params(): array {
		$params                               = [];
		$params['email_capture_selectors']    = $this->get_email_capture_selectors();
		$params['checkout_capture_selectors'] = $this->get_checkout_capture_fields();
		$params['capture_email_url']          = add_query_arg( [ 'action' => 'capture_email_url' ],
			admin_url( 'admin-ajax.php' ) );
		$params['capture_checkout_field_url'] = add_query_arg( [ 'action' => 'capture_checkout_field_url' ],
			admin_url( 'admin-ajax.php' ) );

		return $params;
	}

	/**
	 * @return mixed[]
	 */
	private function get_email_capture_selectors(): array {
		return apply_filters(
			'shopmagic/core/presubmit/guest_capture_fields',
			[
				'.woocommerce-checkout [type="email"]',
				'#billing_email',
				'.automatewoo-capture-guest-email',
				'input[name="billing_email"]',
			]
		);
	}

	/**
	 * @return mixed[]
	 */
	private function get_checkout_capture_fields(): array {
		return apply_filters(
			'shopmagic/core/presubmit/checkout_capture_fields',
			[
				'billing_first_name',
				'billing_last_name',
				'billing_company',
				'billing_phone',
				'billing_country',
				'billing_address_1',
				'billing_address_2',
				'billing_city',
				'billing_state',
				'billing_postcode',
			]
		);
	}

	public function ajax_capture_email(): void {
		$email           = sanitize_email( $_REQUEST['email'] );
		$checkout_fields = $_REQUEST['checkout_fields'];

		$this->customer_interceptor->set_user_email( $email );

		if ( \is_array( $checkout_fields ) ) {
			foreach ( $checkout_fields as $field_name => $field_value ) {
				if ( ! $this->is_checkout_capture_field( $field_name ) || empty( $field_value ) ) {
					continue; // IMPORTANT don't save the field if it is empty.
				}

				$this->customer_interceptor->set_meta( sanitize_key( $field_name ),
					sanitize_text_field( $field_value ) );
			}
		} else {
			$location = wc_get_customer_default_location();
			if ( $location['country'] ) {
				$this->customer_interceptor->set_meta( 'billing_country', $location['country'] );
			}
		}

		wp_send_json_success();
	}

	private function is_checkout_capture_field( string $field_name ): bool {
		return \in_array( $field_name, $this->get_checkout_capture_fields(), true );
	}

	/**
	 * Capture an additional field from the checkout page
	 */
	public function ajax_capture_checkout_field(): void {
		$field_name  = sanitize_key( $_REQUEST['field_name'] );
		$field_value = stripslashes( sanitize_text_field( $_REQUEST['field_value'] ) );

		if ( $this->is_checkout_capture_field( $field_name ) ) {
			$this->customer_interceptor->set_meta( $field_name, $field_value );
		}

		wp_send_json_success();
	}
}
