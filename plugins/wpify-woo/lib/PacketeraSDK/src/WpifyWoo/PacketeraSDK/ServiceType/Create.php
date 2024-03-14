<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Create ServiceType
 * @subpackage Services
 */
class Create extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named createPacket
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributes $attributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail|bool
     */
    public function createPacket($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketAttributes $attributes)
    {
        try {
            $this->setResult($resultCreatePacket = $this->getSoapClient()->__soapCall('createPacket', [
                $apiPassword,
                $attributes,
            ], [], [], $this->outputHeaders));
        
            return $resultCreatePacket;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named createPackets
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketsAttributes $packets
     * @param string $transaction
     * @return \WpifyWoo\PacketeraSDK\StructType\CreatePacketsResults|bool
     */
    public function createPackets($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketsAttributes $packets, $transaction)
    {
        try {
            $this->setResult($resultCreatePackets = $this->getSoapClient()->__soapCall('createPackets', [
                $apiPassword,
                $packets,
                $transaction,
            ], [], [], $this->outputHeaders));
        
            return $resultCreatePackets;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named createPacketClaim
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\ClaimAttributes $claimAttributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail|bool
     */
    public function createPacketClaim($apiPassword, \WpifyWoo\PacketeraSDK\StructType\ClaimAttributes $claimAttributes)
    {
        try {
            $this->setResult($resultCreatePacketClaim = $this->getSoapClient()->__soapCall('createPacketClaim', [
                $apiPassword,
                $claimAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultCreatePacketClaim;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named createPacketClaimWithPassword
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\ClaimWithPasswordAttributes $claimWithPasswordAttributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketDetail|bool
     */
    public function createPacketClaimWithPassword($apiPassword, \WpifyWoo\PacketeraSDK\StructType\ClaimWithPasswordAttributes $claimWithPasswordAttributes)
    {
        try {
            $this->setResult($resultCreatePacketClaimWithPassword = $this->getSoapClient()->__soapCall('createPacketClaimWithPassword', [
                $apiPassword,
                $claimWithPasswordAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultCreatePacketClaimWithPassword;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named createShipment
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIds $packetIds
     * @param string $customBarcode
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentIdDetail|bool
     */
    public function createShipment($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketIds $packetIds, $customBarcode)
    {
        try {
            $this->setResult($resultCreateShipment = $this->getSoapClient()->__soapCall('createShipment', [
                $apiPassword,
                $packetIds,
                $customBarcode,
            ], [], [], $this->outputHeaders));
        
            return $resultCreateShipment;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named createPacketLiftago
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes $liftagoAttributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail|bool
     */
    public function createPacketLiftago($apiPassword, \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes $liftagoAttributes)
    {
        try {
            $this->setResult($resultCreatePacketLiftago = $this->getSoapClient()->__soapCall('createPacketLiftago', [
                $apiPassword,
                $liftagoAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultCreatePacketLiftago;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named createStorageFile
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param string $apiPassword
     * @param \WpifyWoo\PacketeraSDK\StructType\StorageFileAttributes $storageFileAttributes
     * @return \WpifyWoo\PacketeraSDK\StructType\StorageFile|bool
     */
    public function createStorageFile($apiPassword, \WpifyWoo\PacketeraSDK\StructType\StorageFileAttributes $storageFileAttributes)
    {
        try {
            $this->setResult($resultCreateStorageFile = $this->getSoapClient()->__soapCall('createStorageFile', [
                $apiPassword,
                $storageFileAttributes,
            ], [], [], $this->outputHeaders));
        
            return $resultCreateStorageFile;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \WpifyWoo\PacketeraSDK\StructType\CreatePacketsResults|\WpifyWoo\PacketeraSDK\StructType\PacketDetail|\WpifyWoo\PacketeraSDK\StructType\PacketIdDetail|\WpifyWoo\PacketeraSDK\StructType\ShipmentIdDetail|\WpifyWoo\PacketeraSDK\StructType\StorageFile
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
