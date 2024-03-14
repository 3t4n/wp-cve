<?php

namespace WunderAuto\Types\Internal;

use JsonSerializable;

/**
 * Class Step
 */
class Step extends BaseInternalType implements JsonSerializable
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var FilterGroup[]
     */
    public $filterGroups;

    /**
     * @var Action
     */
    public $action;

    /**
     * @var array<string, string>
     */
    protected $map = [
        'filterGroups' => __NAMESPACE__ . '\\FilterGroup',
        'action'       => __NAMESPACE__ . '\\Action',
    ];

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        // Handle legacy version
        if (is_object($state)) {
            $hasFilterArray  = property_exists($state, 'filters');
            $hasFilterGroups = property_exists($state, 'filterGroups');

            if ($hasFilterArray && !$hasFilterGroups) {
                $state->filterGroups = $state->filters;
                unset($state->filters);
            }
        }

        parent::__construct($state);

        $this->sanitizeObjectProp($this, 'type', 'enum', ['action', 'filters']);
    }

    /**
     * Formatting for JSON
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        if ($this->type === 'action') {
            return [
                'type'   => $this->type,
                'action' => $this->action,
            ];
        }

        if ($this->type === 'filters') {
            return [
                'type'         => $this->type,
                'filterGroups' => $this->filterGroups,
            ];
        }

        return [
            'type' => $this->type,
        ];
    }
}
