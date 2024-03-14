<?php

namespace ShopMagicVendor\WPDesk\Forms\Validator;

use ShopMagicVendor\WPDesk\Forms\Validator;
class ChainValidator implements Validator
{
    /** @var Validator[] */
    private $validators;
    /** @var array */
    private $messages;
    public function __construct()
    {
        $this->validators = [];
        $this->messages = [];
    }
    /**
     * @param Validator $validator
     *
     * @return $this
     */
    public function attach(Validator $validator) : self
    {
        $this->validators[] = $validator;
        return $this;
    }
    public function is_valid($value) : bool
    {
        $result = \true;
        $messages = [[]];
        foreach ($this->validators as $validator) {
            if (!$validator->is_valid($value)) {
                $result = \false;
                $messages[] = $validator->get_messages();
            }
        }
        $this->messages = \array_merge(...$messages);
        return $result;
    }
    public function get_messages() : array
    {
        return $this->messages;
    }
}
