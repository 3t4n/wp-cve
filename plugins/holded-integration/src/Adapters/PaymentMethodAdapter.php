<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Adapters;

use Holded\SDK\DTOs\Payment\PaymentMethod;

final class PaymentMethodAdapter
{
    public static function fromWoocommerceToDTO(\WC_Payment_Gateway $gateway): PaymentMethod
    {
        $method = new PaymentMethod();
        $method->key = $gateway->id;
        $method->name = $gateway->title;

        return $method;
    }
}
