<?php

namespace App\Controllers;

use App\Base\Controller;
use App\Models\Shop as ShopModel;
use WP_Error;

class Shop extends Controller
{
    /**
     * All
     * @return array
     */
    public static function settings()
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        return ShopModel::get();
    }
}
