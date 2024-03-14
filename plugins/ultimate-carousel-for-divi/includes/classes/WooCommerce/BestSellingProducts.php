<?php
namespace WPT\UltimateDiviCarousel\WooCommerce;

use WP_Query;

/**
 * BestSellingProducts.
 */
class BestSellingProducts
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
            'meta_key'       => 'total_sales', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'order'          => 'DESC',
            'orderby'        => 'meta_value_num',
        ];

        $query    = new WP_Query($args);
        $products = $query->posts;

        return $products;

    }

}
