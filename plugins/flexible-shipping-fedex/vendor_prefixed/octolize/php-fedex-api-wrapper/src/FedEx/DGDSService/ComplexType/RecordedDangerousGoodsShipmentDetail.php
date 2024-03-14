<?php

namespace FedExVendor\FedEx\DGDSService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * RecordedDangerousGoodsShipmentDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 *
 * @property UploadedDangerousGoodsShipmentDetail $UploadedShipmentDetail
 * @property CompletedDangerousGoodsShipmentDetail $CompletedShipmentDetail
 */
class RecordedDangerousGoodsShipmentDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'RecordedDangerousGoodsShipmentDetail';
    /**
     * Set UploadedShipmentDetail
     *
     * @param UploadedDangerousGoodsShipmentDetail $uploadedShipmentDetail
     * @return $this
     */
    public function setUploadedShipmentDetail(\FedExVendor\FedEx\DGDSService\ComplexType\UploadedDangerousGoodsShipmentDetail $uploadedShipmentDetail)
    {
        $this->values['UploadedShipmentDetail'] = $uploadedShipmentDetail;
        return $this;
    }
    /**
     * Set CompletedShipmentDetail
     *
     * @param CompletedDangerousGoodsShipmentDetail $completedShipmentDetail
     * @return $this
     */
    public function setCompletedShipmentDetail(\FedExVendor\FedEx\DGDSService\ComplexType\CompletedDangerousGoodsShipmentDetail $completedShipmentDetail)
    {
        $this->values['CompletedShipmentDetail'] = $completedShipmentDetail;
        return $this;
    }
}
