<?php

namespace App\Hooks;

use App\Base\Webhook;
use App\Models\Order as OrderModel;

class Order
{
    /**
     * Create product webhook
     * @return void
     */
    public static function create($id)
    {
        $data = OrderModel::get($id);

        if (!$data) {
            return;
        }

        Webhook::send(Webhook::ORDER_CREATE, $data);
    }

    /**
     * Update product webhook
     * @return void
     */
    public static function update($id)
    {
        $data = OrderModel::get($id);

        if (!$data) {
            return;
        }

        Webhook::send(Webhook::ORDER_UPDATE, $data);
    }

    /**
     * Delete product webhook
     * @return void
     */
    public static function delete($id)
    {
        if (get_post_type($id) == 'shop_order') {
            $data = [
                'id' => $id
            ];

            Webhook::send(Webhook::ORDER_DELETE, $data);
        }
    }
}
