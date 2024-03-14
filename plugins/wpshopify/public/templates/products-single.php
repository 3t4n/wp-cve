<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

global $post;

$Products = ShopWP\Factories\Render\Products\Products_Factory::build();
$Reviews = ShopWP\Factories\Render\Reviews\Reviews_Factory::build();
$Settings_General = new ShopWP\DB\Settings_General;

$show_reviews_below_product = $Settings_General->get_col_val('yotpo_reviews', 'bool');

$post_id = $post->ID;

$product_id = get_post_meta($post_id, 'product_id', true);

?>

<section class="wps-container" itemtype="https://schema.org/Product" itemscope>

   <style>

      .wps-container {
         max-width: 1100px;
         width: 100%;
         margin: 0 auto 50px auto;
      }

      .single-wps_products .wps-breadcrumbs + .wps-product-single {
            margin-top: 0;
         }

      .single-wps_products .wps-product-single {
         margin-top: 1em;
         margin-bottom: 0;
         display: flex;
      }

      .single-wps_products .wps-product-single + [data-wpshopify-component] {
         margin-top: -40px;
      }

      .single-wps_products .wps-product-single-content,
      .single-wps_products .wps-product-single-gallery {
         width: 50%;
         max-width: 50%;
         flex: 0 0 50%;
      }

      .single-wps_products .wps-product-single-content {
         padding: 0em 0 2em 2em;
         width: calc(50% - 4em);
         max-width: calc(50% - 4em);
         flex: 0 0 calc(50% - 4em);
      }

      .single-wps_products .wps-component-products-title .wps-products-title {
         margin-top: 0;
         font-size: 34px;
      }

      .single-wps_products .wps-items {
         width: 100%;
         max-width: 100%;
      }

      @media (max-width: 600px) {

         .single-wps_products .wps-product-single + [data-wpshopify-component] {
            margin-top: 0;
         }

         .single-wps_products .wps-product-single {
            flex-direction: column;
         }

         .single-wps_products .wps-container {
            padding: 0 1em;
         }

         .single-wps_products .wps-product-single-content,
         .single-wps_products .wps-product-single-gallery {
            width: 100%;
            max-width: 100%;
            padding: 0;
            flex: none;
         }

         .single-wps_products .wps-product-single .wps-product-image-wrapper .wps-product-image {
            margin: 0 auto;
            display: block;      
         }

      }

   </style>

   <?= do_action('shopwp_breadcrumbs') ?>
   
   <div class="wps-product-single">

      <div class="wps-product-single-gallery">
         <div id="product_gallery"></div>
      </div>

      <div class="wps-product-single-content">
         
         <div id="product_review_rating" itemscope="" itemprop="review" itemtype="https://schema.org/Review"></div>
         <div id="product_title"></div>
         <div id="product_pricing"></div>
         <div id="product_description"></div>
         <div id="product_buy_button"></div>

      </div>

   </div>

   <?php 
   
         $Products->products(
            apply_filters('shopwp_products_single_args', [
               'dropzone_product_buy_button' => '#product_buy_button',
               'dropzone_product_title' => '#product_title',
               'dropzone_product_description' => '#product_description',
               'dropzone_product_pricing' => '#product_pricing',
               'dropzone_product_gallery' => '#product_gallery',
               'link_to' => 'none',
               'excludes' => false,
               'post_id' => $post_id,
               'pagination' => false,
               'show_out_of_stock_variants' => false,
               'limit' => 1,
               'skeleton' => 'components/skeletons/products-single'
            ])
         );

         if (defined('SHOPWP_DOWNLOAD_ID_YOTPO_REVIEWS_EXTENSION') ) {
            $Reviews->reviews(
               apply_filters('shopwp_products_single_args', [
                  'product_id' => $product_id,
                  'dropzone_rating' => '#product_review_rating',
                  'dropzone_listing' => '#product_reviews',
                  'show_rating' => true,
                  'show_create_new' => $show_reviews_below_product ? true : false,
                  'show_listing' => $show_reviews_below_product
               ])
            );
         }

      ?>   

</section>

<?php 

get_footer('wpshopify');