<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Dispatch_Status {

    /**
     * @var int
     */
    private $dispathOrderId;

    /**
     * @var int
     */
    private $dispathOrderStatus;

    /**
     * @var int
     */
    private $dispathOrderPointId;

    /**
     * @var array
     */
    private $dispathOrderPointName;

    /**
     * @return int
     */
    public function getDispathOrderId() {
        return $this->dispathOrderId;
    }

    /**
     * @param int $dispathOrderId
     */
    public function setDispathOrderId( $dispathOrderId ) {
        $this->dispathOrderId = $dispathOrderId;
    }

    /**
     * @return int
     */
    public function getDispathOrderStatus() {
        return $this->dispathOrderStatus;
    }

    /**
     * @param int $dispathOrderStatus
     */
    public function setDispathOrderStatus( $dispathOrderStatus ) {
        $this->dispathOrderStatus = $dispathOrderStatus;
    }

    /**
     * @return int
     */
    public function getDispathOrderPointId() {
        return $this->dispathOrderPointId;
    }

    /**
     * @param int $dispathOrderPointId
     */
    public function setDispathOrderPointId( $dispathOrderPointId ) {
        $this->dispathOrderPointId = $dispathOrderPointId;
    }

    /**
     * @return array | null
     */
    public function getDispathOrderPointName() {
        return $this->dispathOrderPointName;
    }

    /**
     * @param array $dispathOrderPointName
     */
    public function setDispathOrderPointName( $dispathOrderPointName ) {
        $this->dispathOrderPointName = $dispathOrderPointName;
    }
}
