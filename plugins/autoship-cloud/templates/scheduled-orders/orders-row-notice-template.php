<?php
/**
 * Scheduled Orders Notice Row Template
 *
 * Displays the Error & Notice Information row for each order in the table on the Scheduled Orders Screen.
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/orders-row-notice-template.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'autoship_include_scheduled_orders_row_notice', true, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the template classes.
*/
$skin = apply_filters( 'autoship_orders_form_row_notice_skin', array(
'table_row_notice'      => 'woocommerce-orders-table__row',
'table_cell_notice'     => 'autoship-order-notice',
'table_cell_notice_msg' => '',
));

$notice  = apply_filters(
'autoship_orders_notice_message',
isset( $autoship_order->scheduledOrderFailureReason ) ? substr($autoship_order->scheduledOrderFailureReason, 0, 200 )  : '',
$autoship_order );

$columns = autoship_get_my_account_scheduled_orders_columns();

if ( empty( $notice ) )
return;

// If the Notice was truncated indicate that
$notice .= strlen( $notice ) != strlen( $autoship_order->scheduledOrderFailureReason ) ? '...' : '';

?>

	<tr class="autoship-notice-row <?php echo $skin['table_row_notice'];?>" id="row-<?php echo $autoship_order->id;?>-notices">

    <td class="<?php echo $skin['table_cell_notice'];?>" data-title="<?php echo __( 'Notice', 'autoship' ); ?>" colspan="<?php echo count( $columns ) - 1;?>"><p class="<?php echo $skin['table_cell_notice_msg'];?>" role="alert"><?php echo $notice; ?></p></td>

	</tr>
