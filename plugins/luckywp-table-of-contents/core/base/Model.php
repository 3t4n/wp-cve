<?php

namespace luckywp\tableOfContents\core\base;

use Exception;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\core\validators\Validator;
use ReflectionClass;
use ReflectionProperty;

/**
 * @property array $attributes
 */
abstract class Model extends BaseObject
{

    /**
     * @param array $data
     * @param string $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        if ($scope === '' && !empty($data)) {
            $this->setAttributes($data);
            return true;
        }
        if (isset($data[$scope])) {
            $this->setAttributes($data[$scope]);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function formName()
    {
        $reflector = new ReflectionClass($this);
        return $reflector->getShortName();
    }


    /**
     * ---------------------------------------------------------------------------
     *  Валидация
     * ---------------------------------------------------------------------------
     */

    /**
     * @var Validator[]
     */
    private $_validators;

    /**
     * @param string $attribute
     * @return Validator[]
     */
    public function getValidators($attribute = null)
    {
        if ($this->_validators === null) {
            $this->_validators = [];
            foreach ($this->rules() as $rule) {
                if ($rule instanceof Validator) {
                    $this->_validators[] = $rule;
                } elseif (is_array($rule) && isset($rule[0], $rule[1])) {
                    $validator = Validator::createValidator($rule[1], $this, (array)$rule[0], array_slice($rule, 2));
                    $this->_validators[] = $validator;
                } else {
                    throw new Exception('Invalid validation rule: a rule must specify both attribute names and validator type.');
                }
            }
        }

        if ($attribute === null) {
            return $this->_validators;
        }

        $validators = [];
        foreach ($this->_validators as $validator) {
            if (in_array($attribute, $validator->getAttributeNames(), true)) {
                $validators[] = $validator;
            }
        }
        return $validators;
    }

    /**
     * @param array $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }
        if ($attributeNames === null) {
            $attributeNames = $this->attributeNames();
        }
        foreach ($this->getValidators() as $validator) {
            $validator->validateAttributes($this, $attributeNames);
        }
        return !$this->hasErrors();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }


    /**
     * ---------------------------------------------------------------------------
     *  Атрибуты
     * ---------------------------------------------------------------------------
     */

    /**
     * @var string[]
     */
    private $_attributeNames;

    /**
     * @return string[]
     */
    public function attributeNames()
    {
        if ($this->_attributeNames === null) {
            $class = new ReflectionClass($this);
            $this->_attributeNames = [];
            foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                if (!$property->isStatic()) {
                    $this->_attributeNames[] = $property->getName();
                }
            }
        }
        return $this->_attributeNames;
    }

    /**
     * @return string[]
     */
    public function safeAttributeNames()
    {
        $names = [];
        foreach ($this->getValidators() as $validator) {
            foreach ($validator->attributes as $attribute) {
                $names[] = $attribute;
            }
        }
        return array_unique($names);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getAttributeLabel($attribute)
    {
        return ArrayHelper::getValue($this->attributeLabels(), $attribute, $attribute);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        return [];
    }

    /**
     * @param array $names
     * @return array
     */
    public function getAttributes($names = null)
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributeNames();
        }
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        return $values;
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributeNames() : $this->attributeNames());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                }
            }
        }
    }


    /**
     * ---------------------------------------------------------------------------
     *  Ошибки
     * ---------------------------------------------------------------------------
     */

    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @param string|null $attribute
     * @return array
     */
    public function getErrors($attribute = null)
    {
        if ($attribute === null) {
            return $this->_errors;
        }
        return ArrayHelper::getValue($this->_errors, $attribute, []);
    }

    /**
     * @return array
     */
    public function getFirstErrors()
    {
        if (empty($this->_errors)) {
            return [];
        }

        $errors = [];
        foreach ($this->_errors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }
        return $errors;
    }

    /**
     * @param bool $showAllErrors
     * @return array
     */
    public function getErrorSummary($showAllErrors = true)
    {
        $lines = [];
        $errors = $showAllErrors ? $this->getErrors() : $this->getFirstErrors();
        foreach ($errors as $es) {
            $lines = array_merge($lines, (array)$es);
        }
        return $lines;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getFirstError($attribute)
    {
        return isset($this->_errors[$attribute]) ? reset($this->_errors[$attribute]) : null;
    }

    /**
     * @param string $attribute
     * @param string $error
     */
    public function addError($attribute, $error = '')
    {
        $this->_errors[$attribute][] = $error;
    }

    /**
     * @param string|null $attribute
     */
    public function clearErrors($attribute = null)
    {
        if ($attribute === null) {
            $this->_errors = [];
        } else {
            unset($this->_errors[$attribute]);
        }
    }

    /**
     * @param string|null $attribute
     * @return bool
     */
    public function hasErrors($attribute = null)
    {
        return $attribute === null ? !empty($this->_errors) : isset($this->_errors[$attribute]);
    }
}
