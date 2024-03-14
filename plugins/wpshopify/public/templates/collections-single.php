<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

global $post;

$Collections = ShopWP\Factories\Render\Collections\Collections_Factory::build();

?>

<style>
   .wps-breadcrumbs {
      max-width: 1100px;
      margin: 0 auto;
   }

   .wps-breadcrumbs-name {
         text-transform: capitalize;
      }
      
   .wps-collection-single-content {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
   }

   .wps-collections-wrapper {
      width: 100%;
      max-width: 100%;      
   }

   #collection_products {
      margin-top: 2em;
   }

</style>

<section class="wps-collections-wrapper wps-container">
   <?= do_action('shopwp_breadcrumbs') ?>

   <div class="wps-collection-single row">
      
      <div class="wps-collection-single-content col">

         <div id="collection_image"></div>
         <div id="collection_title"></div>
         <div id="collection_description"></div>
         <div id="collection_products_sorting"></div>
         <div id="collection_products"></div>
         

         <?php 
         
            $Collections->collections(
               apply_filters('shopwp_collections_single_args', [
                  'title' => $post->post_title,
                  'single' => true,
                  'dropzone_collection_image' => '#collection_image',
                  'dropzone_collection_title' => '#collection_title',
                  'dropzone_collection_description' => '#collection_description',
                  'dropzone_collection_products' => '#collection_products',
                  'dropzone_collection_products_sorting' => '#collection_products_sorting',
                  'excludes' => false,
                  'products_excludes' => ['description'],
                  'products_infinite_scroll' => false,
                  'products_sort_by' => 'title',
                  'products_page_size' => 9,
                  'sorting' => true
               ])
            );
         
         ?>

      </div>

   </div>
</section>

<?php 

get_footer('wpshopify');