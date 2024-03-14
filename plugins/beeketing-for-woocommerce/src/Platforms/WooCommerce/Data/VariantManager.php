<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:21 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;


use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\VariantManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Variant;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Constants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

class VariantManager extends VariantManagerAbstract
{

    /**
     * @var array
     */
    private $option1s = array();

    /**
     * @var bool
     */
    private $is_option1s_loaded = false;

    /**
     * @var array
     */
    private $wc_products_variants = array();

    /**
     * @var array
     */
    private $wc_products_variants_images = array();

    /**
     * @var array
     */
    private static $terms = array();

    /**
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * VariantManager constructor.
     * @param ResourceManager $resourceManager
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    /**
     * Get one
     * @param $arg
     * @return Variant|boolean
     */
    public function get($arg)
    {
        $id = $arg['resource_id'];
        $variant = wc_get_product($id);
        if (!$variant) {
            return false;
        }

        $parentId = Helper::isWc3() ? $variant->get_parent_id() : $variant->parent_id;
        if ($parentId) {
            $parent = wc_get_product($parentId);
            $result = $this->formatVariant($variant, $parent);
        } else {
            $result = $this->formatVariant($variant, $variant);
        }

        return $result;
    }

    /**
     * Update variant
     * @param $arg
     * @return Variant
     * @throws \Exception
     */
    public function put($arg)
    {
        $id = $arg['resource_id'];
        $variantData = $arg['variant'];

        $variant = get_post($id);
        if (!$variant) {
            throw new \Exception(sprintf('Variant %s not found', $id));
        }

        // Update meta
        if (isset($variantData['price'])) {
            update_post_meta($id, '_price', $variantData['price']);
            update_post_meta($id, '_regular_price', $variantData['price']);
        }

        if (isset($variantData['price_compare'])) {
            update_post_meta($id, '_sale_price', $variantData['price_compare']);
        }

        if (isset($variantData['inventory_policy']) && $variantData['inventory_policy']) {
            update_post_meta($id, '_manage_stock', 'no');
            delete_post_meta($id, '_backorders');
        }

        if (isset($variantData['inventory_quantity'])) {
            update_post_meta($id, '_stock', $variantData['inventory_quantity']);
        }

        if (isset($variantData['sku'])) {
            update_post_meta($id, '_sku', $variantData['sku']);
        }

        if (isset($variantData['option1'])) {
            update_post_meta($id, '_beeketing_option1', $variantData['option1']);
        }

        if (isset($variantData['attributes'])) {
            foreach ($variantData['attributes'] as $name => $attribute) {
                update_post_meta($id, $name, $attribute);
            }
        }

        if (isset($variantData['title'])) {
            wp_update_post(array(
                'ID' => $id,
                'post_title' => $variantData['title'],
            ));
        }

        return $this->get(array(
            'resource_id' => $id,
        ));
    }

    /**
     * Create new variant for product
     * @param $arg
     * @return Variant
     * @throws \Exception
     */
    public function post($arg)
    {
        $productId = $arg['product_id'];
        $content = $arg['variant'];

        // Create
        $product = $this->resourceManager->productManager->get(array(
            'resource_id' => $productId,
        ));
        if (!$product) {
            throw new \Exception(sprintf('Cant find product %d', $productId));
        }

        // Check option1
        if (!isset($content['option1'])) {
            throw new \Exception(sprintf('Option1 is required for product %d', $productId));
        }

        // Check variant exists
        /** @var Variant $variant */
        foreach ($product->variants as $variant) {
            if ($variant->option1 == $content['option1']) {
                throw new \Exception(sprintf('Variant existed for product %d variant option1 %s', $productId, $variant->option1));
            }
        }

        // If product variation doesn't exist, add one
        $productId = $product->id;
        $admin = get_users('orderby=nicename&role=administrator&number=1');
        $variation = array(
            'post_author' => $admin[0]->ID,
            'post_status' => 'publish',
            'post_name' => 'product-' . $productId . '-variation',
            'post_parent' => $productId,
            'post_title' => $content['option1'],
            'post_type' => 'product_variation',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
        );

        // Insert
        $variantId = wp_insert_post($variation);

        // Update meta
        if (isset($content['price'])) {
            update_post_meta($variantId, '_price', $content['price']);
            update_post_meta($variantId, '_regular_price', $content['price']);
        }

        if (isset($content['option1'])) {
            update_post_meta($variantId, '_beeketing_option1', $content['option1']);
        }

        // Check simple product
        $checkProduct = wc_get_product($productId);
        if ($checkProduct->is_type('simple')) {
            // If downloadable product
            $downloadable = get_post_meta($productId, '_downloadable', true);
            if ($downloadable) {
                update_post_meta($variantId, '_downloadable', $downloadable);
                $downloadable_files = get_post_meta($productId, '_downloadable_files', true);
                update_post_meta($variantId, '_downloadable_files', $downloadable_files);
            }

            // If virtual product
            $virtual = get_post_meta($productId, '_virtual', true);
            if ($virtual) {
                update_post_meta($variantId, '_virtual', $virtual);
            }
        }

        if (isset($content['sku'])) {
            update_post_meta($variantId, '_sku', $content['sku']);
        }

        if (isset($content['attributes'])) {
            foreach ($content['attributes'] as $name => $attribute) {
                update_post_meta($variantId, $name, $attribute);
            }
        }

        return $this->get(array(
            'resource_id' => $variantId,
        ));

    }

    /**
     * Delete variant
     * @param $arg
     * @return boolean
     */
    public function delete($arg)
    {
        $variant_id = $arg['variant_id'];
        $variation = get_post($variant_id);
        if ($variation && 'product_variation' == $variation->post_type) {
            return wp_delete_post($variant_id);
        }

        return false;
    }

    /**
     * Set option1s
     * @param $option1s
     */
    public function setOption1s($option1s)
    {
        $this->option1s = $option1s;
        $this->is_option1s_loaded = true;
    }

    /**
     * Get variants by product
     *
     * @param \WC_Product $product
     * @return array
     */
    public function getVariantsByProduct($product)
    {
        if ($product->is_type('variable')) {
            $variants = $this->getVariantsForVariableProduct($product);
        } else {
            $variants = array(
                $this->formatVariant($product, $product)
            );
        }

        return $variants;
    }

    /**
     * Set wc products variants
     * @param $wc_products_variants
     */
    public function setWCProductsVariants($wc_products_variants)
    {
        $this->wc_products_variants = $wc_products_variants;
    }

    /**
     * Set wc products variants images
     * @param $images
     */
    public function setWCProductsVariantsImages($images)
    {
        $this->wc_products_variants_images = $images;
    }

    /**
     * Get Variants images by variants
     * @param array $variants An array of beeketing variants
     * @return array|mixed
     */
    public function getVariantsImages($variants)
    {
        $images = [];

        if (is_array($variants) && count($variants) > 0) {

            foreach ($variants as $variant) {
                if (!isset($this->wc_products_variants_images[$variant->id])
                    || !is_array($this->wc_products_variants_images[$variant->id])) {
                    continue;
                }

                $images = array_merge($images, $this->wc_products_variants_images[$variant->id]);
            }

        }

        return $images;
    }

    /**
     * Format variant
     *
     * @param \WC_Product|\WC_Product_Variation|\WC_Product_Variable $variant
     * @param \WC_Product $product
     * @return Variant
     */
    public function formatVariant($variant, $product)
    {
        // Check wc version
        if (Helper::isWc3()) {
            $variantId = $variant->get_id();
            $productId = $product->get_id();
            $dateCreated = $variant->get_date_created();
            $dateModified = $variant->get_date_modified();
        } else {
            $variantId = $variant->is_type('variation') ? $variant->get_variation_id() : $variant->id;
            $productId = $product->is_type('variation') ? $product->get_variation_id() : $product->id;
            $dateCreated = $variant->date_created;
            $dateModified = $variant->date_modified;
        }

        // Get variant attributes
        $attributes = array();
        $variantName = array();
        if ($variant->is_type('variation')) {
            $productAttributes = array();
            if ($product->is_type('variable')) {
                $productAttributes = $product->get_variation_attributes();
            }
            // Variation attributes
            foreach ($variant->get_variation_attributes() as $attributeName => $attribute) {
                // Taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
                $product_attribute_name = str_replace('attribute_', '', $attributeName);
                if (
                    !$attribute &&
                    isset($productAttributes[$product_attribute_name]) &&
                    is_array($productAttributes[$product_attribute_name])
                ) {
                    // Get first attribute if any attribute
                    $attribute = array_shift($productAttributes[$product_attribute_name]);
                }

                $attrName = Helper::getAttributeName($product_attribute_name);
                $attributes[$attrName] = $attribute;

                // Get term by slug
                $term_name = null;
                $term_key = $product_attribute_name . $attribute;
                if (isset(self::$terms[$term_key])) {
                    $term_name = self::$terms[$term_key];
                } else {
                    $term = get_term_by('slug', $attribute, $product_attribute_name);
                    if ($term) {
                        self::$terms[$term_key] = $term->name;
                        $term_name = $term->name;
                    }
                }
                $variantName[] = $term_name ? $term_name : $attribute;
            }

        }

        $option1 = null;
        if (!$product->is_type('simple')) {
            if ($this->is_option1s_loaded) {
                $option1 = isset($this->option1s[$variantId]) ? $this->option1s[$variantId] : null;
            } else {
                $option1 = get_post_meta($variantId, '_beeketing_option1', true);
            }
        }

        $images = array();
        if ($variant->is_type('variation')) {
            $images = isset($this->wc_products_variants_images[$variantId]) ?
                $this->wc_products_variants_images[$variantId] :
                $this->resourceManager->imageManager->getProductImagesByWCProduct($variant);
        }

        $result = new Variant();

        $result->id = $variantId;
        $result->product_id = $productId;
        $result->barcode = '';
        $result->image_id = (isset($images[0]) && $images[0]->id) ? $images[0]->id : '';
        $result->title = $option1 ? $option1 : ($variantName ? implode('/', $variantName) : $variant->get_title());
        $result->price = $variant->get_price();
        $result->price_compare = $variant->get_regular_price() ? $variant->get_regular_price() : '';
        $result->option1 = $option1 ? $option1 : (isset($variantName[0]) ? $variantName[0] : $variant->get_title());
        $result->option2 = isset($variantName[1]) ? $variantName[1] : '';
        $result->option3 = isset($variantName[2]) ? $variantName[2] : '';
        $result->grams = '';
        $result->position = '';
        $result->sku = $variant->get_sku();
        $result->inventory_management = $variant->managing_stock() ? Constants::PLATFORM_CODE : '';
        $result->inventory_policy = $variant->backorders_allowed() ? 'continue' : 'deny';
        $result->inventory_quantity = $variant->get_stock_quantity();
        $result->fulfillment_service = 'n/a';
        $result->weight = $variant->get_weight() ? $variant->get_weight() : '';
        $result->weight_unit = '';
        $result->requires_shipping = 'n/a';
        $result->taxable = $variant->is_taxable();
        $result->updated_at = CommonHelper::formatDate($dateModified);
        $result->created_at = CommonHelper::formatDate($dateCreated);
        $result->in_stock = $variant->is_in_stock();
        $result->attributes = $attributes;

        return $result;
    }

    /**
     * @param \WC_Product $product
     * @return array
     */
    public function getVariantsForVariableProduct($product)
    {
        $variants = array();

        $product_id = Helper::isWc3() ? $product->get_id() : $product->id; // Check wc version
        if (isset($this->wc_products_variants[$product_id])) {
            /** @var \WC_Product $variant */
            foreach ($this->wc_products_variants[$product_id] as $variant) {
                if (!$variant || !$variant->exists() || !$variant->get_regular_price()) {
                    continue;
                }

                $variant_id = $variant->get_id();
                if ($this->is_option1s_loaded) {
                    $option1 = isset($this->option1s[$variant_id]) ? $this->option1s[$variant_id] : null;
                } else {
                    $option1 = get_post_meta($variant_id, '_beeketing_option1', true);
                }
                if ($product->is_type('variable') || Helper::isBeeketingHiddenName($option1)) {
                    $variants[] = $this->formatVariant($variant, $product);
                }
            }
        } else {
            $args = array(
                'post_parent' => $product_id, // Check wc version
                'post_type' => 'product_variation',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'fields' => 'ids',
                'post_status' => 'publish',
                'numberposts' => -1
            );
            $variant_ids = get_posts($args);

            foreach ($variant_ids as $variant_id) {
                $variant = wc_get_product($variant_id);
                if (!$variant || !$variant->exists()) {
                    continue;
                }

                if ($this->is_option1s_loaded) {
                    $option1 = isset($this->option1s[$variant_id]) ? $this->option1s[$variant_id] : null;
                } else {
                    $option1 = get_post_meta($variant_id, '_beeketing_option1', true);
                }
                if ($product->is_type('variable') || Helper::isBeeketingHiddenName($option1)) {
                    $variants[] = $this->formatVariant($variant, $product);
                }
            }
        }

        return $variants;
    }

}
