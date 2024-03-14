<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

/**
 * An item to be packed where additional constraints need to be considered. Only implement this interface if you actually
 * need this additional functionality as it will slow down the packing algorithm.
 */
interface ConstrainedPlacementItem extends \DhlVendor\DVDoug\BoxPacker\Item
{
    /**
     * Hook for user implementation of item-specific constraints, e.g. max <x> batteries per box.
     */
    public function canBePacked(\DhlVendor\DVDoug\BoxPacker\Box $box, \DhlVendor\DVDoug\BoxPacker\PackedItemList $alreadyPackedItems, int $proposedX, int $proposedY, int $proposedZ, int $width, int $length, int $depth) : bool;
}
