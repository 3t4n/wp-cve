<?php

declare(strict_types=1);

namespace Siel\Acumulus\Data;

use Siel\Acumulus\Api;
use Siel\Acumulus\Fld;

/**
 * Represents an invoice line object of an Acumulus API invoice object.
 *
 * Field names are copied from the API, though capitals are introduced for
 * readability and to prevent PhpStorm typo inspections.
 *
 * Metadata can be added via the {@see MetadataCollection} methods.
 *
 * @property ?string $itemNumber
 * @property ?string $product
 * @property ?string $nature
 * @property ?float $unitPrice
 * @property ?float $vatRate
 * @property ?float $quantity
 * @property ?float $costPrice
 *
 * @method bool setItemNumber(?string $value, int $mode = PropertySet::Always)
 * @method bool setProduct(?string $value, int $mode = PropertySet::Always)
 * @method bool setNature(?string $value, int $mode = PropertySet::Always)
 * @method bool setUnitPrice(?float $value, int $mode = PropertySet::Always)
 * @method bool setVatRate(?float $value, int $mode = PropertySet::Always)
 * @method bool setQuantity(?float $value, int $mode = PropertySet::Always)
 * @method bool setCostPrice(?float $value, int $mode = PropertySet::Always)
 */
class Line extends AcumulusObject
{
    protected function getPropertyDefinitions(): array
    {
        return [
            ['name' => Fld::ItemNumber, 'type' => 'string'],
            ['name' => Fld::Product, 'type' => 'string'],
            ['name' => Fld::Nature, 'type' => 'string', 'allowedValues' => [Api::Nature_Product, Api::Nature_Service]],
            ['name' => Fld::UnitPrice, 'type' => 'float', 'required' => true],
            ['name' => Fld::VatRate, 'type' => 'float', 'required' => true],
            ['name' => Fld::Quantity, 'type' => 'float', 'required' => true],
            ['name' => Fld::CostPrice, 'type' => 'float'],
        ];
    }

    /** @var \Siel\Acumulus\Data\Line[] */
    protected array $children = [];

    /**
     * @return \Siel\Acumulus\Data\Line[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(Line $child): void
    {
        $this->children[] = $child;
    }

    public function removeChildren(): void
    {
        $this->children = [];
    }

    public function hasWarning(): bool
    {
        $hasWarning = parent::hasWarning();
        if (!$hasWarning) {
            foreach ($this->getChildren() as $line) {
                if ($line->hasWarning()) {
                    $hasWarning  = true;
                    break;
                }
            }
        }
        return $hasWarning;
    }
}
