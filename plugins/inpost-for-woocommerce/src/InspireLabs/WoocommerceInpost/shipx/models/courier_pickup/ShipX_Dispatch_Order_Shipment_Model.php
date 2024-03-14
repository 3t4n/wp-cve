<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\courier_pickup;

class ShipX_Dispatch_Order_Shipment_Model
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $tracking_number;

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->tracking_number;
    }

    /**
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $tracking_number
     */
    public function setTrackingNumber($tracking_number)
    {
        $this->tracking_number = $tracking_number;
    }
}
