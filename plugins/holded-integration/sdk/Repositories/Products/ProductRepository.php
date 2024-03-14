<?php

declare(strict_types=1);

namespace Holded\SDK\Repositories\Products;

use Holded\SDK\DTOs\Product\Product;
use Holded\SDK\Repositories\AbstractRepository;

final class ProductRepository extends AbstractRepository
{
    public function update(Product $product): bool
    {
        $result = $this->client->call('internal/pluginconnector/product:sync', $product, 'POST', true);

        return is_array($result) && isset($result['status']) && $result['status'] == 1;
    }

    public function updateStock(Product $product): bool
    {
        $result = true;
        if ($product->variants) {
            foreach ($product->variants as $variant) {
                $callResult = $this->client->call('internal/pluginconnector/stock', [
                    'shopUrl'  => $product->shopUrl,
                    'provider' => $product->provider,
                    'sku'      => $variant->sku,
                    'stock'    => $variant->stock,
                ], 'PUT', true);

                $result = $result && is_array($callResult) && isset($callResult['status']) && $callResult['status'] == 1;
            }
        } else {
            $callResult = $this->client->call('internal/pluginconnector/stock', [
                'shopUrl'  => $product->shopUrl,
                'provider' => $product->provider,
                'sku'      => $product->sku,
                'stock'    => $product->stock,
            ], 'PUT', true);

            $result = is_array($callResult) && isset($callResult['status']) && $callResult['status'] == 1;
        }

        return $result;
    }
}
