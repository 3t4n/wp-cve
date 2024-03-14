<?php

namespace TalentlmsIntegration\Validations;

class TLMSUrl extends Rule
{
    protected function validate(): void
    {
        if (empty($this->value) || !filter_var($this->value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Value is not a url');
        }
    }

    public function getValue(): string
    {
        return filter_var($this->value, FILTER_VALIDATE_URL);
    }
}
