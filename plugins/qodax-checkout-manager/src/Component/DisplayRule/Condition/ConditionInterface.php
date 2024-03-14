<?php

declare(strict_types=1);

namespace Qodax\CheckoutManager\Component\DisplayRule\Condition;

interface ConditionInterface extends \JsonSerializable
{
    public function apply(): bool;
}
