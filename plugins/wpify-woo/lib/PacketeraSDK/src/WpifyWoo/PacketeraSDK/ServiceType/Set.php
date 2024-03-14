<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Set ServiceType
 * @subpackage Services
 */
class Set extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named setCountryStatus
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $country
     * @param string $status
     * @return void|bool
     */
    public function setCountryStatus($apiPassword, $country, $status)
    {
        try {
            $this->setResult($resultSetCountryStatus = $this->getSoapClient()->__soapCall('setCountryStatus', [
                $apiPassword,
                $country,
                $status,
            ], [], [], $this->outputHeaders));
        
            return $resultSetCountryStatus;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named setBranchStatus
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $id
     * @param string $status
     * @return void|bool
     */
    public function setBranchStatus($apiPassword, $id, $status)
    {
        try {
            $this->setResult($resultSetBranchStatus = $this->getSoapClient()->__soapCall('setBranchStatus', [
                $apiPassword,
                $id,
                $status,
            ], [], [], $this->outputHeaders));
        
            return $resultSetBranchStatus;
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
