<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_product_list_sec',false) == true){
    return;
  }
?>
<section class="wzta-product-list-section">
        <?php z_companion_display_customizer_shortcut( 'royal_shop_product_slide_list' );
         ?>
  <div class="wzta-heading">
    <h4 class="wzta-title">
    <span class="title"><?php echo esc_html(get_theme_mod('royal_shop_product_list_heading','ListView Slider'));?></span>
   </h4>
</div>
<div class="content-wrap">
    <div class="wzta-slide wzta-product-list owl-carousel">
      <?php    
          $term_id = get_theme_mod('royal_shop_product_list_cat',0);  
          $prdct_optn = get_theme_mod('royal_shop_product_list_optn','recent');
          z_companion_product_slide_list_loop($term_id,$prdct_optn); 
      ?>
    </div>
  </div>
</section>