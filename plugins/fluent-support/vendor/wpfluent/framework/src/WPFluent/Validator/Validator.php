<?php

namespace FluentSupport\Framework\Validator;

use Closure;
use FluentSupport\Framework\Support\Arr;
use FluentSupport\Framework\Support\Str;
use FluentSupport\Framework\Foundation\App;

class Validator
{
    use ValidatesAttributes, MessageBag;

    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data;

    /**
     * The valid data after validation.
     *
     * @var array
     */
    protected $validated;

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * All of the error messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * All of the user provided messages.
     *
     * @var array
     */
    protected $customMessages = [];

    /**
     * The validation rules that imply the field is required.
     *
     * @var array
     */
    protected $implicitRules = [
        'Required',
        'Filled',
        'RequiredWith',
        'RequiredWithAll',
        'RequiredWithout',
        'RequiredWithoutAll',
        'RequiredIf',
        'RequiredUnless',
        'Accepted',
        'Present',
    ];

    /**
     * The current rule being handled
     * @var mixed
     */
    protected $currentRule = null;

    /**
     * Custom rules added by developer
     * @var array
     */
    protected static $customRules = [];

    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     *
     * @return void
     */
    public function __construct(array $data = [], array $rules = [], array $messages = [])
    {
        $this->data = $data;

        $this->messages = [];

        $this->customMessages = $messages;

        $this->setRules($rules);
    }

    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     *
     * @return \FluentSupport\Framework\Validator\Validator
     */
    public function make(array $data, array $rules = [], array $messages = [])
    {
        return new static($data, $rules, $messages);
    }

    /**
     * Set the validation rules.
     *
     * @param array $rules
     *
     * @return $this
     */
    protected function setRules(array $rules = [])
    {
        $this->rules = array_merge_recursive($this->rules, $rules);

        return $this;
    }

    /**
     * Validate the data against the provided rules.
     *
     * @return $this
     */
    public function validate()
    {
        $this->rules = (new ValidationRuleParser($this->data))->explode($this->rules);

        foreach ($this->rules as $attribute => $rules) {
            $rules = $this->filterExcludeables($attribute, $rules);
            foreach ($rules as $key => $rule) {
                $this->validateAttribute($attribute, $rule, $key);
            }
        }

        return $this;
    }

    /**
     * Remove the rules which should be excluded
     * @param  string $attribute
     * @param  array $rules
     * @return array
     */
    protected function filterExcludeables($attribute, $rules)
    {
        if (array_search('nullable', $rules) !== false) {
            if (!$this->getValue($attribute)) {
                $rules = [];
            }
        }

        return $rules;
    }

    /**
     * Validate each of the attribute of the data.
     *
     * @param $attribute
     * @param $rule
     *
     * @return void
     */
    protected function validateAttribute($attribute, $rule, $key = null)
    {
        $this->currentRule = $rule;

        list($rule, $parameters) = ValidationRuleParser::parse($rule);

        if ($rule === '') {
            return;
        }

        $value = $this->getValue($attribute);

        if ($rule instanceof Closure) {
            
            $params = [];

            if ($key && strpos($key, ':') !== false) {
                $params = explode(':', $key);
                $params = array_filter(array_map('trim', explode(',', end($params))));
            }

            if ($message = $rule($attribute, $value, $this->rules, $this->data, ...$params)) {
                is_string($message) && $this->messages[$attribute][$key] = str_replace(
                    ':attribute', $attribute, $message
                );
            }

            return $this->setValidatedAttributeData($attribute, $value);
        }

        $ruleCamelCase = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $rule)));
        
        $shouldValidate = $this->shouldValidate($ruleCamelCase, $attribute, $value);

        $method = 'validate'.$ruleCamelCase;

        if ($shouldValidate && !$this->$method($attribute, $value, $parameters)) {
            $this->addFailure($attribute, $rule, $parameters);
        }

        $this->setValidatedAttributeData($attribute, $value);
    }

    /**
     * Access the data by attribute name.
     *
     * @param $attribute
     *
     * @return mixed
     */
    protected function getValue($attribute)
    {
        $attribute = str_replace(['[', ']'], ['.', ''], $attribute);

        return Arr::get($this->data, $attribute);
    }

    /**
     * Add error message upon validation failed of an attribute.
     *
     * @param $attribute
     * @param $rule
     * @param $parameters
     *
     * @return void
     */
    protected function addFailure($attribute, $rule, $parameters)
    {
        $this->messages[$attribute][$rule] = $this->generate($attribute, $rule, $parameters);
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->messages;
    }

    /**
     * Determine if the data passes the validation rules.
     *
     * @return bool
     */
    public function passes()
    {
        return ! $this->fails();
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        return (bool) count($this->messages);
    }

    /**
     * Get the valid data after validation has been passed.
     *
     * @return array
     */
    public function validated()
    {
        return (array) $this->validated;
    }

    /**
     * Set the valid data after validation has
     * been passed for a specific attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return null
     */
    public function setValidatedAttributeData($attribute, $value)
    {
        $this->validated[$attribute] = $value;
    }

    /**
     * Add conditions to a given field based on a Closure.
     *
     * @param string|array $attribute
     * @param string|array $rules
     * @param callable $callback
     *
     * @return $this
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        $payload = $this->data;

        if (call_user_func($callback, $payload)) {
            foreach ((array) $attribute as $key) {
                $this->setRules([$key => $rules]);
            }
        }

        return $this;
    }

    /**
     * Determine if the attribute has a required rule.
     *
     * @param $attribute
     *
     * @return bool
     */
    public function hasRequired($attribute)
    {
        foreach ($this->rules[$attribute] as $rule) {
            if (strpos($rule, 'required') !== false) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * Determine if the attribute should be validated.
     *
     * @param $method
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function shouldValidate($rule, $attribute, $value)
    {
        return $this->presentOrRuleIsImplicit($rule, $attribute, $value);
    }

    /**
     * Determines if this object has this method.
     *
     * @param $method
     *
     * @return bool
     */
    public function hasMethod($method)
    {
        return method_exists($this, $method);
    }

    /**
     * Determine if a given rule implies the attribute is required.
     *
     * @param string $rule
     *
     * @return bool
     */
    protected function isImplicit($rule)
    {
        return in_array($rule, $this->implicitRules) || in_array($rule, static::$customRules);
    }

    /**
     * Determine if the field is present, or the rule implies required.
     *
     * @param string $rule
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    protected function presentOrRuleIsImplicit($rule, $attribute, $value)
    {
        return $this->validatePresent($attribute, $value) || $this->isImplicit($rule);
    }

    public function extend($rule, $callback, $message = null)
    {
        $ruleCamelCase = ucwords(Str::camel($rule));

        static::$customRules[$ruleCamelCase] = $callback;
    }

    /**
     * Get all the custom rules with handlers
     * 
     * @return array
     */
    public function getExtentions()
    {
        return static::$customRules;
    }

    /**
     * Handle dynamic calls for custom rules
     * @param  string $method
     * @param  array $params
     * @return bool (true)
     */
    public function __call($method, $params)
    {
        list($attribute, $value, $params) = $params;

        $rule = substr($this->currentRule, 0, strpos($this->currentRule, ':'));

        if ($callback = Arr::get(static::$customRules, ucwords(str::camel($rule)))) {

            if (is_string($callback) && class_exists($callback)) {
                $callback = App::make($callback);
            }

            if ($message = $callback($attribute, $value, $this->rules, $this->data, ...$params)) {

                is_string($message) && $this->messages[$attribute][$rule] = str_replace(
                    ':attribute', $attribute, $message
                );
            }
        }

        return true;
    }
}
