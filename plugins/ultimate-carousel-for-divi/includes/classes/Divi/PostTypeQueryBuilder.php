<?php
namespace WPT\UltimateDiviCarousel\Divi;

/**
 * PostTypeQueryBuilder.
 */
class PostTypeQueryBuilder
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function get_fields(
        $name,
        $label,
        $tab_slug,
        $toggle_slug,
        $description = '',
        $default = []
    ) {
        $default = $this->setup_args($default);

        $fields = [];

        $fields[$name] = [
            'label'       => esc_html__('Post Type', 'ultimate-carousel-for-divi'),
            'type'        => 'wpdt_post_type_query_builder',
            'options'     => ['' => __('-- Select Post Type --', 'ultimate-carousel-for-divi')] + $this->container['post_types']->get_all_post_types(),
            'tab_slug'    => $tab_slug,
            'toggle_slug' => $toggle_slug,
            'description' => esc_html__($description, 'ultimate-carousel-for-divi'),
            'show_if'     => [],
            // phpcs:ignore
            'default'     => base64_encode(json_encode($default)), // encode/decode contents since they are in array/object format. Divi's serialize fails and errors.
        ];

        return $fields;
    }

    /**
     * Get the posts.
     */
    public function get_posts(
        $name,
        $module
    ) {
        // phpcs:ignore
        $prop_values = json_decode(base64_decode($module->props[$name]), true);

        $prop_values = $this->setup_args($prop_values);

        if (isset($prop_values['post_type']) && $prop_values['post_type']) {
            $args = [
                'post_type'   => $prop_values['post_type'],
                'post_status' => $prop_values['post_status'],
                'orderby'     => $prop_values['orderby'],
                'order'       => $prop_values['order'],
            ];

            // post__in query
            if (trim($prop_values['post__in'])) {
                $args['post__in'] = array_map(function ($val) {
                    return intval(trim($val));
                }, explode(',', $prop_values['post__in']));
            }

            // posts_per_page query
            if (isset($prop_values['posts_per_page'], $prop_values['posts_per_page']['value'])) {
                $args['posts_per_page'] = $prop_values['posts_per_page']['value'];
            }

            // post__not_in query
            if (trim($prop_values['post__not_in'])) {
                $args['post__not_in'] = array_map(function ($val) {
                    return intval(trim($val));
                }, explode(',', $prop_values['post__not_in']));
            }

            // taxonomy query.
            if ($prop_values['filter_by'] != 'none') {
                if ($prop_values['filter_by'] == 'categories') {
                    // phpcs:ignore
                    $args['tax_query'] = [
                        'relation' => 'OR',
                    ];

                    foreach ($prop_values['category_taxonomies'] as $taxonomy) {
                        // phpcs:ignore
                        $args['tax_query'][] = [
                            'taxonomy' => $taxonomy,
                            'field'    => 'id',
                            'terms'    => $prop_values['selected_categories'],
                        ];
                    }
                }

                if ($prop_values['filter_by'] == 'tags') {
                    // phpcs:ignore
                    $args['tax_query'] = [
                        [
                            'taxonomy' => $prop_values['post_type'] . '_tag',
                            'field'    => 'id',
                            'terms'    => $prop_values['selected_tags'],
                        ],
                    ];
                }
            }

            $query = new \WP_Query();

            $posts = $query->query($args);

            return $posts;
        }

        return [];
    }

    public function setup_args($args)
    {
        $args                  = shortcode_atts($this->get_defaults(), $args);
        $args['post_statuses'] = get_post_statuses();
        return $args;
    }

    /**
     * Get defaults for the field.
     */
    public function get_defaults()
    {
        return [
            'post_type'           => '',
            'orderby'             => 'date',
            'order'               => 'ASC',
            'filter_by'           => 'none',
            'categories'          => [],
            'category_taxonomies' => [],
            'selected_categories' => [],
            'post_tags'           => [],
            'selected_tags'       => [],
            'post_status'         => ['publish'],
            'post__in'            => '',
            'post__not_in'        => '',
            'posts_per_page'      => [
                'min'   => -1,
                'max'   => 500,
                'step'  => 1,
                'value' => 12,
            ],
        ];
    }

    /**
     * Initialize the REST API
     */
    public function rest_api_init()
    {
        register_rest_route(
            'wpt-divi-post-type-query-builder/v1',
            '/get_categories_by_rest_api',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'get_categories_by_rest_api'],
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                },
            ]
        );
        register_rest_route(
            'wpt-divi-post-type-query-builder/v1',
            '/get_tags_by_rest_api',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'get_tags_by_rest_api'],
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                },
            ]
        );
    }

    /**
     * Get the categories for a post type via rest api
     */
    public function get_categories_by_rest_api($request)
    {
        if (!$request->has_param('post_type') && $request->get_param('post_type')) {
            return ['success' => false];
        }

        $categories = [];

        $post_type = $request->get_param('post_type');

        $taxonomies = get_object_taxonomies($post_type);

        $index = array_search($post_type . '_format', $taxonomies);
        if ($index !== false) {
            unset($taxonomies[$index]);
        }

        $index = array_search($post_type . '_tag', $taxonomies);
        if ($index !== false) {
            unset($taxonomies[$index]);
        }

        if (!empty($taxonomies)) {
            $terms = get_terms([
                'taxonomy'   => $taxonomies,
                'hide_empty' => false,
            ]);

            foreach ($terms as $term) {
                $categories[$term->term_id] = $term;
            }
        }

        return [
            'success'    => true,
            'categories' => $categories,
        ];
    }

    /**
     * Get the tags for a post type via rest api
     */
    public function get_tags_by_rest_api($request)
    {
        if (!$request->has_param('post_type') && $request->get_param('post_type')) {
            return ['success' => false];
        }

        $tags = [];

        $post_type = $request->get_param('post_type');

        $taxonomies = get_object_taxonomies($post_type);

        $index = array_search($post_type . '_tag', $taxonomies);
        if ($index === false) {
            // tag not present. Ignore request
            return [];
        }

        $terms = get_terms([
            'taxonomy'   => $post_type . '_tag',
            'hide_empty' => false,
        ]);

        foreach ($terms as $term) {
            $tags[$term->term_id] = $term->name;
        }

        return [
            'success'   => true,
            'post_tags' => $tags,
        ];
    }

}
