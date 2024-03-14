<?php

namespace TalentlmsIntegration\Validations;

class TLMSInteger extends Rule
{
    public function validate(): void
    {
        if ($this->value !== 0 && !filter_var($this->value, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Value is not integer');
        }
    }

    public function getValue(): int
    {
        return filter_var($this->value, FILTER_VALIDATE_INT);
    }
}
