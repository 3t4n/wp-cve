<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Parcel_Weight_Model
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @var int
     */
    private $unit = 'kg';

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param int $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }
}