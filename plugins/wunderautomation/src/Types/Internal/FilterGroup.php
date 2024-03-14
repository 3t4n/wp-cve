<?php

namespace WunderAuto\Types\Internal;

/**
 * Class FilterGroup
 */
class FilterGroup extends BaseInternalType
{
    /**
     * @var Filter[]
     */
    public $filters;

    /**
     * @var array<string, string>
     */
    protected $map = [
        'filters' => __NAMESPACE__ . '\\Filter',
    ];
}
