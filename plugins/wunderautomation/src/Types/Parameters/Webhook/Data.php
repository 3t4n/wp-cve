<?php

namespace WunderAuto\Types\Parameters\Webhook;

use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Data
 */
class Data extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'webhook';
        $this->title       = 'data';
        $this->description = __('Webhook data', 'wunderauto');
        $this->objects     = ['webhook'];

        $this->usesTreatAsType = true;
        $this->usesDefault     = true;
        $this->usesName        = true;
        $this->usesObjectPath  = true;

        $this->customFieldNameCaption = __('Parameter name', 'wunderauto');
    }

    /**
     * @param array<string, string>|null $request
     * @param \stdClass                  $modifiers
     *
     * @return mixed
     */
    public function getValue($request, $modifiers)
    {
        $wunderAuto = wa_wa();

        $value = $this->getDefaultValue($modifiers);
        $name  = isset($modifiers->name) ? $modifiers->name : null;
        if (!$name) {
            // no parameter name was defined, return default value
            return $value;
        }

        $data = isset($request[$name]) ? $request[$name] : null;
        if (!$data) {
            // the element wan't found, return default value
            return $value;
        }

        return $this->getDataWithPath($data, $modifiers);
    }
}
