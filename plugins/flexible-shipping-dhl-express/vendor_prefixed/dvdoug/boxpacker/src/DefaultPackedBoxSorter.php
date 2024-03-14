<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

class DefaultPackedBoxSorter implements \DhlVendor\DVDoug\BoxPacker\PackedBoxSorter
{
    public function compare(\DhlVendor\DVDoug\BoxPacker\PackedBox $boxA, \DhlVendor\DVDoug\BoxPacker\PackedBox $boxB) : int
    {
        $choice = $boxB->getItems()->count() <=> $boxA->getItems()->count();
        if ($choice === 0) {
            $choice = $boxB->getVolumeUtilisation() <=> $boxA->getVolumeUtilisation();
        }
        if ($choice === 0) {
            $choice = $boxB->getUsedVolume() <=> $boxA->getUsedVolume();
        }
        return $choice;
    }
}
