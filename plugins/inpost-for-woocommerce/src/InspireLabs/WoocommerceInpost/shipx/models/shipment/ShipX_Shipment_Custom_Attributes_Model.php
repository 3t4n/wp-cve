<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Custom_Attributes_Model
{
    const SENDING_METHOD_PARCEL_LOCKER = 'parcel_locker';
    const SENDING_METHOD_PARCEL_DISPATCH_ORDER = 'dispatch_order';
    const SENDING_METHOD_POK = 'pok';
    const SENDING_METHOD_POP = 'pop';
    const SENDING_METHOD_COURIER_POK = 'courier_pok';
    const SENDING_METHOD_BRANCH = 'branch';
    const SENDING_METHOD_DISPATCH_ORDER = 'dispatch_order';

    const DROPOFF_POINT_PARCEL_LOCKER = 'parcel_locker';
    const DROPOFF_POINT_POK = 'pok';
    const DROPOFF_POINT_COURIER_POK = 'courier_pok';


    /**
     * @var string
     */
    private $target_point;

    /**
     * @var string
     */
    private $sending_method;

    /**
     * @var string
     */
    private $dropoff_point;

    /**
     * @var string
     */
    private $allegro_transaction_id;

    /**
     * @return string
     */
    public function getTargetPoint()
    {
        return $this->target_point;
    }

    /**
     * @param string $target_point
     */
    public function setTargetPoint($target_point)
    {
        $this->target_point = $target_point;
    }

    /**
     * @return string
     */
    public function getSendingMethod()
    {
        return $this->sending_method;
    }

    /**
     * @param string $sending_method
     */
    public function setSendingMethod($sending_method)
    {
        $this->sending_method = $sending_method;
    }

    /**
     * @return string
     */
    public function getDropoffPoint()
    {
        return $this->dropoff_point;
    }

    /**
     * @param string $dropoff_point
     */
    public function setDropoffPoint($dropoff_point)
    {
        $this->dropoff_point = $dropoff_point;
    }

    /**
     * @return string
     */
    public function getAllegroTransactionid()
    {
        return $this->allegro_transaction_id;
    }

    /**
     * @param string $allegro_transaction_id
     */
    public function setAllegroTransactionid($allegro_transaction_id)
    {
        $this->allegro_transaction_id = $allegro_transaction_id;
    }
}
