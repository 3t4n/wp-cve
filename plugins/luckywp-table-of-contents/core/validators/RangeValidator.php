<?php

namespace luckywp\tableOfContents\core\validators;

class RangeValidator extends Validator
{

    /**
     * @var array
     */
    public $range;

    /**
     * @var bool
     */
    public $strict = false;

    /**
     * @var bool
     */
    public $not = false;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = sprintf(
            /* translators: %s: Attribute name */
                esc_html__('%s is invalid.', 'luckywp-table-of-contents'),
                '{attribute}'
            );
        }
    }

    protected function validateValue($value)
    {
        $in = in_array($value, $this->range, $this->strict);
        return $this->not !== $in ? null : [$this->message, []];
    }
}
