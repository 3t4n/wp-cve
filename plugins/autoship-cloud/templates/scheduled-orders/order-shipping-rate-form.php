<?php
/**
 * Scheduled Order Preffered Shipping Rate Edit Form
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-shipping-rate-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_preferred_shipping_rate_form', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_preferred_shipping_rate_form_skin', array(
'form'                  => '',
'container'             => '',
'shipping_rate_container'     => '',
'shipping_rate_action_label'  => '',
'shipping_rate_action_btn'    => '',
'shipping_rate_action_select'  => '',
'shipping_rate_action_btn_container'  => '',
));

?>

  <?php

  /**
  * @hooked autoship_edit_scheduled_order_shipping_rate_form_wrapper_open - 10
  */
  do_action( 'autoship_before_scheduled_order_edit_preferred_shipping_rate_form', $autoship_order, $customer_id, $autoship_customer_id ); ?>

  <form class="<?php echo $skin['form'];?> <?php echo apply_filters( 'autoship_scheduled_order_edit_preferred_shipping_rate_form_classes', 'autoship-scheduled-order-edit-form autoship-edit-scheduled-order-preferred-shipping-rate-form', $autoship_order ); ?>" method="post" <?php do_action( 'autoship_edit_scheduled_order_preferred_shipping_rate_form_tag' ); ?> >

    <div class="autoship-order-details-general-form <?php echo $skin['container'];?>">

      <?php do_action( 'autoship_before_update_scheduled_order_preferred_shipping_rate', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="shipping-rates <?php echo $skin['shipping_rate_container'];?>">

        <label class="<?php echo $skin['shipping_rate_action_label'];?>" for="autoship_order_preferred_shipping_rate"><?php echo apply_filters( 'autoship_scheduled_order_edit_preferred_shipping_rate_label', __( 'Preferred Shipping Rate', 'autoship') ); ?></label>

        <select name="autoship_order_preferred_shipping_rate" class="autoship-shipping-rate-select <?php echo $skin['shipping_rate_action_select'];?>" id="autoship_order_preferred_shipping_rate">

          <?php

          // Get the current Payment Method
          $shipping_rates = autoship_get_available_scheduled_order_shipping_rates( $autoship_order );
          $has_preferred = autoship_is_valid_shipping_rate( $shipping_rates['preferred_rate'], $autoship_order );

          foreach ( $shipping_rates['available_rates'] as $shipping_rate ):

            $selected = $has_preferred ?
            $shipping_rates['preferred_rate'] == $shipping_rate['value'] : $shipping_rate['default'];

            ?>

            <option value="<?php echo $shipping_rate['value'];?>" <?php echo $selected ? 'selected="selected"' : ''; ?>><?php

            echo __( $shipping_rate['label_html'], 'autoship' );

            ?></option>

          <?php endforeach; ?>

        </select>
                
      </div>

      <?php do_action( 'autoship_after_scheduled_order_edit_preferred_shipping_rate_form_input', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="autoship-update-action autoship-update-preferred-shipping-rate-action <?php echo $skin['shipping_rate_action_btn_container'];?>">

        <?php do_action( 'autoship_before_scheduled_order_edit_preferred_shipping_rate_form_action', $autoship_order, $customer_id, $autoship_customer_id ); ?>

        <input type="hidden" name="autoship_scheduled_order_id" value="<?php echo $autoship_order['id']; ?>" />

      	<?php

        // Get Nonce & Push Autoship order to Session
        $nonce = wp_create_nonce( 'autoship-update-scheduled-order-preferred-shipping-rate' );
        autoship_load_scheduled_order_into_session( $nonce, $autoship_order );?>

        <input type="hidden" id="autoship-update-scheduled-order-preferred-shipping-rate-nonce" name="autoship-update-scheduled-order-preferred-shipping-rate-nonce" value="<?php echo $nonce; ?>">

        <?php wp_referer_field(); ?>

        <button type="submit" class="button autoship-action-btn <?php echo $skin['shipping_rate_action_btn'];?>" name="autoship_update_order_preferred_shipping_rate" value="<?php esc_attr_e( 'Update Shipping Method', 'autoship' ); ?>"><?php echo apply_filters( 'autoship_scheduled_order_edit_preferred_shipping_rate_form_action_label', __('Update Shipping Method', 'autoship' )); ?></button>

        <?php do_action( 'autoship_update_scheduled_order_preferred_shipping_rate_form_hidden_fields', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      </div>

      <?php do_action( 'autoship_after_update_scheduled_order_preferred_shipping_rate_form_action', $autoship_order, $customer_id, $autoship_customer_id ); ?>

    </div>

  </form>
