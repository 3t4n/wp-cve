<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_brand_sec',false) == true){
    return;
  }
?>
<section class="wzta-brand-section">
<?php z_companion_display_customizer_shortcut( 'royal_shop_brand' );
?>
<div class="content-wrap">
    <div class="wzta-slide wzta-brand owl-carousel">
    	<?php   
             z_companion_brand_content('royal_shop_brand_content', '');
        ?>
    </div>
</div>
</section>