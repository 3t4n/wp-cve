<?php
namespace WPT\UltimateDiviCarousel\WooCommerce;

use WP_Query;

/**
 * RecentProducts.
 */
class RecentProducts
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
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $query    = new WP_Query($args);
        $products = $query->posts;

        return $products;

    }

}
