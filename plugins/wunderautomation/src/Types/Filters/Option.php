<?php

namespace WunderAuto\Types\Filters;

/**
 * Class Webhook
 */
class Option extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('WordPress option', 'wunderauto');
        $this->description = __('Filter based on WordPress site wide option values.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = $this->stringOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';

        $this->usesCustomField        = true;
        $this->usesObjectPath         = true;
        $this->customFieldPlaceholder = __('Option name', 'wunderauto');
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $path        = $this->filterConfig->path;
        $actualValue = get_option($this->filterConfig->field);
        $actualValue = $this->evaluateJSONPath($actualValue, $path);

        return $this->evaluateCompare($actualValue);
    }
}
