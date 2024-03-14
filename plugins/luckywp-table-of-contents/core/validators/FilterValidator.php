<?php

namespace luckywp\tableOfContents\core\validators;

class FilterValidator extends Validator
{

    /**
     * @var callable
     */
    public $filter;

    /**
     * @var bool
     */
    public $skipOnEmpty = false;

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $model->$attribute = call_user_func($this->filter, $value);
    }
}
