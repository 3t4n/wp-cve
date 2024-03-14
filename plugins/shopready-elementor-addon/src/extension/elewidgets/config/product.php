<?php
if (!defined('ABSPATH')) {
     exit;
}
/************************** ***************** 
 * 
 * all Widgets Meta and category settings 
 * since 1.0
 * Registerd Widget Category for Widget Config
 *
 ***********************************************/
return [

     'comming_soon' => [

          '_woo_ready_product_comming_soon' => [
               'id' => '_woo_ready_product_comming_soon',
               'wrapper_class' => '',
               'label' => __('Comming Soon', 'shopready-elementor-addon'),
               'description' => __('Comming Soon', 'shopready-elementor-addon')
          ],
          '_woo_ready_product_comming_soon_expire_date' => [
               'id' => '_woo_ready_product_comming_soon_expire_date',
               'wrapper_class' => '',
               'label' => __('Expire Date', 'shopready-elementor-addon'),
               'description' => __('Comming Soon Expire date', 'shopready-elementor-addon')
          ],
          '_woo_ready_product_comming_soon_expire_time' => [
               'id' => '_woo_ready_product_comming_soon_expire_time',
               'wrapper_class' => '',
               'label' => __('Expire Time', 'shopready-elementor-addon'),
               'description' => __('Comming Soon Expire Time', 'shopready-elementor-addon')
          ]
     ]

];