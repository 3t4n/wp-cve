<?php

namespace App\Hooks;

use App\Base\Webhook;
use App\Models\Product as ProductModel;

class Product
{
    /**
     * Create product webhook
     * @return void
     */
    public static function create($id)
    {
        $data = ProductModel::get($id);

        if (!$data) {
            return;
        }

        Webhook::send(Webhook::PRODUCT_CREATE, $data);
    }

    /**
     * Update product webhook
     * @return void
     */
    public static function update($id)
    {
        $data = ProductModel::get($id);

        if (!$data) {
            return;
        }

        Webhook::send(Webhook::PRODUCT_UPDATE, $data);
    }

    /**
     * Delete product webhook
     * @return void
     */
    public static function delete($id)
    {
        $data = [
            'id' => $id
        ];

        Webhook::send(Webhook::PRODUCT_DELETE, $data);
    }
}
