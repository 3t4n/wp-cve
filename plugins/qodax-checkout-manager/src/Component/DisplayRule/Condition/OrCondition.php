<?php

declare(strict_types=1);

namespace Qodax\CheckoutManager\Component\DisplayRule\Condition;

class OrCondition implements ConditionInterface
{
    /**
     * @var ConditionInterface[]
     */
    private array $conditions;

    /**
     * @param ConditionInterface[] $conditions
     */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    public function apply(): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->apply()) {
                return false;
            }
        }

        return true;
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'or',
            'conditions' => array_map(function ($condition) {
                return $condition->jsonSerialize();
            }, $this->conditions),
        ];
    }
}
