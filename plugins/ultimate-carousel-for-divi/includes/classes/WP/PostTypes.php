<?php
namespace WPT\UltimateDiviCarousel\WP;

/**
 * PostTypes.
 */
class PostTypes
{
    /**
     * @var mixed
     */
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
        add_filter('register_post_type_args', [$this, 'register_post_type_args_filter'], 10, 2);
    }

    /**
     * Get the available post type definitions.
     */
    public function get_all_post_types()
    {
        // et_builder_get_public_post_types() use this.

        $post_types = [];

        $all_post_types = get_post_types([], 'objects');
        if (!empty($all_post_types)) {
            $post_types = array_merge($post_types, $all_post_types);
        }

        $response = [];

        foreach ($post_types as $post_type) {
            $response[$post_type->name] = $post_type->label;
        }

        return $response;
    }

    /**
     * Get taxonomies
     */
    public function get_taxonomies()
    {
        $postTypeTaxonomies = get_taxonomies([], 'objects');

        foreach ($postTypeTaxonomies as $taxonomy) {
            $taxonomies[$taxonomy->name] = ucwords($taxonomy->label);
        }

        return $taxonomies;
    }

    public function get_featured_image($post_id)
    {
        $attachment_id  = get_post_thumbnail_id($post_id);
        $featured_image = $this->container['plugin_url'] . '/images/placeholder.png';
        if ($attachment_id) {
            $featured_image = wp_get_attachment_image_url($attachment_id, '');
        }

        return $featured_image;
    }

    public function register_post_type_args_filter(
        $args,
        $post_type
    ) {
        if (!isset($args['supports'])) {
            $args['supports'] = [];
        }

        if ($args['supports'] !== false) {
            $args['supports'][] = 'page-attributes';
        }

        return $args;
    }

}
