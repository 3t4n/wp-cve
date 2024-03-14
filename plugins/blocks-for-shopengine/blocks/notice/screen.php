<?php

defined('ABSPATH') || exit;

 ob_start(); ?>
<div class="shopengine-checkout-notice">
	<div class="woocommerce-message" role="alert">
		<a href="#" tabindex="1" class="button wc-forward"> <?php esc_html_e('View cart', 'shopengine-gutenberg-addon'); ?></a> <?php esc_html_e('“Hoodie with Logo” has been added to your cart.', 'shopengine-gutenberg-addon'); ?>
	</div>
</div>
<?php $editor_markup = ob_get_clean(); ?>

<div class="shopengine shopengine-widget">
	<div class="shopengine-checkout-notice">
		<?php
		if($block->is_editor){
			echo wp_kses_post($editor_markup);
		}elseif(is_single()){
			wc_print_notices();
		}
		?>
	</div>
</div>