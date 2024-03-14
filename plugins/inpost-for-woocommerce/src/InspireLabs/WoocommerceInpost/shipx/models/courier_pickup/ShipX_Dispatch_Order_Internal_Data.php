<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\courier_pickup;

class ShipX_Dispatch_Order_Internal_Data
{
    /**
     * @var ShipX_Dispatch_Order_Point_Model
     */
    private $dispath_order_point;

    /**
     * @return ShipX_Dispatch_Order_Point_Model
     */
    public function getDispathOrderPoint()
    {
        return $this->dispath_order_point;
    }

    /**
     * @param ShipX_Dispatch_Order_Point_Model $dispath_order_point
     */
    public function setDispathOrderPoint($dispath_order_point)
    {
        $this->dispath_order_point = $dispath_order_point;
    }
}

