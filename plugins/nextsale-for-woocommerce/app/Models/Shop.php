<?php

namespace App\Models;

use App\Utils\Helper;

class Shop
{
    /**
     * Get product
     * @param $id
     * @return Product
     */
    public static function get()
    {
        $user_email = get_option('admin_email');
        $user = get_user_by('email', $user_email);

        $first_name = isset($user->first_name) ? $user->first_name : 'Shop';
        $last_name = isset($user->last_name) ? $user->last_name : 'Admin';

        $data = [
            'name' => get_option('blogname'),
            'domain' => Helper::getDomain(),
            'shop_owner' => $first_name . ' ' . $last_name,
            'email' => $user_email,
            'currency' => get_option('woocommerce_currency'),
            'platform' => Helper::getPlatform()
        ];

        return $data;
    }
}
