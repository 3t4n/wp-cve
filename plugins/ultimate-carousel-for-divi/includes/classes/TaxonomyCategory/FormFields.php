<?php
namespace WPT\UltimateDiviCarousel\TaxonomyCategory;

/**
 * FormFields.
 */
class FormFields
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function admin_init()
    {
        if (is_admin()) {
            $post_type_taxonomies = $this->container['taxonomies']->get_by_post_types();

            // Add image and order fields
            foreach ($post_type_taxonomies as $post_type => $item) {
                foreach ($item['taxonomies'] as $taxonomy) {
                    add_action($taxonomy['value'] . '_add_form_fields', [$this, 'add_category_fields'], 10, 1);
                    add_action($taxonomy['value'] . '_edit_form_fields', [$this, 'edit_category_fields'], 10, 1);
                }
            }

            add_action('created_term', [$this, 'save_category_fields'], 10, 3);
            add_action('edit_term', [$this, 'save_category_fields'], 10, 3);
        }
    }

    /**
     * Save the image and order fields
     */
    public function save_category_fields(
        $term_id,
        $tt_id = '',
        $taxonomy = ''
    ) {

        // phpcs:ignore
        if (isset($_POST['wpt_taxonomy_term_order'])) {
            // phpcs:ignore
            update_term_meta($term_id, 'term_order', intval($_POST['wpt_taxonomy_term_order']));
        }

        // phpcs:ignore
        if (('product_cat' !== $taxonomy) && isset($_POST['wpt_tax_term_thumbnail_id'])) {
            // phpcs:ignore
            update_term_meta($term_id, 'thumbnail_id', absint($_POST['wpt_tax_term_thumbnail_id']));
        }

    }

    public function add_category_fields($taxonomy)
    {
        $type            = 'add';
        $placeholder_img = $this->placeholder_img();

        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/custom-fields/taxonomy-cat-add-form-fields.php';
        echo et_core_intentionally_unescaped(ob_get_clean(), 'html');
    }

    public function edit_category_fields($term)
    {
        $type            = 'edit';
        $taxonomy        = $term->taxonomy;
        $placeholder_img = $this->placeholder_img();
        $thumbnail_id    = absint(get_term_meta($term->term_id, 'thumbnail_id', true));
        $term_order      = intval(get_term_meta($term->term_id, 'term_order', true));

        if ($thumbnail_id) {
            $image = wp_get_attachment_thumb_url($thumbnail_id);
        } else {
            $image = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : $this->container['plugin_url'] . '/images/placeholder.png';
        }

        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/custom-fields/taxonomy-cat-edit-form-fields.php';
        echo et_core_intentionally_unescaped(ob_get_clean(), 'html');
    }

    public function placeholder_img()
    {
        return $this->container['plugin_url'] . '/images/placeholder-2.png';
    }

}
