<?php

/**
 * Packed packages meta data builder.
 *
 * @package WPDesk\PluginBuilder\Plugin\Hookable
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\OrderMetaData;

/**
 * Can build packed packages meta data.
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\OrderMetaData;

use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
class PackedPackagesMetaDataBuilder
{
    const META_DATA_KEY = 'packed_packages';
    /**
     * @var Shipment
     */
    private $shipment;
    /**
     * PackedPackagesMetaDataBuilder constructor.
     *
     * @param Shipment $shipment .
     */
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        $this->shipment = $shipment;
    }
    /**
     * Create meta data.
     *
     * @return string
     */
    public function create_meta_data()
    {
        $packed_packages = [];
        foreach ($this->shipment->packages as $package) {
            $items = [];
            foreach ($package->items as $item) {
                if (!isset($items[$item->name])) {
                    $items[$item->name] = 1;
                } else {
                    $items[$item->name]++;
                }
            }
            $packed_packages[] = ['package' => isset($package->description) ? $package->description : \__('Custom', 'flexible-shipping-ups'), 'items' => $items];
        }
        return \json_encode($packed_packages);
    }
}
