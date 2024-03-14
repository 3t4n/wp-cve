<div 
  class="wcpt-product-form wcpt-modal" 
  data-wcpt-product-id="<?php echo $product_id; ?>"
>
  <div class="wcpt-modal-content">
    <div class="wcpt-close-modal">
      <?php echo wcpt_icon('x', 'wcpt-close-modal-icon'); ?>
    </div>
    <span class="wcpt-product-form-title">
      <?php echo $product->get_title(); ?>
      <span class="wcpt-product-form-price"><?php echo $product->get_price_html(); ?></span>
    </span>    
    <?php do_action('wcpt_modal_form_content_start'); ?>
    <?php do_action('woocommerce_'. $product_type .'_add_to_cart'); ?>
  </div>
</div>