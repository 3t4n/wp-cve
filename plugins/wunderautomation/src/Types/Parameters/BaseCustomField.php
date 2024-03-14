<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class BaseCustomField
 */
class BaseCustomField extends BaseParameter
{
    /**
     * @var string
     */
    protected $objectType = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->usesTreatAsType = true;
        $this->usesDefault     = true;
        $this->usesFieldName   = true;
    }

    /**
     * Format a custom field with date and phone rules
     *
     * @param mixed     $value
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return string|float|int
     */
    protected function formatCustomField($value, $object, $modifiers)
    {
        return $this->formatField($value, $modifiers);
    }
}
