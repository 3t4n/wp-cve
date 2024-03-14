<?php

namespace FedExVendor\FedEx\DGDSService;

use FedExVendor\FedEx\AbstractRequest;
/**
 * Request sends the SOAP call to the FedEx servers and returns the response
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 */
class Request extends \FedExVendor\FedEx\AbstractRequest
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/dgds';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/dgds';
    protected static $wsdlFileName = 'DGDS_v3.wsdl';
    /**
     * Sends the AddDangerousGoodsHandlingUnitRequest and returns the response
     *
     * @param ComplexType\AddDangerousGoodsHandlingUnitRequest $addDangerousGoodsHandlingUnitRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\AddDangerousGoodsHandlingUnitReply|stdClass
     */
    public function getAddDangerousGoodsHandlingUnitReply(\FedExVendor\FedEx\DGDSService\ComplexType\AddDangerousGoodsHandlingUnitRequest $addDangerousGoodsHandlingUnitRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->addDangerousGoodsHandlingUnit($addDangerousGoodsHandlingUnitRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $addDangerousGoodsHandlingUnitReply = new \FedExVendor\FedEx\DGDSService\ComplexType\AddDangerousGoodsHandlingUnitReply();
        $addDangerousGoodsHandlingUnitReply->populateFromStdClass($response);
        return $addDangerousGoodsHandlingUnitReply;
    }
    /**
     * Sends the ModifyDangerousGoodsShipmentRequest and returns the response
     *
     * @param ComplexType\ModifyDangerousGoodsShipmentRequest $modifyDangerousGoodsShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ModifyDangerousGoodsShipmentReply|stdClass
     */
    public function getModifyDangerousGoodsShipmentReply(\FedExVendor\FedEx\DGDSService\ComplexType\ModifyDangerousGoodsShipmentRequest $modifyDangerousGoodsShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->modifyDangerousGoodsShipment($modifyDangerousGoodsShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $modifyDangerousGoodsShipmentReply = new \FedExVendor\FedEx\DGDSService\ComplexType\ModifyDangerousGoodsShipmentReply();
        $modifyDangerousGoodsShipmentReply->populateFromStdClass($response);
        return $modifyDangerousGoodsShipmentReply;
    }
    /**
     * Sends the UploadDangerousGoodsRequest and returns the response
     *
     * @param ComplexType\UploadDangerousGoodsRequest $uploadDangerousGoodsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\UploadDangerousGoodsReply|stdClass
     */
    public function getUploadDangerousGoodsReply(\FedExVendor\FedEx\DGDSService\ComplexType\UploadDangerousGoodsRequest $uploadDangerousGoodsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->uploadDangerousGoods($uploadDangerousGoodsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $uploadDangerousGoodsReply = new \FedExVendor\FedEx\DGDSService\ComplexType\UploadDangerousGoodsReply();
        $uploadDangerousGoodsReply->populateFromStdClass($response);
        return $uploadDangerousGoodsReply;
    }
    /**
     * Sends the ModifyDangerousGoodsHandlingUnitRequest and returns the response
     *
     * @param ComplexType\ModifyDangerousGoodsHandlingUnitRequest $modifyDangerousGoodsHandlingUnitRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ModifyDangerousGoodsHandlingUnitReply|stdClass
     */
    public function getModifyDangerousGoodsHandlingUnitReply(\FedExVendor\FedEx\DGDSService\ComplexType\ModifyDangerousGoodsHandlingUnitRequest $modifyDangerousGoodsHandlingUnitRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->modifyDangerousGoodsHandlingUnit($modifyDangerousGoodsHandlingUnitRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $modifyDangerousGoodsHandlingUnitReply = new \FedExVendor\FedEx\DGDSService\ComplexType\ModifyDangerousGoodsHandlingUnitReply();
        $modifyDangerousGoodsHandlingUnitReply->populateFromStdClass($response);
        return $modifyDangerousGoodsHandlingUnitReply;
    }
    /**
     * Sends the DeleteDangerousGoodsHandlingUnitRequest and returns the response
     *
     * @param ComplexType\DeleteDangerousGoodsHandlingUnitRequest $deleteDangerousGoodsHandlingUnitRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\DeleteDangerousGoodsHandlingUnitReply|stdClass
     */
    public function getDeleteDangerousGoodsHandlingUnitReply(\FedExVendor\FedEx\DGDSService\ComplexType\DeleteDangerousGoodsHandlingUnitRequest $deleteDangerousGoodsHandlingUnitRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deleteDangerousGoodsHandlingUnit($deleteDangerousGoodsHandlingUnitRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $deleteDangerousGoodsHandlingUnitReply = new \FedExVendor\FedEx\DGDSService\ComplexType\DeleteDangerousGoodsHandlingUnitReply();
        $deleteDangerousGoodsHandlingUnitReply->populateFromStdClass($response);
        return $deleteDangerousGoodsHandlingUnitReply;
    }
    /**
     * Sends the DeleteDangerousGoodsRequest and returns the response
     *
     * @param ComplexType\DeleteDangerousGoodsRequest $deleteDangerousGoodsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\DeleteDangerousGoodsReply|stdClass
     */
    public function getDeleteDangerousGoodsReply(\FedExVendor\FedEx\DGDSService\ComplexType\DeleteDangerousGoodsRequest $deleteDangerousGoodsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deleteDangerousGoods($deleteDangerousGoodsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $deleteDangerousGoodsReply = new \FedExVendor\FedEx\DGDSService\ComplexType\DeleteDangerousGoodsReply();
        $deleteDangerousGoodsReply->populateFromStdClass($response);
        return $deleteDangerousGoodsReply;
    }
    /**
     * Sends the RetrieveDangerousGoodsRequest and returns the response
     *
     * @param ComplexType\RetrieveDangerousGoodsRequest $retrieveDangerousGoodsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\RetrieveDangerousGoodsReply|stdClass
     */
    public function getRetrieveDangerousGoodsReply(\FedExVendor\FedEx\DGDSService\ComplexType\RetrieveDangerousGoodsRequest $retrieveDangerousGoodsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->retrieveDangerousGoods($retrieveDangerousGoodsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $retrieveDangerousGoodsReply = new \FedExVendor\FedEx\DGDSService\ComplexType\RetrieveDangerousGoodsReply();
        $retrieveDangerousGoodsReply->populateFromStdClass($response);
        return $retrieveDangerousGoodsReply;
    }
    /**
     * Sends the ValidateDangerousGoodsRequest and returns the response
     *
     * @param ComplexType\ValidateDangerousGoodsRequest $validateDangerousGoodsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ValidateDangerousGoodsReply|stdClass
     */
    public function getValidateDangerousGoodsReply(\FedExVendor\FedEx\DGDSService\ComplexType\ValidateDangerousGoodsRequest $validateDangerousGoodsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->validateDangerousGoods($validateDangerousGoodsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $validateDangerousGoodsReply = new \FedExVendor\FedEx\DGDSService\ComplexType\ValidateDangerousGoodsReply();
        $validateDangerousGoodsReply->populateFromStdClass($response);
        return $validateDangerousGoodsReply;
    }
}
