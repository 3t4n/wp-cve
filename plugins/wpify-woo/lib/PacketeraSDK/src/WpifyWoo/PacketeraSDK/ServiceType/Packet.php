<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Packet ServiceType
 * @subpackage Services
 */
class Packet extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named packetAttributesValid
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributes $attributes
     * @return void|bool
     */
    public function packetAttributesValid($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketAttributes $attributes)
    {
        try {
            $this->setResult($resultPacketAttributesValid = $this->getSoapClient()->__soapCall('packetAttributesValid', [
                $apiPassword,
                $attributes,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketAttributesValid;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetClaimAttributesValid
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\ClaimAttributes $claimAttributes
     * @return void|bool
     */
    public function packetClaimAttributesValid($apiPassword, \WpifyWoo\PacketeraSDK\StructType\ClaimAttributes $claimAttributes)
    {
        try {
            $this->setResult($resultPacketClaimAttributesValid = $this->getSoapClient()->__soapCall('packetClaimAttributesValid', [
                $apiPassword,
                $claimAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketClaimAttributesValid;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetStatus
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\CurrentStatusRecord|bool
     */
    public function packetStatus($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketStatus = $this->getSoapClient()->__soapCall('packetStatus', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketStatus;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetTracking
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecords|bool
     */
    public function packetTracking($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketTracking = $this->getSoapClient()->__soapCall('packetTracking', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketTracking;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierTracking
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecords|bool
     */
    public function packetCourierTracking($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketCourierTracking = $this->getSoapClient()->__soapCall('packetCourierTracking', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierTracking;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetGetStoredUntil
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\NullableDate|bool
     */
    public function packetGetStoredUntil($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketGetStoredUntil = $this->getSoapClient()->__soapCall('packetGetStoredUntil', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketGetStoredUntil;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetSetStoredUntil
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $date
     * @return void|bool
     */
    public function packetSetStoredUntil($apiPassword, $packetId, $date)
    {
        try {
            $this->setResult($resultPacketSetStoredUntil = $this->getSoapClient()->__soapCall('packetSetStoredUntil', [
                $apiPassword,
                $packetId,
                $date,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketSetStoredUntil;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetLabelPdf
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $format
     * @param string $offset
     * @return base64Binary|bool
     */
    public function packetLabelPdf($apiPassword, $packetId, $format, $offset)
    {
        try {
            $this->setResult($resultPacketLabelPdf = $this->getSoapClient()->__soapCall('packetLabelPdf', [
                $apiPassword,
                $packetId,
                $format,
                $offset,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketLabelPdf;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetLabelZpl
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $dpi
     * @return string|bool
     */
    public function packetLabelZpl($apiPassword, $packetId, $dpi)
    {
        try {
            $this->setResult($resultPacketLabelZpl = $this->getSoapClient()->__soapCall('packetLabelZpl', [
                $apiPassword,
                $packetId,
                $dpi,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketLabelZpl;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierNumber
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return string|bool
     */
    public function packetCourierNumber($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketCourierNumber = $this->getSoapClient()->__soapCall('packetCourierNumber', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierNumber;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierNumberV2
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCourierNumberV2Result|bool
     */
    public function packetCourierNumberV2($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketCourierNumberV2 = $this->getSoapClient()->__soapCall('packetCourierNumberV2', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierNumberV2;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierBarcode
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $courierNumber
     * @return string|bool
     */
    public function packetCourierBarcode($apiPassword, $packetId, $courierNumber)
    {
        try {
            $this->setResult($resultPacketCourierBarcode = $this->getSoapClient()->__soapCall('packetCourierBarcode', [
                $apiPassword,
                $packetId,
                $courierNumber,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierBarcode;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierLabelPng
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $courierNumber
     * @return base64Binary|bool
     */
    public function packetCourierLabelPng($apiPassword, $packetId, $courierNumber)
    {
        try {
            $this->setResult($resultPacketCourierLabelPng = $this->getSoapClient()->__soapCall('packetCourierLabelPng', [
                $apiPassword,
                $packetId,
                $courierNumber,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierLabelPng;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierLabelPdf
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $courierNumber
     * @return base64Binary|bool
     */
    public function packetCourierLabelPdf($apiPassword, $packetId, $courierNumber)
    {
        try {
            $this->setResult($resultPacketCourierLabelPdf = $this->getSoapClient()->__soapCall('packetCourierLabelPdf', [
                $apiPassword,
                $packetId,
                $courierNumber,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierLabelPdf;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierLabelZpl
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $courierNumber
     * @param string $dpi
     * @return string|bool
     */
    public function packetCourierLabelZpl($apiPassword, $packetId, $courierNumber, $dpi)
    {
        try {
            $this->setResult($resultPacketCourierLabelZpl = $this->getSoapClient()->__soapCall('packetCourierLabelZpl', [
                $apiPassword,
                $packetId,
                $courierNumber,
                $dpi,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierLabelZpl;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCourierConfirm
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @param string $courierNumber
     * @return void|bool
     */
    public function packetCourierConfirm($apiPassword, $packetId, $courierNumber)
    {
        try {
            $this->setResult($resultPacketCourierConfirm = $this->getSoapClient()->__soapCall('packetCourierConfirm', [
                $apiPassword,
                $packetId,
                $courierNumber,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCourierConfirm;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetCod
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return money|bool
     */
    public function packetCod($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketCod = $this->getSoapClient()->__soapCall('packetCod', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketCod;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named packetInfo
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketInfoResult|bool
     */
    public function packetInfo($apiPassword, $packetId)
    {
        try {
            $this->setResult($resultPacketInfo = $this->getSoapClient()->__soapCall('packetInfo', [
                $apiPassword,
                $packetId,
            ], [], [], $this->outputHeaders));
        
            return $resultPacketInfo;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return base64Binary|money|string|void|\WpifyWoo\PacketeraSDK\StructType\CurrentStatusRecord|\WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecords|\WpifyWoo\PacketeraSDK\StructType\NullableDate|\WpifyWoo\PacketeraSDK\StructType\PacketCourierNumberV2Result|\WpifyWoo\PacketeraSDK\StructType\PacketInfoResult|\WpifyWoo\PacketeraSDK\StructType\StatusRecords
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
