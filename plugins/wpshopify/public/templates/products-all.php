<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

$Products = ShopWP\Factories\Render\Products\Products_Factory::build();
$Settings = ShopWP\Factories\DB\Settings_General_Factory::build();

$paged_var = get_query_var('paged');
$paged = $paged_var ? $paged_var : 1;
$post_id = $Settings->get_col_val('page_products_default', 'int');
$num_posts = $Settings->get_col_val('num_posts', 'int');

$is_showing_heading = $Settings->get_col_val(
    'products_heading_toggle',
    'bool'
);

$description_toggle = $Settings->get_col_val(
    'products_plp_descriptions_toggle',
    'bool'
);

if (!$description_toggle) {
    $products_args = [
        'excludes' => ['description'],
    ];
} else {
    $products_args = [
        'excludes' => [],
    ];
}

$posts_ids = get_posts([
    'post_type' => 'wps_products',
    'posts_per_page' => -1,
    'fields' => 'ids',
]);

if (!empty($posts_ids)) {
   $products_args['post_id'] = array_values(array_slice($posts_ids, -250, 250, true));
}

$products_args['connective'] = 'OR';
$products_args['page_size'] = $num_posts;
$products_args['available_for_sale'] = true;
$products_args['skeleton'] = 'components/skeletons/products-all';

?>

<style>

   .wps-container {
      max-width: 1100px;
      width: 100%;
      margin: 0 auto 50px auto;
   }
   
   .wps-breadcrumbs {
      max-width: 1100px;
      margin: 0 auto;
   }

   .wps-breadcrumbs-name {
         text-transform: capitalize;
      }

   .wps-products-wrapper {
      display: flex;
      padding: 2em 0;
   }

   .wps-pagination {
      text-align: center;
      padding: 2em 0;
      font-size: 20px;      
   }

   .wps-products-content {
      flex: 1;
   }

   .wps-products-sidebar {
      width: 30%;
   }

   .wps-heading {
      text-align: center;
      margin-bottom: 20px;
   }
</style>

<section class="wps-products-wrapper wps-container">

   <div class="wps-products-content">
      
   <?php if ($is_showing_heading) { ?>

      <header class="wps-products-header">
         <h1 class="wps-heading">
            <?= get_the_title($post_id); ?>
         </h1>
      </header>

   <?php } ?>
      
      <?= do_action('shopwp_breadcrumbs') ?>

         <div class="wps-products-all">
            <?php $Products->products(
                apply_filters('shopwp_products_all_args', $products_args)
            ); ?>
         </div>

   </div>

   <?php if (apply_filters('shopwp_products_show_sidebar', false)) { ?>
      <div class="wps-sidebar wps-products-sidebar">
         <?= get_sidebar('wpshopify') ?>
      </div>
   <?php } ?>

</section>

<?php get_footer('wpshopify');
