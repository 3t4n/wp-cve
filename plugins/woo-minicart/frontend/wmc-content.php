  <?php

  // If this file is called directly, abort.
  if ( ! defined( 'WPINC' ) ) {
    die;
  }

  if ( is_admin() ) return false;

  if( empty( WC()->cart ) ){
    $items = array();
  }
  else{
    $items = WC()->cart->get_cart();
    $cart_count = WC()->cart->get_cart_contents_count();
        $item_text  = ( $cart_count == 1 ) ? __( 'item', 'woo-minicart' ) : __( 'items', 'woo-minicart' );
  }
  ?>
  <?php if( $items ) : ?>
    <div class="wmc-content">
      <h3><?php printf( __( 'You have %d %s in cart', 'woo-minicart'), $cart_count, $item_text ); ?></h3>      
      <ul class="wmc-products">
       <?php foreach($items as $item => $values) {  
         $_product =  wc_get_product( $values['data']->get_id() ); 
         ?>
         <li class="woocommerce-mini-cart-item mini_cart_item">
           <div class="wmc-remove">
            <?php
                // @codingStandardsIgnoreLine
            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
             '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
             esc_url( wc_get_cart_remove_url( $item ) ),
             __( 'Remove this item', 'woo-minicart' ),
             esc_attr( $_product->get_id() ),
             esc_attr( $item ),
             esc_attr( $_product->get_sku() )
           ), $item );
           ?>
         </div>
         <div class="wmc-image">
          <?php
          $getProductDetail = wc_get_product( $values['product_id'] );
          ?>
          <a href="<?php echo esc_url( $_product->get_permalink() ); ?>">
            <?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $values, $item ); ?>
          </a>
        </div>  
        <div class="wmc-details">
          <a class="wmc-product-title" href="<?php echo esc_url($_product->get_permalink()); ?>">
           <h4><?php echo esc_html($_product->get_title()); ?></h4>
         </a>
         <?php
         $price      = $_product->get_price_html();
         ?>
         <p>
           <?php
           echo '<span class="wmc-price">'. wp_kses_post($price) .'</span> x '. esc_html($values["quantity"]);
           ?>
         </p>
       </div>
     </li>
   <?php } ?>
 </ul>
 <div class="wmc-subtotal">
  <h5><?php _e( 'Subtotal:&nbsp;', 'woo-minicart' ); echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></h5>
</div>
<div class="wmc-bottom-buttons">
  <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php _e( 'View Cart', 'woo-minicart' ) ?></a>
  <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"><?php _e( 'Checkout', 'woo-minicart' ) ?></a>
</div>
</div>
<?php else: ?>
  <div class="wmc-content wmc-empty">
    <h3><?php  _e('Your cart is empty.', 'woo-minicart'); ?></h3>
  </div>
  <?php endif; ?>