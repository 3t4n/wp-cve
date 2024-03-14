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
use function array_map;
use function count;
use function max;
use function reset;
use function usort;
/**
 * Actual packer.
 */
class VolumePacker implements \DhlVendor\Psr\Log\LoggerAwareInterface
{
    /**
     * The logger instance.
     */
    protected \DhlVendor\Psr\Log\LoggerInterface $logger;
    /**
     * Box to pack items into.
     */
    protected \DhlVendor\DVDoug\BoxPacker\Box $box;
    /**
     * List of items to be packed.
     */
    protected \DhlVendor\DVDoug\BoxPacker\ItemList $items;
    /**
     * Whether the packer is in single-pass mode.
     */
    protected bool $singlePassMode = \false;
    /**
     * Whether the packer should only try packing along the width.
     */
    protected bool $packAcrossWidthOnly = \false;
    protected bool $beStrictAboutItemOrdering = \false;
    private \DhlVendor\DVDoug\BoxPacker\LayerPacker $layerPacker;
    private bool $hasConstrainedItems;
    public function __construct(\DhlVendor\DVDoug\BoxPacker\Box $box, \DhlVendor\DVDoug\BoxPacker\ItemList $items)
    {
        $this->box = $box;
        $this->items = clone $items;
        $this->logger = new \DhlVendor\Psr\Log\NullLogger();
        $this->hasConstrainedItems = $items->hasConstrainedItems();
        $this->layerPacker = new \DhlVendor\DVDoug\BoxPacker\LayerPacker($this->box);
        $this->layerPacker->setLogger($this->logger);
    }
    /**
     * Sets a logger.
     */
    public function setLogger(\DhlVendor\Psr\Log\LoggerInterface $logger) : void
    {
        $this->logger = $logger;
        $this->layerPacker->setLogger($logger);
    }
    public function packAcrossWidthOnly() : void
    {
        $this->packAcrossWidthOnly = \true;
    }
    public function beStrictAboutItemOrdering(bool $beStrict) : void
    {
        $this->beStrictAboutItemOrdering = $beStrict;
        $this->layerPacker->beStrictAboutItemOrdering($beStrict);
    }
    /**
     * @internal
     */
    public function setSinglePassMode(bool $singlePassMode) : void
    {
        $this->singlePassMode = $singlePassMode;
        if ($singlePassMode) {
            $this->packAcrossWidthOnly = \true;
        }
        $this->layerPacker->setSinglePassMode($singlePassMode);
    }
    /**
     * Pack as many items as possible into specific given box.
     *
     * @return PackedBox packed box
     */
    public function pack() : \DhlVendor\DVDoug\BoxPacker\PackedBox
    {
        $this->logger->debug("[EVALUATING BOX] {$this->box->getReference()}", ['box' => $this->box]);
        $rotationsToTest = [\false];
        if (!$this->packAcrossWidthOnly) {
            $rotationsToTest[] = \true;
        }
        $boxPermutations = [];
        foreach ($rotationsToTest as $rotation) {
            if ($rotation) {
                $boxWidth = $this->box->getInnerLength();
                $boxLength = $this->box->getInnerWidth();
            } else {
                $boxWidth = $this->box->getInnerWidth();
                $boxLength = $this->box->getInnerLength();
            }
            $boxPermutation = $this->packRotation($boxWidth, $boxLength);
            if ($boxPermutation->getItems()->count() === $this->items->count()) {
                return $boxPermutation;
            }
            $boxPermutations[] = $boxPermutation;
        }
        \usort($boxPermutations, static fn(\DhlVendor\DVDoug\BoxPacker\PackedBox $a, \DhlVendor\DVDoug\BoxPacker\PackedBox $b) => $b->getVolumeUtilisation() <=> $a->getVolumeUtilisation());
        return \reset($boxPermutations);
    }
    /**
     * Pack as many items as possible into specific given box.
     *
     * @return PackedBox packed box
     */
    private function packRotation(int $boxWidth, int $boxLength) : \DhlVendor\DVDoug\BoxPacker\PackedBox
    {
        $this->logger->debug("[EVALUATING ROTATION] {$this->box->getReference()}", ['width' => $boxWidth, 'length' => $boxLength]);
        $this->layerPacker->setBoxIsRotated($this->box->getInnerWidth() !== $boxWidth);
        /** @var PackedLayer[] $layers */
        $layers = [];
        $items = clone $this->items;
        while ($items->count() > 0) {
            $layerStartDepth = static::getCurrentPackedDepth($layers);
            $packedItemList = $this->getPackedItemList($layers);
            // do a preliminary layer pack to get the depth used
            $preliminaryItems = clone $items;
            $preliminaryLayer = $this->layerPacker->packLayer($preliminaryItems, clone $packedItemList, 0, 0, $layerStartDepth, $boxWidth, $boxLength, $this->box->getInnerDepth() - $layerStartDepth, 0, \true);
            if (\count($preliminaryLayer->getItems()) === 0) {
                break;
            }
            $preliminaryLayerDepth = $preliminaryLayer->getDepth();
            if ($preliminaryLayerDepth === $preliminaryLayer->getItems()[0]->getDepth()) {
                // preliminary === final
                $layers[] = $preliminaryLayer;
                $items = $preliminaryItems;
            } else {
                // redo with now-known-depth so that we can stack to that height from the first item
                $layers[] = $this->layerPacker->packLayer($items, $packedItemList, 0, 0, $layerStartDepth, $boxWidth, $boxLength, $this->box->getInnerDepth() - $layerStartDepth, $preliminaryLayerDepth, \true);
            }
        }
        if (!$this->singlePassMode && $layers) {
            $layers = $this->stabiliseLayers($layers);
            // having packed layers, there may be tall, narrow gaps at the ends that can be utilised
            $maxLayerWidth = \max(\array_map(static fn(\DhlVendor\DVDoug\BoxPacker\PackedLayer $layer) => $layer->getEndX(), $layers));
            $layers[] = $this->layerPacker->packLayer($items, $this->getPackedItemList($layers), $maxLayerWidth, 0, 0, $boxWidth, $boxLength, $this->box->getInnerDepth(), $this->box->getInnerDepth(), \false);
            $maxLayerLength = \max(\array_map(static fn(\DhlVendor\DVDoug\BoxPacker\PackedLayer $layer) => $layer->getEndY(), $layers));
            $layers[] = $this->layerPacker->packLayer($items, $this->getPackedItemList($layers), 0, $maxLayerLength, 0, $boxWidth, $boxLength, $this->box->getInnerDepth(), $this->box->getInnerDepth(), \false);
        }
        $layers = $this->correctLayerRotation($layers, $boxWidth);
        return new \DhlVendor\DVDoug\BoxPacker\PackedBox($this->box, $this->getPackedItemList($layers));
    }
    /**
     * During packing, it is quite possible that layers have been created that aren't physically stable
     * i.e. they overhang the ones below.
     *
     * This function reorders them so that the ones with the greatest surface area are placed at the bottom
     *
     * @param  PackedLayer[] $oldLayers
     * @return PackedLayer[]
     */
    private function stabiliseLayers(array $oldLayers) : array
    {
        if ($this->hasConstrainedItems || $this->beStrictAboutItemOrdering) {
            // constraints include position, so cannot change
            return $oldLayers;
        }
        $stabiliser = new \DhlVendor\DVDoug\BoxPacker\LayerStabiliser();
        return $stabiliser->stabilise($oldLayers);
    }
    /**
     * Swap back width/length of the packed items to match orientation of the box if needed.
     *
     * @param PackedLayer[] $oldLayers
     *
     * @return PackedLayer[]
     */
    private function correctLayerRotation(array $oldLayers, int $boxWidth) : array
    {
        if ($this->box->getInnerWidth() === $boxWidth) {
            return $oldLayers;
        }
        $newLayers = [];
        foreach ($oldLayers as $originalLayer) {
            $newLayer = new \DhlVendor\DVDoug\BoxPacker\PackedLayer();
            foreach ($originalLayer->getItems() as $item) {
                $packedItem = new \DhlVendor\DVDoug\BoxPacker\PackedItem($item->getItem(), $item->getY(), $item->getX(), $item->getZ(), $item->getLength(), $item->getWidth(), $item->getDepth());
                $newLayer->insert($packedItem);
            }
            $newLayers[] = $newLayer;
        }
        return $newLayers;
    }
    /**
     * Generate a single list of items packed.
     * @param PackedLayer[] $layers
     */
    private function getPackedItemList(array $layers) : \DhlVendor\DVDoug\BoxPacker\PackedItemList
    {
        $packedItemList = new \DhlVendor\DVDoug\BoxPacker\PackedItemList();
        foreach ($layers as $layer) {
            foreach ($layer->getItems() as $packedItem) {
                $packedItemList->insert($packedItem);
            }
        }
        return $packedItemList;
    }
    /**
     * Return the current packed depth.
     *
     * @param PackedLayer[] $layers
     */
    private static function getCurrentPackedDepth(array $layers) : int
    {
        $depth = 0;
        foreach ($layers as $layer) {
            $depth += $layer->getDepth();
        }
        return $depth;
    }
}
