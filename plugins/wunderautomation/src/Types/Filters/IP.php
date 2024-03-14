<?php

namespace WunderAuto\Types\Filters;

/**
 * Class IP
 */
class IP extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Remote IP address', 'wunderauto');
        $this->description = __('Filter based on Remote IP address.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = [
            'innetwork'  => __('Is in network', 'wunderauto'),
            'ninnetwork' => __('Is not in network', 'wunderauto'),
        ];

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
        $actualValue = '';
        $actualValue = isset($_SERVER['REMOTE_ADDR']) ?
            sanitize_text_field($_SERVER['REMOTE_ADDR']) :
            $actualValue;

        $actualValue = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
            sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']) :
            $actualValue;

        return $this->evaluateCompare($actualValue);
    }
}
