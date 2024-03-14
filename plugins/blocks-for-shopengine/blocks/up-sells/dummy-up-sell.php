<?php
defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper; ?>
<section class="up-sells upsells products">
   <h2><?php esc_html_e('You may also like…', 'shopengine-gutenberg-addon'); ?></h2>
   <ul class="products columns-4">

   <?php 
      $args = array(
         'limit' => $limit,
         'include' => $upsell_ids,
         'orderby' =>  $orderby,
         'order' => $order,
      );

      if($orderby == "price"){
         $args = array(
            'limit' => $limit,
            'include' => $upsell_ids,
            'orderby'   => 'meta_value_num',
            'meta_key'  => '_price',
            'order' => $order,
         );
      }

      $products = wc_get_products( $args );

      foreach($products as $single_product):

         $price      = wc_price( $single_product->get_price() );
         $reg_price  = wc_price( $single_product->get_regular_price() ); 
   ?>
      <li class="product type-product post-30 status-publish first instock product_cat-bathroom product_cat-carpets product_cat-fireplaces product_cat-furniture product_cat-household-items product_cat-indoor-chair product_tag-new product_tag-technologies product_tag-wireless has-post-thumbnail sale shipping-taxable purchasable product-type-simple swiper-slide-duplicate-next">
         <a href="javascript:void(0)" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
           
            <?php if( $single_product->is_on_sale()): ?>
                <span class="onsale"> <?php esc_html_e('Sale!', 'shopengine-gutenberg-addon'); ?></span>
            <?php endif; ?>

            <?php shopengine_content_render($single_product->get_image()); ?>

            <h2 class="woocommerce-loop-product__title">
               <?php echo esc_html( $single_product->get_title()); ?> 
            </h2>
            <span class="price">
            <?php if ( $single_product->is_type( 'variable' ) ){ ?>
               <ins>
                  <span class="woocommerce-Price-amount amount">
                     <bdi><?php echo wp_kses(wc_price($single_product->get_variation_price('min')). ' - '.wc_price($single_product->get_variation_price('max')), Helper::get_kses_array()); ?> </bdi>
                  </span>
               </ins>
            <?php } else { ?>
               <?php if( !empty($price) && ( $reg_price != $price )  ) : ?>
               <del aria-hidden="true">
                  <span class="woocommerce-Price-amount amount">
                     <bdi><?php echo wp_kses($reg_price, Helper::get_kses_array()); ?></bdi>
                  </span>
               </del>
               <?php endif; ?>
               <ins>
                  <span class="woocommerce-Price-amount amount">
                     <bdi><?php echo wp_kses($price, Helper::get_kses_array()); ?></bdi>
                  </span>
               </ins>
               <?php } ?>
            </span>
         </a>
         <a class="shopengine_add_to_list_action shopengine-wishlist badge se-btn inactive" href="#"><i class="shopengine-icon-add_to_favourite_1"></i>
         </a>
         <a class="shopengine-quickview-trigger se-btn" href="javascript:void(0)">
            <i class="shopengine-icon-quick_view_1"></i>
         </a>
         <a href="javascript:void(0)" class="button product_type_simple add_to_cart_button ajax_add_to_cart" aria-label="Add “Wireless LCD Audio Video…” to your cart" rel="nofollow"><?php esc_html_e('Add to cart', 'shopengine-gutenberg-addon'); ?></a>
         <a href="javascript:void(0)" class="shopengine_comparison_add_to_list_action shopengine-comparison badge se-btn inactive"> <i class="shopengine-icon-product_compare_1"></i>
         </a>
      </li>

   <?php endforeach; ?>
   </ul>
   <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
</section>