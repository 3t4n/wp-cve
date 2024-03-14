<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Payment;

class PaymentMethod implements \JsonSerializable
{
    /** @var string */
    public $key;

    /** @var string */
    public $name;

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'key'   => $this->key,
            'name'  => $this->name,
        ];
    }
}
