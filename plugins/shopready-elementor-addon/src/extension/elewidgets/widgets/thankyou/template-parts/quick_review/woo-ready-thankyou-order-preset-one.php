<?php 
  /*
  * render order details
  */
  if ( ! defined( 'ABSPATH' ) ) exit;

  $name     = '';
  $quantity = '';
  $subtotal = '';
  $total    = '';
  do_action( 'woocommerce_before_thankyou', $order->get_id() );
  foreach ( $order->get_items() as $item_id => $item ) {
    $name     = $item->get_name();
    $quantity = $item->get_quantity();
    $subtotal = $item->get_subtotal();
    $total    = $item->get_total();
    break;
  }

?>

<div class="wr-thankyou-container">

    <div class="wr-order-details wrproduct">
        <div class="wr-product-label label"> <?php echo esc_html($settings['product_label']); ?></div>
        <div class="wr-product-name wr-value"><?php echo esc_html($name); ?> </div>
    </div>

    <?php if($settings['amount'] == 'yes'): ?>
    <div class="wr-order-details wramount">

        <div class="wr-product-total-label label"> <?php echo esc_html($settings['amount_label']); ?> </div>
        <div class="wr-product-total wr-value"> <?php echo esc_html($order->get_total()); ?> </div>

    </div>
    <?php endif; ?>

    <?php if($settings['invoice'] == 'yes'): ?>
    <div class="wr-order-details wrinvoice">

        <div class="wr-product-invoice-label label"> <?php echo esc_html($settings['invoice_label']); ?> </div>
        <div class="wr-product-invoice wr-value"> <?php echo esc_html($order_id); ?> </div>

    </div>
    <?php endif; ?>

    <?php if($settings['email'] == 'yes'): ?>
    <div class="wr-order-details wremail">

        <div class="wr-product-email-label label"> <?php echo esc_html($settings['email_label']); ?> </div>
        <div class="wr-product-email wr-value"> <?php echo esc_html($order->get_billing_email()); ?> </div>

    </div>
    <?php endif; ?>

</div>
<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>