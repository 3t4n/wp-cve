<?php
if(get_theme_mod('royal_shop_disable_highlight_sec',false) == true){
    return;
  }
?>
<section class="wzta-product-highlight-section">
	 <?php z_companion_display_customizer_shortcut( 'royal_shop_highlight' ); ?>
<div class="content-wrap">
      <div class="wzta-highlight-feature-wrap">
          <?php   
            $default =  Z_COMPANION_Royal_Shop_Defaults_Models::instance()->get_feature_default();
            z_companion_highlight_content('royal_shop_highlight_content', $default);
           ?>
      </div>
  </div>
</section>