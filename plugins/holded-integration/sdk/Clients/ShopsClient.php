<?php

declare(strict_types=1);

namespace Holded\SDK\Clients;

use Holded\SDK\DTOs\Shop\Shop;
use Holded\SDK\Repositories\Shops\ShopRepository;
use Holded\SDK\Services\HTTP\Client;

class ShopsClient
{
    /** @var ShopRepository */
    private $shopRepository;

    public function __construct(Client $client)
    {
        $this->shopRepository = new ShopRepository($client);
    }

    public function check(Shop $shop): bool
    {
        return $this->shopRepository->check($shop);
    }

    public function remove(Shop $shop): bool
    {
        return $this->shopRepository->remove($shop);
    }
}
