<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Product;

class Options implements \JsonSerializable
{
    /** @var string */
    public $name;

    /** @var Option[] */
    public $options;

    public function __construct()
    {
        $this->options = [];
    }

    public function jsonSerialize()
    {
        return [
            'name'    => $this->name,
            'options' => $this->options,
        ];
    }
}
