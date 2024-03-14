<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:20 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\CollectManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collect;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager\QueryHelper;

class CollectManager extends CollectManagerAbstract
{

    /**
     * Get many collects
     * @param $arg
     * @return array
     */
    public function getMany($arg)
    {
        $limit = $arg['limit'];
        $page = $arg['page'];
        $collectionId = $arg['collection_id'];
        $productId = $arg['product_id'];

        if ($collectionId) {
            $collects = $this->getCollectsByCollectionId($collectionId, $page, $limit);
        } else if ($productId) {
            $collects = $this->getCollectsByProductId($productId, $page, $limit);
        } else {
            $collects = $this->getCollects($page, $limit);
        }

        return $collects;
    }

    /**
     * Count collects
     * @return Count
     */
    public function count($arg)
    {
        $collectionId = $arg['collection_id'];
        $productId = $arg['product_id'];

        if ($collectionId) {
            $count = $this->countCollectsByCollectionId($collectionId);
        } else if ($productId) {
            $count = $this->countCollectsByProductId($productId);
        } else {
            $count = $this->countCollects();
        }

        return new Count($count);
    }

    /**
     * Count collects
     *
     * @return int
     */
    private function countCollects()
    {
        global $wpdb;
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "
                SELECT COUNT(p.ID)
                FROM $wpdb->term_relationships tr 
                  JOIN $wpdb->term_taxonomy tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  JOIN $wpdb->posts p 
                    ON tr.object_id = p.ID
                WHERE tt.taxonomy = %s 
                  AND p.post_type = %s
                  AND p.post_status = %s
                  AND p.post_password = %s
                  AND p.ID NOT IN ( " . QueryHelper::getExcludeProductsId() . " )
                ",
                "product_cat",
                "product",
                "publish",
                null
            )
        );

        return $count;
    }

    /**
     * Count collects by collection id
     *
     * @param $collectionId
     * @return int
     */
    private function countCollectsByCollectionId($collectionId)
    {
        global $wpdb;
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "
                SELECT COUNT(p.ID)
                FROM $wpdb->term_relationships tr 
                  JOIN $wpdb->term_taxonomy tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  JOIN $wpdb->posts p 
                    ON tr.object_id = p.ID
                WHERE tt.taxonomy = %s 
                  AND p.post_type = %s
                  AND p.post_status = %s
                  AND p.post_password = %s
                  AND tr.term_taxonomy_id = %d
                  AND p.ID NOT IN ( " . QueryHelper::getExcludeProductsId() . " )
                ",
                "product_cat",
                "product",
                "publish",
                null,
                $collectionId
            )
        );

        return $count;
    }

    /**
     * Count collects by product id
     *
     * @param $productId
     * @return int
     */
    private function countCollectsByProductId($productId)
    {
        global $wpdb;
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "
                SELECT COUNT(p.ID)
                FROM $wpdb->term_relationships tr 
                  JOIN $wpdb->term_taxonomy tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  JOIN $wpdb->posts p 
                    ON tr.object_id = p.ID
                WHERE tt.taxonomy = %s 
                  AND p.post_type = %s
                  AND p.post_status = %s
                  AND p.post_password = %s
                  AND p.ID = %d
                  AND p.ID NOT IN ( " . QueryHelper::getExcludeProductsId() . " )
                ",
                "product_cat",
                "product",
                "publish",
                null,
                $productId
            )
        );

        return $count;
    }

    /**
     * Get collects
     *
     * @param int $page
     * @param int $limit
     * @return array
     */
    private function getCollects($page = CommonConstants::DEFAULT_PAGE, $limit = CommonConstants::DEFAULT_ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "
                SELECT tr.object_id as product_id, tr.term_taxonomy_id as collection_id, tr.term_order as position
                FROM $wpdb->term_relationships tr 
                  JOIN $wpdb->term_taxonomy tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  JOIN $wpdb->posts p 
                    ON tr.object_id = p.ID
                WHERE tt.taxonomy = %s 
                  AND p.post_type = %s
                  AND p.post_status = %s
                  AND p.post_password = %s
                  AND p.ID NOT IN ( " . QueryHelper::getExcludeProductsId() . " )
                LIMIT %d
                OFFSET %d
                ",
                "product_cat",
                "product",
                "publish",
                null,
                $limit,
                $offset
            )
        );

        $results = array();
        if ($result) {
            foreach ($result as $item) {
                $results[] = $this->formatCollect($item);
            }
        }

        return $results;
    }

    /**
     * Get collects by collection id
     *
     * @param $collectionId
     * @param int $page
     * @param int $limit
     * @return array
     */
    private function getCollectsByCollectionId($collectionId, $page = CommonConstants::DEFAULT_PAGE, $limit = CommonConstants::DEFAULT_ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "
                SELECT tr.object_id as product_id, tr.term_taxonomy_id as collection_id, tr.term_order as position
                FROM $wpdb->term_relationships tr 
                  JOIN $wpdb->term_taxonomy tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  JOIN $wpdb->posts p 
                    ON tr.object_id = p.ID
                WHERE tt.taxonomy = %s 
                  AND p.post_type = %s
                  AND p.post_status = %s
                  AND p.post_password = %s
                  AND tr.term_taxonomy_id = %d
                  AND p.ID NOT IN ( " . QueryHelper::getExcludeProductsId() . " )
                LIMIT %d
                OFFSET %d
                ",
                "product_cat",
                "product",
                "publish",
                null,
                $collectionId,
                $limit,
                $offset
            )
        );

        $results = array();
        if ($result) {
            foreach ($result as $item) {
                $results[] = $this->formatCollect($item);
            }
        }

        return $results;
    }

    /**
     * Get collects by product id
     *
     * @param $productId
     * @param int $page
     * @param int $limit
     * @return array
     */
    private function getCollectsByProductId($productId, $page = CommonConstants::DEFAULT_PAGE, $limit = CommonConstants::DEFAULT_ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $limit;

        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "
                SELECT tr.object_id as product_id, tr.term_taxonomy_id as collection_id, tr.term_order as position
                FROM $wpdb->term_relationships tr 
                  JOIN $wpdb->term_taxonomy tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  JOIN $wpdb->posts p 
                    ON tr.object_id = p.ID
                WHERE tt.taxonomy = %s 
                  AND p.post_type = %s
                  AND p.post_status = %s
                  AND p.post_password = %s
                  AND p.ID = %d
                  AND p.ID NOT IN ( " . QueryHelper::getExcludeProductsId() . " )
                LIMIT %d
                OFFSET %d
                ",
                "product_cat",
                "product",
                "publish",
                null,
                $productId,
                $limit,
                $offset
            )
        );

        $results = array();
        if ($result) {
            foreach ($result as $item) {
                $results[] = $this->formatCollect($item);
            }
        }

        return $results;
    }

    /**
     * Format collection
     *
     * @param $collectObject
     * @return Collect
     */
    private function formatCollect($collectObject)
    {
        $collect = new Collect();
        $collect->id = $collectObject->collection_id * 100000 + $collectObject->product_id;
        $collect->collection_id = (int)$collectObject->collection_id;
        $collect->product_id = (int)$collectObject->product_id;
        $collect->position = (int)$collectObject->position;

        return $collect;
    }

}