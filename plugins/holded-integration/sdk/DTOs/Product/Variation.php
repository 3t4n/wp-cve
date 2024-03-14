<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Product;

class Variation implements \JsonSerializable
{
    /** @var string */
    public $barcode;

    /** @var string */
    public $sku;

    /** @var string */
    public $price;

    /** @var string|null */
    public $cost;

    /** @var string */
    public $stock;

    /** @var Option[] */
    public $options;

    public function jsonSerialize()
    {
        return [
            'barcode' => $this->barcode,
            'sku'     => $this->sku,
            'price'   => $this->price,
            'cost'    => $this->cost,
            'stock'   => $this->stock,
            'options' => $this->options,
        ];
    }
}
