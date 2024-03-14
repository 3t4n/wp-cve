<?php

namespace App\Models;

use WP_User;

class Customer
{
    /**
     * Get product
     * @param $id Customer id
     * @return array
     */
    public static function get($id)
    {
        $resp = null;
        $customer = get_user_by('id', $id);

        if (!$customer || !in_array('customer', $customer->roles)) {
            return null;
        }

        try {
            $resp = self::map($customer);
        } catch (\Exception $e) {
            // continue
        }

        return $resp;
    }

    /**
     * Map customer
     *
     * @param WP_User $customer
     * @return array
     */
    public static function map($customer)
    {
        if (!($customer instanceof WP_User)) {
            throw new \Exception('First argument must be instance of WP_User');
        }

        return [
            'id' => $customer->ID,
            'nickname' => $customer->nickname,
            'description' => $customer->description,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'login' => $customer->user_login,
            'nicename' => $customer->user_nicename,
            'email' => $customer->user_email,
            'url' => $customer->user_url,
            'registered' => $customer->user_registered,
            'status' => $customer->user_status,
            'level' => $customer->user_level,
            'display_name' => $customer->display_name,
            'locale' => $customer->locale,
        ];
    }
}
