<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_cat_sec',false) == true){
    return;
  }
?>

<section class="wzta-product-tab-section">
  <?php z_companion_display_customizer_shortcut( 'royal_shop_category_tab_section' );
 ?>
 <!-- wzta head start -->
  <div id="wzta-cat-tab" class="wzta-cat-tab">
  <div class="wzta-heading-wrap">
  <div class="wzta-heading">
    <h4 class="wzta-title">
    <span class="title"><?php echo esc_html(get_theme_mod('royal_shop_cat_tab_heading','Filter Product Slider'));?></span>
   </h4>
  </div>
<!-- tab head start -->
<?php  $term_id = get_theme_mod('z_companion_category_tab_list',0);   
z_companion_category_tab_list($term_id); 
?>
</div>
<!-- tab head end -->
<div class="content-wrap">
  <div class="tab-content">
      <div class="wzta-slide wzta-product-cat-slide owl-carousel">
       <?php 
          $term_id = get_theme_mod('z_companion_category_tab_list',0); 
          $prdct_optn = get_theme_mod('royal_shop_category_optn','recent');
          z_companion_product_cat_filter_default_loop($term_id,$prdct_optn); 
         ?>
      </div>
    </div>
  </div>
</div>
</section>