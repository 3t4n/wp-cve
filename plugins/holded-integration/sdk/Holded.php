<?php

declare(strict_types=1);

namespace Holded\SDK;

use Holded\SDK\Clients\OrdersClient;
use Holded\SDK\Clients\ProductsClient;
use Holded\SDK\Clients\ShopsClient;
use Holded\SDK\DTOs\Order\Order;
use Holded\SDK\DTOs\Product\Product;
use Holded\SDK\DTOs\Shop\Shop;
use Holded\SDK\Services\HTTP\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;

class Holded
{
    /** @var Client */
    private $httpClient;

    /** @var OrdersClient */
    private $ordersClient;

    /** @var ProductsClient */
    private $productsClient;

    /** @var ShopsClient */
    private $shopClient;

    public function __construct(string $apiKey, LoggerInterface $logger, ?string $url = null)
    {
        $this->httpClient = new Client(HttpClient::create(), $logger, $apiKey, $url);
        $this->ordersClient = new OrdersClient($this->httpClient);
        $this->productsClient = new ProductsClient($this->httpClient);
        $this->shopClient = new ShopsClient($this->httpClient);
    }

    /**
     * @return void
     */
    public function syncOrder(Order $order)
    {
        $this->ordersClient->sync($order);
    }

    /**
     * @return bool|mixed
     */
    public function updateProduct(Product $product)
    {
        return $this->productsClient->updateProduct($product);
    }

    public function updateProductStock(Product $product): bool
    {
        return $this->productsClient->updateProductStock($product);
    }

    public function checkShop(Shop $shop): bool
    {
        return $this->shopClient->check($shop);
    }

    public function removeShop(Shop $shop): bool
    {
        return $this->shopClient->remove($shop);
    }
}
