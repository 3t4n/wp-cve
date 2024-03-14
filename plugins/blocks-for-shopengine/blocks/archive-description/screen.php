<?php
defined('ABSPATH') || exit;

?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-archive-description">

		<?php

		if($block->is_editor) {

			$orderby    = 'name';
			$order      = 'asc';
			$hide_empty = false;
			$cat_args   = [
				'orderby'    => $orderby,
				'order'      => $order,
				'number'     => 1,
				'hide_empty' => $hide_empty,
			];

			$product_categories = get_terms('product_cat', $cat_args);

			if(!empty($product_categories[0])) {

				$cat = $product_categories[0];

				if(empty($cat->description)) {
					?>
                    <p> <?php esc_html_e('This is a dummy archive description based paragraph. It only appears on editor page and  will help you to customize your actual description', 'shopengine-gutenberg-addon'); ?> </p> <?php
				} else {

					echo esc_html($cat->description);
				}

				unset($product_categories, $cat);
			}
		}

		/**
		 * Hook: woocommerce_archive_description.
		 *
		 * @hooked woocommerce_taxonomy_archive_description - 10
		 * @hooked woocommerce_product_archive_description - 10
		 */

		if(is_product_category() || is_product_tag()) {
			the_archive_description();
		}

		?>

    </div>
</div>
