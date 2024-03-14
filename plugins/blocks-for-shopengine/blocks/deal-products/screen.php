<?php
defined('ABSPATH') || exit;
use ShopEngine\Utils\Helper;

$args = [
	'post_type'      => 'product',
	'status'         => 'publish',
	'meta_query'     => [
		[
			'key'     => '_sale_price_dates_to',
			'value'   => '',
			'compare' => '!=',
		],
	],
	'posts_per_page' => isset($settings['shopengine_show_product']['desktop']) ? $settings['shopengine_show_product']['desktop'] : 4,
	'order'          => isset($settings['shopengine_product_order']['desktop']) ? $settings['shopengine_product_order']['desktop'] : 'DESC',
	'orderby'        => isset($settings['shopengine_product_order_by']['desktop']) ? $settings['shopengine_product_order_by']['desktop'] : 'date',
];

$query     = new WP_Query($args);
$post_type = get_post_type();
?>

<div class="shopengine-widget">
    <div class="shopengine-deal-products-widget">
        <div class="deal-products-container">
			<?php

			if($query->have_posts()): while($query->have_posts()): $query->the_post();

				$id         = get_the_ID();
				$title      = wp_trim_words(get_the_title(), $settings['shopengine_product_title_word_limit']['desktop'], '...');
				$image_url  = get_the_post_thumbnail_url($id);
				$product    = wc_get_product($id);
				$price      = wc_price($product->get_price());
				$reg_price  = wc_price($product->get_regular_price());
				$stock_qty  = $product->get_stock_quantity();
				$total_sell = $product->get_total_sales();
				$available  = $stock_qty - $total_sell;

				if($product->get_regular_price() > 0) {
					$offPercentage = $product->get_price() / $product->get_regular_price() * 100;
				}

				$sales_price_from = get_post_meta($id, '_sale_price_dates_from', true);
				$sales_price_to   = get_post_meta($id, '_sale_price_dates_to', true);
				$current_time     = strtotime(date('Y-m-d H:i:s')); // get the current time
				$is_time_over     = ($sales_price_to - $current_time) < 0 ? true : false;

				// when time is over, hide the deal product form the list
				if($is_time_over) {
					continue;
				}

				// when woo commerce date form value not found it will take the date when the post was created
				if(!isset($sales_price_from) || empty($sales_price_from)) {
					$sales_price_from = strtotime(get_the_date());
				}

				// data for countdown clock
				$deal_data = [
					'start_time' => date('Y-m-d H:i:s', $sales_price_from),
					'end_time'   => date('Y-m-d H:i:s', $sales_price_to),
					'show_days'  => ($settings['shopengine_show_days']['desktop']) ? 'yes' : 'no',
				];


				// options for sell and available section
				$progress_data = [
					'bg_line_clr'    => (isset($settings['shopengine_stock_progress_normal_line_color']['desktop'])) ? $settings['shopengine_stock_progress_normal_line_color']['desktop'] : '#F2F2F2',
					'bg_line_height' => (isset($settings['shopengine_stock_progress_normal_line_height']['desktop'])) ? $settings['shopengine_stock_progress_normal_line_height']['desktop'] : 2,
					'bg_line_cap'    => (isset($settings['shopengine_stock_progress_line_cap_style']['desktop'])) ? $settings['shopengine_stock_progress_line_cap_style']['desktop'] : 'round', // "butt|round|square"

					'prog_line_clr'    => (isset($settings['shopengine_stock_progress_active_line_color']['desktop'])) ? $settings['shopengine_stock_progress_active_line_color']['desktop'] : '#F03D3F',
					'prog_line_height' => (isset($settings['shopengine_stock_progress_active_line_height']['desktop'])) ? $settings['shopengine_stock_progress_active_line_height']['desktop'] : 4,
					'prog_line_cap'    => (isset($settings['shopengine_stock_progress_line_cap_style']['desktop'])) ? $settings['shopengine_stock_progress_line_cap_style']['desktop'] : 'round',

					'stock_qty'  => $stock_qty,
					'total_sell' => $total_sell,
				];

				?>

                <div class="deal-products" data-deal-data='<?php echo esc_attr( wp_json_encode($deal_data), true ); ?>'>

                    <div class="deal-products__top">
                        <!-- product image -->
                        <img class="deal-products__top--img" src="<?php echo esc_url($image_url) ?>">

                        <!-- offer show in percentage -->
						<?php if($settings['shopengine_percentage_badge']['desktop'] && $product->get_regular_price() !== 0): ?>
                            <span class="shopengine-offer-badge"> -<?php echo esc_html(100 - round($offPercentage, 2)); ?>
							<?php esc_html_e('%', 'shopengine-gutenberg-addon'); ?></span>
						<?php endif; ?>

                        <!-- sale badge -->
						<?php if($settings['shopengine_enable_sale_badge']['desktop']): ?>
                            <span class="shopengine-sale-badge"> <?php echo esc_html($settings['shopengine_sale_badge']['desktop']); ?> </span>
						<?php endif; ?>

                        <!-- countdown clock -->
						<?php if($settings['shopengine_countdown_clock']['desktop']): ?>
                            <div class="shopengine-countdown-clock">

								<?php if($settings['shopengine_show_days']['desktop']): ?>
                                    <span class="se-clock-item">
                     <span class="clock-days"></span>
                     <span class="clock-days-label"><?php esc_html_e('Days', 'shopengine-gutenberg-addon'); ?></span>
                  </span>
								<?php endif; ?>

                                <span class="se-clock-item">
                  <span class="clock-hou"></span>
                  <span class="clock-hou-label"><?php esc_html_e('Hours', 'shopengine-gutenberg-addon'); ?></span>
                </span>

                                <span class="se-clock-item">
                  <span class="clock-min"></span>
                  <span class="clock-min-label"><?php esc_html_e('Min', 'shopengine-gutenberg-addon'); ?></span>
                </span>

                                <span class="se-clock-item">
                  <span class="clock-sec"></span>
                  <span class="clock-sec-label"><?php esc_html_e('Sec', 'shopengine-gutenberg-addon'); ?></span>
                </span>
                            </div>

						<?php endif; ?>

                    </div>

                    <!-- product description -->
                    <div class="deal-products__desc">
                        <h4 class="deal-products__desc--name"><a
                                    href="<?php the_permalink(); ?>"> <?php echo esc_html($title); ?> </a></h4>
                    </div>

                    <!-- product description -->
                    <div class="deal-products__prices">
                        <ins>
                            <span class="woocommerce-Price-amount amount"><?php shopengine_content_render($price); ?> </span>
                        </ins>

						<?php if(!empty($price)) : ?>
                            <del>
                     <span class="woocommerce-Price-amount amount">
                        <?php shopengine_content_render($reg_price); ?>
                     </span>
                            </del>
						<?php endif; ?>

                    </div>

                    <!-- stock and sold line chart -->
                    <div class="deal-products__grap">
                        <canvas class="deal-products__grap--line"
                                height="<?php echo esc_attr($progress_data['prog_line_height'] + 2); ?>"
                                data-settings='<?php echo esc_js( wp_json_encode($progress_data), true ); ?>'></canvas>
                        <div class="deal-products__grap__sells">
                            <div class="deal-products__grap--available">
                                <span><?php esc_html_e('Available:', 'shopengine-gutenberg-addon'); ?></span>
                                <span class="avl_num"><?php echo esc_html($available); ?></span>
                            </div>
                            <div class="deal-products__grap--sold">
                                <span><?php esc_html_e('Sold:', 'shopengine-gutenberg-addon'); ?></span>
                                <span class="sld_num"><?php echo esc_html($total_sell); ?></span>
                            </div>
                        </div>
                    </div>

                </div>

			<?php

			endwhile;
            elseif($post_type == \ShopEngine\Core\Template_Cpt::TYPE):
				esc_html_e('No deal products available', 'shopengine-gutenberg-addon');
			endif;
			wp_reset_postdata(); ?>
        </div>
    </div>
</div>
