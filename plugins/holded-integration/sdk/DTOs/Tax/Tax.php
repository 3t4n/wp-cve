<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Tax;

class Tax implements \JsonSerializable
{
    public const TYPE_PERCENTAGE = 'percentage';

    /** @var string */
    public $name;

    /** @var string */
    public $country;

    /** @var string */
    public $type;

    /** @var float */
    public $rate;

    /** @var mixed[] */
    public $origin;

    public function jsonSerialize()
    {
        return [
            'name'    => $this->name,
            'country' => $this->country,
            'type'    => $this->type,
            'rate'    => $this->rate,
            'origin'  => $this->origin,
        ];
    }
}
