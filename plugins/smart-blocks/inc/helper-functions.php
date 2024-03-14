<?php

if (!function_exists('smart_blocks_custom_excerpt')) {

    function smart_blocks_custom_excerpt($limit) {
        if ($limit) {
            $content = get_the_content();
            $content = strip_tags($content);
            $content = strip_shortcodes($content);
            $excerpt = mb_substr($content, 0, $limit);

            if (strlen($content) >= $limit) {
                $excerpt = $excerpt . '...';
            }

            return $excerpt;
        }
    }

}

if (!function_exists('smart_blocks_author_name')) {

    function smart_blocks_author_name($class = '') {
        return '<span class="sb-post-author ' . $class . '"><i class="mdi-account"></i>' . get_the_author() . '</span>';
    }

}

/** Get Comment Count */
if (!function_exists('smart_blocks_comment_count')) {

    function smart_blocks_comment_count($class = '') {
        return '<span class="sb-post-comment ' . esc_attr($class) . '"><i class="mdi-comment-outline"></i>' . get_comments_number() . '</span>';
    }

}


if (!function_exists('smart_blocks_post_date')) {

    function smart_blocks_post_date($format = '', $class = '') {

        if ($format) {
            return '<span class="sb-post-date ' . $class . '"><i class="mdi-clock-time-four-outline"></i>' . get_the_date($format) . '</span>';
        } else {
            return '<span class="sb-post-date ' . $class . '"><i class="mdi-clock-time-four-outline"></i>' . get_the_date() . '</span>';
        }
    }

}


if (!function_exists('smart_blocks_time_ago')) {

    function smart_blocks_time_ago($class = '') {
        return '<span class="sb-post-date ' . $class . '"><i class="mdi-clock-time-four-outline"></i>' . human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'hash-elements') . '</span>';
    }

}

if (!function_exists('smart_blocks_get_the_primary_category')) {

    function smart_blocks_get_the_primary_category($class = "post-categories", $link_class = '') {
        $post_categories = smart_blocks_get_post_primary_category(get_the_ID());
        $content = '';

        if (!empty($post_categories)) {
            $category_obj = $post_categories['primary_category'];
            $category_link = get_category_link($category_obj->term_id);
            $content .= '<ul class="' . esc_attr($class) . '">';
            $content .= '<li><a class="sb-primary-cat sb-category-' . esc_attr($category_obj->term_id)  . ' ' . $link_class . '" href="' . esc_url($category_link) . '">' . esc_html($category_obj->name) . '</a></li>';
            $content .= '</ul>';
        }
        return $content;
    }

}

if (!function_exists('smart_blocks_get_post_primary_category')) {

    function smart_blocks_get_post_primary_category($post_id, $term = 'category', $return_all_categories = false) {
        $return = array();

        if (class_exists('WPSEO_Primary_Term')) {
            // Show Primary category by Yoast if it is enabled & set
            $wpseo_primary_term = new WPSEO_Primary_Term($term, $post_id);
            $primary_term = get_term($wpseo_primary_term->get_primary_term());

            if (!is_wp_error($primary_term)) {
                $return['primary_category'] = $primary_term;
            }
        }

        if (empty($return['primary_category']) || $return_all_categories) {
            $categories_list = get_the_terms($post_id, $term);

            if (empty($return['primary_category']) && !empty($categories_list)) {
                $return['primary_category'] = $categories_list[0];  //get the first category
            }

            if ($return_all_categories) {
                $return['all_categories'] = array();

                if (!empty($categories_list)) {
                    foreach ($categories_list as &$category) {
                        $return['all_categories'][] = $category->term_id;
                    }
                }
            }
        }

        return $return;
    }

}

if (!function_exists('sb_css_strip_whitespace')) {

    function sb_css_strip_whitespace($css) {
        $replace = array(
            "#/\*.*?\*/#s" => "", // Strip C style comments.
            "#\s\s+#" => " ", // Strip excess whitespace.
        );
        $search = array_keys($replace);
        $css = preg_replace($search, $replace, $css);

        $replace = array(
            ": " => ":",
            "; " => ";",
            " {" => "{",
            " }" => "}",
            ", " => ",",
            "{ " => "{",
            ";}" => "}", // Strip optional semicolons.
            ",\n" => ",", // Don't wrap multiple selectors.
            "\n}" => "}", // Don't wrap closing braces.
            "} " => "}\n", // Put each rule on it's own line.
        );
        $search = array_keys($replace);
        $css = str_replace($search, $replace, $css);

        return trim($css);
    }

}

if (!function_exists('sb_is_taxonomy_assigned_to_post_type')) {

    function sb_is_taxonomy_assigned_to_post_type($post_type, $taxonomy = null) {
        if (is_object($post_type))
            $post_type = $post_type->post_type;

        if (empty($post_type))
            return false;

        $taxonomies = get_object_taxonomies($post_type);

        if (empty($taxonomy))
            $taxonomy = get_query_var('taxonomy');

        return in_array($taxonomy, $taxonomies);
    }

}

/**
 * Returns the custom post types.
 *
 * @return array
 */
function sb_get_CPTs() {
    return get_post_types(array('_builtin' => false, 'public' => true));
}

/**
 * Returns the relative dates of the post.
 *
 * @return array
 */
function sb_get_relative_dates($post) {
    return array(
        'created' => human_time_diff(get_the_date('U', $post['id'])) . ' ' . __('ago', 'smart-blocks'),
        'modified' => human_time_diff(get_the_modified_date('U', $post['id'])) . ' ' . __('ago', 'smart-blocks')
    );
}


function smart_blocks_get_font_class($attr) {
    $retrun_classes = array();
    if (isset($attr['family']) && (strtolower($attr['family']) != 'inherit')) {
        $retrun_classes[] = 'sb-ff';
    }
    if (isset($attr['weight']) && (strtolower($attr['weight']) != 'inherit')) {
        $retrun_classes[] = 'sb-fw';
    }
    if (isset($attr['textTransform']) && (strtolower($attr['textTransform']) != 'inherit')) {
        $retrun_classes[] = 'sb-tt';
    }
    if (isset($attr['textDecoration']) && (strtolower($attr['textDecoration']) != 'inherit')) {
        $retrun_classes[] = 'sb-td';
    }
    return implode(' ', $retrun_classes);
}