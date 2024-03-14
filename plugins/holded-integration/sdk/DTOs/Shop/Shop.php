<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Shop;

class Shop implements \JsonSerializable
{
    /** @var string */
    public $shopUrl;

    /** @var string */
    public $shopName;

    /** @var string */
    public $provider;

    /** @var string */
    public $version;

    public function jsonSerialize()
    {
        return [
            'shopUrl'  => $this->shopUrl,
            'shopName' => $this->shopName,
            'provider' => $this->provider,
            'version'  => $this->version,
        ];
    }
}
