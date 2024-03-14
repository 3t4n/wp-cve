<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class Option
 */
class Option extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'option';
        $this->description = __('WordPress option', 'wunderauto');
        $this->objects     = '*';

        $this->usesTreatAsType = true;
        $this->usesDefault     = true;
        $this->usesName        = true;
        $this->usesObjectPath  = true;

        $this->customFieldNameCaption = __('Option name', 'wunderauto');
        $this->customFieldNameDesc    = __('Option name in database (option_name)', 'wunderauto');
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $value      = $this->getDefaultValue($modifiers);
        $optionName = isset($modifiers->name) ? $modifiers->name : null;
        if (!$optionName) {
            // No option name defined, return default value
            return $value;
        }

        $data = get_option($optionName);
        if (!$data) {
            // The option wasn't found in the db, return default
            return $value;
        }

        return $this->getDataWithPath($data, $modifiers);
    }
}
