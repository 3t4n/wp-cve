<?php

declare(strict_types=1);

namespace Holded\SDK\Repositories\Orders;

use Holded\SDK\DTOs\Order\Order;
use Holded\SDK\Repositories\AbstractRepository;

final class OrderRepository extends AbstractRepository
{
    private const URL_API = 'internal/pluginconnector/sync_order_hook';

    /**
     * @return void
     */
    public function sync(Order $order)
    {
        $data = $this->getDataFromOrder($order);
        $this->client->post(self::URL_API, $data, false);
    }

    /**
     * @return array<string, mixed>
     */
    private function getDataFromOrder(Order $order)
    {
        $orderData = json_decode(json_encode($order) ?: '', true);

        return [
            'holdedId' => $order->siteUrl,
            'siteUrl'  => $order->siteUrl,
            'provider' => $order->marketplace,
            'data'     => $orderData,
        ];
    }
}
