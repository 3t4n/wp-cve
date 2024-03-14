<?php
namespace InspireLabs\WoocommerceInpost\shipx\services\shipment;

use InspireLabs\WoocommerceInpost\EasyPack;
use Exception;
use InspireLabs\WoocommerceInpost\EasyPack_API;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment_cost\ShipX_Shipment_Cost_Model;
use ReflectionClass;
use ReflectionException;

class ShipX_Shipment_Price_Calculator_Service
{

    /**
     * @var EasyPack_API
     */
    private $api;

    public function __construct()
    {
        $this->api = EasyPack_API();
    }


    /**
     * @param $order_id
     *
     * @return ShipX_Shipment_Model|null
     */
    public function get_shipment_by_order_id($order_id)
    {
        $order = wc_get_order($order_id);
        $from_order_meta = $order->get_meta('_shipx_shipment_object');

        return $from_order_meta instanceof ShipX_Shipment_Model
            ? $from_order_meta
            : null;
    }

}
