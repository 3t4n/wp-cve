<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Sender ServiceType
 * @subpackage Services
 */
class Sender extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named senderGetReturnString
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $sender
     * @return string|bool
     */
    public function senderGetReturnString($apiPassword, $sender)
    {
        try {
            $this->setResult($resultSenderGetReturnString = $this->getSoapClient()->__soapCall('senderGetReturnString', [
                $apiPassword,
                $sender,
            ], [], [], $this->outputHeaders));
        
            return $resultSenderGetReturnString;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named senderGetReturnRouting
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $sender
     * @return \WpifyWoo\PacketeraSDK\StructType\SenderGetReturnRoutingResult|bool
     */
    public function senderGetReturnRouting($apiPassword, $sender)
    {
        try {
            $this->setResult($resultSenderGetReturnRouting = $this->getSoapClient()->__soapCall('senderGetReturnRouting', [
                $apiPassword,
                $sender,
            ], [], [], $this->outputHeaders));
        
            return $resultSenderGetReturnRouting;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return string|\WpifyWoo\PacketeraSDK\StructType\SenderGetReturnRoutingResult
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
