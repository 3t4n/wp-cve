<?php

namespace App\Hooks;

use App\Base\Webhook;
use App\Models\Customer as CustomerModel;

class Customer
{
    /**
     * Create customer webhook
     *
     * @param $id user id
     * @throws \Exception
     */
    public static function create($id)
    {
        $data = CustomerModel::get($id);

        if (!$data) {
            return;
        }

        Webhook::send(Webhook::CUSTOMER_CREATE, $data);
    }

    /**
     * Update customer webhook
     *
     * @param $id
     * @throws \Exception
     */
    public static function update($id)
    {
        $data = CustomerModel::get($id);

        if (!$data) {
            return;
        }

        Webhook::send(Webhook::CUSTOMER_UPDATE, $data);
    }

    /**
     * Delete customer webhook
     *
     * @param $id
     * @throws \Exception
     */
    public static function delete($id)
    {
        $data = ['id' => $id];

        Webhook::send(Webhook::CUSTOMER_DELETE, $data);
    }
}
