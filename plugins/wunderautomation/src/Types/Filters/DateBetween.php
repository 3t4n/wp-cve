<?php

namespace WunderAuto\Types\Filters;

/**
 * Class DateBetween
 */
class DateBetween extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Between time / dates', 'wunderauto');
        $this->description = __('Filter based on date when the workflow runs.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = [
            'datebetween'  => __('Is between', 'wunderauto'),
            'datenbetween' => __('Is not between', 'wunderauto'),
        ];

        $this->inputType = 'between';
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $actualValue = time() + wa_get_wp_timezone_offset();
        return $this->evaluateCompare($actualValue);
    }
}
