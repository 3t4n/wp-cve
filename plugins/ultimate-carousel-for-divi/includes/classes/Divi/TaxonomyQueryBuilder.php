<?php
namespace WPT\UltimateDiviCarousel\Divi;

/**
 * TaxonomyQueryBuilder.
 */
class TaxonomyQueryBuilder
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
            'type'        => 'wpdt_taxonomy_query_builder',
            'options'     => $this->container['taxonomies']->get_by_post_types(),
            'tab_slug'    => $tab_slug,
            'toggle_slug' => $toggle_slug,
            'description' => esc_html__($description, 'ultimate-carousel-for-divi'),
            'show_if'     => [],
            // phpcs:ignore
            'default'     => base64_encode(json_encode($default)),
        ];

        return $fields;
    }

    /**
     * Get the terms for the taxonomy
     * https://developer.wordpress.org/reference/classes/wp_term_query/__construct/
     */
    public function get_terms(
        $name,
        $module
    ) {
        // phpcs:ignore
        $prop_values = json_decode(base64_decode($module->props[$name]), true);

        $prop_values = $this->setup_args($prop_values);

        if (isset($prop_values['selected_post_type'], $prop_values['selected_taxonomy']) && $prop_values['selected_post_type'] && $prop_values['selected_taxonomy']) {
            $args = [
                'taxonomy' => $prop_values['selected_taxonomy'],
                'orderby'  => $prop_values['orderby'],
                'order'    => $prop_values['order'],
                'number'   => $prop_values['number']['value'],
            ];

            if ($args['orderby'] == 'meta_value_num') {
                // phpcs:ignore
                $args['meta_query'] = [
                    [
                        'key'  => 'term_order',
                        'type' => 'NUMERIC',
                    ],
                ];
            }

            if (trim($prop_values['include'])) {
                $args['include'] = trim($prop_values['include']);
            }

            if (trim($prop_values['exclude'])) {
                $args['exclude'] = trim($prop_values['exclude']);
            }

            $terms = get_terms($args);
            return $terms;
        }

        return [];
    }

    public function setup_args($args)
    {
        $args = shortcode_atts($this->get_defaults(), $args);
        return $args;
    }

    /**
     * Get defaults for the field.
     */
    public function get_defaults()
    {
        return [
            'selected_post_type' => '',
            'selected_taxonomy'  => '',
            'orderby'            => 'name',
            'order'              => 'ASC',
            'include'            => '',
            'exclude'            => '',
            'number'             => [
                'min'   => 0,
                'max'   => 500,
                'step'  => 1,
                'value' => 12,
            ],
        ];
    }

    public function get_image_url($term_id)
    {
        $featured_image = $this->container['plugin_url'] . '/images/placeholder.png';
        $thumbnail_id   = absint(get_term_meta($term_id, 'thumbnail_id', true));

        if ($thumbnail_id) {
            $featured_image = wp_get_attachment_image_url($thumbnail_id, '');
        }

        return $featured_image;
    }

}
