<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for List ServiceType
 * @subpackage Services
 */
class _List extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named listStorageFile
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\ListStorageFileAttributes $listStorageFileAttributes
     * @return \WpifyWoo\PacketeraSDK\StructType\StorageFiles|bool
     */
    public function listStorageFile($apiPassword, \WpifyWoo\PacketeraSDK\StructType\ListStorageFileAttributes $listStorageFileAttributes)
    {
        try {
            $this->setResult($resultListStorageFile = $this->getSoapClient()->__soapCall('listStorageFile', [
                $apiPassword,
                $listStorageFileAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultListStorageFile;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \WpifyWoo\PacketeraSDK\StructType\StorageFiles
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
