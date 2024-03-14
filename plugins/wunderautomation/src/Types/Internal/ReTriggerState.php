<?php

namespace WunderAuto\Types\Internal;

/**
 * Class ReTriggerState
 */
class ReTriggerState extends BaseState
{
    /**
     * @var Query
     */
    public $query;

    /**
     * @var ReTriggerSchedule
     */
    public $schedule;

    /**
     * @var Step[]
     */
    public $steps = [];

    /**
     * @var int
     */
    public $version = 0;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var array<string, int|string>
     */
    public $asArgs = [];

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var array<string, string>
     */
    protected $map = [
        'query'    => __NAMESPACE__ . '\\Query',
        'schedule' => __NAMESPACE__ . '\\ReTriggerSchedule',
        'steps'    => __NAMESPACE__ . '\\Step',
    ];

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        // Set up reasonable defaults
        $this->query    = new Query(null);
        $this->schedule = new ReTriggerSchedule(null);
        if (isset($state->asArgs) && $state->asArgs instanceof \stdClass) {
            $state->asArgs = (array)$state->asArgs;
        }

        parent::__construct($state);

        $this->sanitizeObjectProp($this, 'version', 'int');
        $this->sanitizeObjectProp($this, 'active', 'bool');
        $this->sanitizeObjectProp($this, 'name', 'text');
        $this->sanitizeObjectProp($this, 'guid', 'text');

        if (count($this->steps) === 0) {
            $initial = (object)[
                'type'         => 'filters',
                'filterGroups' => [new FilterGroup([new Filter(null)])]
            ];

            $this->steps[] = new Step($initial);
        }
    }
}
