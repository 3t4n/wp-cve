<?php
defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper;

$post_type = get_post_type();

if(WC()->session) {
	wc_print_notices();
}

$editor_mode = $block->is_editor;

/**
 * Show Action Buttons
 */
if($settings['shopengine_group_btns']['desktop'] == true) {
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

	add_filter('woocommerce_before_shop_loop_item_title', 'show_product_action_btns', 100);
}

if(!function_exists('show_product_action_btns')) {
	function show_product_action_btns() {

		$data_attr = apply_filters('shopengine/group_btns/optional_tooltip_data_attr', '');

		woocommerce_template_loop_product_link_close();
		?>
        <div class="loop-product--btns" <?php echo esc_attr($data_attr) ?>>
            <div class="loop-product--btns-inner">
				<?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
		<?php
		woocommerce_template_loop_product_link_open();
	}
}


/**
 * Show Categories
 */
if($settings['shopengine_is_cats']['desktop'] === true) {
	add_filter('woocommerce_before_shop_loop_item_title', function () use ($settings) {
		$terms = get_the_terms(get_the_ID(), 'product_cat');
		// global $settings;
		if(empty($terms)) {
			return false;
		}

		$terms_count = count($terms);

		if($terms_count > 0) {
			echo '<ul class="product-categories">';
			foreach($terms as $key => $term) {
				if($settings['shopengine_cats_max']['desktop'] == $key) {
					break;
				}

				echo '<li><span>' . esc_html($term->name) . '</span></li>';
			}
			echo '</ul>';
		}
	}, 15 );

}


add_filter('woocommerce_product_get_rating_html', 'show_empty_product_rating', 99, 3);

if(!function_exists('show_empty_product_rating')) {
	function show_empty_product_rating($html, $ratings, $count) {
		if ('0' === $ratings) :
			$html = '<div class="star-rating"></div>';
		endif;

		global $product;

		$review_count = $product->get_review_count();

		return $html . '<span class="shopengine-product-rating-review-count">(' . $review_count . ')</span>';
	}
}


\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_part_filter_by_match('woocommerce/content-product.php', 'templates/content-product.php');
\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();
\ShopEngine\Compatibility\Conflicts\Theme_Hooks::instance()->theme_conflicts__archive_products_widget_during_render();
$shopengine_is_hover_details = $settings['shopengine_is_hover_details']['desktop'];
$shopengine_group_btns       = $settings['shopengine_group_btns']['desktop'];
$shopengine_is_details       = $settings['shopengine_is_details']['desktop'];
$shopengine_pagination_style = $settings['shopengine_pagination_style']['desktop'];
$shopengine_price_reg_pos    = !empty($settings['shopengine_price_reg_pos']['desktop']) ? $settings['shopengine_price_reg_pos']['desktop'] : 'before';


$wrap_extra_class = sprintf('%1$s%2$s', 'shopengine-grid', ($shopengine_is_hover_details !== true && $shopengine_group_btns !== true) ? ' shopengine-hover-disable' : '');

$shopengine_group_btns_over_image = $settings['shopengine_group_btns_over_image']['desktop'] === true ? 'shopengine-disable-group-btn-over-image-yes' : '';

?>
    <div class="shopengine-price-pos-<?php echo esc_attr($shopengine_price_reg_pos); ?>  elementor-align-<?php echo esc_attr($settings['shopengine_container_text_align']['desktop']); ?> <?php echo esc_attr($shopengine_group_btns_over_image); ?>">
        <div class="shopengine shopengine-widget">
            <div data-pagination="<?php echo esc_attr($shopengine_pagination_style) ?>"
                 class="shopengine-archive-products <?php echo esc_attr($wrap_extra_class); ?>">
				<?php

				// add product description
				add_action('woocommerce_after_shop_loop_item_title', function () use ($shopengine_is_details, $shopengine_group_btns, $shopengine_is_hover_details) {
					if($shopengine_is_hover_details === true) : ?>
                        <div class="shopengine-product-description-footer">
					<?php endif;

					if($shopengine_is_details === true) :
						?>
                        <div class="shopengine-product-excerpt"> <?php
							the_excerpt();
							?> </div> <?php
					endif;

					if($shopengine_is_hover_details === true) : ?>
						<?php if($shopengine_group_btns !== true) : ?>
                            <div class="shopengine-product-description-btn-group">
								<?php woocommerce_template_loop_add_to_cart(); ?>
                            </div>
						<?php endif; ?>
                        </div> <?php
					endif;
				},         40);

				$wp_query_args = ['post_type' => 'product'];

				// pagination next previous button label filter

				if($shopengine_pagination_style === 'numeric') {
					$control_args['prev_icon'] = '<i class="' . esc_attr($settings['shopengine_pagination_prev_icon']['desktop']) . '" style="font-style: normal;"></i>';
					$control_args['next_icon'] = '<i class="' . esc_attr($settings['shopengine_pagination_next_icon']['desktop']) . '" style="font-style: normal;"></i>';
				}

				if($shopengine_pagination_style === 'default') {
					$control_args['prev_icon'] = $settings['shopengine_pagination_prev_text']['desktop'];
					$control_args['next_icon'] = $settings['shopengine_pagination_next_text']['desktop'];
				}

				if($shopengine_pagination_style === 'load-more' || $shopengine_pagination_style === 'load-more-on-scroll') {
					$control_args['prev_icon'] = '';
					$control_args['next_icon'] = $settings['shopengine_pagination_loadmore_text']['desktop'];
				}

				if(isset($control_args)) {
					add_filter('woocommerce_pagination_args', function ($args) use ($control_args) {
						$args['prev_text'] = $control_args['prev_icon'];
						$args['next_text'] = $control_args['next_icon'];

						return $args;
					});
				}

				$page_type = \ShopEngine\Widgets\Products::instance()->get_template_type_by_id(get_the_ID());
				if(in_array($page_type, ['archive', 'shop', 'search']) && $editor_mode) {

					global $wp_query, $post;
					$main_query = clone $wp_query;
					$main_post  = clone $post;
					$wp_query   = new \WP_Query($wp_query_args);
					wc_setup_loop(
						[
							'is_filtered'  => is_filtered(),
							'total'        => $wp_query->found_posts,
							'total_pages'  => $wp_query->max_num_pages,
							'per_page'     => $wp_query->get('posts_per_page'),
							'current_page' => max(1, $wp_query->get('paged', 1)),
						]
					);
				}

				$editor_mode = true;
				$run_loop = $editor_mode ? true : woocommerce_product_loop();

				if($editor_mode) {
					WC()->frontend_includes();

					if(empty(WC()->session)) {
						WC()->session = new WC_Session_Handler();
						WC()->session->init();
					}
				}

				$tooltip = !empty($settings['shopengine_is_tooltip']['desktop']) && $settings['shopengine_is_tooltip']['desktop'] === true ? 'yes' : '';
				if($run_loop) {

					do_action('woocommerce_before_shop_loop');

					$products = new WP_Query(['post_type' => 'product']);

					if ($products->have_posts()) {

						woocommerce_product_loop_start();

						while($products->have_posts()) {
							$products->the_post();

							/**
							 * Hook: woocommerce_shop_loop.
							 */
							do_action('woocommerce_shop_loop');

							global $product;

							// Ensure visibility.
							if ( ! empty( $product ) &&  $product->is_visible() ) : ?>

								<li class="archive-product-container" data-tooltip="<?php echo esc_attr($tooltip); ?>">
									<ul class="shopengine-archive-mode-grid">
										<li class="shopengine-archive-products__left-image">
											<a href="<?php echo esc_url( get_the_permalink() ); ?>">
											<?php shopengine_content_render(woocommerce_get_product_thumbnail(get_the_id())); ?>
											</a>
										</li>

										<?php wc_get_template_part('content', 'product'); ?>

									</ul>
								</li>
							<?php endif;
						}

					woocommerce_product_loop_end();

					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action('woocommerce_after_shop_loop');

				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action('woocommerce_no_products_found');
				}

				if(in_array($page_type, ['archive', 'shop', 'search']) && $editor_mode) {
					global $wp_query, $post;
					$main_query = clone $wp_query;
					$main_post  = clone $post;
					wp_reset_query();
					wp_reset_postdata();
				}
			}
			?>
            </div>
        </div>
    </div>

<?php
/**
 * Reset Filters to Default.
 */
if($shopengine_group_btns === true) {
	remove_filter('woocommerce_before_shop_loop_item_title', 'show_product_action_btns');

	add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

if($settings['shopengine_is_cats']['desktop'] === true) {
	remove_filter('woocommerce_before_shop_loop_item_title', 'show_product_cats');
}

remove_filter('woocommerce_product_get_rating_html', 'show_empty_product_rating', 99);
