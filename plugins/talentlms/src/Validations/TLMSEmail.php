<?php

namespace TalentlmsIntegration\Validations;

class TLMSEmail extends Rule
{

    protected function validate(): void
    {
        if (empty($this->value) || !filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Value is not an email');
        }
    }

    public function getValue(): string
    {
        return filter_var($this->value, FILTER_VALIDATE_EMAIL);
    }
}
