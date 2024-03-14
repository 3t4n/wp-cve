<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

class DefaultItemSorter implements \DhlVendor\DVDoug\BoxPacker\ItemSorter
{
    public function compare(\DhlVendor\DVDoug\BoxPacker\Item $itemA, \DhlVendor\DVDoug\BoxPacker\Item $itemB) : int
    {
        $volumeDecider = $itemB->getWidth() * $itemB->getLength() * $itemB->getDepth() <=> $itemA->getWidth() * $itemA->getLength() * $itemA->getDepth();
        if ($volumeDecider !== 0) {
            return $volumeDecider;
        }
        $weightDecider = $itemB->getWeight() <=> $itemA->getWeight();
        if ($weightDecider !== 0) {
            return $weightDecider;
        }
        return $itemA->getDescription() <=> $itemB->getDescription();
    }
}
