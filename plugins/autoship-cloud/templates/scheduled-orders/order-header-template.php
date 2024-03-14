<?php
/**
 * Scheduled Order Header Template
 *
 * Displays the Header Information for the Scheduled Order Detail page.
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-header-template.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'autoship_include_scheduled_order_header', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_schedule_order_header_skin', array(
'header'                => '',
'notice'                => 'woocommerce-error',
));

$error = isset( $autoship_order['scheduledOrderFailureReason'] ) ? $autoship_order['scheduledOrderFailureReason'] : '';
$notice  = apply_filters(
'autoship_orders_notice_message',
isset( $autoship_order['scheduledOrderFailureReason'] ) ? $autoship_order['scheduledOrderFailureReason'] : '',
$autoship_order );

do_action( 'autoship_before_schedule_order_header', $autoship_order, $customer_id, $autoship_customer_id );?>

<h2 class="<?php echo $skin['header'];?>"><?php apply_filters( 'autoship_schedule_order_header_title', printf( __( '%s #%2$s', 'autoship' ), autoship_translate_text( 'Scheduled Order' ),
'<mark class="order-number">' . $autoship_order['id'] . '</mark>') ); ?></h2>

<?php if ( !empty( $error ) ): ?>

<p class="autoship_order_error <?php echo $skin['notice'];?>" role="alert"><?php echo apply_filters( 'autoship_schedule_order_header_error', $error, $autoship_order['status'], $autoship_order ); ?></p>

<?php
endif;

/**
 * @hooked autoship_scheduled_order_header_locked_notice_display - 10
 */
do_action( 'autoship_after_schedule_order_header', $autoship_order, $customer_id, $autoship_customer_id );
