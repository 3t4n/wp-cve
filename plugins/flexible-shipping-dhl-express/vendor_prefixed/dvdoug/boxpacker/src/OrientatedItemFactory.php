<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor\DVDoug\BoxPacker;

use DhlVendor\Psr\Log\LoggerAwareInterface;
use DhlVendor\Psr\Log\LoggerInterface;
use DhlVendor\Psr\Log\NullLogger;
use function array_filter;
use function count;
use function usort;
/**
 * Figure out orientations for an item and a given set of dimensions.
 *
 * @internal
 */
class OrientatedItemFactory implements \DhlVendor\Psr\Log\LoggerAwareInterface
{
    protected \DhlVendor\Psr\Log\LoggerInterface $logger;
    protected \DhlVendor\DVDoug\BoxPacker\Box $box;
    /**
     * Whether the packer is in single-pass mode.
     */
    protected bool $singlePassMode = \false;
    protected bool $boxIsRotated = \false;
    /**
     * @var array<string, bool>
     */
    protected static array $emptyBoxStableItemOrientationCache = [];
    public function __construct(\DhlVendor\DVDoug\BoxPacker\Box $box)
    {
        $this->box = $box;
        $this->logger = new \DhlVendor\Psr\Log\NullLogger();
    }
    public function setLogger(\DhlVendor\Psr\Log\LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
    public function setSinglePassMode(bool $singlePassMode) : void
    {
        $this->singlePassMode = $singlePassMode;
    }
    public function setBoxIsRotated(bool $boxIsRotated) : void
    {
        $this->boxIsRotated = $boxIsRotated;
    }
    /**
     * Get the best orientation for an item.
     */
    public function getBestOrientation(\DhlVendor\DVDoug\BoxPacker\Item $item, ?\DhlVendor\DVDoug\BoxPacker\OrientatedItem $prevItem, \DhlVendor\DVDoug\BoxPacker\ItemList $nextItems, int $widthLeft, int $lengthLeft, int $depthLeft, int $rowLength, int $x, int $y, int $z, \DhlVendor\DVDoug\BoxPacker\PackedItemList $prevPackedItemList, bool $considerStability) : ?\DhlVendor\DVDoug\BoxPacker\OrientatedItem
    {
        $this->logger->debug("evaluating item {$item->getDescription()} for fit", ['item' => $item, 'space' => ['widthLeft' => $widthLeft, 'lengthLeft' => $lengthLeft, 'depthLeft' => $depthLeft], 'position' => ['x' => $x, 'y' => $y, 'z' => $z]]);
        $possibleOrientations = $this->getPossibleOrientations($item, $prevItem, $widthLeft, $lengthLeft, $depthLeft, $x, $y, $z, $prevPackedItemList);
        $usableOrientations = $considerStability ? $this->getUsableOrientations($item, $possibleOrientations) : $possibleOrientations;
        if (empty($usableOrientations)) {
            return null;
        }
        $sorter = new \DhlVendor\DVDoug\BoxPacker\OrientatedItemSorter($this, $this->singlePassMode, $widthLeft, $lengthLeft, $depthLeft, $nextItems, $rowLength, $x, $y, $z, $prevPackedItemList, $this->logger);
        \usort($usableOrientations, $sorter);
        $this->logger->debug('Selected best fit orientation', ['orientation' => $usableOrientations[0]]);
        return $usableOrientations[0];
    }
    /**
     * Find all possible orientations for an item.
     *
     * @return OrientatedItem[]
     */
    public function getPossibleOrientations(\DhlVendor\DVDoug\BoxPacker\Item $item, ?\DhlVendor\DVDoug\BoxPacker\OrientatedItem $prevItem, int $widthLeft, int $lengthLeft, int $depthLeft, int $x, int $y, int $z, \DhlVendor\DVDoug\BoxPacker\PackedItemList $prevPackedItemList) : array
    {
        $permutations = $this->generatePermutations($item, $prevItem);
        // remove any that simply don't fit
        $orientations = [];
        foreach ($permutations as $dimensions) {
            if ($dimensions[0] <= $widthLeft && $dimensions[1] <= $lengthLeft && $dimensions[2] <= $depthLeft) {
                $orientations[] = new \DhlVendor\DVDoug\BoxPacker\OrientatedItem($item, $dimensions[0], $dimensions[1], $dimensions[2]);
            }
        }
        if ($item instanceof \DhlVendor\DVDoug\BoxPacker\ConstrainedPlacementItem && !$this->box instanceof \DhlVendor\DVDoug\BoxPacker\WorkingVolume) {
            $orientations = \array_filter($orientations, function (\DhlVendor\DVDoug\BoxPacker\OrientatedItem $i) use($x, $y, $z, $prevPackedItemList) : bool {
                /** @var ConstrainedPlacementItem $constrainedItem */
                $constrainedItem = $i->getItem();
                if ($this->boxIsRotated) {
                    $rotatedPrevPackedItemList = new \DhlVendor\DVDoug\BoxPacker\PackedItemList();
                    foreach ($prevPackedItemList as $prevPackedItem) {
                        $rotatedPrevPackedItemList->insert(new \DhlVendor\DVDoug\BoxPacker\PackedItem($prevPackedItem->getItem(), $prevPackedItem->getY(), $prevPackedItem->getX(), $prevPackedItem->getZ(), $prevPackedItem->getLength(), $prevPackedItem->getWidth(), $prevPackedItem->getDepth()));
                    }
                    return $constrainedItem->canBePacked($this->box, $rotatedPrevPackedItemList, $y, $x, $z, $i->getLength(), $i->getWidth(), $i->getDepth());
                } else {
                    return $constrainedItem->canBePacked($this->box, $prevPackedItemList, $x, $y, $z, $i->getWidth(), $i->getLength(), $i->getDepth());
                }
            });
        }
        return $orientations;
    }
    /**
     * @param  OrientatedItem[] $possibleOrientations
     * @return OrientatedItem[]
     */
    protected function getUsableOrientations(\DhlVendor\DVDoug\BoxPacker\Item $item, array $possibleOrientations) : array
    {
        $stableOrientations = $unstableOrientations = [];
        // Divide possible orientations into stable (low centre of gravity) and unstable (high centre of gravity)
        foreach ($possibleOrientations as $orientation) {
            if ($orientation->isStable() || $this->box->getInnerDepth() === $orientation->getDepth()) {
                $stableOrientations[] = $orientation;
            } else {
                $unstableOrientations[] = $orientation;
            }
        }
        /*
         * We prefer to use stable orientations only, but allow unstable ones if
         * the item doesn't fit in the box any other way
         */
        if (\count($stableOrientations) > 0) {
            return $stableOrientations;
        }
        if (\count($unstableOrientations) > 0 && !$this->hasStableOrientationsInEmptyBox($item)) {
            return $unstableOrientations;
        }
        return [];
    }
    /**
     * Return the orientations for this item if it were to be placed into the box with nothing else.
     */
    protected function hasStableOrientationsInEmptyBox(\DhlVendor\DVDoug\BoxPacker\Item $item) : bool
    {
        $cacheKey = $item->getWidth() . '|' . $item->getLength() . '|' . $item->getDepth() . '|' . ($item->getKeepFlat() ? '2D' : '3D') . '|' . $this->box->getInnerWidth() . '|' . $this->box->getInnerLength() . '|' . $this->box->getInnerDepth();
        if (isset(static::$emptyBoxStableItemOrientationCache[$cacheKey])) {
            return static::$emptyBoxStableItemOrientationCache[$cacheKey];
        }
        $orientations = $this->getPossibleOrientations($item, null, $this->box->getInnerWidth(), $this->box->getInnerLength(), $this->box->getInnerDepth(), 0, 0, 0, new \DhlVendor\DVDoug\BoxPacker\PackedItemList());
        $stableOrientations = \array_filter($orientations, static fn(\DhlVendor\DVDoug\BoxPacker\OrientatedItem $orientation) => $orientation->isStable());
        static::$emptyBoxStableItemOrientationCache[$cacheKey] = \count($stableOrientations) > 0;
        return static::$emptyBoxStableItemOrientationCache[$cacheKey];
    }
    /**
     * @return array<array<int>>
     */
    private function generatePermutations(\DhlVendor\DVDoug\BoxPacker\Item $item, ?\DhlVendor\DVDoug\BoxPacker\OrientatedItem $prevItem) : array
    {
        // Special case items that are the same as what we just packed - keep orientation
        if ($prevItem && $prevItem->isSameDimensions($item)) {
            return [[$prevItem->getWidth(), $prevItem->getLength(), $prevItem->getDepth()]];
        }
        $permutations = [];
        $w = $item->getWidth();
        $l = $item->getLength();
        $d = $item->getDepth();
        // simple 2D rotation
        $permutations[$w . '|' . $l . '|' . $d] = [$w, $l, $d];
        $permutations[$l . '|' . $w . '|' . $d] = [$l, $w, $d];
        // add 3D rotation if we're allowed
        if (!$item->getKeepFlat()) {
            $permutations[$w . '|' . $d . '|' . $l] = [$w, $d, $l];
            $permutations[$l . '|' . $d . '|' . $w] = [$l, $d, $w];
            $permutations[$d . '|' . $w . '|' . $l] = [$d, $w, $l];
            $permutations[$d . '|' . $l . '|' . $w] = [$d, $l, $w];
        }
        return $permutations;
    }
}
