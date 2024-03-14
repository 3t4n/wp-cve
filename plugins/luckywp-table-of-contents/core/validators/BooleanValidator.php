<?php

namespace luckywp\tableOfContents\core\validators;

class BooleanValidator extends Validator
{

    /**
     * @var mixed
     */
    public $trueValue = '1';

    /**
     * @var mixed
     */
    public $falseValue = '0';

    /**
     * @var bool
     */
    public $strict = false;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = sprintf(
            /* translators: 1: Attribute name 2: True 3: False */
                esc_html__('%1$s must be either "%2$s" or "%3$s".', 'luckywp-table-of-contents'),
                '{attribute}',
                '{true}',
                '{false}'
            );
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        if ($this->strict) {
            $valid = $value === $this->trueValue || $value === $this->falseValue;
        } else {
            $valid = $value == $this->trueValue || $value == $this->falseValue;
        }

        if ($valid) {
            return null;
        }

        return [
            $this->message,
            [
                '{true}' => $this->trueValue,
                '{false}' => $this->falseValue,
            ]
        ];
    }
}
