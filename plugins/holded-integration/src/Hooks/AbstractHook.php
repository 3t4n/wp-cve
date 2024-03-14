<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Hooks;

use Holded\SDK\Holded as HoldedSDK;

abstract class AbstractHook
{
    /** @var HoldedSDK */
    protected $holdedSDK;

    public function __construct(HoldedSDK $holdedSDK)
    {
        $this->holdedSDK = $holdedSDK;
    }

    abstract public function init(): void;
}
