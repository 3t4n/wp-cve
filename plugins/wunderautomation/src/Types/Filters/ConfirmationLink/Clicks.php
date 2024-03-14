<?php

namespace WunderAuto\Types\Filters\ConfirmationLink;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Clicks
 */
class Clicks extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Confirmation Links', 'wunderauto');
        $this->title       = __('Link clicks', 'wunderauto');
        $this->description = __('Filter based on nr of previous clicks on the confirmation link.', 'wunderauto');
        $this->objects     = ['link'];

        $this->operators = $this->numberOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'number';
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

        $actualValue = $link->clicked;
        return $this->evaluateCompare($actualValue);
    }
}
