<?php

namespace WunderAuto\Types\Filters\ConfirmationLink;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Name
 */
class Name extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Confirmation Links', 'wunderauto');
        $this->title       = __('Link name', 'wunderauto');
        $this->description = __('Filter based on the name of the initiating confirmation link.', 'wunderauto');
        $this->objects     = ['link'];

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
        $link = $this->getObject();
        if (is_null($link)) {
            return false;
        }

        if (!($link instanceof \stdClass)) {
            return false;
        }

        $actualValue = $link->name;
        return $this->evaluateCompare($actualValue);
    }
}
