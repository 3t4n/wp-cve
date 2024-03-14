<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Shipment ServiceType
 * @subpackage Services
 */
class Shipment extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named shipmentPackets
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $shipmentId
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentPacketsResult|bool
     */
    public function shipmentPackets($apiPassword, $shipmentId)
    {
        try {
            $this->setResult($resultShipmentPackets = $this->getSoapClient()->__soapCall('shipmentPackets', [
                $apiPassword,
                $shipmentId,
            ], [], [], $this->outputHeaders));
        
            return $resultShipmentPackets;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentPacketsResult
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
