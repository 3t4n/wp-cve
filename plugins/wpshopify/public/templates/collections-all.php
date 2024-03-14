<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

$Collections = ShopWP\Factories\Render\Collections\Collections_Factory::build();
$Settings = ShopWP\Factories\DB\Settings_General_Factory::build();
$post_id = $Settings->get_col_val('page_collections_default', 'int');
$selection_collections = $Settings->get_col_val('sync_by_collections', 'string');

$selection_collections_pretty = maybe_unserialize($selection_collections);

if (empty($selection_collections_pretty)) {
   $title_query = false;
   $titles = false;

} else {
   $titles = array_reduce($selection_collections_pretty, function($prev, $curr) {
      array_push($prev, $curr['title']);

      return $prev;
   }, []);
}

$params = [
   'link_target' => '_self',
   'link_to' => 'wordpress',
   'reverse' => true,
   'connective' => 'OR',
   'title' => $titles ? $titles : false
];

$is_showing_heading = $Settings->get_col_val(
    'collections_heading_toggle',
    'bool'
);

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

   .wps-heading {
      text-align: center;
   }

</style>

<section class="wps-container">
   <?= do_action('shopwp_breadcrumbs') ?>

   <div class="wps-collections-all">
      
      <?php
      if ($is_showing_heading) { ?>
         <h1 class="wps-heading">
            <?= get_the_title($post_id); ?>
         </h1>
      <?php }

      $Collections->collections(
          apply_filters('wps_collections_all_args', $params)
      );
      ?>

   </div>

</section>


<?php get_footer('wpshopify');
