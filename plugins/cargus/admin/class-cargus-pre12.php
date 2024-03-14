<?php
/**
 * Add woocommerce cargus pre12 shipping method.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Cargus_Pre12' ) ) {
	/**
	 * Add woocommerce cargus shipping method.
	 *
	 * @link       https://cargus.ro/
	 * @since      1.0.0
	 *
	 * @package    Cargus
	 * @subpackage Cargus/admin
	 */
	#[AllowDynamicProperties]
	class Cargus_Pre12 extends WC_Shipping_Method {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param Int $instance_id The woocommerce shipping method instance id.
		 * @since    1.0.0
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id                 = 'cargus_pre12';
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = __( 'Cargus Pre12', 'cargus' );
			$this->method_description = __( 'Cargus livrare la domiciliu de luni pana vineri pana in ora 12:00.', 'cargus' );
			$this->supports           = array(
				'shipping-zones',
				'settings',
			);

			$this->load_dependencies();
			$this->init();

			$this->title             = isset( $this->settings['title'] ) ? $this->settings['title'] : null;
			$this->fixed             = isset( $this->settings['fixed'] ) ? $this->settings['fixed'] : null;
			$this->shipping_cost_tax = isset( $this->settings['shipping_cost_tax'] ) ? $this->settings['shipping_cost_tax'] : null;

		}

		/**
		 * Include the cargus dependencies.
		 *
		 * @since    1.0.0
		 */
		public function load_dependencies() {

			/**
			 * The class responsible for making the php api call.
			 */
			require_once plugin_dir_path( __FILE__ ) . 'class-cargus-api.php';
		}

		/**
		 * Load the settings API.
		 *
		 * @since    1.0.0
		 */
		public function init() {
			$this->init_form_fields();
			$this->init_settings();

			// Save settings in admin if you have any defined.
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

		}

		/**
		 * Initialize the admin form fields.
		 *
		 *  @since    1.0.0
		 */
		public function init_form_fields() {
			$extra_fields = array(
				'title' => array(
					'title'   => __( 'Titlu', 'cargus' ),
					'type'    => 'text',
					'default' => __( 'Cargus Pre12', 'cargus' ),
				),
				'fixed' => array(
					'title'   => __( 'Cost fix transport', 'cargus' ),
					'type'    => 'text',
					'default' => '',
				),
			);

			$this->form_fields += apply_filters( 'cargus_saturday_extra_fields', $extra_fields );

		}

		/**
		 * Calculate the shipping cost.
		 *
		 * @since    1.0.0
		 * @param Array $package The shipping package data array.
		 */
		public function calculate_shipping( $package = array() ) {
			$calculated_cost = $this->get_shipping_cost( $this, $package );

			if ( ! is_null( $calculated_cost ) ) {
				if ( 0 === $calculated_cost ) {
					$this->title .= apply_filters( 'cargus_saturday_method_title_free', ' - Gratuit');
				}

				$rate = array(
					'id'       => $this->id,
					'label'    => $this->title,
					'cost'     => $calculated_cost,
					'calc_tax' => 'per_order',
				);

				$this->add_rate( $rate );
			}
		}

		/**
		 * Get the calculated the shipping cost.
		 *
		 * @param $cargus_options
		 * @param $package
		 *
		 * @return float|int|string|void|null
		 * @since    1.0.0
		 */
		private function get_shipping_cost( $package ) {
			try {
				$cargus = new Cargus_Api();
				$cargus_options = get_option( 'woocommerce_cargus_settings' );

				if ( property_exists( $cargus, 'token' ) && ! is_object( $cargus->token ) && ! is_array( $cargus->token ) &&
				     isset( $cargus_options['apikey'] ) && '' !== $cargus_options['apikey']
				) {
					// Get Payemnt method.
					//phpcs:disable
					$available_payment_gateways = WC()->payment_gateways->get_available_payment_gateways();
					if ( isset( $_POST ) && isset( $_POST['payment_method'] ) && isset( $available_payment_gateways[ $_POST['payment_method'] ] ) ) {
						$current_payment_gateway = $available_payment_gateways[ $_POST['payment_method'] ];
					} elseif ( isset( WC()->session->chosen_payment_method ) && isset( $available_payment_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$current_payment_gateway = $available_payment_gateways[ WC()->session->chosen_payment_method ];
					} elseif ( isset( $available_payment_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$current_payment_gateway = $available_payment_gateways[ get_option( 'woocommerce_default_gateway' ) ];
					} else {
						$current_payment_gateway = current( $available_payment_gateways );
					}
					// Check free shipping coupon.
					//phpcs:enable
					$coupons = WC()->cart->get_coupons();
					if ( $coupons ) {
						foreach ( $coupons as $code => $coupon ) {
							if ( $coupon->is_valid() && $coupon->get_free_shipping() ) {
								return 0;
							}
						}
					}

					// Get total.
					$total = WC()->cart->cart_contents_total + array_sum( WC()->cart->get_cart_contents_taxes() );

					// Get ramburs.
					$ramburs = $total;
					if ( ! in_array( $current_payment_gateway->id, array( 'cod', 'cargus_ship_and_go_payment' ), true ) ) {
						$ramburs = 0;
					}

					// UC check free.
					if ( ! empty( $cargus_options['free'] ) && $total >= $cargus_options['free'] ) {
						return 0;
					}

					// UC check fixed.
					if ( '' !== $this->fixed && is_numeric( $this->fixed ) ) {
						$shipping_cost = $this->fixed;
					}

					$discount_percent = apply_filters( 'cargus_saturday_add_shipping_discount', 0 );

					if ( 0 !== $shipping_cost && is_int( $discount_percent ) ) {
						$shipping_cost -= ( $discount_percent / 100 * $shipping_cost );
						return $shipping_cost;
					}
				}
			} catch ( Exception $ex ) {
				return null;
			}
		}

		/**
		 * Return admin options as a html string.
		 *
		 * @return string
		 */
		public function get_admin_options_html() {
			if ( $this->instance_id ) {
				$settings_html = '<table class="form-table">' . $this->generate_settings_html( $this->get_instance_form_fields(), false ) . '</table>'; // WPCS: XSS ok.
			} else {
				$settings_html = '<table class="form-table">' . $this->generate_settings_html( $this->get_form_fields(), false ) . '</table>'; // WPCS: XSS ok.
			}

			return '<div class="wc-shipping-zone-method-fields">' . $settings_html . '</div>';
		}
	}
}
