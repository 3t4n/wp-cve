<?php

namespace DhlVendor\WPDesk\Packer;

use DhlVendor\DVDoug\BoxPacker\NoBoxesAvailableException;
use DhlVendor\WPDesk\Packer\Box\BoxImplementation;
use DhlVendor\WPDesk\Packer\Exception\NoItemsException;
use DhlVendor\WPDesk\Packer\Item\ItemImplementation;
/**
 * Can pack items into boxes. Each item is packed to separate box with item dimensions and weight.
 *
 * Put some items using add_item()
 *
 * @package WPDesk\Packer
 */
class Packer3D extends \DhlVendor\WPDesk\Packer\Packer
{
    /**
     * Pack items to boxes creating packages.
     *
     * @throws NoItemsException|NoBoxesAvailableException .
     */
    public function pack()
    {
        if (0 === \count($this->items)) {
            throw new \DhlVendor\WPDesk\Packer\Exception\NoItemsException('No items to pack!');
        }
        $this->packages = [];
        $packer = new \DhlVendor\DVDoug\BoxPacker\Packer();
        foreach ($this->boxes as $box) {
            $packer->addBox($box);
        }
        foreach ($this->items as $item) {
            $packer->addItem($item);
        }
        $packed_packages = $packer->pack();
        /** @var \DVDoug\BoxPacker\PackedBox $packed_package */
        foreach ($packed_packages as $packed_package) {
            $items = [];
            foreach ($packed_package->getItems() as $item) {
                $items[] = $item->getItem();
            }
            $packed_box = new \DhlVendor\WPDesk\Packer\PackedBox($this->get_box($packed_package->getBox()->get_name()), $items);
            $packed_box->get_packed_items();
            $this->packages[] = $packed_box;
        }
        $this->items = [];
    }
    /**
     * @param $box_name
     *
     * @return Box
     */
    private function get_box($box_name)
    {
        foreach ($this->boxes as $box) {
            if ($box->get_name() === $box_name) {
                return $box;
            }
        }
    }
}
