<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Barcode ServiceType
 * @subpackage Services
 */
class Barcode extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named barcodePng
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $barcode
     * @return base64Binary|bool
     */
    public function barcodePng($apiPassword, $barcode)
    {
        try {
            $this->setResult($resultBarcodePng = $this->getSoapClient()->__soapCall('barcodePng', [
                $apiPassword,
                $barcode,
            ], [], [], $this->outputHeaders));
        
            return $resultBarcodePng;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return base64Binary
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
