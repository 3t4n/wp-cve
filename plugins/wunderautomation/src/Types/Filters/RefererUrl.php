<?php

namespace WunderAuto\Types\Filters;

/**
 * Class RefererUrl
 */
class RefererUrl extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Referer URL', 'wunderauto');
        $this->description = __('Filter based on the http request referer URL.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = $this->stringOperators();
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
        $actualValue = wp_get_referer();
        if ($actualValue === false) {
            return false;
        }

        return $this->evaluateCompare($actualValue);
    }
}
