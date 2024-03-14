<?php
/**
 * Gateway class
 *
 * @package ZiinaPayment
 */

namespace ZiinaPayment;

use Exception;
use WC_Payment_Gateway;

defined( 'ABSPATH' ) || exit();

/**
 * Class Gateway
 *
 * @package ZiinaPayment
 * @since   1.0.0
 */
class Gateway extends WC_Payment_Gateway {

	/**
	 * Ziina Gateway constructor.
	 */
	public function __construct() {
		$this->id                 = ziina_payment()->plugin_id;
		$this->method_title       = __( 'Ziina Payment', 'ziina' );
		$this->method_description = __( 'Pay via Ziina Payment', 'ziina' );
		$this->has_fields         = true;
		$this->supports           = array( 'products' );

		$this->init_form_fields();

		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );
		$this->enabled     = $this->get_option( 'enabled' );

		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->id,
			array(
				$this,
				'process_admin_options',
			)
		);
	}

	/**
	 * Initialise settings form fields.
	 *
	 * Add an array of fields to be displayed on the gateway's settings screen.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'             => array(
				'title'       => __( 'Enable/Disable', 'ziina' ),
				'label'       => __( 'Enable Ziina Payment', 'ziina' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'title'               => array(
				'title'       => __( 'Title', 'ziina' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'ziina' ),
				'default'     => __( 'Credit/Debit Card, Apple Pay or Google Pay', 'ziina' ),
				'desc_tip'    => true,
			),
			'description'         => array(
				'title'       => __( 'Description', 'ziina' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'ziina' ),
				'default'     => __( 'Pay with credit card, debit card, Apple Pay or Google Pay', 'ziina' ),
				'desc_tip'    => true,
			),
			'authorization_token' => array(
				'title' => __( 'API key', 'ziina' ),
				'label' => __( 'API key', 'ziina' ),
				'type'  => 'text',
			),
			'is_test'             => array(
				'title'       => __( 'Test Mode', 'ziina' ),
				'label'       => __( 'Enable Test Mode', 'ziina' ),
				'type'        => 'checkbox',
				'description' => __( 'When enabled, you can test payments on your site without charging a card.', 'ziina' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'logging'             => array(
				'title'       => __( 'Logging', 'ziina' ),
				'label'       => __( 'Log debug messages', 'ziina' ),
				'type'        => 'checkbox',
				'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'ziina' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Process Payment.
	 *
	 * Process the payment. Override this in your gateway. When implemented, this should.
	 * return the success and redirect in an array. e.g:
	 *
	 *        return array(
	 *            'result'   => 'success',
	 *            'redirect' => $this->get_return_url( $order )
	 *        );
	 *
	 * @param int $order_id Order ID.
	 *
	 * @throws Exception
	 */
	public function process_payment( $order_id ) {
		if ( isset( $_SERVER['CONTENT_TYPE'] ) && 'application/json' === $_SERVER['CONTENT_TYPE'] ) {
			try {
				$_POST = json_decode( file_get_contents( 'php://input' ), true );
			} catch ( Exception $e ) {
				throw new Exception( __( 'Request error. Try again or contact us', 'ziina' ) );
			}
		}

		$redirect_url = ziina_payment()->api()->create_payment_intent( $order_id );

		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			wp_redirect( $redirect_url );
			die;
		}

		return array(
			'result'   => 'success',
			'redirect' => $redirect_url,
		);
	}
}
