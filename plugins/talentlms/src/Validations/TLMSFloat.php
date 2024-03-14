<?php

namespace TalentlmsIntegration\Validations;

class TLMSFloat extends Rule
{
    public function validate(): void
    {
        if (empty($this->value) || !filter_var($this->value, FILTER_VALIDATE_FLOAT)) {
            throw new \InvalidArgumentException('Value is not float');
        }
    }

    public function getValue(): float
    {
        return filter_var($this->value, FILTER_VALIDATE_FLOAT);
    }
}
