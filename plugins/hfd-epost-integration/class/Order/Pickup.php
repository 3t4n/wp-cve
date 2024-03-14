<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 6:06 PM
 */
namespace Hfd\Woocommerce\Order;

use Hfd\Woocommerce\Template;

class Pickup extends Template
{
    /**
     * @param \WC_Order_Item_Shipping $item
     * @return string
     */
    public function renderAdminInfo($item)
    {
        $spotInfo = $item->get_meta('epost_pickup_info');
        if (!$spotInfo) {
            return '';
        }

        $spotInfo = unserialize($spotInfo);

        return $this->fetchView('order/pickup.php', array('spotInfo' => $spotInfo));
    }

    /**
     * @param \WC_Order $order
     * @return \WC_Order_Item_Shipping|null
     */
    public function getShippingItem($order)
    {
        /* @var \WC_Order_Item_Shipping $method */
        foreach ($order->get_shipping_methods() as $method) {
            $methodId = substr($method->get_method_id(), 0, strlen(\Hfd\Woocommerce\Shipping\Epost::METHOD_ID));
            if ($methodId == \Hfd\Woocommerce\Shipping\Epost::METHOD_ID) {
                return $method;
            }
        }

        return null;
    }
}