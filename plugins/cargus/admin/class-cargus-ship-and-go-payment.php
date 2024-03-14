<?php
/**
 * Custom payment gateway for ship&go delivery.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Payment Gateway.
 *
 * Provides a Custom Payment Gateway, mainly for testing purposes.
 */

if ( ! class_exists( 'Cargus_Ship_And_Go_Payment' ) ) {
	/**
	 * Custom payment gateway for ship&go delivery.
	 *
	 * @link       https://cargus.ro/
	 * @since      1.0.0
	 *
	 * @package    Cargus
	 * @subpackage Cargus/admin
	 */
	#[AllowDynamicProperties]
	class Cargus_Ship_And_Go_Payment extends WC_Payment_Gateway {

		/**
		 * The text domani.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $domain    The cargus text domain.
		 */
		private $domain;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {

			$this->domain = 'cargus';

			$this->id                 = 'cargus_ship_and_go_payment';
			$this->has_fields         = false;
			$this->method_title       = __( 'Plata ramburs la Ship & Go', 'cargus' );
			$this->method_description = __( 'Link de plata a rambursului pe telefon la momentul livrarii.', 'cargus' );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables.
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );
			$this->order_status = $this->get_option( 'order_status', 'processing' );

			// Actions.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}

		/**
		 * Initialise Gateway Settings Form Fields.
		 */
		public function init_form_fields() {

			$this->form_fields = array(
				'enabled'      => array(
					'title'   => __( 'Activează/Dezactivează', 'cargus' ),
					'type'    => 'checkbox',
					'label'   => __( 'Activeaza plata ramburs la Ship & Go', 'cargus' ),
					'default' => 'yes',
				),
				'title'        => array(
					'title'       => __( 'Titlu', 'cargus' ),
					'type'        => 'text',
					'description' => __( 'Acesta este titlul pe care utilizatorul îl vede la checkout.', 'cargus' ),
					'default'     => __( 'Ramburs la Ship & Go', 'cargus' ),
					'desc_tip'    => true,
				),
				'order_status' => array(
					'title'       => __( 'Status comandă', 'cargus' ),
					'type'        => 'select',
					'class'       => 'wc-enhanced-select',
					'description' => __( 'Alege statusul comenzii imediat după plasare.', 'cargus' ),
					'default'     => 'wc-processing',
					'desc_tip'    => true,
					'options'     => wc_get_order_statuses(),
				),
				'description'  => array(
					'title'       => __( 'Desriere', 'cargus' ),
					'type'        => 'textarea',
					'description' => __( 'Desrierea metodei de plată pe care o va vede utilizatorul la checkout.', 'cargus' ),
					'default'     => __( 'Link de plata a rambursului pe telefon la momentul livrarii.', 'cargus' ),
					'desc_tip'    => true,
				),
				'instructions' => array(
					'title'       => __( 'Instrucțiuni', 'cargus' ),
					'type'        => 'textarea',
					'description' => __( 'Instrucțiuni care vor fi adăugate pe thank you page și în email-uri.', 'cargus' ),
					'default'     => 'Link de plata a rambursului pe telefon la momentul livrarii.',
					'desc_tip'    => true,
				),
			);
		}

		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) ); //phpcs:ignore
			}
		}

		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order The woocommerce order object.
		 * @param bool     $sent_to_admin Check whenever to send the email or not to the user admin.
		 * @param bool     $plain_text Plain text filter.
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			if ( $this->instructions && ! $sent_to_admin && 'cargus_ship_and_go_payment' === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;  //phpcs:ignore
			}
		}

		/**
		 * Process the payment and return the result.
		 *
		 * @param int $order_id The woocommerce order id.
		 * @return array
		 */
		public function process_payment( $order_id ) {

			$order = wc_get_order( $order_id );

			$status = 'wc-' === substr( $this->order_status, 0, 3 ) ? substr( $this->order_status, 3 ) : $this->order_status;

			// Set order status.
			$order->update_status( $status, __( 'Checkout cu Cargus Ship & Go. ', 'cargus' ) );

			// Reduce stock levels.
			$order->reduce_order_stock();

			// Remove cart.
			WC()->cart->empty_cart();

			// Return thankyou redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}
	}
}
