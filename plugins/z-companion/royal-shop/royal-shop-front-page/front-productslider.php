<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_product_slide_sec',false) == true){
    return;
  }
?>

<section class="wzta-product-slide-section">
    <?php z_companion_display_customizer_shortcut( 'royal_shop_product_slide_section' );
    ?>
  <div class="wzta-heading">
    <h4 class="wzta-title">
    <span class="title"><?php echo esc_html(get_theme_mod('royal_shop_product_slider_heading','Product Slider'));?></span>
   </h4>
</div>
<div class="content-wrap">
    <div class="wzta-slide wzta-product-slide owl-carousel">
      <?php    
          $term_id = get_theme_mod('royal_shop_product_slider_cat',0);  
          $prdct_optn = esc_html(get_theme_mod('royal_shop_product_slide_optn','recent'));
          z_companion_product_cat_filter_default_loop($term_id,$prdct_optn); 
      ?>
    </div>
  </div>
</section>