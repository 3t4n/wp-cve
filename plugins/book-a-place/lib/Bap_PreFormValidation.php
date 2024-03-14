<?php
/**
 * Date: 04.02.14
 * Time: 12:06 
 */

class Bap_PreFormValidation {

    const ERR_MSG_REQUIRED = 'required';
    const ERR_MSG_EMPTY = 'empty';
    const ERR_MSG_VALID_VALUES = 'valid values';

    private $valid = true;

    private $errors = array();

    private $fieldTypes = array(
        'text',
        'multiselect',
        'select',
    );

    private $fieldDataTypes = array(
        'text' => 'string',
        'multiselect' => 'array',
        'select' => 'string',
    );

    private $fieldDefaultData = array(
        'string' => '',
        'array' => array(''),
    );

    private $rowData;
    private $config;
    private $fields = array();

    public function __construct(array $data = array(), array $config = array())
    {
        $this->rowData = $data;
        $this->fields = $data;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        $this->removeUnneededFields();

        foreach ($this->config as $name => $fieldConfig) {
            if (!empty($fieldConfig) && is_array($fieldConfig)) {
                $this->validateField($name, $fieldConfig);
            }
        }

        return $this->valid;
    }

    private function validateField($name, $fieldConfig)
    {
        // TODO check if type is correct in the $fieldConfig, if no throw exception
        $type = isset($fieldConfig[0]) && in_array($fieldConfig[0], $this->fieldTypes) ? $fieldConfig[0] : false;
        $required = isset($fieldConfig[1]) && $fieldConfig[1] === true ? true : false;
        $notEmpty = isset($fieldConfig[2]) && $fieldConfig[2] === true ? true : false;
        $notValidValues = isset($fieldConfig[3]) && is_array($fieldConfig[3]) ? $fieldConfig[3] : false;

        if ($required) {
            $this->checkFieldIsset($name, $type);
        }

        if ($notEmpty) {
            $this->checkFieldEmpty($name, $type);
        }

        if ($notValidValues) {
            $this->checkFieldValidValues($name, $type, $notValidValues);
        }

        $this->checkFieldDataType($name, $type);

        return true;
    }

    private function setEmptyField($name, $type)
    {
        $fieldDataType = $this->fieldDataTypes[$type];
        $this->fields[$name] = $this->fieldDefaultData[$fieldDataType];

        return true;
    }

    private function checkFieldDataType($name, $type)
    {
        if (gettype($this->fields[$name]) !== $this->fieldDataTypes[$type]) {
            $this->setEmptyField($name, $type);
        }

        return true;
    }

    private function checkFieldIsset($name, $type)
    {
        if (!isset($this->fields[$name])) {
            $this->valid = false;
            $this->errors[$name][] = self::ERR_MSG_REQUIRED;
            $this->setEmptyField($name, $type);
        }

        return true;
    }

    private function checkFieldEmpty($name, $type)
    {
        if (empty($this->fields[$name])) {
            $this->valid = false;
            $this->errors[$name][] = self::ERR_MSG_EMPTY;
        }

        return true;
    }

    private function checkFieldValidValues($name, $type, $notValidValues)
    {
        if (in_array($this->fields[$name], $notValidValues)) {
            $this->valid = false;
            $this->errors[$name][] = self::ERR_MSG_VALID_VALUES;
        }

        return true;
    }

    private function removeUnneededFields()
    {
        $neededFields = array_keys($this->config);
        foreach ($this->fields as $name => $value) {
            if (!in_array($name, $neededFields)) {
                unset($this->fields[$name]);
            }
        }

        return true;
    }

    // TODO add method to get all empty fields

    // TODO add method to trim all fields
} 