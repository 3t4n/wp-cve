<?php

if (!defined('ABSPATH')) {
    exit;
}
/**
 * ShopSidebar Layer Nav
 * @since 1.0
 */
global $woocommerce;

$_chosen_attributes = $woocommerce->query::get_layered_nav_chosen_attributes();
$min_price = isset($_GET['min_price']) ? wp_unslash(sanitize_text_field($_GET['min_price'])) : 0; // WPCS: input var ok, CSRF ok.
$max_price = isset($_GET['max_price']) ? wp_unslash(sanitize_text_field($_GET['max_price'])) : 0; // WPCS: input var ok, CSRF ok.
$rating_filter = isset($_GET['rating_filter']) ? array_filter(array_map('absint', explode(',', sanitize_text_field($_GET['rating_filter'])))) : array();
$base_link = isset($base_link)?$base_link:'';

if (0 < count($_chosen_attributes) || 0 < $min_price || 0 < $max_price || !empty($rating_filter)) {


    echo wp_kses_post('<ul>');

    // Attributes.
    if (!empty($_chosen_attributes)) {
        foreach ($_chosen_attributes as $taxonomy => $data) {
            foreach ($data['terms'] as $term_slug) {
                $term = get_term_by('slug', $term_slug, $taxonomy);
                if (!$term) {
                    continue;
                }

                $filter_name = 'filter_' . wc_attribute_taxonomy_slug($taxonomy);
                $current_filter = isset($_GET[$filter_name]) ? explode(',', wc_clean(sanitize_text_field($_GET[$filter_name]))) : array(); // WPCS: input var ok, CSRF ok.
                $current_filter = array_map('sanitize_title', $current_filter);
                $new_filter = array_diff($current_filter, array($term_slug));

                $link = remove_query_arg(array('add-to-cart', $filter_name), $base_link);

                if (count($new_filter) > 0) {
                    $link = add_query_arg($filter_name, implode(',', $new_filter), $link);
                }

                $filter_classes = array('chosen', 'chosen-' . sanitize_html_class(str_replace('pa_', '', $taxonomy)), 'chosen-' . sanitize_html_class(str_replace('pa_', '', $taxonomy) . '-' . $term_slug));

                echo wp_kses_post('<li class="' . esc_attr(implode(' ', $filter_classes)) . '"><a rel="nofollow" aria-label="' . esc_attr__('Remove filter', 'shopready-elementor-addon') . '" href="' . esc_url($link) . '">' . esc_html($term->name) . '</a></li>');
            }
        }
    }

    if ($min_price) {
        $link = remove_query_arg('min_price', $base_link);
        /* translators: %s: minimum price */
        echo wp_kses_post('<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__('Remove filter', 'shopready-elementor-addon') . '" href="' . esc_url($link) . '">' . sprintf(__('Min %s', 'shopready-elementor-addon'), wc_price($min_price)) . '</a></li>'); // WPCS: XSS ok.
    }

    if ($max_price) {
        $link = remove_query_arg('max_price', $base_link);
        /* translators: %s: maximum price */
        echo wp_kses_post('<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__('Remove filter', 'shopready-elementor-addon') . '" href="' . esc_url($link) . '">' . sprintf(__('Max %s', 'shopready-elementor-addon'), wc_price($max_price)) . '</a></li>'); // WPCS: XSS ok.
    }

    if (!empty($rating_filter)) {
        foreach ($rating_filter as $rating) {
            $link_ratings = implode(',', array_diff($rating_filter, array($rating)));
            $link = $link_ratings ? add_query_arg('rating_filter', $link_ratings) : remove_query_arg('rating_filter', $base_link);

            /* translators: %s: rating */
            echo wp_kses_post('<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__('Remove filter', 'shopready-elementor-addon') . '" href="' . esc_url($link) . '">' . sprintf(esc_html__('Rated %s out of 5', 'shopready-elementor-addon'), esc_html($rating)) . '</a></li>');
        }
    }

    echo wp_kses_post('</ul>');


}