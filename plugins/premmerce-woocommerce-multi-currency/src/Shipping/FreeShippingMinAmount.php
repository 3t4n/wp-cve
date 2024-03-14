<?php namespace Premmerce\WoocommerceMulticurrency\Shipping;

use \Premmerce\WoocommerceMulticurrency\Frontend\UserPricesHandler;

class FreeShippingMinAmount
{
    /**
     * @var UserPricesHandler
     */
    private $userPricesHandler;

    /**
     * FreeShippingMinAmount constructor.
     *
     * @param UserPricesHandler $userPricesHandler
     */
    public function __construct(UserPricesHandler $userPricesHandler)
    {
        $this->userPricesHandler = $userPricesHandler;

        add_action('woocommerce_shipping_free_shipping_is_available', array($this, 'isFreeShippingAvailable'), 10, 3);
    }

    /**
     * Recalculate min amount needed for free shipping and recheck if it available
     *
     * @param bool                       $isAvailableOriginal
     * @param array                      $package
     * @param \WC_Shipping_Free_Shipping $freeShippingMethod
     *
     *
     * @return bool
     */
    public function isFreeShippingAvailable($isAvailableOriginal, $package, \WC_Shipping_Free_Shipping $freeShippingMethod)
    {
        if (! in_array($freeShippingMethod->requires, array( 'min_amount', 'either', 'both' ), true)) {//Min amount doesn't matter
            return $isAvailableOriginal;
        }


        $hasCoupon         = false;
        $hasMetMinAmount = false;

        if (in_array($freeShippingMethod->requires, array( 'coupon', 'either', 'both' ), true)) {
            $coupons = WC()->cart->get_coupons();

            if ($coupons) {
                foreach ($coupons as $code => $coupon) {
                    if ($coupon->is_valid() && $coupon->get_free_shipping()) {
                        $hasCoupon = true;
                        break;
                    }
                }
            }
        }

        $total = WC()->cart->get_displayed_subtotal();

        if (WC()->cart->display_prices_including_tax()) {
            $total = round($total - (WC()->cart->get_discount_total() + WC()->cart->get_discount_tax()), wc_get_price_decimals());
        } else {
            $total = round($total - WC()->cart->get_discount_total(), wc_get_price_decimals());
        }

        $minAmount = !$freeShippingMethod->min_amount ?: $this->userPricesHandler->calculatePriceInUsersCurrency($freeShippingMethod->min_amount);

        if ($total >= $minAmount) {
            $hasMetMinAmount = true;
        }

        switch ($freeShippingMethod->requires) {
            case 'min_amount':
                $isAvailable = $hasMetMinAmount;
                break;
            case 'coupon':
                $isAvailable = $hasCoupon;
                break;
            case 'both':
                $isAvailable = $hasMetMinAmount && $hasCoupon;
                break;
            case 'either':
                $isAvailable = $hasMetMinAmount || $hasCoupon;
                break;
            default:
                $isAvailable = true;
                break;
        }

        return $isAvailable;
    }
}
