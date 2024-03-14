<?php

namespace App\Controllers;

use App\Base\Controller;
use App\Models\Customer as CustomerModel;
use App\Utils\Helper;
use WP_Error;

class Customer extends Controller
{
    /**
     * List customers
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

        $customers = new \WP_User_Query([
            'role' => 'customer',
            'number' => $limit,
            'page' => $page,
        ]);

        $customers_data = [];

        foreach ($customers->get_results() as $customer) {
            try {
                $customers_data[] = CustomerModel::map($customer);
            } catch (\Exception $e) {
                // continue...
            }
        }

        return $customers_data;
    }

    /**
     * Get single customer
     *
     * @param [type] $request
     * @return CustomerModel
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

        $customer = CustomerModel::get($request->get_param('id'));

        if (!$customer) {
            return new WP_Error('not_found', 'Resource not found', [
                'status' => 404
            ]);
        }

        return $customer;
    }

    /**
     * Customer count
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

        $customers = new \WP_User_Query([
            'role' => 'customer',
        ]);

        return [
            'count' => $customers->get_total()
        ];
    }
}
