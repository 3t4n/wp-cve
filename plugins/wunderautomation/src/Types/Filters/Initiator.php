<?php

namespace WunderAuto\Types\Filters;

/**
 * Class Initiator
 */
class Initiator extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Request initiator', 'wunderauto');
        $this->description = __('Filter based on WordPress was initiated.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';

        $this->compareValues = [
            [
                'value' => 'HTTP',
                'label' => 'Normal request',
            ],
            [
                'value' => 'CRON',
                'label' => 'Cron process',
            ],
            [
                'value' => 'REST',
                'label' => 'REST Request',
            ],
        ];
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $actualValue = 'HTTP';
        $actualValue = defined('REST_REQUEST') && REST_REQUEST ?
            'REST' :
            $actualValue;

        $actualValue = wp_doing_cron() ?
            'CRON' :
            $actualValue;

        return $this->evaluateCompare($actualValue);
    }
}
