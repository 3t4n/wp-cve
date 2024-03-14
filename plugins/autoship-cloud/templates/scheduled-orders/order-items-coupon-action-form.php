<?php
/**
 * Schedule Order Edit Update Coupon Action
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-items-coupon-action-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_items_coupon_action', autoship_allow_coupons() , $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_items_coupon_form_actions_skin', array(
'table_row'             => 'woocommerce-cart-form__cart-item',
'table_cell'            => '',
'container'             => '',
'action_label'          => '',
'action_btn'            => '',
'action_input'          => '',
));

?>

      <tr class="scheduled-order-coupon-actions <?php echo $skin['table_row'];?>">
        <td colspan="5" class="autoship-update-action autoship-update-order-coupon-action actions <?php echo $skin['table_cell'];?>">

            <div class="coupon <?php echo $skin['container'];?>">

              <label class="<?php echo $skin['action_label'];?>" for="autoship_coupon_code"><?php echo apply_filters( 'autoship_scheduled_order_items_coupon_form_label' , __( 'Coupon:', 'autoship' ) ); ?></label>
              <input type="text" name="autoship_coupon_code" class="input-text <?php echo $skin['action_input'];?>" id="autoship_coupon_code" value="" placeholder="<?php echo apply_filters( 'autoship_scheduled_order_items_coupon_form_placeholder' , __( 'Coupon Code', 'autoship' ) ); ?>" />

              <button type="submit" class="button <?php echo $skin['action_btn'];?>" name="autoship_update_order_coupon" value="<?php echo apply_filters('autoship_scheduled_order_items_form_coupon_button_text', __( 'Apply coupon', 'autoship' )); ?>"><?php echo apply_filters('autoship_scheduled_order_items_form_coupon_button_text', __( 'Apply coupon', 'autoship' )); ?></button>

						</div>

        </td>
      </tr><!-- .scheduled-order-coupon-actions -->
