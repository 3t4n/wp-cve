<?php

namespace WunderAuto\Types\Filters;

use WunderAuto\Resolver;

/**
 * Class BaseAdvancedCustomField
 */
class BaseAdvancedCustomField extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->operators               = $this->stringOperators();
        $this->usesAdvancedCustomField = true;
        $this->inputType               = 'scalar';
        $this->valueType               = 'text';
    }
}
