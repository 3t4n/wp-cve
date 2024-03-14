<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

/**
 * A callback to be used with usort(), implementing logic to determine which Item is a higher priority for packing.
 */
interface ItemSorter
{
    /**
     * Return -1 if $itemA is preferred, 1 if $itemB is preferred or 0 if neither is preferred.
     */
    public function compare(\DhlVendor\DVDoug\BoxPacker\Item $itemA, \DhlVendor\DVDoug\BoxPacker\Item $itemB) : int;
}
