<?php
namespace WPT\UltimateDiviCarousel\WP;

/**
 * Taxonomies.
 */
class Taxonomies
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function init()
    {
        add_action('registered_taxonomy', [$this, 'clear_cache']);
    }

    /**
     * Get all taxonomies.
     */
    public function get_all()
    {
        $cache_key = 'wpt_taxonomies_all_cache';

        $taxonomies = wp_cache_get($cache_key);

        if ($taxonomies === false) {
            $all_taxonomies = get_taxonomies([], 'objects');

            foreach ($all_taxonomies as $taxonomy) {
                $taxonomies[$taxonomy->name] = ucwords($taxonomy->label);
            }

            wp_cache_set($cache_key, $taxonomies);
        }

        return apply_filters($cache_key, $taxonomies);
    }

    /**
     * Get taxonomies by post types
     */
    public function get_by_post_types()
    {
        $cache_key = 'wpt_taxonomies_by_post_types_cache';

        $taxonomies = wp_cache_get($cache_key);
        if ($taxonomies === false) {
            $taxonomies = [];

            $post_types = $this->container['post_types']->get_all_post_types();

            foreach ($post_types as $name => $label) {
                $all_taxonomies = get_object_taxonomies($name);

                $taxonomies[$name] = [
                    'name'       => $label,
                    'taxonomies' => [],
                ];

                foreach ($all_taxonomies as $taxonomy_name) {
                    $taxonomy = get_taxonomy($taxonomy_name);

                    $taxonomies[$name]['taxonomies'][] = [
                        'value' => $taxonomy_name,
                        'label' => ucwords($taxonomy->label),
                    ];
                }
            }

            wp_cache_set($cache_key, $taxonomies);
        }

        return apply_filters($cache_key, $taxonomies);
    }

    /**
     * Clear cache.
     */
    public function clear_cache()
    {
        wp_cache_delete('wpt_taxonomies_all_cache');
        wp_cache_delete('wpt_taxonomies_by_post_types_cache');
    }

}
