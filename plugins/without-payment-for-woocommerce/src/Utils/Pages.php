<?php

declare(strict_types=1);

namespace Coderun\WithoutPaymentWoocommerce\Utils;

/**
 * Class Pages
 */
class Pages
{
    /**
     * Страницы сайта
     *
     * @param string $title
     *
     * @return array<int, string>
     */
    public static function listOfSitePages(string $title): array
    {
        $wp_pages = get_pages('sort_column=menu_order');
        $pagesList = [];
        $pagesList[] = $title;

        foreach ($wp_pages as $page) {
            $prefix = '';
            $has_parent = $page->post_parent;
            while ($has_parent) {
                $prefix .= ' - ';
                $nextPage = get_post($has_parent);
                $has_parent = $nextPage->post_parent;
            }
            $pagesList[$page->ID] = $prefix . $page->post_title;
        }


        return $pagesList;
    }

    /**
     * Доступные статусы WooCommerce для формы настроек
     *
     * @param string $title
     *
     * @return array<mixed, string>
     */
    public static function listOfAvailableOrderStatuses(string $title): array
    {
        $availableStatuses = wc_get_order_statuses();
        $statuses = [];
        foreach ($availableStatuses as $key => $statusTitle) {
            $key = str_replace('wc-', '', $key);
            $statuses[$key] = $statusTitle;
        }
        $select = [];
        $select[0] = $title;

        return ($select + $statuses);
    }
}
