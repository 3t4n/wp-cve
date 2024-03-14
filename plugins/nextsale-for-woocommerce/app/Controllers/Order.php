<?php

namespace App\Controllers;

use App\Base\Controller;
use App\Models\Order as OrderModel;
use App\Utils\Helper;
use WP_Error;

class Order extends Controller
{
    /**
     * List orders
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

        $orders = wc_get_orders([
            'limit' => $limit,
            'page' => $page,
        ]);

        if (empty($orders)) {
            return [];
        }

        $orders_data = [];

        foreach ($orders as $order) {
            try {
                $orders_data[] = OrderModel::map($order);
            } catch (\Exception $e) {
                // continue...
            }
        }

        return $orders_data;
    }

    /**
     * Get single order
     *
     * @param [type] $request
     * @return OrderModel
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

        $order = OrderModel::get($request->get_param('id'));

        if (!$order) {
            return new WP_Error('not_found', 'Resource not found', [
                'status' => 404
            ]);
        }

        return $order;
    }

    /**
     * Order count
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

        $order_statuses = array_keys(wc_get_order_statuses());
        $count = 0;

        foreach ($order_statuses as $status) {
            $count += wc_orders_count(str_replace('wc-', '', $status));
        }

        return [
            'count' => $count
        ];
    }
}
