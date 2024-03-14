<?php
/**
 * Plugin Name: Shop as Client for WooCommerce
 * Plugin URI: https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/
 * Description: Allows a WooCommerce Store Administrator or Shop Manager to use the frontend and assign a new order to a registered or new customer. Useful for phone or email orders.
 * Version: 3.5
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com/
 * Text Domain: shop-as-client
 * Domain Path: /languages
 * Requires at least: 5.4
 * Tested up to: 6.5
 * Requires PHP: 7.0
 * WC requires at least: 5.4
 * WC tested up to: 8.7
**/

/* WooCommerce CRUD ready */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'SHOPASCLIENT_REQUIRED_WC', '5.4' );
define( 'SHOPASCLIENT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SHOPASCLIENT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if WooCommerce is active
 **/
add_action( 'plugins_loaded', function() {
	if ( class_exists( 'WooCommerce' ) && defined( 'WC_VERSION' ) && version_compare( WC_VERSION, SHOPASCLIENT_REQUIRED_WC, '>=' ) ) {

		/* Version */
		if ( ! function_exists( 'get_plugin_data' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}
		$temp_plugin_data = get_plugin_data( __FILE__ );
		define( 'SHOPASCLIENT_VERSION', $temp_plugin_data['Version'] );
	
		/* Languages */
		add_action( 'plugins_loaded', 'shop_as_client_init', 7 );
		function shop_as_client_init() {
			load_plugin_textdomain( 'shop-as-client' );
			add_action( 'wp_enqueue_scripts', 'shop_as_client_enqueue_scripts' );
		}
	
		/* Can checkout with shop as client? - Should be used for both classic and blocks checkout */
		function shop_as_client_can_checkout() {
			// The shop_as_client_allow_checkout filter can be used to allow other user roles to use this functionality - Use carefully and wisely
			return current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) || apply_filters( 'shop_as_client_allow_checkout', false );
		}
	
		/* Our field - Classic checkout only - Blocks checkout in includes/class-shop-as-client-checkout-blocks.php */
		add_filter( 'woocommerce_billing_fields' , 'shop_as_client_init_woocommerce_billing_fields', PHP_INT_MAX );
		function shop_as_client_init_woocommerce_billing_fields( $fields ) {
			if ( shop_as_client_can_checkout() && is_checkout() ) {
				$priority = apply_filters( 'shop_as_client_field_priority', 990 );
				// Shop as client?
				$fields['billing_shop_as_client'] = array(
					'label'		=> __( 'Shop as client', 'shop-as-client' ),
					'required'	=> true,
					'class'		=> array( 'form-row-wide' ),
					'clear'		=> true,
					'priority'	=> $priority,
					'type'		=> 'select',
					'options'	=> array(
						'yes'	=> __( 'Yes', 'shop-as-client' ),
						'no'	=> __( 'No', 'shop-as-client' ),
					),
					'default'	=> apply_filters( 'shop_as_client_default_shop_as_client', 'yes' ),
				);
				$priority++;
				// Create user if it doesn't exist?
				$fields['billing_shop_as_client_create_user'] = array(
					'label'		=> __( 'Create user (if not found by email)?', 'shop-as-client' ),
					'required'	=> true,
					'class'		=> array( 'form-row-wide' ),
					'clear'		=> true,
					'priority'	=> $priority,
					'type'		=> 'select',
					'options'	=> array(
						'yes'	=> __( 'Yes', 'shop-as-client' ),
						'no'	=> __( 'No (leave as guest)', 'shop-as-client' ),
					),
					'default'	=> apply_filters( 'shop_as_client_default_create_user', 'no' ),
				);
			}
			return $fields;
		}
	
		/* Enqueue scripts - Classic checkout only - Blocks checkout in includes/class-shop-as-client-checkout-blocks.php */
		function shop_as_client_enqueue_scripts() {
			if (
				function_exists( 'is_checkout' )
				&&
				is_checkout()
				&&
				( ! has_block( 'woocommerce/checkout' ) ) // Not on the Blocks checkout
			) {
				wp_enqueue_script( 'shop-as-client', plugins_url( 'js/functions.js', __FILE__ ), array( 'jquery' ), '1.3.0', true );
				wp_localize_script( 'shop-as-client', 'shop_as_client', array(
					'txt_pro' => 
					sprintf(
						'<p><a href="https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/" target="_blank">%s</a></p>',
						__( 'Do you want to load the customer details automatically?<br/>Get the PRO add-on!', 'shop-as-client' )
					)
				) );
			}
		}
	
		/* Force our field defaults - Where is this used? Should we remove this? */
		add_filter( 'default_checkout_billing_shop_as_client', 'shop_as_client_default_checkout_billing_shop_as_client', 10, 2 );
		function shop_as_client_default_checkout_billing_shop_as_client( $value, $input ) {
			return apply_filters( 'shop_as_client_default_shop_as_client', 'yes' );
		}
		add_filter( 'default_checkout_billing_shop_as_client_create_user', 'shop_as_client_default_checkout_billing_shop_as_client_create_user', 10, 2 );
		function shop_as_client_default_checkout_billing_shop_as_client_create_user( $value, $input ) {
			return apply_filters( 'shop_as_client_default_create_user', 'no' );
		}
	
		/* Get order "shop as client" */
		function shop_as_client_get_order_status( $order ) {
			return 'yes' === $order->get_meta( '_billing_shop_as_client' );
		}
	
		/* Return yes to woocommerce_registration_generate_password */
		function shop_as_client_woocommerce_registration_generate_password( $value ) {
			return 'yes';
		}
	
		/**
		 * Set order user - Inspiration: https://gist.github.com/twoelevenjay/80294a635969a54e4693
		 * Classic checkout only - Blocks alternative missing - https://github.com/woocommerce/woocommerce/issues/44530
		**/
		add_filter( 'woocommerce_checkout_customer_id', 'shop_as_client_woocommerce_checkout_customer_id' );
		function shop_as_client_woocommerce_checkout_customer_id( $user_id ) {
			if ( shop_as_client_can_checkout() ) {
				if ( isset( $_POST['billing_shop_as_client'] ) && 'yes' === $_POST['billing_shop_as_client'] ) {
					$user_id = 0;
					 // Check if an exisiting user already uses this email address.
					$user_email = sanitize_email( $_POST['billing_email'] );
					if ( empty( $user_email ) ) $user_email = apply_filters( 'shop_as_client_user_email_if_empty', $user_email, $_POST );
					// Get user by profile email
					if ( $user = get_user_by( 'email', $user_email ) ) {
						// User found
						$user_id = $user->ID;
						// Should we update the user details? - This is by WooCommerce on WC_Checkout process_customer - Working on Blocks too
					} else {
						// Get user by WooCommerce billing email
						if ( ( ! empty( $user_email ) ) && ( $users = get_users( array(
							'meta_key'     => 'billing_email',
							'meta_value'   => $user_email,
							'meta_compare' => '='
						) ) ) ) {
							// User found - We should check for more than one... (There's no real solution for this)
							$user_id = $users[0]->ID;
						} else {
							// Create user or guest?
							if ( isset( $_POST['billing_shop_as_client_create_user'] ) && 'yes' === $_POST['billing_shop_as_client_create_user'] ) {
								$temp_user_id = shop_as_client_create_customer( $user_email, sanitize_text_field( $_POST['billing_first_name'] ), sanitize_text_field( $_POST['billing_last_name'] ) );
								if ( ! is_wp_error( $temp_user_id ) ) {
									$user_id = $temp_user_id;
								} else {
									$message = sprintf(
										__( 'Shop as Client failed to create user: %s' , 'shop-as-client' ),
										$temp_user_id->get_error_message()
									);
									throw new Exception( $message );
								}
							}
						}
					}
				}
			}
			return $user_id;
		}
	
		/* Create the user/customer - Should be used for both classic and blocks checkout */
		function shop_as_client_create_customer( $user_email, $user_first_name, $user_last_name ) {
			// Username
			if ( 'yes' === get_option( 'woocommerce_registration_generate_username' ) ) {
				$username = '';
			} else {
				$username = $user_email;
			}
			// Force password generation by WooCommerce (and sending via email), even if the option is not set
			if ( apply_filters( 'shop_as_client_email_password', true ) ) {
				add_filter( 'option_woocommerce_registration_generate_password', 'shop_as_client_woocommerce_registration_generate_password' );
				$password = '';
			} else {
				$password = wp_generate_password();
			}
			$user_id = wc_create_new_customer( $user_email, $username, $password );
			if ( apply_filters( 'shop_as_client_email_password', true ) ) remove_filter( 'option_woocommerce_registration_generate_password', 'shop_as_client_woocommerce_registration_generate_password' );
			if ( ! is_wp_error( $user_id ) ) {
				wp_update_user(
					array( 'ID' => $user_id,
						'first_name' => $user_first_name,
						'last_name' => $user_last_name,
						'display_name' => trim( $user_first_name.' '.$user_last_name ),
						// 'role' => 'customer',
					)
				);
			} else {
				$message = 'Shop as Client failed to create user: '.$user_id->get_error_message();
				// We should notify the admin user somehow - WooCommerce already does that
			}
			return $user_id;
		}
	
		/**
		 * Prevent logged in user to be updated
		 * Not running on the blocks checkout but it seems not to be necessary as only the target user is being updated and not the logged-in one
		**/
		add_action( 'woocommerce_checkout_process', 'shop_as_client_woocommerce_checkout_process' );
		function shop_as_client_woocommerce_checkout_process() {
			if ( shop_as_client_can_checkout() ) {
				if ( isset( $_POST['billing_shop_as_client'] ) && 'yes' === $_POST['billing_shop_as_client'] ) {
					if ( ! apply_filters( 'shop_as_client_update_customer_data', false ) ) {
						add_filter( 'woocommerce_checkout_update_customer_data' , '__return_false' );
					}
				}
			}
		}
	
		/* Save logged in user id as order handler - Classic checkout only - Blocks alternative missing */
		add_action( 'woocommerce_checkout_update_order_meta', 'shop_as_client_woocommerce_checkout_update_order_meta', 10, 2 );
		function shop_as_client_woocommerce_checkout_update_order_meta( $order_id, $data ) {
			if ( shop_as_client_can_checkout() ) {
				if (
					( isset( $data['billing_shop_as_client'] ) && 'yes' === $data['billing_shop_as_client'] ) // The "correct" way to check for our fields
					||
					( isset( $_POST['billing_shop_as_client'] ) && 'yes' === $_POST['billing_shop_as_client'] ) // Because when using Funnelkit our fields are not present on the $data array
				) {
					$order = wc_get_order( $order_id );
					$order->update_meta_data( '_billing_shop_as_client_handler_user_id', get_current_user_id() );
					$order->update_meta_data( '_billing_shop_as_client_checkout', 'classic' );
					$order->save();
				}
			}
		}
	
		/* Information on the order edit screen */
		add_action( 'woocommerce_admin_order_data_after_order_details', 'shop_as_client_woocommerce_admin_order_data_after_order_details' );
		function shop_as_client_woocommerce_admin_order_data_after_order_details( $order ) {
			if ( shop_as_client_get_order_status( $order ) ) {
				?>
				<p class="form-field form-field-wide">
					<label><?php _e( 'Shop as client', 'shop-as-client' ) ?>:</label>
					<?php _e( 'Yes', 'shop-as-client' ); ?>
				</p>
				<?php
				if ( $user_id = $order->get_meta( '_billing_shop_as_client_handler_user_id' ) ) {
					?>
					<p class="form-field form-field-wide">
						<label><?php _e( 'Order handled by', 'shop-as-client' ) ?>:</label>
						<?php
						if ( $user = get_user_by( 'ID', $user_id ) ) {
							printf(
								'<a href="%s" target="_blank">%s</a>',
								esc_url( add_query_arg( 'user_id', $user_id, admin_url( 'user-edit.php' ) ) ),
								sprintf(
									'%s (%s)',
									$user->display_name,
									$user->nickname
								)
							);
						} else {
							/* translators: $d: user id */
							printf( __( 'User %d', 'shop-as-client' ), $user_id );
						}
						?>
					</p>
					<?php
				}
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG && $checkout = $order->get_meta( '_billing_shop_as_client_checkout' ) ) {
					?>
					<p class="form-field form-field-wide">
						<label><?php _e( 'Checkout', 'shop-as-client' ) ?>:</label>
						<?php echo $checkout; ?>
					</p>
					<?php
				}
				do_action( 'shop_as_client_after_order_details', $order );
			}
		}

		/**
		 * Thank you page warning - https://github.com/woocommerce/woocommerce/pull/38983/
		 * Still not working on the blocks checkout
		**/
		if ( version_compare( WC_VERSION, '7.8.1', '>=' ) ) {
			add_filter( 'do_shortcode_tag', 'shop_as_client_checkout_order_received', 10, 2 );
		}
		function shop_as_client_is_our_checkout_order_received( $tag ) {
			if ( $tag == apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) && is_order_received_page() && shop_as_client_can_checkout() ) {
				global $wp;
				if ( isset( $wp->query_vars['order-received'] ) && intval( $wp->query_vars['order-received'] ) > 0 ) {
					if ( $order = wc_get_order( $wp->query_vars['order-received'] ) ) {
						if ( isset( $_GET['key'] ) && hash_equals( $order->get_order_key(), $_GET['key'] ) ) {
							$order_customer_id = $order->get_customer_id();
							if ( $order_customer_id && get_current_user_id() !== $order_customer_id ) {
								// We're pretty sure there's no access right now, so we need also to make sure it's a shop as client order and the handler is logged in
								if ( $order->get_meta( '_billing_shop_as_client' ) === 'yes' && intval( $order->get_meta( '_billing_shop_as_client_handler_user_id' )  ) === intval( get_current_user_id() ) ) {
									// We dealt with the order
									return $order;
								}
							}
						}
					}
				}
			}
			return false;
		}
		function shop_as_client_checkout_order_received( $output, $tag ) {
			if ( $order = shop_as_client_is_our_checkout_order_received( $tag ) ) {
				ob_start();
				?>
				<div class="woocommerce-error">
					<a href="https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/" class="button wc-forward" target="_blank">
						<?php _e( 'Get the PRO add-on to fix this', 'shop-as-client' ); ?>
					</a>
					<?php _e( '<strong>Shop as client</strong><br/>Since WooCommerce 7.8.1 only the order owner/customer is able to see the "Order received" details.', 'shop-as-client' ); ?>
				</div>
				<?php
				$output = ob_get_clean() . $output;
			}
			return $output;
		}

		/**
		 * Fix PRO updates to 2.3 - https://ptwooplugins.com/shop-as-client-pro-add-on-not-working-after-updating-the-free-version-to-1-9-or-above-the-solution-is-here/
		**/
		add_action( 'plugins_loaded', function() {
			if ( is_admin() && class_exists( 'Shop_As_Client_Pro' ) ) {
				if ( isset( $GLOBALS['Shop_As_Client_Pro'] ) ) {
					if ( version_compare( $GLOBALS['Shop_As_Client_Pro']->version, '2.3', '<' ) ) {
						$GLOBALS['Shop_As_Client_Pro']->update_checker();
					}
				}
			}
		}, 15 );

		/* Blocks */
		add_action(
			'woocommerce_blocks_loaded',
			function () {
				require_once __DIR__ . '/includes/class-shop-as-client-checkout-blocks.php';

				add_action(
					'woocommerce_blocks_checkout_block_registration',
					function ( $integration_registry ) {
						$integration_registry->register( new \ShopAsClient_Checkout_Blocks() );
					}
				);
			}
		);

		/* Blocks - Extend Store endpoint */
		add_action(
			'woocommerce_blocks_loaded',
			function () {
				require_once __DIR__ . '/includes/class-shop-as-client-extend-store-endpoint.php';

				( new ShopAsClient_Extend_Store_Endpoint() )->initialize();
			}
		);
	
		/* Simple Order Approval nag */
		add_action( 'admin_init', function() {
			if (
				( ! class_exists( 'Shop_As_Client_Pro' ) ) // Not for PRO add-on users
				&&
				( ! defined( 'PTWOO_SIMPLE_ORDER_APPROVAL_NAG' ) )
				&&
				( ! class_exists( '\PTWooPlugins\SWOA\SWOA' ) )
				&&
				empty( get_transient( 'ptwoo_simple_order_approval_nag' ) )
				&&
				apply_filters( 'shop_as_client_ptwoo_simple_order_approval_nag', true )
			) {
				define( 'PTWOO_SIMPLE_ORDER_APPROVAL_NAG', true );
				require_once( 'simple_order_approval_nag/simple_order_approval_nag.php' );
			}
		} );
	}
}, 6 );

/* HPOS & Blocks Checkout Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( version_compare( WC_VERSION, '7.1', '>=' ) && class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */