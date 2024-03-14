<?php
/**
 * Schedule Order Edit - No Items content
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-no-items-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_no_items_template', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_items_not_items_skin', array(
'table_row'             => 'woocommerce-cart-form__cart-item',
'table_cell'            => 'autoship-update-action autoship-update-order-action actions',
'content'               => '',
'notice'                => '',
'subnotice'             => '',
'action_link'           => 'autoship-action-link',
'action_span'           => '',
));


$actions       = autoship_get_account_scheduled_orders_actions( $autoship_order['id'], $autoship_order['status'], 'empty-order', $customer_id );
$actions       = apply_filters( 'autoship_no_order_items_form_actions', $actions, $autoship_order, $customer_id, $autoship_customer_id );
$action_string = apply_filters( 'autoship_no_order_items_form_call_to_action', sprintf( ! empty( $actions ) ? __('Add a Product to your %s or choose an option below:', 'autoship' ) : __('Add a Product to your %s using the Add a Product drop down.', 'autoship' ), autoship_translate_text( 'Scheduled Order' ) ), $actions, $autoship_order, $customer_id, $autoship_customer_id );

?>

      <tr class="<?php echo $skin['table_row'];?>">
        <td colspan="6" class="<?php echo $skin['table_cell'];?> no-items">

          <div class="no-items-notice <?php echo $skin['content'];?>">

            <h3 class="<?php echo $skin['notice'];?>"><?php echo apply_filters( 'autoship_order_notice_no_items', __( 'No items currently scheduled.', 'autoship' ), $autoship_order, $customer_id, $autoship_customer_id ); ?></h3>

            <p class="<?php echo $skin['subnotice'];?>"><?php echo apply_filters( 'autoship_order_details_no_items', $action_string, $autoship_order, $customer_id, $autoship_customer_id ); ?></p>

            <?php
            if ( ! empty( $actions ) ) {

            foreach ( $actions as $key => $action ) { ?>

            <a title="<?php echo esc_html( $action['title'] ); ?>" href="<?php echo esc_url( $action['url'] );?>" class="<?php echo $skin['action_link'];?> <?php echo sanitize_html_class( strtolower( $key ) ); ?>" data-autoship-order="<?php echo $autoship_order['id']; ?>" data-autoship-action="<?php echo $key; ?>" data-autoship-view="order">
              <span class="<?php echo $skin['action_span'];?>"><?php esc_html_e( $action['name'] ); ?></span>
            </a>

            <?php }
            } ?>

          </div>

        </td>
      </tr>
