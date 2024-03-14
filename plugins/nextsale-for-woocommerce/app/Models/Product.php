<?php

namespace App\Models;

use App\Utils\Helper;
use WC_Product;

class Product
{
    const PUBLISHED = 'publish';
    const TRASH = 'trash';
    const DRAFT = 'auto-draft';
    const TYPE_PRODUCT = 'product';
    const TYPE_VARIABLE = 'variable';

    /**
     * Get product
     * @param $id
     * @return Product
     */
    public static function get($id)
    {
        $resp = null;
        $product = wc_get_product($id);

        if (!$product || $product->get_status() != self::PUBLISHED) {
            return null;
        }

        try {
            $resp = self::map($product);
        } catch (\Exception $e) {
            // continue
        }

        return $resp;
    }

    /**
     * Map product
     *
     * @param WC_Product $product
     * @return array
     */
    public static function map($product)
    {
        if (!($product instanceof WC_Product)) {
            throw new \Exception('First argument must be instance of WC_Product');
        }

        $variations = [];
        if ($product->get_type() == self::TYPE_VARIABLE) {
            $variations = $product->get_available_variations();
        }

        $data = $product->get_data();

        $data['image_url'] = wp_get_attachment_url($product->get_image_id());
        $data['url'] = get_permalink($product->get_id());
        $data['relative_url'] = Helper::getRelativeUrl($data['url']);
        $data['variants'] = $variations;

        return $data;
    }
}
