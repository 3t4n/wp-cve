<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Product;

use Holded\SDK\DTOs\Tax\Tax;

class Product implements \JsonSerializable
{
    /** @var string */
    public $holdedId;

    /** @var string simple|variants */
    public $kind;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var Tax[] */
    public $taxes;

    /** @var string|null */
    public $cost;

    /** @var string */
    public $price;

    /** @var string */
    public $barcode;

    /** @var string */
    public $sku;

    /** @var string */
    public $weight;

    /** @var string */
    public $stock;

    /** @var bool */
    public $forSale;

    /** @var bool */
    public $forPurchase;

    /** @var string */
    public $provider;

    /** @var Variation[] */
    public $variants;

    /** @var string */
    public $shopUrl;

    /** @var mixed[] */
    public $origin;

    /** @var Option[] */
    public $options;

    /** @var mixed[] */
    public $extra;

    /** @var string */
    public $imageUrl;

    public function removeVariantsWithoutSku(): void
    {
        if ($this->kind === 'variants' && isset($this->variants) && !empty($this->variants)) {
            $this->variants = array_filter($this->variants, function (Variation $variant) {
                return !empty($variant->sku);
            });
        }
    }

    public function hasVariants(): bool
    {
        if ($this->kind === 'variants' && isset($this->variants) && !empty($this->variants)) {
            return true;
        }

        return false;
    }

    public function jsonSerialize()
    {
        return [
            'holdedId'    => $this->holdedId,
            'kind'        => $this->kind,
            'name'        => $this->name,
            'description' => $this->description,
            'taxes'       => $this->taxes,
            'cost'        => $this->cost,
            'price'       => $this->price,
            'barcode'     => $this->barcode,
            'sku'         => $this->sku,
            'weight'      => $this->weight,
            'stock'       => $this->stock,
            'forSale'     => $this->forSale,
            'forPurchase' => $this->forPurchase,
            'provider'    => $this->provider,
            'shopUrl'     => $this->shopUrl,
            'origin'      => $this->origin,
            'variants'    => $this->variants,
            'options'     => $this->options,
            'extra'       => $this->extra,
            'imageUrl'    => $this->imageUrl,
        ];
    }
}
