<?php
/**
 * Schedule Order Edit Update Items Action
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-items-action-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_items_action', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_items_form_actions_skin', array(
'table_row'             => 'woocommerce-cart-form__cart-item',
'table_cell'            => '',
'items_action_btn'      => '',
));

?>

      <tr class="scheduled-order-actions <?php echo $skin['table_row'];?>">
        <td colspan="5" class="autoship-update-action autoship-update-order-action actions <?php echo $skin['table_cell'];?>">

          <?php do_action( 'autoship_update_scheduled_order_form_actions', $autoship_order, $customer_id, $autoship_customer_id ); ?>

          <button type="submit" class="button autoship-action-btn <?php echo $skin['items_action_btn'];?>" name="autoship_update_schedule_items" value="<?php echo apply_filters( 'autoship_scheduled_order_items_form_update_items_button_text', __('Update Items', 'autoship' ) ); ?>"><?php echo apply_filters( 'autoship_scheduled_order_items_form_update_items_button_text', __('Update Items', 'autoship' ) ); ?></button>

          <input type="hidden" name="autoship_scheduled_order_id" value="<?php echo $autoship_order['id']; ?>" />
          <input type="hidden" name="autoship_scheduled_order_frequency" value="<?php echo $autoship_order['frequency']; ?>" />
          <input type="hidden" name="autoship_scheduled_order_frequency_type" value="<?php echo $autoship_order['frequencyType']; ?>" />

          <?php do_action( 'autoship_update_scheduled_order_form_actions_hidden_fields', $autoship_order, $customer_id, $autoship_customer_id ); ?>

          <?php

          // Get Nonce & Push Autoship order to Session
          $nonce = wp_create_nonce( 'autoship-update-scheduled-order-items' );
          autoship_load_scheduled_order_into_session( $nonce, $autoship_order );?>

          <input type="hidden" id="autoship-update-scheduled-order-items-nonce" name="autoship-update-scheduled-order-items-nonce" value="<?php echo $nonce; ?>">

          <?php wp_referer_field(); ?>

        </td>
      </tr><!-- .scheduled-order-actions -->
