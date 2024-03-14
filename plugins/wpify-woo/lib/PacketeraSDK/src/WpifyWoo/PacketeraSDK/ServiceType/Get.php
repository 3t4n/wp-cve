<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Get ServiceType
 * @subpackage Services
 */
class Get extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named getConsignmentPassword
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes $packetConsignerAttributes
     * @return \WpifyWoo\PacketeraSDK\StructType\ConsignmentPasswordResult|bool
     */
    public function getConsignmentPassword($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes $packetConsignerAttributes)
    {
        try {
            $this->setResult($resultGetConsignmentPassword = $this->getSoapClient()->__soapCall('getConsignmentPassword', [
                $apiPassword,
                $packetConsignerAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultGetConsignmentPassword;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \WpifyWoo\PacketeraSDK\StructType\ConsignmentPasswordResult
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
