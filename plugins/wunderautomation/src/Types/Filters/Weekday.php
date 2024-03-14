<?php

namespace WunderAuto\Types\Filters;

/**
 * Class Weekday
 */
class Weekday extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Weekday (now)', 'wunderauto');
        $this->description = __('Filter based on weekday of when the workflow runs.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $this->compareValues = [
            [
                'value' => '1',
                'label' => __('Monday', 'wunderauto'),
            ],
            [
                'value' => '2',
                'label' => __('Tuesday', 'wunderauto'),
            ],
            [
                'value' => '3',
                'label' => __('Wednesday', 'wunderauto'),
            ],
            [
                'value' => '4',
                'label' => __('Thursday', 'wunderauto'),
            ],
            [
                'value' => '5',
                'label' => __('Friday', 'wunderauto'),
            ],
            [
                'value' => '6',
                'label' => __('Saturday', 'wunderauto'),
            ],
            [
                'value' => '7',
                'label' => __('Sunday', 'wunderauto'),
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
        $actualValue = (int)date('N', time() + wa_get_wp_timezone_offset());
        return $this->evaluateCompare($actualValue);
    }
}
