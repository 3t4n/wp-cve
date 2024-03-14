<?php
/**
 * This file aims to show you how to use this generated package.
 * In addition, the goal is to show which methods are available and the first needed parameter(s)
 * You have to use an associative array such as:
 * - the key must be a constant beginning with WSDL_ from AbstractSoapClientBase class (each generated ServiceType class extends this class)
 * - the value must be the corresponding key value (each option matches a {@link http://www.php.net/manual/en/soapclient.soapclient.php} option)
 * $options = [
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_URL => 'https://www.zasilkovna.cz/api/soap-php-bugfix.wsdl',
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_TRACE => true,
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_LOGIN => 'you_secret_login',
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_PASSWORD => 'you_secret_password',
 * ];
 * etc...
 */
require_once __DIR__ . '/vendor/autoload.php';
/**
 * Minimal options
 */
$options = [
    WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_URL => 'https://www.zasilkovna.cz/api/soap-php-bugfix.wsdl',
    WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_CLASSMAP => \WpifyWoo\PacketeraSDK\ClassMap::get(),
];
/**
 * Samples for Packet ServiceType
 */
$packet = new \WpifyWoo\PacketeraSDK\ServiceType\Packet($options);
/**
 * Sample call for packetAttributesValid operation/method
 */
if ($packet->packetAttributesValid($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketAttributes()) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetClaimAttributesValid operation/method
 */
if ($packet->packetClaimAttributesValid($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\ClaimAttributes()) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetStatus operation/method
 */
if ($packet->packetStatus($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetTracking operation/method
 */
if ($packet->packetTracking($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierTracking operation/method
 */
if ($packet->packetCourierTracking($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetGetStoredUntil operation/method
 */
if ($packet->packetGetStoredUntil($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetSetStoredUntil operation/method
 */
if ($packet->packetSetStoredUntil($apiPassword, $packetId, $date) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetLabelPdf operation/method
 */
if ($packet->packetLabelPdf($apiPassword, $packetId, $format, $offset) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetLabelZpl operation/method
 */
if ($packet->packetLabelZpl($apiPassword, $packetId, $dpi) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierNumber operation/method
 */
if ($packet->packetCourierNumber($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierNumberV2 operation/method
 */
if ($packet->packetCourierNumberV2($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierBarcode operation/method
 */
if ($packet->packetCourierBarcode($apiPassword, $packetId, $courierNumber) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierLabelPng operation/method
 */
if ($packet->packetCourierLabelPng($apiPassword, $packetId, $courierNumber) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierLabelPdf operation/method
 */
if ($packet->packetCourierLabelPdf($apiPassword, $packetId, $courierNumber) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierLabelZpl operation/method
 */
if ($packet->packetCourierLabelZpl($apiPassword, $packetId, $courierNumber, $dpi) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCourierConfirm operation/method
 */
if ($packet->packetCourierConfirm($apiPassword, $packetId, $courierNumber) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetCod operation/method
 */
if ($packet->packetCod($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Sample call for packetInfo operation/method
 */
if ($packet->packetInfo($apiPassword, $packetId) !== false) {
    print_r($packet->getResult());
} else {
    print_r($packet->getLastError());
}
/**
 * Samples for Create ServiceType
 */
$create = new \WpifyWoo\PacketeraSDK\ServiceType\Create($options);
/**
 * Sample call for createPacket operation/method
 */
if ($create->createPacket($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketAttributes()) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Sample call for createPackets operation/method
 */
if ($create->createPackets($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketsAttributes(), $transaction) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Sample call for createPacketClaim operation/method
 */
if ($create->createPacketClaim($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\ClaimAttributes()) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Sample call for createPacketClaimWithPassword operation/method
 */
if ($create->createPacketClaimWithPassword($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\ClaimWithPasswordAttributes()) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Sample call for createShipment operation/method
 */
if ($create->createShipment($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketIds(), $customBarcode) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Sample call for createPacketLiftago operation/method
 */
if ($create->createPacketLiftago($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes()) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Sample call for createStorageFile operation/method
 */
if ($create->createStorageFile($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\StorageFileAttributes()) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Samples for Cancel ServiceType
 */
$cancel = new \WpifyWoo\PacketeraSDK\ServiceType\Cancel($options);
/**
 * Sample call for cancelPacket operation/method
 */
if ($cancel->cancelPacket($apiPassword, $packetId) !== false) {
    print_r($cancel->getResult());
} else {
    print_r($cancel->getLastError());
}
/**
 * Samples for Shipment ServiceType
 */
$shipment = new \WpifyWoo\PacketeraSDK\ServiceType\Shipment($options);
/**
 * Sample call for shipmentPackets operation/method
 */
if ($shipment->shipmentPackets($apiPassword, $shipmentId) !== false) {
    print_r($shipment->getResult());
} else {
    print_r($shipment->getLastError());
}
/**
 * Samples for Barcode ServiceType
 */
$barcode = new \WpifyWoo\PacketeraSDK\ServiceType\Barcode($options);
/**
 * Sample call for barcodePng operation/method
 */
if ($barcode->barcodePng($apiPassword, $barcode) !== false) {
    print_r($barcode->getResult());
} else {
    print_r($barcode->getLastError());
}
/**
 * Samples for Packets ServiceType
 */
$packets = new \WpifyWoo\PacketeraSDK\ServiceType\Packets($options);
/**
 * Sample call for packetsLabelsPdf operation/method
 */
if ($packets->packetsLabelsPdf($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketIds(), $format, $offset) !== false) {
    print_r($packets->getResult());
} else {
    print_r($packets->getLastError());
}
/**
 * Sample call for packetsCourierLabelsPdf operation/method
 */
if ($packets->packetsCourierLabelsPdf($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketIdsWithCourierNumbers(), $offset, $format) !== false) {
    print_r($packets->getResult());
} else {
    print_r($packets->getLastError());
}
/**
 * Samples for Set ServiceType
 */
$set = new \WpifyWoo\PacketeraSDK\ServiceType\Set($options);
/**
 * Sample call for setCountryStatus operation/method
 */
if ($set->setCountryStatus($apiPassword, $country, $status) !== false) {
    print_r($set->getResult());
} else {
    print_r($set->getLastError());
}
/**
 * Sample call for setBranchStatus operation/method
 */
if ($set->setBranchStatus($apiPassword, $id, $status) !== false) {
    print_r($set->getResult());
} else {
    print_r($set->getLastError());
}
/**
 * Samples for Sender ServiceType
 */
$sender = new \WpifyWoo\PacketeraSDK\ServiceType\Sender($options);
/**
 * Sample call for senderGetReturnString operation/method
 */
if ($sender->senderGetReturnString($apiPassword, $sender) !== false) {
    print_r($sender->getResult());
} else {
    print_r($sender->getLastError());
}
/**
 * Sample call for senderGetReturnRouting operation/method
 */
if ($sender->senderGetReturnRouting($apiPassword, $sender) !== false) {
    print_r($sender->getResult());
} else {
    print_r($sender->getLastError());
}
/**
 * Samples for Advise ServiceType
 */
$advise = new \WpifyWoo\PacketeraSDK\ServiceType\Advise($options);
/**
 * Sample call for adviseBranch operation/method
 */
if ($advise->adviseBranch($apiPassword, $email, $phone, $addressId) !== false) {
    print_r($advise->getResult());
} else {
    print_r($advise->getLastError());
}
/**
 * Samples for Update ServiceType
 */
$update = new \WpifyWoo\PacketeraSDK\ServiceType\Update($options);
/**
 * Sample call for updatePacket operation/method
 */
if ($update->updatePacket($apiPassword, $packetId, new \WpifyWoo\PacketeraSDK\StructType\UpdatePacketAttributes()) !== false) {
    print_r($update->getResult());
} else {
    print_r($update->getLastError());
}
/**
 * Samples for Is ServiceType
 */
$is = new \WpifyWoo\PacketeraSDK\ServiceType\Is($options);
/**
 * Sample call for isLiftagoAvailable operation/method
 */
if ($is->isLiftagoAvailable($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailable()) !== false) {
    print_r($is->getResult());
} else {
    print_r($is->getLastError());
}
/**
 * Samples for Get ServiceType
 */
$get = new \WpifyWoo\PacketeraSDK\ServiceType\Get($options);
/**
 * Sample call for getConsignmentPassword operation/method
 */
if ($get->getConsignmentPassword($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes()) !== false) {
    print_r($get->getResult());
} else {
    print_r($get->getLastError());
}
/**
 * Samples for List ServiceType
 */
$list = new \WpifyWoo\PacketeraSDK\ServiceType\_List($options);
/**
 * Sample call for listStorageFile operation/method
 */
if ($list->listStorageFile($apiPassword, new \WpifyWoo\PacketeraSDK\StructType\ListStorageFileAttributes()) !== false) {
    print_r($list->getResult());
} else {
    print_r($list->getLastError());
}
