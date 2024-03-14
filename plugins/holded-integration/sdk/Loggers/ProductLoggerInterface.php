<?php

declare(strict_types=1);

namespace Holded\SDK\Loggers;

interface ProductLoggerInterface
{
    public function setLastUpdatedProductSku(string $lastUpdatedProductSku): void;

    public function getLastUpdatedProductSku(): ?string;
}
