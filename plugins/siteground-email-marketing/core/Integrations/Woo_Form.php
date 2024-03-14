<?php
namespace SG_Email_Marketing\Integrations;

use Automattic\WooCommerce\StoreApi\StoreApi;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;

use SG_Email_Marketing\Integrations\WooCommerce\Woo_Checkout_Block_Integration;

/**
 * Class managing WooCommerce checkout forms integrations.
 */
class Woo_Form extends Integrations {

	/**
	 * The integration id.
	 *
	 * @var string
	 */
	public $id = 'woo_form';

	/**
	 * Get the integration data.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array containing integration data.
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled'       => 0,
				'labels'        => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system'        => 0,
				'name'          => $this->id,
			)
		);

		$settings['title']       = __( 'WooCommerce Checkout Page', 'siteground-email-marketing' );
		$settings['description'] = __( 'Add an optional checkbox to your WooCommerce checkout page, enabling customers to sign up for your mailing list.', 'siteground-email-marketing' );

		$settings['enabled'] = ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ? 2 : $settings['enabled'];

		return $settings;
	}

	/**
	 * Add consent field to WooCommerce Checkout.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields The checkout fields.
	 *
	 * @return array $fields
	 */
	public function add_checkout_form_consent( $fields ) {
		// Get integration data.
		$integration = $this->fetch_settings();

		// Prepare the fields.
		$fields['billing']['sg_email_consent'] = array(
			'type'  => 'checkbox',
			'label' => ! empty( $integration['checkbox_text'] ) ? $integration['checkbox_text'] : __( 'Sign-up for updates and special offers', 'siteground-email-marketing' ),
			'class' => array(
				'form-row-wide',
			),
			'clear' => true,
		);

		return $fields;
	}

	/**
	 * Add consent field upon order creation.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $order_id        The id of the order.
	 * @param array  $posted_data     Order details.
	 * @param object $order           Order options.
	 */
	public function add_create_order_form_consent( $order_id, $posted_data, $order ) {

		if (
			! isset( $_REQUEST['woocommerce-process-checkout-nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_REQUEST['woocommerce-process-checkout-nonce'] ), 'woocommerce-process_checkout' )
			) {
			return;
		}

		if ( ! isset( $_POST['sg_email_consent'] ) ) { // phpcs:ignore
			return;
		}

		$data = array(
			'timestamp' => strtotime( $order->get_date_created() ),
			'ip'        => $order->get_customer_ip_address(),
			'labels'    => $this->get_label_ids( $this->fetch_settings() ),
			'firstName' => $posted_data['billing_first_name'],
			'lastName'  => $posted_data['billing_last_name'],
			'email'     => $posted_data['billing_email'],
		);

		if ( $this->helper->is_cron_disabled() ) {
			$this->mailer_api->send_data( $data );
			return;
		}

		update_post_meta( $order_id, 'sg_email_marketing_user_data', $data );
	}

	/**
	 * Registers the endpoint data for the WooCommerce Store API.
	 *
	 * @version 1.1.4
	 *
	 * @return void
	 */
	public function sg_wc_store_api_register_endpoint_data() {
		if ( function_exists( 'woocommerce_store_api_register_endpoint_data' ) ) {
			woocommerce_store_api_register_endpoint_data(
				array(
					'endpoint'        => CheckoutSchema::IDENTIFIER,
					'namespace'       => 'sg-email-marketing',
					'data_callback'   => array( $this, 'sg_woo_block_data_callback' ),
					'schema_callback' => array( $this, 'sg_woo_block_schema_callback' ),
					'schema_type'     => ARRAY_A,
				)
			);
		}
	}

	/**
	 * Register the SG Block into the integration registry.
	 *
	 * @version 1.1.4
	 *
	 * @param      object $integration_registry  The integration registry
	 */
	public function sg_wc_block_integration_registry( $integration_registry ) {
		$integration_registry->register( new Woo_Checkout_Block_Integration() );
	}

	/**
	 * Callback function for providing the data to the WooCommerce Store API.
	 *
	 * @version 1.1.4
	 *
	 * @return array The data to be exposed in the WooCommerce Store API.
	 */
	public function sg_woo_block_data_callback() {
		return array(
			'sg-email-marketing-woo-checkbox' => '',
		);
	}

	/**
	 * Callback function for providing the schema for the custom endpoint data.
	 *
	 * @version 1.1.4
	 *
	 * @return array The schema definition for the custom endpoint data.
	 */
	public function sg_woo_block_schema_callback() {
		return array(
			'sg-email-marketing-woo-checkbox'  => array(
				'description' => __( 'SG Woo Checkbox', 'sg-email-marketing' ),
				'type'        => array( 'bool', 'null' ),
				'readonly'    => false,
			),
		);
	}

	/**
	 * Filter to modify the checkbox label.
	 *
	 * @version 1.1.4
	 *
	 * @param string $label The original label.
	 * @return string The modified label.
	 */
	public function sg_checkbox_label_filter( $label ) {
		// Get integration data.
		$integration = $this->fetch_settings();

		// Modify the label as per the integration data.
		$custom_label = ! empty( $integration['checkbox_text'] ) ? $integration['checkbox_text'] : __( 'Sign-up for updates and special offers', 'siteground-email-marketing' );

		// Return the modified label.
		return $custom_label;
	}

	/**
	 * Fetches a sg woo checkbox value.
	 *
	 * @version 1.1.4
	 *
	 * @param      object $order    The order
	 * @param      object $request  The request
	 */
	public function fetch_sg_woo_checkbox_value( $order, $request ) {
		// Return if the visitor have not subscribed.
		if ( empty( $request['extensions']['sg-email-marketing']['sg-email-marketing-woo-checkbox'] ) ) {
			return;
		}

		$data = array(
			'timestamp' => strtotime( $order->get_date_created() ),
			'ip'        => $order->get_customer_ip_address(),
			'labels'    => $this->get_label_ids( $this->fetch_settings() ),
			'firstName' => $order->get_billing_first_name(),
			'lastName'  => $order->get_billing_last_name(),
			'email'     => $order->get_billing_email(),
		);

		if ( $this->helper->is_cron_disabled() ) {
			$this->mailer_api->send_data( $data );
			return;
		}

		update_post_meta( $order->get_id(), 'sg_email_marketing_user_data', $data );
	}
}
