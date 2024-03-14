<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Advise ServiceType
 * @subpackage Services
 */
class Advise extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named adviseBranch
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $email
     * @param string $phone
     * @param string $addressId
     * @return void|bool
     */
    public function adviseBranch($apiPassword, $email, $phone, $addressId)
    {
        try {
            $this->setResult($resultAdviseBranch = $this->getSoapClient()->__soapCall('adviseBranch', [
                $apiPassword,
                $email,
                $phone,
                $addressId,
            ], [], [], $this->outputHeaders));
        
            return $resultAdviseBranch;
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
