<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Packets ServiceType
 * @subpackage Services
 */
class Packets extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named packetsLabelsPdf
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIds $packetIds
     * @param string $format
     * @param string $offset
     * @return base64Binary|bool
     */
    public function packetsLabelsPdf($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketIds $packetIds, $format, $offset)
    {
        try {
            $this->setResult($resultPacketsLabelsPdf = $this->getSoapClient()->__soapCall('packetsLabelsPdf', [
                $apiPassword,
                $packetIds,
                $format,
                $offset,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketsLabelsPdf;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetsCourierLabelsPdf
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdsWithCourierNumbers $packetIdsWithCourierNumbers
     * @param string $offset
     * @param string $format
     * @return base64Binary|bool
     */
    public function packetsCourierLabelsPdf($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketIdsWithCourierNumbers $packetIdsWithCourierNumbers, $offset, $format)
    {
        try {
            $this->setResult($resultPacketsCourierLabelsPdf = $this->getSoapClient()->__soapCall('packetsCourierLabelsPdf', [
                $apiPassword,
                $packetIdsWithCourierNumbers,
                $offset,
                $format,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketsCourierLabelsPdf;
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
