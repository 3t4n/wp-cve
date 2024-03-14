<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Services;

use Holded\SDK\Holded as HoldedSDK;

abstract class AbstractService
{
    /** @var HoldedSDK */
    protected $holdedSDK;

    public function __construct(HoldedSDK $holdedSDK)
    {
        $this->holdedSDK = $holdedSDK;
    }
}
