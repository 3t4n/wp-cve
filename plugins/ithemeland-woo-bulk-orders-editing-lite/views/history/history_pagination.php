<?php

if (!empty($history_count)) {
    $per_page = (!empty($per_page)) ? $per_page : 10;
    $current_page = (!empty($current_page)) ? $current_page : 1;
    $max_num_pages = ($history_count > $per_page) ? ceil($history_count / $per_page) : 1;
    $prev = max(1, $current_page - 1);
    $next = min($max_num_pages, $current_page + 1);
    $max_display = 3;

    $pagination = "<div>";
    if (isset($max_num_pages)) {
        $pagination .= "<a href='javascript:;' data-index='" . esc_attr($prev) . "' class='wobel-history-pagination-item'><</a>";
        if ($current_page < $max_display) {
            for ($i = 1; $i <= min($max_display, $max_num_pages); $i++) {
                $current = ($i == $current_page) ? 'current' : '';
                $pagination .= "<a href='javascript:;' data-index='" . esc_attr($i) . "' class='wobel-history-pagination-item " . esc_attr($current) . "'>" . esc_html($i) . "</a>";
            }
            if ($max_num_pages > $max_display) {
                $pagination .= "<span>...</span>";
                $pagination .= "<a href='javascript:;' data-index='" . esc_attr($max_num_pages) . "' class='wobel-history-pagination-item'>" . esc_html($max_num_pages) . "</a>";
            }
        } elseif ($current_page == $max_display) {
            $max_num = ($max_display < $max_num_pages) ? $max_display + 1 : $max_display;
            for ($i = 1; $i <= $max_num; $i++) {
                $current = ($i == $current_page) ? 'current' : '';
                $pagination .= "<a href='javascript:;' data-index='" . esc_attr($i) . "' class='wobel-history-pagination-item " . esc_attr($current) . "'>" . esc_html($i) . "</a>";
            }
            if ($max_num_pages > $current_page) {
                $pagination .= "<span>...</span>";
                $pagination .= "<a href='javascript:;' data-index='" . esc_attr($max_num_pages) . "' class='wobel-history-pagination-item'>" . esc_html($max_num_pages) . "</a>";
            }
        } else {
            $pagination .= "<a href='javascript:;' data-index='1' class='wobel-history-pagination-item'>1</a>";
            $pagination .= "<span>...</span>";
            for ($i = $current_page - 2; $i <= min($current_page + 2, $max_num_pages); $i++) {
                $current = ($i == $current_page) ? 'current' : '';
                $pagination .= "<a href='javascript:;' data-index='" . esc_attr($i) . "' class='wobel-history-pagination-item " . esc_attr($current) . "'>" . esc_html($i) . "</a>";
            }
            if ($current_page + 2 < $max_num_pages) {
                $pagination .= "<span>...</span>";
                $pagination .= "<a href='javascript:;' data-index='" . esc_attr($max_num_pages) . "' class='wobel-history-pagination-item'>" . esc_html($max_num_pages) . "</a>";
            }
        }
        $pagination .= "<a href='javascript:;' data-index='" . esc_attr($next) . "' class='wobel-history-pagination-item'>></a>";
    }
    $pagination .= "</div>";
    $pagination .= "<div class='wobel-history-pagination-loading'>Loading</div>";

    if (!empty($pagination)) {
        echo sprintf('%s', $pagination);
    }
}
