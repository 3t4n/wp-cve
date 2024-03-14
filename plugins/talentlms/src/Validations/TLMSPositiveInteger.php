<?php

namespace TalentlmsIntegration\Validations;

class TLMSPositiveInteger extends Rule
{
    public function validate(): void
    {
        if (empty($this->value) || $this->value <= 0 || !filter_var($this->value, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Value is not positive integer');
        }
    }

    public function getValue(): int
    {
        return filter_var($this->value, FILTER_VALIDATE_INT);
    }
}
