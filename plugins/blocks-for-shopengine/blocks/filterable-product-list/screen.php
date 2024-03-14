<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
	<?php 
	$is_content = [
		'shopengine_is_cats' => ($settings['shopengine_is_cats']['desktop'] == true) ? 'yes' : 'no',
		'shopengine_is_details' => ($settings['shopengine_is_details']['desktop'] == true) ?  'yes' : 'no',
		'shopengine_is_product_rating' => ($settings['shopengine_is_product_rating']['desktop'] == true) ? 'yes' : 'no',
		'shopengine_group_btns' => ($settings['shopengine_group_btns']['desktop'] == true) ?  'yes' : 'no',
	];
	
	?>
	<div class="shopengine-<?php echo esc_attr($block->block_key); ?>" data-widget_settings='<?php echo esc_attr(wp_json_encode($is_content)); ?>'>
		<div class="shopengine-filterable-product-wrap">
			<?php
			$uid      = [];
			$products = [];
			$count    = 0;
			?>
			<!-- -----------------------
    Filterable product navbar
    ------------------------- -->
			<div class="filter-nav">
				<ul>
					<?php if (!empty($settings['shopengine_filter_content']) && !empty($settings['shopengine_filter_content'][0]['product_list'])) :
						foreach ($settings['shopengine_filter_content'] as $key => $content) :

							// collect navbar label
							array_push($uid, uniqid());
							if ($key == 0) {
								$products_lists = $content['product_list'];
								$products = !empty($products_lists) ? wp_list_pluck($products_lists['desktop'], 'id') : '';
							}

							$all_product_list = wp_list_pluck($content['product_list']['desktop'], 'id');

					?>		
							
							<li class="filter-nav-item">
								<a href="#" class="filter-nav-link <?php echo esc_attr($key == 0 ? 'active' : ''); ?>" data-filter-uid="<?php echo esc_attr($uid[$count]); ?>" data-product-list='<?php echo !empty($all_product_list) ? esc_attr(wp_json_encode($all_product_list)) : ''; ?>'>
									<?php echo esc_html($content['filter_label']['desktop']); ?>
								</a>
							</li>
					<?php $count++;
						endforeach;
					endif; ?>
				</ul>
			</div>

			<!-- -----------------------
    Filterable product content
    ------------------------- -->
			<div class="filter-content">
				<div class="filter-content-row filtered-product-list active <?php echo !empty($uid) ? 'filter-' . esc_attr($uid[0]) : '' ?>">
					<?php

					/*
				-------------------------
				arguments for the query
				-------------------------
			*/

					$args = [
						'post_type'      => 'product',
						'posts_per_page' => isset($settings['shopengine_products_per_page']['desktop']) ? $settings['shopengine_products_per_page']['desktop'] : 6,
						'order'          => isset($settings['shopengine_product_order']['desktop']) ? $settings['shopengine_product_order']['desktop'] : 'desc',
						'post__in'       => $products,
						'orderby'        => isset($settings['shopengine_product_orderby']['desktop']) ? $settings['shopengine_product_orderby']['desktop'] : 'date',
					];

					// query start
					$query   = new WP_Query($args);



					$content = isset($settings['shopengine_product_custom_order_list']['desktop']['lists']) ? $settings['shopengine_product_custom_order_list']['desktop']['lists'] : $settings['shopengine_product_custom_order_list']['desktop'];

					$content = !empty($content) ? $content : [];

					if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

					?>
							<div class='shopengine-single-product-item'>
								<?php
								foreach ($content as $key => $value) {
									if (
										($settings['shopengine_is_cats']['desktop'] !== true && $value['id'] == 'category') ||
										($settings['shopengine_is_details']['desktop'] !== true && $value['id'] == 'description') ||
										($settings['shopengine_is_product_rating']['desktop'] !== true && $value['id'] == 'rating')
									) {
										continue;
									}
									$function = '_product_' . $value['id'];
									\Shopengine_Gutenberg_Addon\Utils\Helper::$function($settings);
								}
								?>

							</div>
					<?php endwhile;
					endif;
					wp_reset_postdata();

					?>
				</div>
			</div>
		</div>

	</div>
</div>