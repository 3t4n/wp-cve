<?php

declare(strict_types=1);

namespace Qodax\CheckoutManager\Component\DisplayRule;

use Qodax\CheckoutManager\Component\DisplayRule\Condition\ConditionInterface;
use Qodax\CheckoutManager\Includes\Fields\CheckoutField;

if ( ! defined('ABSPATH')) {
    exit;
}

class DisplayRule implements \JsonSerializable
{
    private string $action;
    private ConditionInterface $condition;

    public function __construct(string $action, ConditionInterface $condition)
    {
        $this->action = $action;
        $this->condition = $condition;
    }

    public function showField(CheckoutField $field): bool
    {
        $show = $this->action === 'show';

        if ($this->condition->apply()) {
            return $show;
        } else {
            return !$show;
        }
    }

    public function jsonSerialize()
    {
        return [
            'action' => $this->action,
            'condition' => $this->condition->jsonSerialize(),
        ];
    }
}
