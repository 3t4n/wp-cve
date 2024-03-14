<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Hooks;

use Holded\SDK\Holded as HoldedSDK;

class HooksLoader
{
    /** @var HoldedSDK */
    protected $holdedSDK;

    public function __construct(HoldedSDK $holdedSDK)
    {
        $this->holdedSDK = $holdedSDK;
    }

    public function load(): void
    {
        $hooks = [
            OrderCompleted::class,
            ProductStockUpdated::class,
            ProductUpdated::class,
            RefundedCreated::class,
        ];

        foreach ($hooks as $hook) {
            (new $hook($this->holdedSDK))->init();
        }
    }
}
