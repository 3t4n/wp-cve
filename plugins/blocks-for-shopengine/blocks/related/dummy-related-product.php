
<?php 
defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper; ?>

<div class="shopengine shopengine-widget">

   <div class="shopengine-related <?php echo($is_slider_enable ? 'slider-enabled' : 'slider-disabled'); ?>"
      data-controls="<?php echo esc_attr($encode_slider_options); ?>">

      <section
         class="related products">

         <?php         
               $related_products = [];
               $product_qty = $settings["shopengine_products_to_show"]["desktop"];

               if ( !empty( get_option('shopengine_product_id') ) ) {
                  $related_products = wc_get_related_products( get_option('shopengine_product_id'), $product_qty );
               }
         ?>

         <h2><?php esc_html_e('Related products ', 'shopengine-gutenberg-addon') ;?></h2>

         <ul class="products columns-4">

         <?php 

            foreach($related_products as $product):
               $product = wc_get_product($product);
               $price      = wc_price( $product->get_price() );
               $reg_price  = wc_price( $product->get_regular_price() ); 

         ?>

            <li class="product type-product post-42 status-publish first instock product_cat-uncategorized product_tag-hot product_tag-indoor-chair product_tag-sofa has-post-thumbnail shipping-taxable product-type-grouped swiper-slide-active">
               <a href="javascript:void(0)"
                  class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                  <?php shopengine_content_render($product->get_image()); ?>
                  <h2 class="woocommerce-loop-product__title"><?php echo esc_html($product->get_name()) ;?></h2>
                 
                  <?php if ( $product->is_type( 'variable' ) ) { ?>
                     <div class="price">
                        <ins><span class="woocommerce-Price-amount amount"><?php echo wp_kses(wc_price($product->get_variation_price('min')). ' - '.wc_price($product->get_variation_price('max')), \ShopEngine\Utils\Helper::get_kses_array()); ?> </span></ins>
                     </div>
                  <?php }else{ ?>
                     
                  <span class="price">
                     <span class="woocommerce-Price-amount amount">
                        <bdi><?php echo wp_kses($price, Helper::get_kses_array());?></bdi>
                     </span>
                     <?php if( !empty($price) && ( $reg_price != $price )  ) : ?>
                        <del>
                           <span class="woocommerce-Price-amount amount">
                              <?php echo wp_kses($reg_price, Helper::get_kses_array()); ?>
                           </span>
                        </del>
                     <?php endif; ?>
                  </span>
                  <?php } ?>
               </a>
               <a class="shopengine_add_to_list_action shopengine-wishlist badge se-btn inactive"
                  href="#"><i class="shopengine-icon-add_to_favourite_1"></i>
               </a>
               <a
                  class="shopengine-quickview-trigger se-btn"
                  href="javascript:void(0)">
                  <i class="shopengine-icon-quick_view_1"></i>
               </a>
               <a href="javascript:void(0)"
                  class="button product_type_grouped"
                  aria-label="<?php esc_attr_e("View products in the “SANDA Top White Men's Sports” group", 'shopengine-gutenberg-addon' ); ?>" rel="nofollow"><?php esc_html_e('View products ', 'shopengine-gutenberg-addon') ;?>
               </a>
               <a href="javascript:void(0)"
                  class="shopengine_comparison_add_to_list_action shopengine-comparison badge se-btn inactive"> <i
                     class="shopengine-icon-product_compare_1"></i> 
               </a>
            </li>
         <?php endforeach; ?>
         </ul>

         <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
      </section>

      
   </div>
</div>