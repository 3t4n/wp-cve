<?php
defined('ABSPATH') || exit;
use ShopEngine\Utils\Helper;

$module_list = \ShopEngine\Core\Register\Module_List::instance();
if ($module_list->get_list()['flash-sale-countdown']['status'] === 'active') :
   $module_settings = $module_list->get_settings('flash-sale-countdown');
   $flash_sale_index = $settings['flash_sale']['desktop'] ?? 0;
   $campaigns = \ShopEngine_Pro\Modules\Flash_Sale\Flash_Sale_Countdown::flash_sale_campaign($module_settings);
   if (!empty($campaigns[$flash_sale_index])) :
      $flash_sale = $campaigns[$flash_sale_index];
      $products = $flash_sale['product_list'];
      $args = array(
         'post_type' => 'product',
         'status'    => 'publish',
         'post__in'       => $products,
         'posts_per_page' => isset($settings['products_per_page']['desktop']) ? $settings['products_per_page']['desktop'] : 4,
         'order'          => isset($settings['product_order']['desktop']) ? $settings['product_order']['desktop'] : 'DESC',
         'orderby'        => isset($settings['product_orderby']['desktop']) ? $settings['product_orderby']['desktop'] : 'date',
      );

      $query = new WP_Query($args);
      $post_type = get_post_type();
?>
      <div class="shopengine shopengine-widget">
         <div class="shopengine-widget">
            <div class="shopengine-deal-products-widget">
               <div class="deal-products-container">
                  <?php

                  if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

                        $id         = get_the_ID();
                        $title      = wp_trim_words(get_the_title(),  $settings['title_word_limit']['desktop'], '...');
                        $image_url  = get_the_post_thumbnail_url($id);
                        $product    = wc_get_product($id);
                        $price      = wc_price($product->get_price());
                        $reg_price  = wc_price($product->get_regular_price());
                        $stock_qty  = $product->get_stock_quantity();
                        $total_sell = $product->get_total_sales();
                        $available  = $stock_qty - $total_sell;

                        if (intval($product->get_regular_price()) !== 0) {
                           $offPercentage = intval($product->get_price()) / intval($product->get_regular_price()) * 100;
                        }

                        $sales_price_from = !empty($flash_sale['start_date']) ? $flash_sale['start_date'] : '';
                        $sales_price_to   = !empty($flash_sale['end_date']) ? $flash_sale['end_date'] : '';
                        $current_time     = strtotime(date('Y-m-d H:i:s')); // get the current time
                        // when woo commerce date form value not found it will take the date when the post was created
                        if (!isset($sales_price_from) || empty($sales_price_from)) {
                           $sales_price_from = strtotime(get_the_date());
                        }
                        // data for countdown clock
                        $deal_data = [
                           'start_time'   => date('Y-m-d H:i:s', strtotime($sales_price_from)),
                           'end_time'     => date('Y-m-d H:i:s', strtotime($sales_price_to . ' 24:00:00')),
                           'show_days'    => ($settings['shopengine_show_countdown_clock_days']['desktop'] == true) ? 'yes' : 'no',
                        ];

                        // options for sell and available section
                        $progress_data = [
                           'bg_line_clr'     => (isset($settings['shopengine_product_stock_bg_line_clr']['desktop'])) ? $settings['shopengine_product_stock_bg_line_clr']['desktop'] : '#F2F2F2',
                           'bg_line_height'  => (isset($settings['shopengine_product_stock_bg_line_height']['size']['desktop'])) ? $settings['shopengine_product_stock_bg_line_height']['size']['desktop'] : 2,
                           'bg_line_cap'     => (isset($settings['shopengine_product_stock_line_cap']['desktop'])) ? $settings['shopengine_product_stock_line_cap']['desktop'] : 'round', // "butt|round|square"

                           'prog_line_clr'   => (isset($settings['shopengine_product_stock_prog_line_clr']['desktop'])) ? $settings['shopengine_product_stock_prog_line_clr']['desktop'] : '#F03D3F',
                           'prog_line_height' => (isset($settings['shopengine_product_stock_prog_line_height']['size']['desktop'])) ? $settings['shopengine_product_stock_prog_line_height']['size']['desktop'] : 4,
                           'prog_line_cap'   => (isset($settings['shopengine_product_stock_line_cap']['desktop'])) ? $settings['shopengine_product_stock_line_cap']['desktop'] : 'round',

                           'stock_qty'       => $stock_qty,
                           'total_sell'      => $total_sell
                        ];

                  ?>

                        <div class="deal-products" data-deal-data='<?php echo esc_attr(wp_json_encode($deal_data)); ?>'>

                           <div class="deal-products__top">
                              <!-- product image -->
                              <img class="deal-products__top--img" src="<?php echo esc_url($image_url) ?>">

                              <!-- offer show in percentage -->
                              <?php if ($settings['shopengine_show_percentage_badge']['desktop'] == true && intval($product->get_regular_price()) !== 0) : ?>
                                 <?php if ($flash_sale['discount_type'] === 'percent') : ?>
                                    <span class="shopengine-offer-badge">-<?php echo esc_html($flash_sale['discount_amount']) ?>%</span>
                                 <?php else : ?>
                                    <span class="shopengine-offer-badge">
                                       -<?php 
                                       shopengine_content_render(apply_filters('flash_sale_fixed_discount_amount', $flash_sale['discount_amount']));
                                       shopengine_content_render(get_woocommerce_currency_symbol()); 
                                       ?>
                                    </span>
                                 <?php endif; ?>
                              <?php endif; ?>

                              <!-- sale badge -->
                              <?php if ($settings['shopengine_is_sale_badge']['desktop'] == true) : ?>
                                 <span class="shopengine-sale-badge"> <?php echo esc_html($settings['shopengine_sale_badge_text']['desktop']); ?> </span>
                              <?php endif; ?>

                              <!-- countdown clock -->
                              <?php if ($settings['shopengine_show_countdown_clock'] == true) : ?>
                                 <div class="shopengine-countdown-clock">

                                    <?php if ($settings['shopengine_show_countdown_clock_days'] == true) : ?>
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
                              <h4 class="deal-products__desc--name"> <a href="<?php the_permalink() ?>"> <?php echo esc_html($title); ?> </a> </h4>
                           </div>

                           <!-- product description -->
                           <div class="deal-products__prices">
                              <ins><span class="woocommerce-Price-amount amount"><?php shopengine_content_render($price); ?> </span></ins>

                              <?php if (!empty($price)) : ?>
                                 <del>
                                    <span class="woocommerce-Price-amount amount">
                                       <?php shopengine_content_render($reg_price); ?>
                                    </span>
                                 </del>
                              <?php endif; ?>

                           </div>

                           <!-- stock and sold line chart -->
                           <div class="deal-products__grap">
                              <canvas class="deal-products__grap--line" height="<?php echo esc_attr($progress_data['prog_line_height'] + 2) ?>" data-settings='<?php echo esc_attr(wp_json_encode($progress_data)); ?>'></canvas>
                              <div class="deal-products__grap__sells">
                                 <div class="deal-products__grap--available">
                                    <span><?php esc_html_e('Available:', 'shopengine-gutenberg-addon'); ?></span>
                                    <span class="avl_num"><?php echo esc_html($available) ?></span>
                                 </div>
                                 <div class="deal-products__grap--sold">
                                    <span><?php esc_html_e('Sold:', 'shopengine-gutenberg-addon'); ?></span>
                                    <span class="sld_num"><?php echo esc_html($total_sell) ?></span>
                                 </div>
                              </div>
                           </div>

                        </div>

                  <?php
                     endwhile;
                  endif;
                  wp_reset_postdata();
                  ?>
               </div>
            </div>
         </div>
      </div>
<?php
   elseif ($block->is_editor) :
      esc_html_e('No deal products available', 'shopengine-gutenberg-addon');
   endif;
elseif ($block->is_editor) :
   esc_html_e('Please active shopengine flash sale module', 'shopengine-gutenberg-addon');
endif;
