<?php

namespace wobel\classes\repositories;

class Product
{
    public function get_products($args)
    {
        $posts = new \WP_Query($args);
        return $posts;
    }

    public function get_taxonomies($output = "object")
    {
        return get_object_taxonomies(['product'], sanitize_text_field($output));
    }

    public function get_grouped_taxonomies()
    {
        $output['taxonomy'] = [];
        $output['attribute'] = [];
        $vain_taxonomies = $this->get_vain_taxonomies();
        $taxonomies = $this->get_taxonomies();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if (!in_array($taxonomy->name, $vain_taxonomies)) {
                    $tax_type = (substr($taxonomy->name, 0, 3) == 'pa_') ? 'attribute' : 'taxonomy';
                    $output[$tax_type][$taxonomy->name] = $taxonomy;
                }
            }
        }

        return $output;
    }

    public function get_object_taxonomies()
    {
        return $this->unset_vain_taxonomies(get_object_taxonomies(['product'], 'object'));
    }

    private function unset_vain_taxonomies($taxonomies)
    {
        $vain_taxonomies = $this->get_vain_taxonomies();
        if (!empty($vain_taxonomies)) {
            foreach ($vain_taxonomies as $vain_taxonomy) {
                if (isset($taxonomies[$vain_taxonomy])) {
                    unset($taxonomies[$vain_taxonomy]);
                }
            }
        }
        return $taxonomies;
    }

    private function get_vain_taxonomies()
    {
        return [
            'product_type',
            'product_visibility',
            'product_shipping_class',
        ];
    }

    public function get_taxonomies_by_name($name)
    {
        $output = [];
        $taxonomies = $this->get_taxonomies('names');
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $output[$taxonomy] = get_terms([
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false,
                    'name__like' => strtolower(sanitize_text_field($name))
                ]);
            }
        }
        return $output;
    }

    public function get_tags_by_name($name)
    {
        return get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
            'name__like' => strtolower(sanitize_text_field($name))
        ]);
    }

    public function get_categories_by_name($name)
    {
        return get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'name__like' => strtolower(sanitize_text_field($name))
        ]);
    }

    public function get_categories_by_id($category_ids)
    {
        if (empty($category_ids)) {
            return null;
        }
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'include' => array_map('intval', $category_ids),
            'hide_empty' => false,
            'fields' => 'id=>name'
        ]);

        return $categories;
    }

    public function get_tags_by_id($tag_ids)
    {
        if (empty($tag_ids)) {
            return null;
        }
        $tags = get_terms([
            'taxonomy' => 'product_tag',
            'include' => array_map('intval', $tag_ids),
            'hide_empty' => false,
            'fields' => 'id=>name'
        ]);
        return $tags;
    }

    public function get_taxonomies_by_id($taxonomies_ids)
    {
        if (empty($taxonomies_ids)) {
            return null;
        }

        $output = [];
        $taxonomies = get_terms([
            'include' => array_map('intval', $taxonomies_ids),
            'hide_empty' => false,
        ]);
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy instanceof \WP_Term) {
                    $output[$taxonomy->taxonomy . '__' . $taxonomy->term_id] = $taxonomy->taxonomy . ': ' . $taxonomy->name;
                }
            }
        }
        return $output;
    }
}
