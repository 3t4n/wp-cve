<?php

namespace DhlVendor\Octolize\ShippingExtensions;

/**
 * .
 */
trait AdminPage
{
    /**
     * @return bool
     */
    public function is_shipping_extensions_page() : bool
    {
        return (\get_current_screen()->id ?? '') === \DhlVendor\Octolize\ShippingExtensions\Page::SCREEN_ID;
    }
}
