<?php

namespace App\Hooks;

use App\Base\Webhook;
use App\Models\Shop as ShopModel;

class Shop
{
    /**
     * Update
     * @return void
     */
    public static function update($option_name, $old_value, $new_value)
    {
        if ($option_name != 'blogname') {
            return;
        }

        if ($old_value == $new_value) {
            return;
        }

        $data = ShopModel::get();

        Webhook::send(Webhook::SHOP_UPDATE, $data);
    }
}
