<?php

namespace HQRentalsPlugin\HQRentalsSettings;

class HQRentalsPagesHandler
{
    public static $pagesArgs = [
        'post_type' => 'page',
        'post_status' => 'publish'
    ];
    // name changed -> avoid deleting metas
    public static $metaKey = 'hq_pages_wordpress_is_wordpress_page';
    public function createPagesOnInit()
    {
        $page = get_page_by_title('Quotes');
        $payments = get_page_by_title('Payments');
        // avoid override existing pages
        if (empty($page)) {
            $this->resolvePageOnCreation('Quotes');
        }
        if (empty($payments)) {
            $this->resolvePageOnCreation('Payments');
        }
    }
    public function resolvePageOnCreation($pageTitle)
    {
        $args = array_merge(
            HQRentalsPagesHandler::$pagesArgs,
            array(
                'post_title' => $pageTitle,
            )
        );
        $post_id = wp_insert_post($args);
        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, HQRentalsPagesHandler::$metaKey, '1');
        }
    }
    public function deleteAllPages()
    {
        $args = array_merge(
            HQRentalsPagesHandler::$pagesArgs,
            array(
                'meta_query' => array(
                    array(
                        'key'       => HQRentalsPagesHandler::$metaKey,
                        'value'     => '1',
                        'compare'   => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        foreach ($query->get_posts() as $post) {
            delete_post_meta($post->ID, HQRentalsPagesHandler::$metaKey);
            wp_delete_post($post->ID, true);
        }
    }
}
