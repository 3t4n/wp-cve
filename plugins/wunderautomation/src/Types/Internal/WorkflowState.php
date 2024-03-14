<?php

namespace WunderAuto\Types\Internal;

/**
 * Class WorfklowState
 */
class WorkflowState extends BaseState
{
    /**
     * @var Trigger
     */
    public $trigger;

    /**
     * @var Step[]
     */
    public $steps = array();

    /**
     * @var Schedule
     */
    public $schedule;

    /**
     * @var Options
     */
    public $options;

    /**
     * @var int
     */
    public $version = 0;

    /**
     * @var bool
     */
    public $active = true;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var array<string, string>
     */
    protected $map = [
        'trigger'  => __NAMESPACE__ . '\\Trigger',
        'schedule' => __NAMESPACE__ . '\\Schedule',
        'steps'    => __NAMESPACE__ . '\\Step',
    ];

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        $this->trigger  = new Trigger(null);
        $this->schedule = new Schedule(null);
        $this->options  = new Options(null);

        // Handle legacy versions (pre 1.6)
        if (is_object($state)) {
            $hasSteps   = property_exists($state, 'steps');
            $hasFilters = property_exists($state, 'filters');
            $hasActions = property_exists($state, 'actions');

            // pre 1.6 workflow
            if (!$hasSteps && ($hasFilters || $hasActions)) {
                $state->steps = array();

                if ($hasFilters) {
                    $step = (object)[
                        'type'         => 'filters',
                        'filterGroups' => $state->filters,
                    ];

                    $state->steps[] = $step;
                }

                if ($hasActions) {
                    foreach ($state->actions as $action) {
                        $step           = (object)[
                            'type'   => 'action',
                            'action' => $action,
                        ];
                        $state->steps[] = $step;
                    }
                }
            }
        }

        parent::__construct($state);

        $this->sanitizeObjectProp($this, 'version', 'int');
        $this->sanitizeObjectProp($this, 'active', 'bool');
        $this->sanitizeObjectProp($this, 'name', 'text');
        $this->sanitizeObjectProp($this, 'guid', 'text');
    }
}
