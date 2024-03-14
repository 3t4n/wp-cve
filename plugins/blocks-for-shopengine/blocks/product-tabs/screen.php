
<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
	<div class="shopengine-product-tabs">
		<?php

		if ($block->is_editor || !is_product()) {

			$product_tabs = woocommerce_default_product_tabs();

			if (!empty($product_tabs)) : ?>

				<?php include_once __DIR__ . '/dummy-tabs.php'; ?>

		<?php endif;
		} else {

			woocommerce_output_product_data_tabs();
		}

		?>
	</div>
</div>