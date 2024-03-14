<?php
defined( 'ABSPATH' ) || exit;
global $product;
?>
<div class="woopb-product-left">
	<div class="woopb-product-image">
		<?php if ( has_post_thumbnail() ) {
			echo get_the_post_thumbnail( '', 'shop_catalog' );
		} else {
			echo wc_placeholder_img();
		} ?>
	</div>
</div>
