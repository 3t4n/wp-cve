<?php

declare(strict_types=1);

namespace Qodax\CheckoutManager\Component\DisplayRule\Condition;

class ShippingMethodCondition implements ConditionInterface
{
    private string $operator;
    private array $values;

    public function __construct(string $operator, array $values)
    {
        $this->operator = $operator;
        $this->values = $values;
    }

    public function apply(): bool
    {
        $shippingMethod = null;
        if (isset($_POST['shipping_method'])) {
            $shippingMethod = reset($_POST['shipping_method']);
        } else {
            $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
            if ($chosen_shipping_methods) {
                $shippingMethod = reset($chosen_shipping_methods);
            }
        }

        if ($shippingMethod === null) {
            return false;
        }

        $shippingMethod = preg_replace('/^(.+):.*/', '$1', $shippingMethod);
        $result = in_array($shippingMethod, $this->values);

        return $this->operator === 'in' ? $result : !$result;
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'shipping_method',
            'operator' => $this->operator,
            'value' => $this->values,
        ];
    }
}
