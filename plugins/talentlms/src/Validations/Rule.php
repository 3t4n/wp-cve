<?php

namespace TalentlmsIntegration\Validations;

abstract class Rule
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
        $this->validate();
    }
    abstract protected function validate(): void;

    abstract public function getValue();
}
