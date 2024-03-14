<?php

declare(strict_types=1);

namespace Holded\SDK\Repositories\Shops;

use Holded\SDK\DTOs\Shop\Shop;
use Holded\SDK\Repositories\AbstractRepository;

class ShopRepository extends AbstractRepository
{
    public function check(Shop $shop): bool
    {
        $result = $this->client->call('internal/pluginconnector/shop:sync', $shop, 'POST', true);

        return (is_array($result) && isset($result['status']) && $result['status'] == 1) || $result === true;
    }

    public function remove(Shop $shop): bool
    {
        $result = $this->client->call("internal/pluginconnector/shop/$shop->provider/$shop->shopUrl", $shop, 'DELETE');

        return (is_array($result) && isset($result['status']) && $result['status'] == 1) || $result === true;
    }
}
