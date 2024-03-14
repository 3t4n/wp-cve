<?php

declare(strict_types=1);

namespace Qodax\CheckoutManager\Component\DisplayRule;

use Qodax\CheckoutManager\Component\DisplayRule\Condition\AndCondition;
use Qodax\CheckoutManager\Component\DisplayRule\Condition\OrCondition;
use Qodax\CheckoutManager\Component\DisplayRule\Condition\ShippingMethodCondition;

class DisplayRuleBuilder
{
    public function buildFromArray(array $data): DisplayRule
    {
        $conditions = [];
        foreach ($data['conditions'] as $item) {
            switch ($item['name']) {
                case 'shipping_method':
                    $conditions[] = new ShippingMethodCondition($item['operator'], $item['value']);
                    break;
                default:
                    throw new \LogicException("Condition {$item['name']} not supported");
            }
        }

        if ($data['logic'] === 'and') {
            $condition = new AndCondition($conditions);
        } elseif ($data['logic'] === 'or') {
            $condition = new OrCondition($conditions);
        } else {
            throw new \LogicException("Unsupported logic {$data['logic']}");
        }

        return new DisplayRule($data['action'], $condition);
    }
}
