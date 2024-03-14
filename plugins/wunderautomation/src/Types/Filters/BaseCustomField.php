<?php

namespace WunderAuto\Types\Filters;

use WunderAuto\Resolver;

/**
 * Class CustomField
 */
class BaseCustomField extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->operators = $this->stringOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';

        $this->usesCustomField        = true;
        $this->customFieldPlaceholder = __('Custom field identifier', 'wunderauto');
    }
}
