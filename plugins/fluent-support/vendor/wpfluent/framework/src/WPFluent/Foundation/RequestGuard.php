<?php

namespace FluentSupport\Framework\Foundation;

use FluentSupport\Framework\Foundation\App;
use FluentSupport\Framework\Validator\Validator;
use FluentSupport\Framework\Validator\ValidationException;

abstract class RequestGuard
{
    /**
     * Retrive the validation rules
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Retrive the validation messages set by the developer
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Allow the developer tinker with data before the validation.
     * @return array
     */
    public function beforeValidation()
    {
        return [];
    }

    /**
     * Allow the developer tinker with data after the validation.
     * @return array
     */
    public function afterValidation()
    {
        return [];
    }

    /**
     * Validate the request.
     * 
     * @param  array $rules Optional
     * @param  array $messages Optional
     * @return array Request Data
     * @throws FluentSupport\Framework\Validator\ValidationException
     */
    public function validate($rules = [], $messages = [])
    {
        try {
            return App::make('request')->validate(
                $rules ?: (array) $this->rules(),
                $messages ?: (array) $this->messages()
            );
        } catch (ValidationException $e) {

            if (defined('REST_REQUEST') && REST_REQUEST) {
                throw $e;
            } else {
                App::getInstance()->doCustomAction('handle_exception', $e);
            }
        }
    }

    /**
     * Handles validation including before and after calls
     *
     * @throws FluentSupport\Framework\Validator\ValidationException
     * @return null
     */
    public static function applyValidation()
    {
        $instance = new static;

        $request = App::getInstance('request');

        $request->merge($instance->beforeValidation());

        $instance->validate();

        $request->merge($instance->afterValidation());
    }

    /**
     * Get an input element from the request.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Handle the dynamic method calls
     * @param  string $method
     * @param  array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return call_user_func_array(
            [App::make('request'), $method], $params
        );
    }
}
