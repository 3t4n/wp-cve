<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Product;

class Option implements \JsonSerializable
{
    /** @var string */
    public $id;

    /** @var string */
    public $value;

    /** @var string */
    public $parentId;

    /** @var string */
    public $parentName;

    public function jsonSerialize()
    {
        return [
            'parentId'   => $this->parentId,
            'parentName' => $this->parentName,
            'id'         => $this->id,
            'value'      => $this->value,
        ];
    }
}
