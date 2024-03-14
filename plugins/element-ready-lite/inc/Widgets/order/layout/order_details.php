<?php 
  /*
  * render order details
  */
  if ( ! defined( 'ABSPATH' ) ) exit;

  $name     = '';
  $quantity = '';
  $subtotal = '';
  $total    = '';
 
  foreach ( $order->get_items() as $item_id => $item ) {
    $name     = $item->get_name();
    $quantity = $item->get_quantity();
    $subtotal = $item->get_subtotal();
    $total    = $item->get_total();
    break;
  }

?>

<div class="er-thankyou-container"> 

     <div class="er-order-details">
          <div class="er-product-label label"> <?php echo esc_html__('Product','element-ready-lite'); ?></div> 
          <div class="er-product-name er-value"><?php echo esc_html($name); ?> </div> 
     </div>

     <div class="er-order-details">
      
      <div class="er-product-total-label label"> <?php echo esc_html__('Amount','element-ready-lite'); ?> </div> 
      <div class="er-product-total er-value"> <?php echo esc_html($order->get_total()); ?> </div> 

    </div> 

    <div class="er-order-details">
      
      <div class="er-product-invoice-label label"> <?php echo esc_html__('Invoice Number','element-ready-lite'); ?> </div> 
      <div class="er-product-invoice er-value"> <?php echo esc_html($order_id); ?> </div> 

    </div>
    <div class="er-order-details">
      
      <div class="er-product-email-label label"> <?php echo esc_html__('Email','element-ready-lite'); ?> </div> 
      <div class="er-product-email er-value"> <?php echo esc_html($order->get_billing_email()); ?> </div> 

    </div>

 <div>
   
