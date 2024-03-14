<?php

namespace luckywp\tableOfContents\core\validators;

class InlineValidator extends Validator
{

    /**
     * @var string|callable
     */
    public $method;

    /**
     * @var mixed
     */
    public $params;

    public function validateAttribute($model, $attribute)
    {
        $method = $this->method;
        if (is_string($method)) {
            $method = [$model, $method];
        }
        call_user_func($method, $attribute, $this->params, $this);
    }
}
