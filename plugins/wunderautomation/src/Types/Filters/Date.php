<?php

namespace WunderAuto\Types\Filters;

/**
 * Class Date
 */
class Date extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Date (now)', 'wunderauto');
        $this->description = __('Filter based on date when the workflow runs.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = $this->dateOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
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
