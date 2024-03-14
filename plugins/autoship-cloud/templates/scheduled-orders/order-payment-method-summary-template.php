<?php
/**
 * Scheduled Order Payment Method Summary Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-payment-method-summary-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_payment_method_summary', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_payment_method_summary_skin', array(
'container'             => '',
'content'               => '',
'title'                 => '',
'notice'                => '',
));

$notice = isset($autoship_order['paymentMethod']) && !empty($autoship_order['paymentMethod']) ? sprintf( '<mark class="order-date">%1$s</mark>', $autoship_order['paymentMethod']['description'] ) : apply_filters( 'autoship_no_payment_method_notice', "<strong><em>No Payment Method Assigned</em></strong>", $autoship_order );

$notice = apply_filters( 'autoship_scheduled_order_payment_method_summary_notice', $notice, $autoship_order, $customer_id, $autoship_customer_id );

?>

<div class="<?php echo $skin['container'];?> <?php echo apply_filters( 'autoship_scheduled_order_view_payment_template_classes', 'autoship-scheduled-order-payment-method-summary', $autoship_order ); ?>">

  <?php do_action( 'autoship_before_autoship_scheduled_order_payment_method_summary_content', $autoship_order, $customer_id, $autoship_customer_id ); ?>

  <div class="payment-method <?php echo $skin['content'];?>">

    <h3 class="<?php echo $skin['title'];?>"><?php echo apply_filters( 'autoship_scheduled_order_payment_method_summary_title', __( 'Payment Method: ', 'autoship' ) ); ?></h3>
    <p class="notice payment-method-name <?php echo $skin['notice'];?>"><?php echo $notice; ?></p>

  </div>

  <?php do_action( 'autoship_after_autoship_scheduled_order_payment_method_summary_content',  $autoship_order, $customer_id, $autoship_customer_id  ); ?>

</div>

<?php do_action( 'autoship_after_autoship_scheduled_order_payment_method_summary',  $autoship_order, $customer_id, $autoship_customer_id  ); ?>
