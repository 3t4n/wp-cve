<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );


function custom_pc_builder_lite_generate_paginator($query, $currenPage)
    {

        if ($query->max_num_pages <= 1)
            return;
        $max = intval($query->max_num_pages);

        if ($currenPage >= 1)
            $links[] = $currenPage;

        if ($currenPage >= 3) {
            $links[] = $currenPage - 1;
            $links[] = $currenPage - 2;
        }
        if (($currenPage + 2) <= $max) {
            $links[] = $currenPage + 2;
            $links[] = $currenPage + 1;
        }
        echo '<ul class="pagination">';

        if (get_previous_posts_link())
            printf('<li>%s</li>' . "\n", get_previous_posts_link('<<'));


        if (!in_array(1, $links)) {
            $class = 1 == $currenPage ? ' class="active"' : '';
            printf('<li %s><a rel="nofollow" class="page larger" data-page="1" href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');
            if (!in_array(2, $links))
                echo '<li>…</li>';
        }

        sort($links);
        foreach ((array)$links as $link) {
            $class = $currenPage == $link ? ' class="active"' : '';
            printf('<li%s><a rel="nofollow" class="page larger" data-page="%s" href="%s">%s</a></li>' . "\n", $class, $link, esc_url(get_pagenum_link($link)), $link);
        }

        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links))
                echo '<li>…</li>' . "\n";
            $class = $currenPage == $max ? ' class="active"' : '';
            printf('<li%s><a rel="nofollow" class="page larger" data-page="%s" href="%s">%s</a></li>' . "\n", $class, $max, esc_url(get_pagenum_link($max)), $max);
        }

        if (get_next_posts_link())
            printf('<li>%s</li>' . "\n", get_next_posts_link('>>'));
        echo '</ul>';
    }
function custom_pc_builder_lite_get_value_by_key($key, $array)
{
    foreach ($array as $item) {
        if ($item['name'] == $key) return $item['value'];
    }
    return '';
}
function custom_pc_builder_lite_post_title_filter($where, $wp_query)
{
    global $wpdb;
    if ($keyword = $wp_query->get('nk_search_post_title')) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like($keyword) . '%\'';
    }
    return $where;
}
function custom_pc_builder_lite_unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();
    foreach ($array as $k => $val) {
        if (!in_array($val[sanitize_key($key)], $key_array) && $k < 7) {
            $key_array[$i] = $val[sanitize_key($key)];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}