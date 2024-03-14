<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Beacon;

use DropshippingXmlFreeVendor\WPDesk\Beacon\BeaconShouldShowStrategy;
class BeaconShowStrategy implements \DropshippingXmlFreeVendor\WPDesk\Beacon\BeaconShouldShowStrategy
{
    public function shouldDisplay()
    {
        return \true;
    }
}
