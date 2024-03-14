<?php

namespace App\Controllers;

use App\Utils\Helper;
use App\Base\Controller;
use App\Models\Product as ProductModel;
use WP_Error;

class Product extends Controller
{
    /**
     * List products
     * @return array
     */
    public static function list($request)
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        if (!Helper::isWooCommerceActive() || !Helper::isMinVerWc()) {
            return [];
        }

        $page = $request->get_param('page') ?: 1;
        $limit = $request->get_param('limit') ?: 20;

        $products = wc_get_products([
            'status' => 'publish',
            'limit' => $limit,
            'page' => $page,
        ]);

        if (empty($products)) {
            return [];
        }

        $products_data = [];

        foreach ($products as $product) {
            try {
                $products_data[] = ProductModel::map($product);
            } catch (\Exception $e) {
                // continue...
            }
        }

        return $products_data;
    }

    /**
     * Get single product
     *
     * @param [type] $request
     * @return ProductModel
     */
    public static function get($request)
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        if (!Helper::isWooCommerceActive() || !Helper::isMinVerWc()) {
            return [];
        }

        $product = ProductModel::get($request->get_param('id'));

        if (!$product) {
            return new WP_Error('not_found', 'Resource not found', [
                'status' => 404
            ]);
        }

        return $product;
    }

    /**
     * Product count
     * @return int
     */
    public static function count()
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        if (!Helper::isWooCommerceActive() || !Helper::isMinVerWc()) {
            return [
                'count' => 0
            ];
        }

        $count_posts = wp_count_posts('product');

        return [
            'count' => (int) $count_posts->publish
        ];
    }
}
