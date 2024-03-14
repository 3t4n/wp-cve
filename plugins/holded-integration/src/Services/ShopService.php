<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Services;

use Holded\SDK\DTOs\Shop\Shop;

class ShopService extends AbstractService
{
    public static function getShopUrl(): string
    {
        $url = site_url();

        return is_string($url) ? $url : '';
    }

    public static function getProviderName(): string
    {
        return 'woocommerce';
    }

    public function checkShop(): bool
    {
        return $this->holdedSDK->checkShop($this->createShop());
    }

    public function removeShop(): bool
    {
        return $this->holdedSDK->removeShop($this->createShop());
    }

    public function createShop(): Shop
    {
        $shop = new Shop();
        $shop->shopUrl = self::getShopUrl();
        $shop->shopName = get_bloginfo('name');
        $shop->provider = self::getProviderName();
        $shop->version = HOLDED_VERSION;

        return $shop;
    }
}
