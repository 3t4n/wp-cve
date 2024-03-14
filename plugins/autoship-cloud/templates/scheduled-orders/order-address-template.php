<?php
/**
 * Scheduled Order Address Summary Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-address-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_address_summary', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_address_summary_skin', array(
'container'             => 'woocommerce-customer-details',
'content'               => 'woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses',
'column_billing'        => 'woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1',
'column_shipping'       => 'woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2',
'title'                 => 'woocommerce-column__title',
'subnote'               => '',
'table_head_cell'       => '',
'table_body'            => '',
'table_row'             => 'woocommerce-cart-form__cart-item',
'table_cell'            => '',
'table_row_placeholder' => 'scheduled-order-add-ons-placeholder',
'table_row_addon'       => '',
'table_cell_addon'      => '',
'table_row_coupons'     => '',
'table_cell_coupons'    => '',
'table_row_addon_msg'   => 'scheduled-order-add-ons-msg-container',
'table_cell_addon_msg'  => ''
));

?>

<section class="<?php echo $skin['container']; ?> <?php echo apply_filters('autoship_scheduled_order_address_template_classes', 'autoship-address-summary', $autoship_order );?>">

  <?php do_action( 'autoship_before_schedule_order_address_summary', $autoship_order, $customer_id, $autoship_customer_id ); ?>

  <?php

  $billing_data = autoship_order_address_values( $autoship_order, 'billing' );
  $billing = autoship_formatted_scheduled_order_addresses ( $billing_data, 'billing' );

  $shipping_data = autoship_order_address_values( $autoship_order, 'shipping' );
  $shipping = autoship_formatted_scheduled_order_addresses ( $shipping_data, 'shipping' );

  ?>

	<section class="autoship-address-summary-content <?php echo $skin['content']; ?>">

    <div class="autoship-address-summary-column <?php echo $skin['column_billing']; ?>">

    	<h2 class="autoship-address-summary-title <?php echo $skin['title']; ?>"><?php echo apply_filters( 'autoship_scheduled_order_address_billing_label', __( 'Billing Address', 'autoship' ) ); ?></h2>

    	<address>

    		<?php echo empty( $billing ) ? apply_filters( 'autoship_no_address_notice', "<strong><em>No Billing Address Assigned</em></strong>", 'billing', $autoship_order ) : wp_kses_post( $billing ); ?>

        <?php if ( isset( $billing_data['phone'] ) && !empty( $billing_data['phone'] ) ) : ?>
          <p class="autoship-address-phone"><?php echo esc_html( $billing_data['phone'] ); ?></p>
        <?php endif; ?>

    	</address>

      <p class="autoship-address-summary-subnote <?php echo $skin['subnote']; ?>"><?php echo apply_filters( 'autoship_scheduled_order_address_billing_subnote', sprintf( __( 'Update your address in <a href="%s">My Account</a>.', 'autoship' ),  wc_get_account_endpoint_url( 'edit-address' ) ), $autoship_order, $context  ); ?></p>

		</div>

		<div class="autoship-address-summary-title <?php echo $skin['column_shipping']; ?>">
			<h2 class="woocommerce-column__title <?php echo $skin['title']; ?>"><?php echo apply_filters( 'autoship_scheduled_order_address_shipping_label', __( 'Shipping Address', 'autoship' ) ); ?></h2>

    	<address>

    		<?php echo empty( $shipping ) ? apply_filters( 'autoship_no_address_notice', __( "<strong><em>No Shipping Address Assigned</em></strong>", 'autoship' ), 'shipping', $autoship_order ) : wp_kses_post( $shipping ); ?>

        <?php if ( isset( $billing_data['phone'] ) && !empty( $billing_data['phone'] ) ) : ?>
          <p class="autoship-address-phone"><?php echo esc_html( $billing_data['phone'] ); ?></p>
        <?php endif; ?>

    	</address>

      <p class="autoship-address-summary-subnote <?php echo $skin['subnote']; ?>"><?php echo apply_filters( 'autoship_scheduled_order_address_shipping_subnote', sprintf( __( 'Update your address in <a href="%s">My Account</a>.', 'autoship' ),  wc_get_account_endpoint_url( 'edit-address' ) ), $autoship_order, $context ); ?></p>


		</div>

	</section>

	<?php do_action( 'autoship_after_schedule_order_address_summary', $autoship_order, $customer_id, $autoship_customer_id ); ?>

</section>
