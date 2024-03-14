<?php

namespace App\Base;

use App\Models\Shop as ShopModel;

class Activate
{
    /**
     * Activate
     * @return void
     */
    public static function invoke()
    {
        flush_rewrite_rules();

        self::updateShopDetails();
    }

    /**
     * Update shop details
     *
     * @return void
     */
    public static function updateShopDetails()
    {
        $access_token = get_option('nextsale_access_token');

        if (!$access_token) {
            return;
        }

        $data = ShopModel::get();
        Webhook::send(Webhook::SHOP_UPDATE, $data);
    }
}
