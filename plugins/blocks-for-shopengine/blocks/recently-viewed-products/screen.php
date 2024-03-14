<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
	<div class="shopengine-recently-viewed-products">
		<div class="recent-viewed-product-list">
			<?php

			$args = [
				'post_type'      => 'product',
				'orderby'        => 'post__in'
			];

			$no_cookie_posts = false;

			if (!empty($_COOKIE['shopengine_recent_viewed_product'])) {

				$product_ids = array_unique(explode(',', sanitize_text_field( wp_unslash($_COOKIE['shopengine_recent_viewed_product']) )));

				$product = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());
				$product_id = get_the_ID();
				$cookie_array_key = array_search($product_id, $product_ids);

				if (false !== $cookie_array_key) {
					unset($product_ids[$cookie_array_key]);
				}

				$product_limit = isset($settings['products_per_page']['desktop']) ? $settings['products_per_page']['desktop'] : 6;

				if (isset($settings['product_order']['desktop']) && $settings['product_order']['desktop'] == 'ASC') {

					$product_ids = array_reverse($product_ids);

					if($product_limit < count($product_ids)) {
						$product_ids = array_slice($product_ids, 0, $product_limit);
					}

				} else {

					$total_product = count($product_ids);

					if($product_limit < $total_product) {
						$product_ids = array_slice($product_ids, $total_product - $product_limit, $total_product - 1);
					}
				}


				$args['post__in'] = isset($product_ids) ? $product_ids : [];

			} else {


				if ($block->is_editor) {

					$product_limit = isset($settings['products_per_page']['desktop']) ? $settings['products_per_page']['desktop'] : 4;
					$order = isset($settings['product_order']['desktop']) ? $settings['product_order']['desktop'] : 'ASC';

					$products = get_posts(
						array(
							'post_type'        => 'product',
							'posts_per_page'   => $product_limit,
							'order'          	=> $order,

						),

					);

					if ($products) {
						$product_ids = wp_list_pluck($products, 'ID');
					}

					$args['post__in'] = isset($product_ids) ? $product_ids : [];
				} else {
					$no_cookie_posts = true;
				}
			}

			if(!$no_cookie_posts){
				$view = ['image','title','price','buttons'];
				$query = new WP_Query($args);
				if($query->have_posts()) :
					while($query->have_posts()) :
						$query->the_post();
	
						$default_content = [
							'image',
							'category',
							'title',
							'rating',
							'price',
							'description',
							'buttons'
						];
	
						$content = (!empty($view) ? $view : $default_content);
						asort($content, SORT_NUMERIC);
						?>
						<div class='shopengine-single-product-item'>
							<?php
							foreach($content as $key => $value) {
								$function = '_product_' . (is_numeric($value) ? $key : $value);
								\Shopengine_Gutenberg_Addon\Utils\Helper::$function($settings);
							}
	
							if(!empty($settings['counter_position']['desktop']) && $settings['counter_position']['desktop'] == 'footer') {
								\Shopengine_Gutenberg_Addon\Utils\Helper::_product_sale_end_date($settings, esc_html__('Ends in ', 'shopengine-gutenberg-addon'));
							}
							?>
	
						</div>
					<?php
					endwhile;
				endif;
				wp_reset_query();
				wp_reset_postdata();
			} else {
				esc_html_e("No recently viewed products to display", "shopengine-gutenberg-addon");
			}		

			?>
		</div>
	</div>

</div>
