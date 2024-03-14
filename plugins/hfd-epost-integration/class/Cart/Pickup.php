<?php
/**
 * Created by PhpStorm.
 * Date: 6/5/18
 * Time: 6:14 PM
 */
namespace Hfd\Woocommerce\Cart;

class Pickup
{
    /**
     * @param array $spotInfo
     */
    public function saveSpotInfo($spotInfo)
    {
        $session = WC()->session;
        $session->set('epost_spot_info', $spotInfo);
    }

    /**
     * @return array
     */
    public function getSpotInfo()
    {
        $spotInfo = WC()->session->get('epost_spot_info');

        if (!$spotInfo || !is_array($spotInfo)) {
            return null;
        }

        return array_map(function ($value) {
            return stripslashes($value);
        }, $spotInfo);
    }

    /**
     * Clear pickup session data
     */
    public function clearSpotInfo()
    {
        WC()->session->set('epost_spot_info', null);
    }

    /**
     * @param \WC_Order $order
     */
    public function convertToOrder($order)
    {
        $shippingItems = $order->get_shipping_methods();

        if (!count($shippingItems)) {
            return;
        }

        /* @var \WC_Order_Item_Shipping $shippingItem */
        foreach ($shippingItems as $shippingItem) {
            $shippingItem->get_formatted_meta_data();
            $methodId = \Hfd\Woocommerce\Shipping\Epost::METHOD_ID;
            if (substr($shippingItem->get_method_id(), 0, strlen($methodId)) == $methodId) {
                $spotInfo = $this->getSpotInfo();
                if ($spotInfo) {
                    // add pickup info into shipping item
                    $spotInfo = serialize($spotInfo);
                    $shippingItem->add_meta_data('epost_pickup_info', $spotInfo);
                    $shippingItem->save_meta_data();
                }
            }
        }

        $this->clearSpotInfo();
    }
}