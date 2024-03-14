<?php

namespace WunderAuto\Types\Parameters;

use WC_Countries;
use WC_Order;
use WunderAuto\Format\Phone;

/**
 * Class AdvancedCustomFieldPost
 */
class BaseAdvancedCustomField extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->usesTreatAsType  = true;
        $this->usesDefault      = true;
        $this->usesReturnAs     = true;
        $this->usesAcfFieldName = true;
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $wunderAuto = wa_wa();

        $resolver = $wunderAuto->getCurrentResolver();

        $value = $this->getDefaultValue($modifiers);
        $id    = $resolver->getObjectId($object);
        $key   = isset($modifiers->key) ? $modifiers->key : null;
        if (!$key || is_null($id)) {
            return $value;
        }

        $acfField = get_field_object($modifiers->key, $id);
        if (!$acfField) {
            return $value;
        }

        $value    = $acfField['value'];
        $returnAs = isset($modifiers->return) ? $modifiers->return : null;
        if ($returnAs === 'label' && isset($acfField['choices'][$value])) {
            $value = $acfField['choices'][$value];
        }

        if (isset($modifiers->type)) {
            switch ($modifiers->type) {
                case 'date':
                    $value = $this->formatDate($value, $modifiers);
                    break;
                case 'phone':
                    if (isset($modifiers->format) && trim($modifiers->format) === 'e.164') {
                        $isoCountry = '';
                        if ($object instanceof WC_Order) {
                            $isoCountry = $object->get_billing_country();
                        }
                        if (empty($isoCountry) && class_exists('WC_Countries')) {
                            $wcCountries = new WC_Countries();
                            $isoCountry  = $wcCountries->get_base_country();
                        }
                        if (empty($isoCountry)) {
                            $isoCountry = 'US';
                        }
                        $phoneFormat = new Phone();
                        $value       = $phoneFormat->formatE164($value, $isoCountry);
                    }
                    break;
            }
        }

        return $this->formatField($value, $modifiers);
    }
}
