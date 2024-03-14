<?php

namespace WunderAuto\Types\Filters;

/**
 * Class Webhook
 */
class WebhookParameter extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Webhook parameter', 'wunderauto');
        $this->description = __('Filter based on webhook parameter value.', 'wunderauto');
        $this->objects     = ['webhook'];

        $this->operators = $this->stringOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';

        $this->usesCustomField        = true;
        $this->usesObjectPath         = true;
        $this->customFieldPlaceholder = __('Webhook parameter name', 'wunderauto');
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        /** @var array<string, string>|null $request */
        $request = $this->getObject();
        if (empty($request)) {
            return false;
        }

        $field       = $this->filterConfig->field;
        $path        = $this->filterConfig->path;
        $actualValue = isset($request[$field]) ? $request[$field] : '{}';
        $actualValue = $this->evaluateJSONPath($actualValue, $path);

        return $this->evaluateCompare($actualValue);
    }
}
