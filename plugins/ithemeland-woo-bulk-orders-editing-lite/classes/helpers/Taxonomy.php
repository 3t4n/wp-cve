<?php

namespace wobel\classes\helpers;

use wobel\classes\lib\WobelOrdersTaxonomyWalker;

class Taxonomy
{
    public static function wobel_order_taxonomy_list($taxonomy = 'order_cat', array $checked = [], $args = [])
    {
        $defaults = array(
            'taxonomy' => esc_sql($taxonomy),
            'hide_empty' => false,
            'show_option_none' => '',
            'echo' => 0,
            'depth' => 0,
            'wrap_class' => 'wobel-orders-category-list',
            'level_class' => '',
            'parent_title_format' => '%s',
        );
        $args = wp_parse_args($args, $defaults);
        if (!taxonomy_exists($args['taxonomy'])) {
            return false;
        }
        $categories = get_categories($args);
        $output = "<ul class='" . sanitize_text_field($args['wrap_class']) . "'>";
        if (empty($categories)) {
            if (!empty($args['show_option_none'])) {
                $output .= "<li>" . sanitize_text_field($args['show_option_none']) . "</li>";
            }
        } else {
            $walker = new WobelOrdersTaxonomyWalker($checked);
            $output .= $walker->walk($categories, $args['depth'], $args);
        }
        $output .= "</ul>";
        return $output;
    }
}
