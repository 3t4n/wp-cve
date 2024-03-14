<?php

declare(strict_types=1);

namespace Holded\SDK\Loggers;

class ProductLogger implements ProductLoggerInterface
{
    /** @var ProductLogger */
    protected static $instance;

    /** @var string */
    private $lastUpdatedProductSku;

    public static function getLogger(): ProductLogger
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setLastUpdatedProductSku(string $lastUpdatedProductSku): void
    {
        $this->lastUpdatedProductSku = $lastUpdatedProductSku;
    }

    public function getLastUpdatedProductSku(): ?string
    {
        return $this->lastUpdatedProductSku;
    }
}
