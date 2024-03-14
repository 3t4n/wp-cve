<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

use function count;
/**
 * A version of the packer that swallows internal exceptions.
 */
class InfalliblePacker extends \DhlVendor\DVDoug\BoxPacker\Packer
{
    protected \DhlVendor\DVDoug\BoxPacker\ItemList $unpackedItems;
    public function __construct()
    {
        $this->unpackedItems = new \DhlVendor\DVDoug\BoxPacker\ItemList();
        parent::__construct();
    }
    /**
     * Return the items that couldn't be packed.
     */
    public function getUnpackedItems() : \DhlVendor\DVDoug\BoxPacker\ItemList
    {
        return $this->unpackedItems;
    }
    /**
     * {@inheritdoc}
     */
    public function pack() : \DhlVendor\DVDoug\BoxPacker\PackedBoxList
    {
        $this->sanityPrecheck();
        while (\true) {
            try {
                return parent::pack();
            } catch (\DhlVendor\DVDoug\BoxPacker\NoBoxesAvailableException $e) {
                $this->unpackedItems->insert($e->getItem());
                $this->items->remove($e->getItem());
            }
        }
    }
    private function sanityPrecheck() : void
    {
        $cache = [];
        foreach ($this->items as $item) {
            $cacheKey = $item->getWidth() . '|' . $item->getLength() . '|' . $item->getDepth() . '|' . ($item->getKeepFlat() ? '2D' : '3D');
            foreach ($this->boxes as $box) {
                if ($item->getWeight() <= $box->getMaxWeight() - $box->getEmptyWeight() && (isset($cache[$cacheKey]) || \count((new \DhlVendor\DVDoug\BoxPacker\OrientatedItemFactory($box))->getPossibleOrientations($item, null, $box->getInnerWidth(), $box->getInnerLength(), $box->getInnerDepth(), 0, 0, 0, new \DhlVendor\DVDoug\BoxPacker\PackedItemList())) > 0)) {
                    $cache[$cacheKey] = \true;
                    continue 2;
                }
            }
            $this->unpackedItems->insert($item);
            $this->items->remove($item);
        }
    }
}
