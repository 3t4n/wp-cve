<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class ChangeCustomField
 */
class ChangeCustomField extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Change custom field', 'wunderauto');
        $this->description = __('Change / update custom field', 'wunderauto');
        $this->group       = 'WordPress';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'type', 'key');
        $config->sanitizeObjectProp($config->value, 'dataType', 'key');
        $config->sanitizeObjectProp($config->value, 'fieldName', 'text');
        $config->sanitizeObjectProp($config->value, 'newValue', 'text');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $objectType = $this->get('value.type');
        $dataType   = $this->get('value.dataType');
        $fieldName  = trim($this->getResolved('value.fieldName'));
        $dataType   = (!in_array($dataType, ['string', 'int', 'float'])) ?
            'string' :
            $dataType;
        $newValue   = $this->getResolved('value.newValue', null, $dataType);

        if (!$objectType || !$fieldName) {
            return false;
        }

        $this->resolver->setMetaValue($objectType, $fieldName, $newValue);
        return true;
    }
}
