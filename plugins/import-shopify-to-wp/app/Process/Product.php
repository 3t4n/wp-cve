<?php

namespace S2WPImporter\Process;

use S2WPImporter\IdMapping;
use S2WPImporter\Image;
use S2WPImporter\VariationsLog;
use WC_Product;
use WC_Product_Attribute;
use WC_Product_Variation;

class Product extends AbstractRecord implements IRecord
{
    /**
     * @var WC_Product
     */
    protected $product;

    /**
     * @var array
     */
    protected $item;

    /**
     * @var int|null Parent product image ID
     */
    protected $parentProductImageId;

    /**
     * @var IdMapping Map the variation IDs: <old_shopify_variation_id>:<new_woocommerce_variation_id>
     */
    protected $variationIds;

    /**
     * @var IdMapping Map the image IDs: <old_shopify_image_id>:<new_woocommerce_image_id>
     */
    protected $imageIds;

    /**
     * Product constructor.
     *
     * @param array      $item
     * @param WC_Product $product
     */
    public function __construct($item, $product)
    {
        $this->item = $item;
        $this->product = $product;
        $this->variationIds = new IdMapping();
        $this->imageIds = new IdMapping();
    }

    /**
     * Prepare the parent product
     *
     * @return $this
     */
    public function parse()
    {
        $this->product->set_name(sanitize_text_field($this->item['title']));
//        $this->product->set_price($this->item['variants'][0]['price']);
//        $this->product->set_regular_price($this->item['variants'][0]['price']);
//        $this->product->set_sale_price($this->item['variants'][0]['price']);

        $this->product->set_date_created(sanitize_text_field($this->item['created_at']));
        $this->product->set_date_modified(sanitize_text_field($this->item['created_at']));

        if ($this->item['status'] === 'active') {
            $this->product->set_status('publish');
        }
        else {
            $this->product->set_status('draft');
        }

        $this->product->set_description(!empty($this->item['body_html']) ? wp_kses_post($this->item['body_html']) : '');

        return $this;
    }

    /**
     * Before inserting the parent product in DB
     */
    public function beforeSave()
    {
        $this->addAttributes();
        $this->addTags();
        $this->addCategories();
    }

    /**
     * Insert the parent product in DB
     *
     * @return int
     */
    public function save()
    {
        return $this->product->save();
    }

    /**
     * After inserting the parent product in DB
     */
    public function afterSave($productId)
    {
        $this->addVariants();
        $this->addImages();
    }

    /*
    -------------------------------------------------------------------------------
    Internal
    -------------------------------------------------------------------------------
    */
    protected function addTags()
    {
        $tagIds = [];

        if (!empty($this->item['tags'])) {
            $tags = array_map('trim', explode(',', $this->item['tags']));

            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $sanitizedTag = wc_sanitize_term_text_based($tag);

                    if (term_exists($sanitizedTag, 'product_tag')) {
                        $existingTermId = get_term_by('name', $sanitizedTag, 'product_tag', ARRAY_A);
                        if (!is_wp_error($existingTermId) && is_array($existingTermId) && !empty($existingTermId['term_id'])) {
                            $tagIds[] = $existingTermId['term_id'];
                            continue;
                        }
                    }

                    $term = wp_insert_term($sanitizedTag, 'product_tag');

                    if (is_wp_error($term)) {
                        $this->addSoftError($term->get_error_message());
                    }

                    if (is_numeric($term)) {
                        $tagIds[] = $term;
                    }

                    if (is_array($term) && !empty($term['term_id'])) {
                        $tagIds[] = $term['term_id'];
                    }
                }
            }
        }

        if (!empty($tagIds)) {
            $this->product->set_tag_ids($tagIds);
        }
    }

    protected function addCategory($categoryKey)
    {
        $categoryId = null;

        if (!empty($this->item[$categoryKey])) {
            $category = wc_sanitize_term_text_based($this->item[$categoryKey]);

            if (!empty($category)) {
                if (term_exists($category, 'product_cat')) {
                    $existingTermId = get_term_by('name', $category, 'product_cat', ARRAY_A);

                    if (is_wp_error($existingTermId)) {
                        $this->addSoftError($existingTermId->get_error_message());
                    }

                    if (!is_wp_error($existingTermId) && is_array($existingTermId) && !empty($existingTermId['term_id'])) {
                        $categoryId = $existingTermId['term_id'];
                    }
                }
                else {
                    $term = wp_insert_term($category, 'product_cat');

                    if (is_wp_error($term)) {
                        $this->addSoftError($term->get_error_message());
                    }

                    if (is_numeric($term)) {
                        $categoryId = $term;
                    }

                    if (is_array($term) && !empty($term['term_id'])) {
                        $categoryId = $term['term_id'];
                    }
                }
            }
        }

        return $categoryId;
    }

    protected function addCategories()
    {
        $vendor = $this->addCategory('vendor');
        $product_type = $this->addCategory('product_type');
        $categoryIds = array_filter([$vendor, $product_type]);

        if (!empty($categoryIds)) {
            $this->product->set_category_ids($categoryIds);
        }
    }

    /**
     * Assign a featured image to the parent product
     */
    protected function addFeaturedImage()
    {
        if (!empty($this->item['image']['src'])) {
            $image = new Image();
            $image->downloadAttachment($this->item['image']['src']);

            if ($image->hasSoftErrors()) {
                foreach ($image->getSoftErrors() as $softImageError) {
                    $this->addSoftError($softImageError);
                }
            }

            if (!$image->hasId()) {
                $this->addSoftError('EmptyID: Failed to download --> ' . $this->item['image']['src']);
            }
            else {
                $this->product->set_image_id($image->getId());
                $this->imageIds->add((int)$this->item['image']['id'], $image->getId());
                $this->parentProductImageId = $image->getId();
            }
        }
    }

    /**
     * Assign attributes
     */
    public function addAttributes()
    {
        $attributes = [];

        if (!empty($this->item['options'])) {
            foreach ($this->item['options'] as $option) {
                $attributes[] = $this->addAttribute($option);
            }
        }

        if (!empty($attributes)) {
            $this->product->set_attributes($attributes);
        }
    }

    /**
     * Single attribute handler
     *
     * @param array $option
     *
     * @return WC_Product_Attribute
     */
    protected function addAttribute($option): WC_Product_Attribute
    {
        $attribute = new WC_Product_Attribute();
        $attribute->set_id(0);
        $attribute->set_name(wc_sanitize_term_text_based($option['name']));
        $attribute->set_options(is_array($option['values']) ? array_map('wc_sanitize_term_text_based', $option['values']) : []);
        $attribute->set_position((int)$option['position']);
        $attribute->set_visible(1);
        $attribute->set_variation(1);

        return $attribute;
    }

    /**
     * Assign variations
     */
    protected function addVariants()
    {
        if (!empty($this->item['variants'])) {
            foreach ($this->item['variants'] as $variant) {
                $this->addVariation($variant);
            }
        }
    }

    /**
     * Single variation handler
     *
     * @param array $variant
     */
    protected function addVariation($variant)
    {
        $variation = new WC_Product_Variation();
        $variation->set_name($variant['title']);

        if (!empty($variant['sku'])) {
            try {
                $variation->set_sku(wc_sanitize_term_text_based($variant['sku']));
            }
            catch (\WC_Data_Exception $e) {
                $this->addSoftError($e->getMessage());
            }
        }

        $variation->set_regular_price(wc_format_decimal($variant['price']));
        $variation->set_parent_id((int)$this->product->get_id());

        $attributes = [];
        if (!empty($this->item['options'])) {
            foreach ($this->item['options'] as $key => $option) {
                $prop = 'option' . ($key + 1);

                if (!empty($variant[$prop])) {
                    $attributes[sanitize_title($option['name'])] = $variant[$prop];
                }
            }
        }

        if (!empty($attributes)) {
            $variation->set_attributes($attributes);
        }

        if (!empty($this->item['weight']) && (float)$this->item['weight'] > 0) {
            $variation->set_weight((float)$this->item['weight']);
        }

        // Managing inventory
        if (!empty($this->item['inventory_management'])) {
            $variation->set_manage_stock(true);
            $variation->set_stock_quantity((int)$this->item['inventory_quantity']);
        }

        $variation->save();

        // Keep an history of the old and new IDs.
        $this->variationIds->add((int)$variant['id'], $variation->get_id());

        $vlog = new VariationsLog();
        $vlog->insert([
            'old_id' => (int)$variant['id'],
            'new_id' => (int)$variation->get_id(),
        ]);
    }

    /**
     * Assign images to variations and parent product gallery.
     */
    protected function addImages()
    {
        $this->addFeaturedImage();

        if (!empty($this->item['images'])) {
            $productImageIds = [];

            foreach ($this->item['images'] as $key => $imgObj) {
                // If this image has not been already uploaded
                if (!$this->imageIds->has($imgObj['id'])) {
                    $image = new Image();
                    $image->downloadAttachment($imgObj['src']);

                    if ($image->hasSoftErrors()) {
                        foreach ($image->getSoftErrors() as $softImageError) {
                            $this->addSoftError($softImageError);
                        }
                    }

                    if (!$image->hasId()) {
                        $this->addSoftError('EmptyID: Failed to download --> ' . $imgObj['src']);
                    }
                    else {
                        $this->imageIds->add((int)$imgObj['id'], $image->getId());
                    }
                }

                // If this new image ID is uploaded add it to respective variation
                if (!empty($imgObj['variant_ids'])) {
                    if ($this->imageIds->has($imgObj['id'])) {
                        foreach ($imgObj['variant_ids'] as $oldVariantId) {
                            if (!$this->variationIds->has($oldVariantId)) {
                                continue; // No variant ID
                            }

                            $variation = new WC_Product_Variation($this->variationIds->get($oldVariantId));
                            $variation->set_image_id($this->imageIds->get((int)$imgObj['id']));

                            $variation->save();
                        }
                    }
                }
                else {
                    if ($this->imageIds->has($imgObj['id'])) {
                        $productImageIds[] = $this->imageIds->get((int)$imgObj['id']);
                    }
                }
            }

            // Images that do not belong to a variation, will be added to the parent product
            $productImageIds = array_filter($productImageIds, function ($imageId) {
                return $imageId !== $this->parentProductImageId;
            });

            if (!empty($productImageIds)) {
                $this->product->set_gallery_image_ids($productImageIds);
            }
        }

        $this->product->save();
    }

}
