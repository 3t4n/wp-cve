<?php
/**
 * Scheduled Order Address Edit Form
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-address-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_address_form', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_address_form_skin', array(
'form'                      => '',
'container'                 => '',
'title'                     => '',
'address_container'         => '',
'action_btn_container'      => '',
'action_btn'                => '',
));


?>

  <?php
  /**
  * @hooked autoship_edit_scheduled_order_schedule_form_display_action
  */
  do_action( 'autoship_before_update_scheduled_order_address_form', $autoship_order, $customer_id, $autoship_customer_id ); ?>

  <form class="<?php echo $skin['form'];?> <?php echo apply_filters( 'autoship_edit_scheduled_order_address_form_classes', 'autoship-scheduled-order-edit-form autoship-edit-scheduled-order-shipping-address-form', $autoship_order, $customer_id, $autoship_customer_id ); ?>"  method="post" <?php do_action( 'autoship_edit_scheduled_order_address_form_tag' ); ?> >

    <div class="autoship-order-details-summary-form <?php echo $skin['container'];?>">

			<h2 class="woocommerce-column__title <?php echo $skin['title']; ?>"><?php echo apply_filters( 'autoship_scheduled_order_address_form_shipping_label', __( 'Edit Shipping Address', 'autoship' ) ); ?></h2>

      <?php do_action( 'autoship_before_edit_scheduled_order_schedule_form', $autoship_order, $customer_id, $autoship_customer_id ); ?>

			<div class="woocommerce-address-fields__field-wrapper <?php echo $skin['address_container'];?>">

        <?php
				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, $field['value'] );
				} ?>

      </div>

      <?php do_action( 'autoship_after_update_scheduled_order_schedule', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="autoship-update-action autoship-update-schedule-action <?php echo $skin['action_btn_container'];?>">

        <?php do_action( 'autoship_update_scheduled_order_schedule_actions', $autoship_order, $customer_id, $autoship_customer_id ); ?>

        <button type="submit" class="button autoship-action-btn <?php echo $skin['action_btn'];?>" name="autoship_update_order_shipping_address" value="<?php echo apply_filters( 'autoship_scheduled_order_edit_shipping_address_form_action_label', __('Update Schedule', 'autoship' )); ?>"><?php echo apply_filters( 'autoship_scheduled_order_edit_shipping_address_form_action_label', __('Update Shipping Address', 'autoship' )); ?></button>

        <input type="hidden" name="autoship_scheduled_order_id" value="<?php echo $autoship_order['id']; ?>" />

        <?php do_action( 'autoship_update_scheduled_order_shipping_address_form_hidden_fields', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      	<?php

        // Get Nonce & Push Autoship order to Session
        $nonce = wp_create_nonce( 'autoship-update-scheduled-order-shipping-address' );
        autoship_load_scheduled_order_into_session( $nonce, $autoship_order );?>

        <input type="hidden" id="autoship-update-scheduled-order-shipping-address-nonce" name="autoship-update-scheduled-order-shipping-address-nonce" value="<?php echo $nonce; ?>">

        <?php wp_referer_field(); ?>

      </div>

    </div>

  </form>
