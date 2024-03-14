<?php
/**
 * WooCommerce PayPal Here Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * @var \Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes\Order_Data $meta_box
 * @var array $payment_gateways
 */
?>
<style type="text/css">
	#post-body-content, #titlediv { display:none }
</style>
<div class="panel-wrap woocommerce">
	<input name="post_title" type="hidden" value="<?php echo empty( $post->post_title ) ? esc_attr( 'Order', 'woocommerce-gateway-paypal-here' ) : esc_attr( $post->post_title ); ?>" />
	<input name="post_status" type="hidden" value="<?php echo esc_attr( $post->post_status ); ?>" />
	<div id="order_data" class="panel woocommerce-order-data">
		<h2 class="woocommerce-order-data__heading">
			<?php

			$order_type_object = get_post_type_object( $post->post_type );

			printf(
				/* translators: %1$s: order type %2$s: order number */
				esc_html__( '%1$s #%2$s details', 'woocommerce-gateway-paypal-here' ),
				esc_html( $order_type_object->labels->singular_name ),
				esc_html( $order->get_order_number() )
			);

			?>
		</h2>
		<p class="woocommerce-order-data__meta order_number">
			<?php

			$meta_list = array();

			if ( $payment_method = $order->get_payment_method() ) {

				$payment_method_string = sprintf(
					/* translators: %s: payment method */
					esc_html__( 'Payment via %s', 'woocommerce-gateway-paypal-here' ),
					esc_html( isset( $payment_gateways[ $payment_method ] ) ? $payment_gateways[ $payment_method ]->get_title() : $payment_method )
				);

				if ( $transaction_id = $order->get_transaction_id() ) {

					if ( isset( $payment_gateways[ $payment_method ] ) && ( $url = $payment_gateways[ $payment_method ]->get_transaction_url( $order ) ) ) {
						$payment_method_string .= ' (<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $transaction_id ) . '</a>)';
					} else {
						$payment_method_string .= ' (' . esc_html( $transaction_id ) . ')';
					}
				}

				$meta_list[] = $payment_method_string;
			}

			if ( $order->get_date_paid() ) {

				$meta_list[] = sprintf(
					/* translators: 1: date 2: time */
					esc_html__( 'Paid on %1$s @ %2$s', 'woocommerce-gateway-paypal-here' ),
					wc_format_datetime( $order->get_date_paid() ),
					wc_format_datetime( $order->get_date_paid(), get_option( 'time_format' ) )
				);
			}

			if ( $ip_address = $order->get_customer_ip_address() ) {

				$meta_list[] = sprintf(
					/* translators: %s: IP address */
					esc_html__( 'Customer IP: %s', 'woocommerce-gateway-paypal-here' ),
					'<span class="woocommerce-Order-customerIP">' . esc_html( $ip_address ) . '</span>'
				);
			}

			echo wp_kses_post( implode( '. ', $meta_list ) );

			?>
		</p>
		<div class="order_data_column_container">
			<div class="order_data_column">

				<p class="form-field form-field-wide wc-customer-user">
					<!--email_off--> <!-- Disable CloudFlare email obfuscation -->
					<label for="customer_user">
						<?php
						_e( 'Customer:', 'woocommerce-gateway-paypal-here' );
						if ( $order->get_user_id( 'edit' ) ) {
							$args = array(
								'post_status'    => 'all',
								'post_type'      => 'shop_order',
								'_customer_user' => $order->get_user_id( 'edit' ),
							);
							printf(
								'<a href="%s">%s</a>',
								esc_url( add_query_arg( $args, admin_url( 'edit.php' ) ) ),
								' ' . __( 'View other orders &rarr;', 'woocommerce-gateway-paypal-here' )
							);
							printf(
								'<a href="%s">%s</a>',
								esc_url( add_query_arg( 'user_id', $order->get_user_id( 'edit' ), admin_url( 'user-edit.php' ) ) ),
								' ' . __( 'Profile &rarr;', 'woocommerce-gateway-paypal-here' )
							);
						}
						?>
					</label>
					<?php
					$user_string = '';
					$user_id     = '';
					if ( $order->get_user_id() ) {
						$user_id     = absint( $order->get_user_id() );
						$user        = get_user_by( 'id', $user_id );
						$user_string = sprintf(
							/* translators: 1: user display name 2: user ID 3: user email */
							esc_html__( '%1$s (#%2$s &ndash; %3$s)', 'woocommerce-gateway-paypal-here' ),
							$user->display_name,
							absint( $user->ID ),
							$user->user_email
						);
					}
					?>
					<select class="wc-customer-search" id="customer-selection" name="customer_user" data-placeholder="<?php esc_attr_e( 'Guest', 'woocommerce-gateway-paypal-here' ); ?>" data-allow_clear="true">
						<option value="<?php echo esc_attr( $user_id ); ?>" selected="selected"><?php echo htmlspecialchars( $user_string ); ?></option>
					</select>
					<!--/email_off-->
				</p>
				<input type="hidden" name="order_status" value="<?php echo $order->get_status( 'edit' ); ?>" />
			</div>

			<div class="order_data_column address-column billing">

				<a href="#" class="load_customer_billing hidden"></a>
				<a href="#" class="edit-address-button heading-button billing button"><?php esc_html_e( 'Edit', 'woocommerce-gateway-paypal-here' ); ?></a>

				<h3>
					<?php esc_html_e( 'Billing details', 'woocommerce-gateway-paypal-here' ); ?>
				</h3>

				<div class="address">
					<?php $meta_box->output_placed_order_billing_data(); ?>
				</div>

				<div class="edit_address_above">
					<?php $meta_box->output_edit_order_billing_fields( 'above' ); ?>
				</div>

				<a href="#" class="edit-address-button more-details-button billing button" data-address-type="billing"><?php esc_html_e( 'Add more details', 'woocommerce-gateway-paypal-here' ); ?></a>

				<div class="edit_address">

					<?php $meta_box->output_edit_order_billing_fields_below(); ?>

					<input type="hidden" name="_payment_method" id="_payment_method" value="<?php esc_attr_e( \Automattic\WooCommerce\PayPal_Here\Plugin::GATEWAY_ID ); ?>"/>

				</div>
			</div>

			<div class="order_data_column address-column shipping">

				<a href="#" class="edit-address-button heading-button shipping button"><?php esc_html_e( 'Edit', 'woocommerce-gateway-paypal-here' ); ?></a>

				<h3>
					<?php esc_html_e( 'Shipping details', 'woocommerce-gateway-paypal-here' ); ?>
					<span>
						<a href="#" class="billing-same-as-shipping show-on-edit" style="display:none;"><?php esc_html_e( 'Copy billing address', 'woocommerce-gateway-paypal-here' ); ?></a>
					</span>
				</h3>

				<div class="address">
					<?php $meta_box->output_placed_order_shipping_data(); ?>
				</div>

				<div class="edit_address">
					<?php $meta_box->output_edit_order_shipping_fields(); ?>
				</div>

			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
