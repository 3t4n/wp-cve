<?php
namespace WPT\UltimateDiviCarousel\WooCommerce;

use WP_Query;

/**
 * CustomFilterProducts.
 */
class CustomFilterProducts
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
        $posts_per_page,
        $orderby,
        $order,
        $props
    ) {
        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_per_page,
            'order'          => $order,
            'orderby'        => $orderby,
        ];

        // post__in query
        if (trim($props['post__in'])) {
            $args['post__in'] = array_map(function ($val) {
                return intval(trim($val));
            }, explode(',', $props['post__in']));
        }

        // post__not_in query
        if (trim($props['post__not_in'])) {
            $args['post__not_in'] = array_map(function ($val) {
                return intval(trim($val));
            }, explode(',', $props['post__not_in']));
        }

        if (trim($props['categories'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => explode(',', $props['categories']),
            ];
        }

        if (isset($props['tags']) && trim($props['tags'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => explode(',', $props['tags']),
            ];
        }

        $query    = new WP_Query($args);
        $products = $query->posts;

        return $products;
    }

}
