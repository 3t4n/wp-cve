<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ProductRepository
{
    public const TABLE_NAME = 'wc_product_meta_lookup';
    private const POST_STATUS = "'publish'";
    private const POST_STATUS_DISABLED = "'private'";
    private const POST_TYPE = "'product'";
    private const POST_ATTACHMENT_TYPE = "'attachment'";
    private const WC_ORDER_ITEM_META = 'woocommerce_order_itemmeta';
    private const WC_META_KEY_PRODUCT_ID = "'_product_id'";

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    private $tableName;
    /** @var string */
    private $productMapTable;
    /** @var string */
    private $postTable;
    /** @var string */
    private $postMetaTable;
    /** @var string */
    private $wcOrderItemMeta;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
        $this->productMapTable = $this->wpDb->prefix.ProductMapRepository::TABLE_NAME;
        $this->postTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS;
        $this->postMetaTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_POST_META;
        $this->wcOrderItemMeta = $this->wpDb->prefix.self::WC_ORDER_ITEM_META;
    }

    /**
     * @param array $productShopIds
     *
     * @return array
     */
    public function findByProductShopIdsIndexedByProductId(array $productShopIds): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT product_id FROM '.$this->tableName.' WHERE product_id IN ('.implode(',', $productShopIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'product_id', 'product_id');
    }

    /**
     * @param array $productShopIds
     *
     * @return array
     */
    public function findDisabledByProductShopIdIndexedByProductShopId(array $productShopIds): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT ID FROM '.$this->postTable.' WHERE ID IN ('.implode(',', $productShopIds).') AND post_status = '.self::POST_STATUS_DISABLED;
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'ID', 'ID');
    }

    /**
     * @param array $productShopIds
     *
     * @return array
     */
    public function findProductShopIdIndexedBySku(array $productShopIds): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT product_id, sku FROM '.$this->tableName.' WHERE sku IN ("'.implode('","', $productShopIds).'")';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'product_id', 'sku');
    }

    public function countTotalMapped(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.$this->productMapTable;

        return (int)$this->wpDb->get_var($sql);
    }

    public function countTotalProductShop(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->postTable.' 
                WHERE post_parent != 0';

        return (int)$this->wpDb->get_var($sql);
    }

    public function countTotalMappedAndActive(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->productMapTable.' pm
            INNER JOIN '.$this->postTable.' pa ON pm.product_shop_id = pa.ID
            WHERE pa.post_status = '.self::POST_STATUS;

        return (int)$this->wpDb->get_var($sql);
    }

    public function countTotalMappedAndDisabled(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->productMapTable.' pm
            INNER JOIN '.$this->postTable.' pa ON pm.product_shop_id = pa.ID
            WHERE pa.post_status = '.self::POST_STATUS_DISABLED;

        return (int)$this->wpDb->get_var($sql);
    }

    public function countTotalProductShopActive(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->postTable.' 
                WHERE post_parent != 0
                AND post_status = '.self::POST_STATUS;

        return (int)$this->wpDb->get_var($sql);
    }

    public function countTotalProductShopDisabled(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->postTable.' 
                WHERE post_parent != 0
                AND post_status = '.self::POST_STATUS_DISABLED;

        return (int)$this->wpDb->get_var($sql);
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        $sql = 'SELECT DISTINCT(pm.product_id) AS ProductID, p.sku AS SKU, pa.post_status AS Active, p.stock_quantity AS Stock
            FROM '.$this->productMapTable.' pm 
            INNER JOIN '.$this->tableName.' p ON p.product_id = pm.product_shop_id 
            INNER JOIN '.$this->postTable.' pa ON pm.product_shop_id = pa.ID';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param array $productShopIds
     *
     * @return array
     */
    public function findProductIdAndSkuByProductIds(array $productShopIds): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT product_id, sku FROM '.$this->tableName.' WHERE product_id IN ('.implode(',', $productShopIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'sku', 'product_id');
    }

    /**
     * @param array $productShopIds
     * @return array
     */
    public function getProductUrls(array $productShopIds): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT p.guid, p.ID FROM '.$this->postTable.' p WHERE p.ID IN ('.implode(',', $productShopIds).')';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @return array
     */
    public function getProductImages(): array
    {
        $sql = "SELECT p.post_id as post_parent, p.meta_key, p.meta_value 
                FROM ".$this->productMapTable." pm 
                INNER JOIN ".$this->postMetaTable." p ON pm.product_shop_id = p.post_id
                WHERE p.meta_key = '_product_image_gallery' OR p.meta_key = '_thumbnail_id';";

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param array $productShopIds
     * @param int $days
     * @return array
     */
    public function findMappedProductsIdsDisableByDays(array $productShopIds, int $days): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT ID FROM '.$this->postTable.' WHERE ID IN ('.implode(',', $productShopIds).') AND post_status = '.self::POST_STATUS_DISABLED.' 
        AND post_type = '.self::POST_TYPE.' AND post_modified < (NOW() - INTERVAL '.$days.' DAY)';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'ID');
    }

    /**
     * @param int $days
     * @return array
     */
    public function findIdsDisabledToPurge(int $days = 90): array
    {
        $sql = 'SELECT ID FROM '.$this->postTable.' p 
        INNER JOIN '.$this->productMapTable.' pm
        ON pm.product_shop_id = p.ID AND p.post_status = '.self::POST_STATUS_DISABLED.' AND p.post_type = '.self::POST_TYPE.' AND pm.date_update < (NOW() - INTERVAL '.$days.' DAY)
        WHERE p.ID NOT IN (SELECT CAST(meta_value AS UNSIGNED) FROM '.$this->wcOrderItemMeta.' WHERE meta_key = '.self::WC_META_KEY_PRODUCT_ID.')
        LIMIT 100';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'ID');
    }

    /**
     * @param string $productSku
     * @return array
     */
    public function findImagePostsIndexedByProductShop(string $productSku): array
    {
        $sql = 'SELECT ID, guid FROM '.$this->postTable.' WHERE post_title LIKE "'.$productSku.'%" AND post_type = '.self::POST_ATTACHMENT_TYPE.' AND post_mime_type LIKE "image/%";';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param string $productSku
     *
     * @return array
     */
    public function findProductShopIdWithSameSku(string $productSku): array
    {
        if (empty($productSku)) {
            return [];
        }

        $sql = 'SELECT product_id FROM '.$this->tableName.' WHERE sku = "'.$productSku.'"';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param array $data
     * @param array $filter
     * @return bool
     */
    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->postTable, $data, $filter);
    }

    /**
     * @param int $productShopId
     * @return array
     */
    public function getProductImagesTitlesByProductId(int $productShopId): array
    {
        if (empty($productShopId)) {
            return [];
        }

        $sql = 'SELECT post_title FROM '.$this->postTable.' WHERE post_parent = "'.$productShopId.'" AND post_type = '.self::POST_ATTACHMENT_TYPE.' AND post_mime_type LIKE "image/%";';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'post_title');
    }

    /**
     * @param string[] $imageTitles
     * @return array
     */
    public function getImagesNotAssociateToProductByImageTitles(array $imageTitles): array
    {
        if (empty($imageTitles)) {
            return [];
        }

        $imageTitlesCondition = implode('"% LIKE "%', $imageTitles);

        $sql = 'SELECT ID FROM '.$this->postTable.' WHERE post_title LIKE "%'.$imageTitlesCondition.'%" AND post_parent = 0 AND post_type = '.self::POST_ATTACHMENT_TYPE.' AND post_mime_type LIKE "image/%";';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'ID');
    }

    /**
     * @param int[] $imageIds
     * @return array
     */
    public function getImageIdsWithProductMetaByImageIds(array $imageIds): array
    {
        if (empty($imageIds)) {
            return [];
        }

        $imageTitlesCondition = implode('", "', $imageIds);
        $sql = 'SELECT meta_value FROM '.$this->postMetaTable.' WHERE (meta_key = "_thumbnail_id" OR meta_key = "_product_image_gallery") and meta_value IN ("'.$imageTitlesCondition.'");';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'meta_value');
    }

    /**
     * @param array $variationIds
     * @return array
     */
    public function getVariationShopIdsIndexedByProductId(array $variationIds): array
    {
        if (empty($variationIds)) {
            return [];
        }

        $sql = 'SELECT p.ID, p.post_parent FROM '.$this->postTable.' p WHERE p.ID IN ('.implode(',', $variationIds).')';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (empty($result)) {
            return [];
        }

        $variationShopIdsIndexedByProductId = [];

        foreach ($result as $product) {
            $variationShopIdsIndexedByProductId[$product['post_parent']][] = $product['ID'];
        }

        return $variationShopIdsIndexedByProductId;
    }

    public function getSkusIndexedByProductId(array $references): array
    {
        if (empty($references)) {
            return [];
        }

        $sql = 'SELECT product_meta_lookup.product_id, product_meta_lookup.sku
                FROM '.$this->tableName.' product_meta_lookup
                WHERE product_meta_lookup.sku IN ("'.implode('","', $references).'")';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'sku', 'product_id');
    }

    public function getPostParentsIndexedByProductIds(array $skusIndexedByProductId): array
    {
        if (empty($skusIndexedByProductId)) {
            return [];
        }

        $sql = 'SELECT post.ID, post.post_parent
                FROM '.$this->postTable.' post
                WHERE post.post_parent > 0 AND post.ID IN ('.implode(',', \array_keys($skusIndexedByProductId)).')';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'post_parent', 'ID');
    }

    /**
     * @param string $productImageIds
     * @return array|null
     */
    public function getImagesUrl(string $productImageIds): ?array
    {
        if (empty($productImageIds)) {
            return [];
        }

        $sql = 'SELECT ID, guid FROM '.$this->postTable.' WHERE ID IN ('.rtrim($productImageIds, ", ").');';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'guid', 'ID');
    }

    /**
     * @param array $productImages
     * @param array $imageUrls
     * @return array
     */
    public function getProductImagesUrl(array $productImages, array $imageUrls): array
    {
        $productImagesUrl = [];

        foreach ($productImages as $productImage) {
            $image = [];

            if ($productImage['meta_key'] === '_thumbnail_id') {
                $image['post_parent'] = $productImage['post_parent'];
                $image['ID'] = $productImage['meta_value'];

                if (\array_key_exists($productImage['meta_value'], $imageUrls)) {
                    $image['guid'] = $imageUrls[$productImage['meta_value']];
                }

                $image['Cover'] = true;
                $productImagesUrl[] = $image;

                continue;
            }

            $explodeImages = explode(',', $productImage['meta_value']);

            foreach ($explodeImages as $explodeImage) {
                $image['post_parent'] = $productImage['post_parent'];
                $image['ID'] = $explodeImage;

                if (\array_key_exists($explodeImage, $imageUrls)) {
                    $image['guid'] = $imageUrls[$explodeImage];
                }

                $image['Cover'] = false;
                $productImagesUrl[] = $image;
            }

            $productImagesUrl[] = $image;
        }

        return $productImagesUrl;
    }

    /**
     * @param array $where
     * @return bool
     */
    public function delete(array $where): bool
    {
        return $this->wpDb->delete($this->tableName, $where);
    }

    /**
     * @param int[] $productIds
     */
    public function updateMetaLookUpStockByProductIds(array $productIds): void
    {
        if (empty($productIds)) {
            return;
        }

        $sql = 'UPDATE '.$this->tableName.' SET stock_quantity = 0, stock_status = "outofstock" WHERE product_id IN ('.implode(',', $productIds).')';
        $this->wpDb->query($sql);
    }

    /**
     * @param int[] $productIds
     */
    public function updatePostMetaStockByProductIds(array $productIds): void
    {
        if (empty($productIds)) {
            return;
        }

        $sql = 'UPDATE '.$this->postMetaTable.' SET meta_value = "0" WHERE meta_key like "_stock" AND post_id IN ('.implode(',', $productIds).')';
        $this->wpDb->query($sql);
    }

    /**
     * @param int[] $productIds
     */
    public function updatePostMetaStockStatusByProductIds(array $productIds): void
    {
        if (empty($productIds)) {
            return;
        }

        $sql = 'UPDATE '.$this->postMetaTable.' SET meta_value = "outofstock" WHERE meta_key like "_stock_status" AND post_id IN ('.implode(',', $productIds).')';
        $this->wpDb->query($sql);
    }
}