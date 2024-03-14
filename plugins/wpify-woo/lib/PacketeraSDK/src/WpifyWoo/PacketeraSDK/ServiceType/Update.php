<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Update ServiceType
 * @subpackage Services
 */
class Update extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named updatePacket
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param \WpifyWoo\PacketeraSDK\StructType\UpdatePacketAttributes $updateAttributes
     * @return void|bool
     */
    public function updatePacket($apiPassword, $packetId, \WpifyWoo\PacketeraSDK\StructType\UpdatePacketAttributes $updateAttributes)
    {
        try {
            $this->setResult($resultUpdatePacket = $this->getSoapClient()->__soapCall('updatePacket', [
                $apiPassword,
                $packetId,
                $updateAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultUpdatePacket;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return void
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
