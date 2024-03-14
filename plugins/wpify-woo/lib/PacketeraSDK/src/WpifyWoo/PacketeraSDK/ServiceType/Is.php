<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Is ServiceType
 * @subpackage Services
 */
class Is extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named isLiftagoAvailable
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailable $addresses
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailableDetail|bool
     */
    public function isLiftagoAvailable($apiPassword, \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailable $addresses)
    {
        try {
            $this->setResult($resultIsLiftagoAvailable = $this->getSoapClient()->__soapCall('isLiftagoAvailable', [
                $apiPassword,
                $addresses,
            ], [], [], $this->outputHeaders));
        
            return $resultIsLiftagoAvailable;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailableDetail
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
