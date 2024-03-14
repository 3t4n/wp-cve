<?php
/**
 * Scheduled Order Payment Method Edit Form
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-payment-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_payment_form', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_payment_form_skin', array(
'form'                  => '',
'container'             => '',
'payment_container'     => '',
'payment_action_label'  => '',
'payment_action_btn'    => '',
'payment_action_select'  => '',
'payment_action_btn_container'  => '',
));

?>

  <?php
  /**
  * @hooked autoship_edit_scheduled_order_payment_form_display_action - 10
  */
  do_action( 'autoship_before_scheduled_order_edit_payment_form', $autoship_order, $customer_id, $autoship_customer_id ); ?>

  <form class="<?php echo $skin['form'];?> <?php echo apply_filters( 'autoship_scheduled_order_edit_payment_form_classes', 'autoship-scheduled-order-edit-form autoship-edit-scheduled-order-payment-form', $autoship_order ); ?>" method="post" <?php do_action( 'autoship_edit_scheduled_order_payment_form_tag' ); ?> >

    <div class="autoship-order-details-summary-form <?php echo $skin['container'];?>">

      <?php do_action( 'autoship_before_update_scheduled_order_payment', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="payment <?php echo $skin['payment_container'];?>">

        <label class="<?php echo $skin['payment_action_label'];?>" for="autoship-payment-select"><?php echo apply_filters( 'autoship_scheduled_order_edit_payment_label', __( 'Payment Method', 'autoship') ); ?></label>

        <select name="autoship_order_payment_method" class="autoship-payment-select <?php echo $skin['payment_action_select'];?>" id="autoship_order_payment_method">

          <?php

          // Get the current Payment Method
          $payment_methods = autoship_get_available_scheduled_order_payment_methods( $autoship_order['customerId'] );

          foreach ( $payment_methods as $payment_method ):?>

            <option value="<?php echo $payment_method->id;?>" <?php selected( $payment_method->id, $autoship_order['paymentMethodId'] ); ?>><?php

            echo esc_html( $payment_method->description );

            ?></option>

          <?php endforeach; ?>

        </select>

      </div>

      <?php do_action( 'autoship_after_scheduled_order_edit_payment_form_input', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="autoship-update-action autoship-update-payment-action <?php echo $skin['payment_action_btn_container'];?>">

        <?php do_action( 'autoship_before_scheduled_order_edit_payment_form_action', $autoship_order, $customer_id, $autoship_customer_id ); ?>

        <input type="hidden" name="autoship_scheduled_order_id" value="<?php echo $autoship_order['id']; ?>" />

      	<?php

        // Get Nonce & Push Autoship order to Session
        $nonce = wp_create_nonce( 'autoship-update-scheduled-order-payment' );
        autoship_load_scheduled_order_into_session( $nonce, $autoship_order );?>

        <input type="hidden" id="autoship-update-scheduled-order-payment-nonce" name="autoship-update-scheduled-order-payment-nonce" value="<?php echo $nonce; ?>">

        <?php wp_referer_field(); ?>

        <button type="submit" class="button autoship-action-btn <?php echo $skin['payment_action_btn'];?>" name="autoship_update_order_payment_method" value="<?php esc_attr_e( 'Update Payment Method', 'autoship' ); ?>"><?php echo apply_filters( 'autoship_scheduled_order_edit_payment_form_action_label', __('Update Payment Method', 'autoship' )); ?></button>

        <?php do_action( 'autoship_update_scheduled_order_payment_form_hidden_fields', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      </div>

      <?php do_action( 'autoship_after_scheduled_order_edit_payment_form_action', $autoship_order, $customer_id, $autoship_customer_id ); ?>

    </div>

  </form>
