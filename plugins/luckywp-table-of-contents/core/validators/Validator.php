<?php

namespace luckywp\tableOfContents\core\validators;

use Exception;
use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\base\Model;
use luckywp\tableOfContents\core\Core;

abstract class Validator extends BaseObject
{

    /**
     * @var array Атрибуты для валидации
     */
    public $attributes;

    /**
     * @var string Сообщение об ошибке
     */
    public $message;

    /**
     * @var callable
     */
    public $when;

    /**
     * @var bool
     */
    public $skipOnError = true;

    /**
     * @var bool
     */
    public $skipOnEmpty = true;

    /**
     * @return array
     */
    public static function builtInValidators()
    {
        return [
            'boolean' => BooleanValidator::class,
            'filter' => FilterValidator::class,
            'in' => RangeValidator::class,
            'required' => RequiredValidator::class,
        ];
    }

    /**
     * Создаёт и возвращает валидатор
     * @param string|callable $name
     * @param Model $model
     * @param array $attributes
     * @param array $params
     * @return mixed
     */
    public static function createValidator($name, $model, $attributes, $params = [])
    {
        $params['attributes'] = $attributes;
        if (is_callable($name) || method_exists($model, $name)) {
            $params['class'] = InlineValidator::class;
            $params['method'] = $name;
        } else {
            if (isset(static::builtInValidators()[$name])) {
                $name = static::builtInValidators()[$name];
            }
            $params['class'] = $name;
        }
        return Core::createObject($params);
    }

    /**
     * @param Model $model
     * @param array|null $attributes
     */
    public function validateAttributes($model, $attributes = null)
    {
        if (is_array($attributes)) {
            $newAttributes = [];
            foreach ($attributes as $attribute) {
                if (in_array($attribute, $this->getAttributeNames(), true)) {
                    $newAttributes[] = $attribute;
                }
            }
            $attributes = $newAttributes;
        } else {
            $attributes = $this->getAttributeNames();
        }

        foreach ($attributes as $attribute) {
            $skip = $this->skipOnError && $model->hasErrors($attribute)
                || $this->skipOnEmpty && $this->isEmpty($model->$attribute);
            if (!$skip) {
                if ($this->when === null || call_user_func($this->when, $model, $attribute)) {
                    $this->validateAttribute($model, $attribute);
                }
            }
        }
    }

    /**
     * @param Model $model
     * @param string
     */
    public function validateAttribute($model, $attribute)
    {
        $result = $this->validateValue($model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * @param mixed $value
     */
    protected function validateValue($value)
    {
        throw new Exception(get_class($this) . ' does not support validateValue().');
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @param string $message
     * @param array $params
     */
    public function addError($model, $attribute, $message, $params = [])
    {
        $params['attribute'] = $model->getAttributeLabel($attribute);
        if (!isset($params['value'])) {
            $value = $model->$attribute;
            if (is_array($value)) {
                $params['value'] = 'array()';
            } elseif (is_object($value) && !method_exists($value, '__toString')) {
                $params['value'] = '(object)';
            } else {
                $params['value'] = $value;
            }
        }
        $model->addError($attribute, $this->formatMessage($message, $params));
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    protected function formatMessage($message, $params)
    {
        $placeholders = [];
        foreach ($params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }
        return ($placeholders === []) ? $message : strtr($message, $placeholders);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isEmpty($value)
    {
        return $value === null || $value === [] || $value === '';
    }

    /**
     * @return array
     */
    public function getAttributeNames()
    {
        return $this->attributes;
    }
}
