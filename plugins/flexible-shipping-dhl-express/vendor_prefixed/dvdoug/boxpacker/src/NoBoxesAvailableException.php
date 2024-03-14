<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

use RuntimeException;
/**
 * Exception used when an item cannot be packed into any box.
 */
class NoBoxesAvailableException extends \RuntimeException
{
    public \DhlVendor\DVDoug\BoxPacker\Item $item;
    public function __construct(string $message, \DhlVendor\DVDoug\BoxPacker\Item $item)
    {
        $this->item = $item;
        parent::__construct($message);
    }
    public function getItem() : \DhlVendor\DVDoug\BoxPacker\Item
    {
        return $this->item;
    }
}
