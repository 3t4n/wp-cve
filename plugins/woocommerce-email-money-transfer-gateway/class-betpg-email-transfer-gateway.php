<?php
/**
 * Plugin Name: BLAZING Email Transfer Payment Gateway
 * Plugin URI: https://blazingspider.com/plugins/woocommerce-email-money-transfer
 * Description: Many customers prefer to pay by Email Money Transfer, like Interac e-Transfer. This plugin provides a unique and secret question & answer for them.
 * Version: 2.6.0
 * Author: Massoud Shakeri, BlazingSpider
 * Author URI: https://www.blazingspider.com/
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @package BLAZING Email Transfer Payment Gateway
 *
 * @class         BETPG_Email_Transfer_Gateway
 * @extends        WC_Payment_Gateway
 * @since 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'plugins_loaded', 'betpg_email_transfer_gateway_init', 0 );

/**
 * That's the function to load this plugin
 *
 * @return void
 */
function betpg_email_transfer_gateway_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	/**
	 * BLAZING Email Money Transfer Gateway Class
	 */
	class BETPG_Email_Transfer_Gateway extends WC_Payment_Gateway {

		/**
		 * Initialize the class
		 */
		public function __construct() {

			// Register plugin information.
			$this->id = 'betpg';
			// Load plugin checkout icon.
			$this->icon               = plugins_url( '/images/e-transfer.jpg', __FILE__ );
			$this->method_title       = __( 'BLAZING Email Transfer', 'betpg_gateway' );
			$this->method_description = __( 'Have your customers pay thru Interac (or any other Email Transfer means).', 'betpg_gateway' );
			$this->has_fields         = true;
			/**
				$this->supports   = array(
				'products',
				'subscriptions',
				'subscription_cancellation',
				'subscription_suspension',
				'subscription_reactivation',
				'subscription_amount_changes',
				'subscription_date_changes',
				'subscription_payment_method_change',
				'refunds'
			);
			*/
			// Create plugin fields and settings.
			$this->init_form_fields();
			$this->init_settings();

			// Get settings.
			$this->title              = esc_textarea( $this->get_option( 'title' ) );
			$this->description        = esc_textarea( $this->get_option( 'description' ) );
			$this->instructions       = esc_textarea( $this->get_option( 'instructions', $this->description ) );
			$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );
			$this->enable_for_virtual = $this->get_option( 'enable_for_virtual', 'yes' ) === 'yes' ? true : false;

			// Add hooks.
			add_action(
				'woocommerce_update_options_payment_gateways_' . $this->id,
				array(
					$this,
					'process_admin_options',
				)
			);
			add_action( 'woocommerce_thankyou', array( $this, 'thankyou_page' ), 1 );
			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );

			// Get setting values.
			foreach ( $this->settings as $key => $val ) {
				$this->$key = $val;
			}

		}

		/**
		 * Initialize Gateway Settings Form Fields.
		 */
		public function init_form_fields() {
			$shipping_methods = array();

			if ( is_admin() ) {
				foreach ( WC()->shipping()->load_shipping_methods() as $method ) {
					$shipping_methods[ $method->id ] = $method->get_method_title();
				}
			}

			$this->form_fields = array(
				'enabled'            => array(
					'title'       => __( 'Enable BLAZING EMT', 'betpg_gateway' ),
					'label'       => __( 'Enable BLAZING Email Transfer', 'betpg_gateway' ),
					'type'        => 'checkbox',
					'description' => '',
					'default'     => 'no',
				),
				'title'              => array(
					'title'       => __( 'Title', 'betpg_gateway' ),
					'type'        => 'text',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'betpg_gateway' ),
					'default'     => __( 'BLAZING Email Transfer', 'betpg_gateway' ),
					'desc_tip'    => true,
				),
				'description'        => array(
					'title'       => __( 'Description', 'betpg_gateway' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your website.', 'betpg_gateway' ),
					'default'     => __( 'After placing your order, please send an Email money transfer to us (thru Interac or any other Email Transfer means).', 'betpg_gateway' ),
					'desc_tip'    => true,
				),
				'instructions'       => array(
					'title'       => __( 'Instructions', 'betpg_gateway' ),
					'type'        => 'textarea',
					'description' => __( 'MAKE SURE YOU KEEP {1} and {2} PARAMETERS, and provide a legitimate Email address', 'betpg_gateway' ),
					'default'     => __( 'After placing your order, please send an Email money transfer to the following:<br />Email: xxx@yyy.com<br />Secret Question: Your Order Number {1}<br />Secret Answer: {2} (MAKE SURE YOU DO NOT REMOVE THESE TWO PARAMETERS)<br />Thanks for choosing us! We appreciate your business.', 'betpg_gateway' ),
					'desc_tip'    => true,
				),
				'enable_for_methods' => array(
					'title'             => __( 'Enable for Shipping Methods', 'betpg_gateway' ),
					'type'              => 'multiselect',
					'class'             => 'wc-enhanced-select',
					'css'               => 'width: 450px;',
					'default'           => '',
					'description'       => __( 'If EMT is only available for certain methods, set it up here. Leave blank to enable for all methods.', 'betpg_gateway' ),
					'options'           => $shipping_methods,
					'desc_tip'          => true,
					'custom_attributes' => array(
						'data-placeholder' => __( 'Select Shipping Methods', 'betpg_gateway' ),
					),
				),
				'enable_for_virtual' => array(
					'title'   => __( 'Enable for virtual orders', 'betpg_gateway' ),
					'label'   => __( 'Enable BLAZING EMT if the order is virtual', 'betpg_gateway' ),
					'type'    => 'checkbox',
					'default' => 'yes',
				),
				'emt_order_status'   => array(
					'title'   => __( 'Order Status', 'betpg_gateway' ),
					'type'    => 'select',
					'default' => 'on-hold',
					'options' => array(
						'on-hold'    => 'on-hold',
						'pending'    => 'Pending',
						'processing' => 'Processing',
					),
				),
			);
		}

		/**
		 * Generate a string of 36 alphanumeric characters to associate with each saved billing method.
		 *
		 * @param int $user_id random key is unique for every user.
		 */
		private function random_key( $user_id ) {
			$key = '';
			if ( 0 != $user_id ) {
				$key = esc_attr( get_the_author_meta( 'emt_secret_answer', $user_id ) );
				if ( ! empty( $key ) ) {
					return $key;
				}
			}
			$valid_chars = array(
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p',
				'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '2', '3', '4', '5', '6', '7', '8', '9',
			);
			for ( $i = 0; $i < 6; $i ++ ) {
				$key .= $valid_chars[ wp_rand( 0, 31 ) ];
			}
			if ( 0 != $user_id ) {
				update_user_meta( $user_id, 'emt_secret_answer', $key );
			}

			return $key;

		}

		/**
		 * Check If The Gateway Is Available For Use
		 *
		 * @return bool
		 */
		public function is_available() {
			$order = null;

			if ( ! $this->enable_for_virtual ) {
				if ( WC()->cart && ! WC()->cart->needs_shipping() ) {
					return false;
				}

				if ( is_page( wc_get_page_id( 'checkout' ) ) && 0 < get_query_var( 'order-pay' ) ) {
					$order_id = absint( get_query_var( 'order-pay' ) );
					$order    = wc_get_order( $order_id );

					// Test if order needs shipping.
					$needs_shipping = false;

					if ( 0 < count( $order->get_items() ) ) {
						foreach ( $order->get_items() as $item ) {
							$_product = $item->get_product();

							if ( $_product->needs_shipping() ) {
								$needs_shipping = true;
								break;
							}
						}
					}

					$needs_shipping = apply_filters( 'woocommerce_cart_needs_shipping', $needs_shipping );

					if ( $needs_shipping ) {
						return false;
					}
				}
			}

			if ( ! empty( $this->enable_for_methods ) ) {
				// -------- Updated in ver. 1.0.1:
				// Apparently, in presence of other plugins, this plugin was called before woocommerce was initiated.
				// So I just added a few lines to check if WC()->session exists.
				if ( ! is_object( WC() ) ) {
					return false;
				}
				if ( ! is_object( WC()->session ) ) {
					return false;
				}
				// -------- end of update.

				// Only apply if all packages are being shipped via local pickup.
				$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );

				if ( isset( $chosen_shipping_methods_session ) ) {
					$chosen_shipping_methods = array_unique( $chosen_shipping_methods_session );
				} else {
					$chosen_shipping_methods = array();
				}

				$check_method = false;

				if ( is_object( $order ) ) {
					if ( $order->get_shipping_method() ) {
						$check_method = $order->get_shipping_method();
					}
				} elseif ( ! empty( $chosen_shipping_methods ) && count( $chosen_shipping_methods ) === 1 ) {
					$check_method = $chosen_shipping_methods[0];
				}

				if ( ! $check_method ) {
					return false;
				}

				$found = false;

				foreach ( $this->enable_for_methods as $method_id ) {
					if ( strpos( $check_method, $method_id ) === 0 ) {
						$found = true;
						break;
					}
				}

				if ( ! $found ) {
					return false;
				}
			}
			return parent::is_available();
		}
		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id The id of the current order.
		 *
		 * @return array
		 */
		public function process_payment( $order_id ) {

			$order = wc_get_order( $order_id );

			// Add secret question as an order note.
			$rnd_key = $this->random_key( $order->get_user_id() );
			$order->add_order_note( "Answer to the Secret Question: $rnd_key" );

			// Changed in 2.3.
			$status = ! empty( $this->emt_order_status ) ? $this->emt_order_status : 'on-hold';
			$order->update_status( $status );

			// Reduce stock levels.
			// $order->reduce_order_stock(); changed to.
			wc_reduce_stock_levels( $order_id );

			// Remove cart.
			WC()->cart->empty_cart();

			// Put order number & secret answer in the instructions.

			// Return thankyou redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}

		/**
		 * Output for the order received page.
		 *
		 * @param int $order_id id of the order.
		 */
		public function thankyou_page( $order_id ) {
			// -------- Updated in ver. 1.0.2:
			// to show instructions only if this payment method is selected.
			$order = wc_get_order( $order_id );
			if ( $this->instructions && 'betpg' === $order->get_payment_method() ) {
				echo wp_kses_post( wpautop( wptexturize( $this->get_instructions( $order ) ) ) );
			}
		}

		/**
		 * It retrieves tthe Answer to the secret question from order note
		 *
		 * @param WC_Order $order current order object.
		 *
		 * @return string   $instructions
		 */
		public function get_instructions( $order ) {
			$args    = array(
				'post_id' => $order->get_order_number(),
				'type'    => 'order_note',
				'status'  => 'all',
			);
			$rnd_key = '';
			$user_id = $order->get_user_id();
			if ( 0 != $user_id ) {
				$rnd_key = esc_attr( get_the_author_meta( 'emt_secret_answer', $user_id ) );
			}
			if ( empty( $rnd_key ) ) {
				$rnd_key = '{2}';
				remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
				$comments = get_comments( $args );
				foreach ( $comments as $comment ) {
					$pos = strpos( $comment->comment_content, 'Answer to the Secret Question' );
					if ( false !== $pos ) {
						$rnd_key = substr( $comment->comment_content, - 6 );
						break;
					}
				}
				add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
			}

			$instructions = str_replace( '{1}', $order->get_order_number(), $this->instructions );
			$instructions = str_replace( '{2}', $rnd_key, $instructions );

			return $instructions;
		}

		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 *
		 * @param WC_Order $order that's the order object.
		 * @param bool     $sent_to_admin if the email is going to sent to admin.
		 * @param bool     $plain_text if the email is in plain text.
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			if ( $this->instructions && ! $sent_to_admin && 'betpg' === $order->get_payment_method() &&
				( $order->has_status( 'on-hold' ) || $order->has_status( 'processing' ) || $order->has_status( 'pending' ) ) ) {
				echo wp_kses_post( wpautop( wptexturize( $this->get_instructions( $order ) ) ) ) . PHP_EOL;
			}
		}

	}

	/*
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			if ( $this->instructions && ! $sent_to_admin && 'betpg' === $order->get_payment_method() ) {
				$rnd_key = "{2}";
				$args = array(
					'post_id' => $order->get_order_number(),
					'type' => 'order_note',
					'status' => 'all',
				);

				remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

				$comments = get_comments( $args );
				$instructions = $this->instructions;
				foreach ( $comments as $comment ) {
					$instructions .= $comment->comment_content;
				}

				add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
				$instructions = str_replace("{1}", $order->get_order_number(), $instructions);
				$instructions = str_replace("{2}", $rnd_key, $instructions);
				echo wp_kses_post(wpautop( wptexturize( $instructions ) )) . PHP_EOL;
			}
		}
	*/

	/**
	 * Add the gateway to woocommerce
	 *
	 * @param array $methods allowed payment methods.
	 *
	 * @return array $methods array of allowed payment methods
	 */
	function add_email_money_transfer_gateway( $methods ) {
		$methods[] = 'BETPG_Email_Transfer_Gateway';

		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_email_money_transfer_gateway' );

	/**
	 * Add new fields above 'Update' button.
	 *
	 * @param WP_User $user User object.
	 */
	function emt_secret_answer_profile_field( $user ) {

		$emt_secret_answer = esc_attr( get_the_author_meta( 'emt_secret_answer', $user->ID ) );

		?>
		<h3>Email Money Transfer Secret Answer</h3>

		<input type="text" name="emt_secret_answer" id="emt_secret_answer" value="<?php echo sanitize_text_field( $emt_secret_answer ); ?>"
			class="regular-text"/><br/>
		<span class="description"><?php esc_html_e( 'Please enter your secret answer.' ); ?></span>
		<?php
	}

	/**
	 * Save additional profile fields.
	 *
	 * @param int $user_id Current user ID.
	 */
	function emt_save_profile_fields( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		$secret_answer = isset( $_POST['emt_secret_answer'] ) ? sanitize_text_field( wp_unslash( $_POST['emt_secret_answer'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		if ( empty( $secret_answer ) ) {
			return;
		}

		update_user_meta( $user_id, 'emt_secret_answer', $secret_answer );
	}

	add_action( 'show_user_profile', 'emt_secret_answer_profile_field', 20 );
	add_action( 'edit_user_profile', 'emt_secret_answer_profile_field', 20 );
	add_action( 'personal_options_update', 'emt_save_profile_fields' );
	add_action( 'edit_user_profile_update', 'emt_save_profile_fields' );
}
