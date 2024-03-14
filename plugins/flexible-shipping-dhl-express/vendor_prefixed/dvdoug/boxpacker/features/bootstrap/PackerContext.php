<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
declare (strict_types=1);
namespace DhlVendor;

use DhlVendor\Behat\Behat\Context\Context;
use DhlVendor\DVDoug\BoxPacker\Box;
use DhlVendor\DVDoug\BoxPacker\BoxList;
use DhlVendor\DVDoug\BoxPacker\ItemList;
use DhlVendor\DVDoug\BoxPacker\PackedBox;
use DhlVendor\DVDoug\BoxPacker\PackedBoxList;
use DhlVendor\DVDoug\BoxPacker\Packer;
use DhlVendor\DVDoug\BoxPacker\Rotation;
use DhlVendor\DVDoug\BoxPacker\Test\TestBox;
use DhlVendor\DVDoug\BoxPacker\Test\TestItem;
use DhlVendor\DVDoug\BoxPacker\VolumePacker;
use DhlVendor\PHPUnit\Framework\Assert;
\chdir(__DIR__ . '/../..');
/**
 * Defines application features from the specific context.
 */
class PackerContext implements \DhlVendor\Behat\Behat\Context\Context
{
    protected \DhlVendor\DVDoug\BoxPacker\Box $box;
    protected \DhlVendor\DVDoug\BoxPacker\BoxList $boxList;
    protected \DhlVendor\DVDoug\BoxPacker\ItemList $itemList;
    protected \DhlVendor\DVDoug\BoxPacker\PackedBox $packedBox;
    protected \DhlVendor\DVDoug\BoxPacker\PackedBoxList $packedBoxList;
    protected string $packerClass = \DhlVendor\DVDoug\BoxPacker\Packer::class;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->boxList = new \DhlVendor\DVDoug\BoxPacker\BoxList();
        $this->itemList = new \DhlVendor\DVDoug\BoxPacker\ItemList();
    }
    /**
     * @Given /^there is a box "([^"]+)", which has external dimensions (\d+)mm w × (\d+)mm l × (\d+)mm d × (\d+)g and internal dimensions (\d+)mm w × (\d+)mm l × (\d+)mm d and has a max weight of (\d+)g$/
     */
    public function thereIsABox($boxType, $outerWidth, $outerLength, $outerDepth, $emptyWeight, $innerWidth, $innerLength, $innerDepth, $maxWeight) : void
    {
        $box = new \DhlVendor\DVDoug\BoxPacker\Test\TestBox($boxType, $outerWidth, $outerLength, $outerDepth, $emptyWeight, $innerWidth, $innerLength, $innerDepth, $maxWeight);
        $this->boxList->insert($box);
    }
    /**
     * @Given /^the box "([^"]+)", which has external dimensions (\d+)mm w × (\d+)mm l × (\d+)mm d × (\d+)g and internal dimensions (\d+)mm w × (\d+)mm l × (\d+)mm d and has a max weight of (\d+)g$/
     */
    public function theBox($boxType, $outerWidth, $outerLength, $outerDepth, $emptyWeight, $innerWidth, $innerLength, $innerDepth, $maxWeight) : void
    {
        $box = new \DhlVendor\DVDoug\BoxPacker\Test\TestBox($boxType, $outerWidth, $outerLength, $outerDepth, $emptyWeight, $innerWidth, $innerLength, $innerDepth, $maxWeight);
        $this->box = $box;
    }
    /**
     * @When /^I add (\d+) x "([^"]+)" with dimensions (\d+)mm w × (\d+)mm l × (\d+)mm d × (\d+)g$/
     */
    public function thereIsAnItem($qty, $itemName, $width, $length, $depth, $weight) : void
    {
        $item = new \DhlVendor\DVDoug\BoxPacker\Test\TestItem($itemName, $width, $length, $depth, $weight, \DhlVendor\DVDoug\BoxPacker\Rotation::BestFit);
        $this->itemList->insert($item, $qty);
    }
    /**
     * @When /^I add (\d+) x keep flat "([^"]+)" with dimensions (\d+)mm w × (\d+)mm l × (\d+)mm d × (\d+)g$/
     */
    public function thereIsAKeepFlatItem($qty, $itemName, $width, $length, $depth, $weight) : void
    {
        $item = new \DhlVendor\DVDoug\BoxPacker\Test\TestItem($itemName, $width, $length, $depth, $weight, \DhlVendor\DVDoug\BoxPacker\Rotation::KeepFlat);
        $this->itemList->insert($item, $qty);
    }
    /**
     * @When I do a packing
     */
    public function iDoAPacking() : void
    {
        $packer = new $this->packerClass();
        $packer->setBoxes($this->boxList);
        $packer->setItems($this->itemList);
        $this->packedBoxList = $packer->pack();
    }
    /**
     * @When I do a volume-only packing
     */
    public function iDoAVolumePacking() : void
    {
        $packer = new \DhlVendor\DVDoug\BoxPacker\VolumePacker($this->box, $this->itemList);
        $this->packedBox = $packer->pack();
    }
    /**
     * @Then /^I should have (\d+) boxes of type "([^"]+)"$/
     */
    public function thereExistsBoxes($qty, $boxType) : void
    {
        $foundBoxes = 0;
        foreach ($this->packedBoxList as $packedBox) {
            if ($packedBox->getBox()->getReference() === $boxType) {
                ++$foundBoxes;
            }
        }
        \DhlVendor\PHPUnit\Framework\Assert::assertEquals($qty, $foundBoxes);
    }
    /**
     * @Then /^the packed box should have (\d+) items of type "([^"]+)"$/
     */
    public function thePackedBoxShouldHaveItems($qty, $itemType) : void
    {
        $foundItems = 0;
        foreach ($this->packedBox->getItems() as $packedItem) {
            if ($packedItem->getItem()->getDescription() === $itemType) {
                ++$foundItems;
            }
        }
        \DhlVendor\PHPUnit\Framework\Assert::assertEquals($qty, $foundItems);
    }
    /**
     * @Transform /^(\d+)$/
     */
    public function castStringToNumber($string) : int
    {
        return (int) $string;
    }
}
