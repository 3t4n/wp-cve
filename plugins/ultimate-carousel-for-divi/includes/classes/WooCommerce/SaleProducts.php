<?php
namespace WPT\UltimateDiviCarousel\WooCommerce;

use WP_Query;

/**
 * SaleProducts.
 */
class SaleProducts
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Get the featured products
     */
    public function get(
        $limit,
        $props = []
    ) {
        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        if (function_exists('wc_get_product_ids_on_sale')) {
            $args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());
        }

        $query    = new WP_Query($args);
        $products = $query->posts;
        return $products;
    }

}
