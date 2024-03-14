<?php

namespace Avecdo\Woocommerce\Models;

use Avecdo\SDK\POPO\Product;

if (!defined('ABSPATH')) {
    exit;
}

class WooQueries
{
    private static $_sttributeTaxonomyCache = array();
    protected $wpdb;
    protected $wpdb_prefix;

    /**
     * Class constructor.
     * @global \wpdb $wpdb
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb        = $wpdb;
        $this->wpdb_prefix = $wpdb->prefix;
    }

    /**
     * Gets a boolean value indicating if the product has any children
     * @param int $productId
     * @return boolean
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.2.3
     * @deprecated since v1.2.5 not reliable since WooCommerce '3.1.2'
     *
     * ------
     *
     * In WooCommerce '3.1.2', this returns a minimum of one id for all products,
     * so it is not reliable, use the following to check, for children
     *
     * $productId = 78;
     * $productVairations = $this->getProductVairationsByProductId((int) $productId);
     *
     * if(count($productVairations)>0) {
     *      // product has vairations.
     * }
     *
     * ------
     */
    protected function hasChildren($productId)
    {
        $productId = (int) $productId;
        if ($productId <= 0) {
            return false;
        }
        $query        = "SELECT ID FROM ".$this->wpdb_prefix."posts WHERE post_parent={$productId} LIMIT 1";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? true : false;
    }
    /**
     * Alias of 'hasChildren'
     * @deprecated since v1.2.3, miss spelled. Use: 'hasChildren'
     * @param int $productId
     * @return boolean
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function hasChilden($productId)
    {
        return $this->hasChildren($productId);
    }

    /**
     * Get product variations for a product by product id
     * @param int $productId
     * @return \stdClass[] [productId, parentId, name, description]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getProductVairationsByProductId($productId)
    {
        $productId = (int) $productId;
        if ($productId <= 0) {
            return array();
        }
        $query        = "SELECT
                    IF(post_content IS NULL or post_content = '', post_excerpt, post_content) AS description,
                    post_title AS name, ID AS productId,
                    post_parent AS parentId FROM ".$this->wpdb_prefix."posts
                    WHERE ".$this->wpdb_prefix."posts.post_parent = {$productId}
                    AND ".$this->wpdb_prefix."posts.post_type = 'product_variation'
                    AND ".$this->wpdb_prefix."posts.post_status = 'publish'
                    ORDER BY ".$this->wpdb_prefix."posts.ID";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get product variations for a product by product id
     * @param int $productId
     * @param string $language
     * @return \stdClass[] [productId, parentId, name, description]
     * @author Nikolai Straarup <nikolai@modified.dk>
     * @since 1.3.12
     */
    protected function getProductVairationsByProductIdInLanguage($productId, $language)
    {
        $productId = (int) $productId;
        if ($productId <= 0) {
            return array();
        }
        $query        = "SELECT DISTINCT
                    IF(post_content IS NULL or post_content = '', post_excerpt, post_content) AS description,
                    post_title AS name, ID AS productId,
                    post_parent AS parentId FROM ".$this->wpdb_prefix."posts
                    INNER JOIN ".$this->wpdb_prefix."icl_translations AS langstable
                    ON ".$this->wpdb_prefix."posts.ID = langstable.element_id
                    AND langstable.language_code = '{$language}'
                    WHERE ".$this->wpdb_prefix."posts.post_parent = {$productId}
                    AND ".$this->wpdb_prefix."posts.post_type = 'product_variation'
                    AND ".$this->wpdb_prefix."posts.post_status = 'publish'
                    ORDER BY ".$this->wpdb_prefix."posts.ID";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get attribute id and label by attribute name
     * @param string $name
     * @return \stdClass[] [id, name, label]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getAttributeTaxonomyByName($name)
    {
        if (empty($name)) {
            return array();
        }
        if (isset(self::$_sttributeTaxonomyCache[$name])) {
            return self::$_sttributeTaxonomyCache[$name];
        }
        $query                                = $this->wpdb->prepare("
            SELECT
                attribute_id as id,
                attribute_name as name,
                attribute_label as label
            FROM ".$this->wpdb_prefix."woocommerce_attribute_taxonomies
            WHERE attribute_name='%s'", array($name)
        );
        $query_result                         = $this->wpdb->get_results($query, OBJECT);
        self::$_sttributeTaxonomyCache[$name] = $query_result ? $query_result : array();
        return self::$_sttributeTaxonomyCache[$name];
    }

    /**
     * Get related products by tags
     * @param int[] $tagIds
     * @param int $limit
     * @param int $offset
     * @return \stdClass[] [productId, name]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getRelatedProductsByTags(array $tagIds, $limit = 5, $offset = 0)
    {
        if (empty($tagIds)) {
            return array();
        }
        $tags = implode(',', array_map('intval', $tagIds));
        $tags = trim($tags);
        if (empty($tags)) {
            return array();
        }
        $query        = "SELECT ".$this->wpdb_prefix."posts.ID AS productId, ".$this->wpdb_prefix."posts.post_title AS name FROM ".$this->wpdb_prefix."posts
		INNER JOIN ".$this->wpdb_prefix."term_relationships AS tt1 ON ".$this->wpdb_prefix."posts.ID = tt1.object_id
		INNER JOIN ".$this->wpdb_prefix."postmeta ON ".$this->wpdb_prefix."posts.ID = ".$this->wpdb_prefix."postmeta.post_id
		WHERE tt1.term_taxonomy_id IN ({$tags})
		AND ".$this->wpdb_prefix."posts.post_type = 'product'
		AND ".$this->wpdb_prefix."posts.post_status = 'publish'
		AND ".$this->wpdb_prefix."postmeta.meta_key = '_visibility'
		AND CAST(".$this->wpdb_prefix."postmeta.meta_value AS CHAR) IN ('visible','catalog')
		GROUP BY ".$this->wpdb_prefix."posts.ID ORDER BY ".$this->wpdb_prefix."posts.ID DESC LIMIT {$offset}, {$limit}";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get related products by categories
     * @param int[] $categoryIds
     * @param int $limit
     * @param int $offset
     * @return \stdClass[] [productId, name]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getRelatedProductsByCategories(array $categoryIds, $limit = 6, $offset = 0)
    {
        if (empty($categoryIds)) {
            return array();
        }
        $categoris = implode(',', array_map('intval', $categoryIds));
        $categoris = trim($categoris);
        if (empty($categoris)) {
            return array();
        }
        $query        = "SELECT ".$this->wpdb_prefix."posts.ID AS productId, ".$this->wpdb_prefix."posts.post_title AS name FROM ".$this->wpdb_prefix."posts
		INNER JOIN ".$this->wpdb_prefix."term_relationships AS tt1 ON ".$this->wpdb_prefix."posts.ID = tt1.object_id
		INNER JOIN ".$this->wpdb_prefix."postmeta ON ".$this->wpdb_prefix."posts.ID = ".$this->wpdb_prefix."postmeta.post_id
		WHERE tt1.term_taxonomy_id IN ({$categoris})
		AND ".$this->wpdb_prefix."posts.post_type = 'product'
		AND ".$this->wpdb_prefix."posts.post_status = 'publish'
		AND ".$this->wpdb_prefix."postmeta.meta_key = '_visibility'
		AND CAST(".$this->wpdb_prefix."postmeta.meta_value AS CHAR) IN ('visible','catalog')
		GROUP BY ".$this->wpdb_prefix."posts.ID ORDER BY ".$this->wpdb_prefix."posts.ID DESC LIMIT {$offset}, {$limit}";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get related products by categories and tags
     * @param int[] $categoryIds
     * @param int[] $tagIds
     * @param int $limit
     * @param int $offset
     * @return \stdClass[] [productId, name]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getRelatedProductsByCategoriesAndTags(array $categoryIds, array $tagIds, $limit = 6, $offset = 0)
    {
        $categoryQuery = "";
        if (!empty($categoryIds)) {
            $categoryQuery .= "".$this->wpdb_prefix."term_relationships.term_taxonomy_id IN (".implode(',', array_map('intval', $categoryIds)).")";
        }
        $tagQuery = "";
        if (!empty($tagIds)) {
            $tagQuery .= "tt1.term_taxonomy_id IN (".implode(',', array_map('intval', $tagIds)).")";
        }
        $exQuery = "";
        if (!empty($tagQuery) && !empty($categoryQuery)) {
            $exQuery = "({$categoryQuery} OR {$tagQuery}) AND";
        } else if (empty($tagQuery) && !empty($categoryQuery)) {
            $exQuery = "({$categoryQuery}) AND";
        } else if (!empty($tagQuery) && empty($categoryQuery)) {
            $exQuery = "({$tagQuery}) AND";
        }

        $query        = "SELECT ".$this->wpdb_prefix."posts.ID AS productId, ".$this->wpdb_prefix."posts.post_title AS name FROM ".$this->wpdb_prefix."posts
		INNER JOIN ".$this->wpdb_prefix."term_relationships ON ".$this->wpdb_prefix."posts.ID = ".$this->wpdb_prefix."term_relationships.object_id
		INNER JOIN ".$this->wpdb_prefix."term_relationships AS tt1 ON ".$this->wpdb_prefix."posts.ID = tt1.object_id
		INNER JOIN ".$this->wpdb_prefix."postmeta ON ".$this->wpdb_prefix."posts.ID = ".$this->wpdb_prefix."postmeta.post_id
		WHERE ".$exQuery." ".$this->wpdb_prefix."posts.post_type = 'product'
		AND ".$this->wpdb_prefix."posts.post_status = 'publish'
		AND ".$this->wpdb_prefix."postmeta.meta_key = '_visibility'
		AND CAST(".$this->wpdb_prefix."postmeta.meta_value AS CHAR) IN ('visible','catalog')
		GROUP BY ".$this->wpdb_prefix."posts.ID ORDER BY ".$this->wpdb_prefix."posts.ID DESC LIMIT {$offset}, {$limit}";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get related products by product id(s)
     * @param int[] $productIds
     * @param int $limit
     * @param int $offset
     * @return \stdClass[] [productId, name]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getRelatedProductsByProductIds(array $productIds, $limit = 6, $offset = 0)
    {
        $query        = "SELECT ".$this->wpdb_prefix."posts.ID AS productId, ".$this->wpdb_prefix."posts.post_title AS name FROM ".$this->wpdb_prefix."posts
		INNER JOIN ".$this->wpdb_prefix."term_relationships ON ".$this->wpdb_prefix."posts.ID = ".$this->wpdb_prefix."term_relationships.object_id
		INNER JOIN ".$this->wpdb_prefix."term_relationships AS tt1 ON ".$this->wpdb_prefix."posts.ID = tt1.object_id
		INNER JOIN ".$this->wpdb_prefix."postmeta ON ".$this->wpdb_prefix."posts.ID = ".$this->wpdb_prefix."postmeta.post_id
		WHERE (
			".$this->wpdb_prefix."term_relationships.term_taxonomy_id IN (
				SELECT t.term_id FROM ".$this->wpdb_prefix."terms AS t
				INNER JOIN ".$this->wpdb_prefix."term_taxonomy AS tt ON tt.term_id = t.term_id
				INNER JOIN ".$this->wpdb_prefix."term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
				WHERE tt.taxonomy IN ('product_cat') AND tr.object_id IN
                                (".implode(',', array_map('intval', $productIds)).") ORDER BY t.term_id ASC
			) OR tt1.term_taxonomy_id IN (
				SELECT t.term_id FROM ".$this->wpdb_prefix."terms AS t
				INNER JOIN ".$this->wpdb_prefix."term_taxonomy AS tt ON tt.term_id = t.term_id
                                INNER JOIN ".$this->wpdb_prefix."term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                                WHERE tt.taxonomy IN ('product_tag') AND tr.object_id IN
                                (".implode(',', array_map('intval', $productIds)).") ORDER BY t.term_id ASC
			)
		)
		AND ".$this->wpdb_prefix."posts.post_type = 'product'
		AND ".$this->wpdb_prefix."posts.post_status = 'publish'
		AND ".$this->wpdb_prefix."postmeta.meta_key = '_visibility'
		AND CAST(".$this->wpdb_prefix."postmeta.meta_value AS CHAR) IN ('visible','catalog')
		GROUP BY ".$this->wpdb_prefix."posts.ID ORDER BY ".$this->wpdb_prefix."posts.ID DESC LIMIT {$offset}, {$limit}";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get all categories for products by product id.
     * @param int[] $productIds
     * @return \stdClass[] [term_id, name]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getProductCategories(array $productIds)
    {
        $query        = "SELECT t.*, tt.* FROM ".$this->wpdb_prefix."terms AS t
            INNER JOIN ".$this->wpdb_prefix."term_taxonomy AS tt ON tt.term_id = t.term_id
            INNER JOIN ".$this->wpdb_prefix."term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.taxonomy IN ('product_cat') AND tr.object_id IN (".implode(',', array_map('intval', $productIds)).") ORDER BY t.name ASC";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get all tags for products by product id.
     * @param int[] $productIds
     * @return \stdClass[] [term_id, name]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getProductTags(array $productIds)
    {
        $query        = "SELECT t.term_id, t.name FROM ".$this->wpdb_prefix."terms AS t
          INNER JOIN ".$this->wpdb_prefix."term_taxonomy AS tt ON tt.term_id = t.term_id INNER
          JOIN ".$this->wpdb_prefix."term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
          WHERE tt.taxonomy IN ('product_tag') AND tr.object_id IN (".implode(',', array_map('intval', $productIds)).") ORDER BY t.name ASC";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get meta data for $postId
     * @param int $postId
     * @return \stdClass[] [meta_key, meta_value]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getMetaData($postId)
    {
        $query        = "SELECT meta_key, meta_value FROM ".$this->wpdb_prefix."postmeta WHERE post_id={$postId}";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get product list from simple db query
     * @param int $offset
     * @param int $limit
     * @param array $type
     * @return \stdClass[]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getSimpleProductList($offset, $limit, $lastModified, $type = array('product'))
    {
        $query = "SELECT
            IF(post_content IS NULL or post_content = '', post_excerpt, post_content) AS description,
            post_content AS description_long,
            post_excerpt AS description_short,
            post_title AS name, ID AS productId,
            post_parent AS parentId FROM ".$this->wpdb_prefix."posts
            WHERE post_type IN ('".implode("','", $type)."')";

        if ($lastModified) {
            $date = str_replace("T", " ", $lastModified);
            $query .= " AND post_modified > '{$date}'";
        }

        $query .= " AND post_status = 'publish' ORDER BY ID ASC
            LIMIT {$offset}, {$limit}";

        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get product list from simple db query
     * @param int $offset
     * @param string $language
     * @param int $limit
     * @param array $type
     * @return \stdClass[]
     * @author Nikolai Straarup <nikolai@modified.dk>
     * @since 1.3.12
     */
    protected function getSimpleProductListInLanguage($offset, $limit, $lastModified, $language, $type = array('product'))
    {
        $query = "SELECT DISTINCT
            IF(post_content IS NULL or post_content = '', post_excerpt, post_content) AS description,
            post_content AS description_long,
            post_excerpt AS description_short,
            post_title AS name, ID AS productId,
            post_parent AS parentId FROM ".$this->wpdb_prefix."posts
            INNER JOIN ".$this->wpdb_prefix."icl_translations AS langstable
            ON ".$this->wpdb_prefix."posts.ID = langstable.element_id
            AND langstable.language_code = '{$language}'
            WHERE post_type IN ('".implode("','", $type)."')
            AND element_type = 'post_product'
            AND post_status = 'publish' ORDER BY ID ASC";

        if ($lastModified) {
            $date = str_replace("T", " ", $lastModified);
            $query .= " AND post_modified > '{$date}'";
        }

        $query .= " LIMIT {$offset}, {$limit}";

        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get translation info of a product with a specific product id.
     * It would be optimal to combine this with
     * getTranslationInfoOfProductWithTridAndLang, to get the info in one database
     * query.
     * @return \stdClass[]
     * @author Nikolai Straarup <nikolai@modified.dk>
     * @since 1.3.12
     */
    protected function getTranslationInfoOfProduct($productId)
    {
        $query = "SELECT *
            FROM ".$this->wpdb_prefix."icl_translations
            WHERE element_id = '{$productId}'
            LIMIT 1";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get translation info of a product with product-translation-id "trid",
     * which is used to identify which translations are related,
     * in a specific language. It's used together with getTranslationInfoOfProduct.
     * @return \stdClass[]
     * @author Nikolai Straarup <nikolai@modified.dk>
     * @since 1.3.12
     */
    protected function getTranslationInfoOfProductWithTridAndLang($trid, $lang)
    {
        $query        = "SELECT *
            FROM ".$this->wpdb_prefix."icl_translations
            WHERE trid = '{$trid}'
            AND language_code = '{$lang}'
            LIMIT 1";
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * get Categories, Tags and ShippingClasses data [term_id, name, parent, taxonomy]
     * @param int $productId
     * @return \stdClass[]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getCategoriesTagsAndShippingClasses($productId)
    {
        /* ShippingClasses: is added for future use as it is here it will be fetched when finding the best way to use it. */
        if (in_array('termmeta', $this->wpdb->tables)) {
            $query = "SELECT t.term_id, t.name, tt.parent, tt.taxonomy FROM ".$this->wpdb_prefix."terms AS t
                       INNER JOIN ".$this->wpdb_prefix."term_taxonomy AS tt ON t.term_id = tt.term_id
                       INNER JOIN ".$this->wpdb_prefix."term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                       LEFT JOIN ".$this->wpdb_prefix."termmeta AS tm ON t.term_id = tm.term_id AND tm.meta_key = 'order'
                       WHERE tt.taxonomy IN ('product_cat', 'product_tag', 'product_shipping_class') AND tr.object_id = {$productId}";
        } else {
            $query = "SELECT t.term_id, t.name, tt.parent, tt.taxonomy FROM ".$this->wpdb_prefix."terms AS t
                       INNER JOIN ".$this->wpdb_prefix."term_taxonomy AS tt ON t.term_id = tt.term_id
                       INNER JOIN ".$this->wpdb_prefix."term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                       LEFT JOIN ".$this->wpdb_prefix."woocommerce_termmeta AS tm ON t.term_id = tm.woocommerce_term_id AND tm.meta_key = 'order'
                       WHERE tt.taxonomy IN ('product_cat', 'product_tag', 'product_shipping_class') AND tr.object_id = {$productId}";
        }
        $query_result = $this->wpdb->get_results($query, OBJECT);
        return $query_result ? $query_result : array();
    }

    /**
     * Get currently active sale price for a product.
     * @param int $productId
     * @return string|null
     */
    protected function getSalePrice($productId)
    {
        $product = wc_get_product($productId);
        $discountRulesPrice = $this->getDiscountRulesSalePrice($product);

        // If discount rules plugin is enabled and returns a sale price, we return
        // that price instead of looking for it manually in the database.
        if (isset($discountRulesPrice)) {
            return $discountRulesPrice;
        }

        // Only return sale price if product is currently on sale.
        if ($product->is_on_sale()) {
            return $product->get_sale_price();
        }

        return null;
    }

    /**
     * get images meta data [post_id, file, meta, image_alt]
     * @param int[] $imagesIds
     * @return \stdClass[]
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    protected function getImagesData($imagesIds)
    {
        $query  = "SELECT t1.post_id, t1.meta_value as file, t2.meta_value as meta, t3.post_title as image_alt
                       FROM ".$this->wpdb_prefix."postmeta AS t1
                       INNER JOIN ".$this->wpdb_prefix."postmeta AS t2
                       ON t2.post_id=t1.post_id
                       AND t2.meta_key='_wp_attachment_metadata'
                       LEFT JOIN ".$this->wpdb_prefix."posts AS t3
                       ON t3.ID=t1.post_id AND t3.post_type='attachment'
                       WHERE t1.post_id IN (".implode(',', array_map('intval', $imagesIds)).")
                       AND t1.meta_key='_wp_attached_file'";
        $result = $this->wpdb->get_results($query, OBJECT);
        return $result ? $result : array();
    }

    /**
     * Get WooCommerce instance
     * @global array $GLOBALS
     * @return \WooCommerce|\Woocommerce
     */
    public function getWooCommerceInstance()
    {
        global $GLOBALS;
        return $GLOBALS['woocommerce'];
    }

    // Check if the Discount Rules plugin is enabled.
    // https://wordpress.org/plugins/woo-discount-rules
    public function isDiscountRulesEnabled()
    {
        if ( ! function_exists( 'is_plugin_active' ) )
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        return \is_plugin_active('woo-discount-rules/woo-discount-rules.php');
    }

    /**
     * @param $product
     * @author Kasper Færch Mikkelsen <kasper@modified.dk>, Nikolai Straarup <nikolai@modified.dk>
     * @date 13/10/20
     *
     * Check if Discount Rules plugin is active, and then get the sale price for the selected product.
     */
    public function getDiscountRulesSalePrice($product)
    {
        // Only return a discount rule price if plugin is enabled.
        if (!$this->isDiscountRulesEnabled()) {
            return null;
        }

        if (!$product){
            return null;
        }

        $discount = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', (float)$product->get_price(), $product, 1, 0, 'discounted_price', true, false);
        if (!empty($discount) && $discount !== $product->get_price()) {
            return (float)$discount;
        }

        return null;
    }

    // Set the sale price for a product to the value from the Discount Rules plugin.
    public function setDiscountRuleSalePrice($productId, Product $avecdoProduct)
    {
        if (!$this->isDiscountRulesEnabled()) {
            return;
        }
        $product = wc_get_product($productId);

        if(is_null($this->getDiscountRulesSalePrice($product))){
            return;
        }


        if ($product && $avecdoProduct) {
            $avecdoProduct->setPriceSale($this->getDiscountRulesSalePrice($product));
        }
    }
}
