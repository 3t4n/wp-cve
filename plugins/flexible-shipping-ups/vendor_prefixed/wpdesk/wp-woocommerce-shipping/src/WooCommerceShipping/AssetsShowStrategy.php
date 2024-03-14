<?php

namespace UpsFreeVendor\WPDesk\WooCommerceShipping;

use UpsFreeVendor\WPDesk\ShowDecision\ShouldShowStrategy;
/**
 * Can decide when to add assets to frontend.
 */
class AssetsShowStrategy implements \UpsFreeVendor\WPDesk\ShowDecision\ShouldShowStrategy
{
    const MANAGE_WOOCOMMERCE = 'manage_woocommerce';
    /**
     * @inheritDoc
     */
    public function shouldDisplay() : bool
    {
        return \current_user_can(self::MANAGE_WOOCOMMERCE) || \is_cart() || \is_checkout();
    }
}
