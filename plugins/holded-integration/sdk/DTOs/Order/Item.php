<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Order;

class Item implements \JsonSerializable
{
    /** @var string */
    public $name;

    /** @var string */
    public $desc;

    /** @var mixed */
    public $units;

    /** @var float */
    public $subtotal;

    /** @var float */
    public $tax;

    /** @var string */
    public $sku;

    /** @var string */
    public $stock;

    /** @var mixed */
    public $taxes;

    /** @var string */
    public $k;

    public function jsonSerialize()
    {
        return [
            'name'     => $this->name,
            'desc'     => $this->desc,
            'units'    => $this->units,
            'subtotal' => $this->subtotal,
            'tax'      => $this->tax, // Legacy, just in case
            'sku'      => $this->sku,
            'stock'    => $this->stock,
            'taxes'    => $this->taxes,
            'k'        => $this->k,
        ];
    }
}
