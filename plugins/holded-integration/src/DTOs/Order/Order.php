<?php

declare(strict_types=1);

namespace Holded\Woocommerce\DTOs\Order;

class Order extends \Holded\SDK\DTOs\Order\Order
{
    /** @var string */
    public $woocommerceUrl;

    /** @var string|false */
    public $woocommerceTaxes;

    /** @var string|false */
    public $woocommerceSummaryTaxes;

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'woocommerceUrl'          => $this->woocommerceUrl,
                'woocommerceTaxes'        => $this->woocommerceTaxes,
                'woocommerceSummaryTaxes' => $this->woocommerceSummaryTaxes,
            ]
        );
    }
}
