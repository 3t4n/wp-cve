<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
   <div class="shopengine-checkout-review-order">
      <div class="col2-set">
         <h3 id="order_review_heading"><?php esc_html_e('Your order', 'shopengine-gutenberg-addon') ;?></h3>

         <div id="order_review" class="woocommerce-checkout-review-order">
            <table class="shop_table woocommerce-checkout-review-order-table">
               <thead>
                  <tr>
                     <th class="product-name"><?php  esc_html_e('Product', 'shopengine-gutenberg-addon');?></th>
                     <th class="product-total"><?php esc_html_e('Subtotal', 'shopengine-gutenberg-addon') ;?></th>
                  </tr>
               </thead>
               <tbody>
                  <tr class="cart_item shopengine-order-review-product">
                     <td class="product-name">
                        <img width="600" height="600"
                           src="<?php echo esc_url('https://secure.gravatar.com/userimage/221270643/c7853736bddd2dd6333b2c5ba35cbda5.png?size=200');?>"
                           class="attachment-single-post-thumbnail size-single-post-thumbnail" alt="" loading="lazy"
                           sizes="(max-width: 600px) 100vw, 600px" /> <?php esc_html_e('Kitchen Appliances with wood', 'shopengine-gutenberg-addon'); ?>&nbsp;
                        <strong class="product-quantity">&times;&nbsp; <?php esc_html_e('3', 'shopengine-gutenberg-addon'); ?></strong>
                     </td>
                     <td class="product-total">
                        <span class="woocommerce-Price-amount amount"><bdi><span
                                 class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('429.30', 'shopengine-gutenberg-addon'); ?></bdi></span>
                     </td>
                  </tr>
                  <tr class="cart_item shopengine-order-review-product">
                     <td class="product-name">
                        <img width="600" height="600"
                           src=" <?php echo esc_url('https://secure.gravatar.com/userimage/221270643/caf9b709b011aa3037870deda912f335.png?size=200'); ?>"
                           class="attachment-single-post-thumbnail size-single-post-thumbnail" alt="" loading="lazy"
                           sizes="(max-width: 600px) 100vw, 600px" /> <?php esc_html_e('Wireless LCD Audio Video', 'shopengine-gutenberg-addon');?> &nbsp;
                        <strong class="product-quantity">&times;&nbsp; <?php esc_html_e('1', 'shopengine-gutenberg-addon') ;?></strong>
                     </td>
                     <td class="product-total">
                        <span class="woocommerce-Price-amount amount"><bdi><span
                                 class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('140.00', 'shopengine-gutenberg-addon') ;?></bdi></span>
                     </td>
                  </tr>
                  <tr class="cart_item shopengine-order-review-product">
                     <td class="product-name">
                        <img width="600" height="600"
                           src="<?php echo esc_url('https://secure.gravatar.com/userimage/221270643/8cca8048aeca0c8f4862d1f424fe788e.png?size=200') ;?>"
                           class="attachment-single-post-thumbnail size-single-post-thumbnail" alt="" loading="lazy"
                           sizes="(max-width: 600px) 100vw, 600px" /><?php esc_html_e('Trending Watch for Man ', 'shopengine-gutenberg-addon');?>&nbsp;
                        <strong class="product-quantity">&times;&nbsp;<?php esc_html_e('2', 'shopengine-gutenberg-addon') ;?></strong>
                     </td>
                     <td class="product-total">
                        <span class="woocommerce-Price-amount amount"><bdi><span
                                 class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('250.86', 'shopengine-gutenberg-addon') ;?></bdi></span>
                     </td>
                  </tr>
                  <tr class="cart_item shopengine-order-review-product">
                     <td class="product-name">
                        <img width="600" height="600"
                           src="<?php echo esc_url('https://secure.gravatar.com/userimage/221270643/a44ab32678f013155aa8de695062ef0e.png?size=200')  ;?>"
                           class="attachment-single-post-thumbnail size-single-post-thumbnail" alt="" loading="lazy"
                           sizes="(max-width: 600px) 100vw, 600px" /><?php esc_html_e('Golden Horse ', 'shopengine-gutenberg-addon');?>&nbsp;
                        <strong class="product-quantity">&times;&nbsp;<?php esc_html_e('1', 'shopengine-gutenberg-addon');?></strong>
                     </td>
                     <td class="product-total">
                        <span class="woocommerce-Price-amount amount"><bdi><span
                                 class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('120.00', 'shopengine-gutenberg-addon');?></bdi></span>
                     </td>
                  </tr>
               </tbody>
               <tfoot>
                  <tr class="cart-subtotal">
                     <th><?php esc_html_e('Subtotal', 'shopengine-gutenberg-addon'); ?></th>
                     <td>
                        <span class="woocommerce-Price-amount amount"><bdi><span
                                 class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('940.16', 'shopengine-gutenberg-addon'); ?></bdi></span>
                     </td>
                  </tr>

                  <tr class="order-total">
                     <th><?php esc_html_e('Total', 'shopengine-gutenberg-addon'); ?></th>
                     <td>
                        <strong><span class="woocommerce-Price-amount amount"><bdi><span
                                    class="woocommerce-Price-currencySymbol">$</span><?php esc_html_e('940.16', 'shopengine-gutenberg-addon') ;?></bdi></span></strong>
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
      </div>
   </div>
</div>