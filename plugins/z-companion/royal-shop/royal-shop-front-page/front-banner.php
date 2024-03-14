<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_banner_sec',false) == true){
    return;
  }
?>
<section class="wzta-banner-section">
	<?php z_companion_display_customizer_shortcut( 'royal_shop_banner' );
	?>
	<div class="content-wrap">
  <?php z_companion_front_banner(); ?>
</div>
</section>