<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\courier_pickup;

class ShipX_Dispatch_Order_Model
{
    /**
     * @var int
     */
    const STATUS_NEW = 1;
    /**
     * @var int
     */
    const STATUS_SENT = 2;
    /**
     * @var int
     */
    const STATUS_ACCEPTED = 3;
    /**
     * @var int
     */
    const STATUS_DONE = 4;
    /**
     * @var int
     */
    const STATUS_REJECTED = 5;
    /**
     * @var int
     */
    const STATUS_CANCELED = 6;

    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $created_at;

    /**
     * @var ShipX_Dispatch_Order_Point_Address_Model
     */
    private $address;

    /**
     * @var ShipX_Dispatch_Order_Internal_Data
     */
    private $internal_data;

    /**
     * @var ShipX_Dispatch_Order_Shipment_Model[]
     */
    private $shipments;

    /**
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return ShipX_Dispatch_Order_Point_Address_Model
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return ShipX_Dispatch_Order_Shipment_Model[]
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * @param ShipX_Dispatch_Order_Point_Address_Model $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param ShipX_Dispatch_Order_Shipment_Model[] $shipments
     */
    public function setShipments($shipments)
    {
        $this->shipments = $shipments;
    }

    /**
     * @return ShipX_Dispatch_Order_Internal_Data
     */
    public function getInternalData()
    {
        return $this->internal_data;
    }

    /**
     * @param ShipX_Dispatch_Order_Internal_Data $internal_data
     */
    public function setInternalData($internal_data)
    {
        $this->internal_data = $internal_data;
    }
}
