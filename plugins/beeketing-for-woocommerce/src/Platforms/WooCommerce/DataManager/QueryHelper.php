<?php
/**
 * Created by PhpStorm.
 * User: tungquach
 * Date: 31/03/2017
 * Time: 15:07
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager;


class QueryHelper
{
    /**
     * Get products id sql
     *
     * @return string
     */
    public static function getExcludeProductsId()
    {
        global $wpdb;
        $sql = "
            SELECT p.ID
            FROM $wpdb->term_relationships tr
              JOIN $wpdb->term_taxonomy tt
                ON tr.term_taxonomy_id = tt.term_taxonomy_id
              JOIN $wpdb->terms t
                ON t.term_id = tt.term_id
              JOIN $wpdb->posts p
                ON tr.object_id = p.ID
            WHERE tt.taxonomy = 'product_type'
              AND t.slug IN ('grouped')
        ";

        return $sql;
    }
}
