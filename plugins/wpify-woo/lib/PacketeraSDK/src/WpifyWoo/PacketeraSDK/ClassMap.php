<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK;

/**
 * Class which returns the class map definition
 */
class ClassMap
{
    /**
     * Returns the mapping between the WSDL Structs and generated Structs' classes
     * This array is sent to the \SoapClient when calling the WS
     * @return string[]
     */
    final public static function get(): array
    {
        return [
            'ExternalStatusRecord' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ExternalStatusRecord',
            'StatusRecord' => '\\WpifyWoo\\PacketeraSDK\\StructType\\StatusRecord',
            'CurrentStatusRecord' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CurrentStatusRecord',
            'DispatchOrder' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DispatchOrder',
            'DispatchOrder2' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DispatchOrder2',
            'DispatchOrder2Item' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DispatchOrder2Item',
            'PacketConsignerAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketConsignerAttributes',
            'PacketAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketAttributes',
            'PacketLiftagoAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketLiftagoAttributes',
            'PacketsAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketsAttributes',
            'UpdatePacketAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\UpdatePacketAttributes',
            'ClaimAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ClaimAttributes',
            'ClaimWithPasswordAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ClaimWithPasswordAttributes',
            'CustomsDeclarationItems' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CustomsDeclarationItems',
            'CustomsDeclaration' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CustomsDeclaration',
            'CustomsDeclarationItem' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CustomsDeclarationItem',
            'ItemCollection' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ItemCollection',
            'Item' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Item',
            'AttributeCollection' => '\\WpifyWoo\\PacketeraSDK\\StructType\\AttributeCollection',
            'Attribute' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Attribute',
            'Size' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Size',
            'PacketIdDetail' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketIdDetail',
            'PacketDetail' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketDetail',
            'CreatePacketResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CreatePacketResult',
            'CreatePacketsResults' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CreatePacketsResults',
            'SenderGetReturnRoutingResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\SenderGetReturnRoutingResult',
            'PacketIds' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketIds',
            'PacketIdsWithCourierNumbers' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketIdsWithCourierNumbers',
            'PacketIdWithCourierNumber' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketIdWithCourierNumber',
            'ShipmentIdDetail' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ShipmentIdDetail',
            'PacketCollection' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketCollection',
            'ShipmentPacketsResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ShipmentPacketsResult',
            'StatusRecords' => '\\WpifyWoo\\PacketeraSDK\\StructType\\StatusRecords',
            'ExternalStatusRecords' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ExternalStatusRecords',
            'IsLiftagoAvailable' => '\\WpifyWoo\\PacketeraSDK\\StructType\\IsLiftagoAvailable',
            'Contact' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Contact',
            'Address' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Address',
            'isLiftagoAvailableResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\IsLiftagoAvailableResult',
            'PacketConsignerDetail' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketConsignerDetail',
            'getConsignmentPasswordResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\GetConsignmentPasswordResult',
            'PacketInfoResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketInfoResult',
            'CourierInfo' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierInfo',
            'CourierInfoItem' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierInfoItem',
            'CourierNumbers' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierNumbers',
            'CourierBarcodes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierBarcodes',
            'CourierTrackingNumbers' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierTrackingNumbers',
            'CourierTrackingUrls' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierTrackingUrls',
            'CourierTrackingUrl' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CourierTrackingUrl',
            'AttributeFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\AttributeFault',
            'PacketAttributesFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketAttributesFault',
            'attributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Attributes',
            'PacketIdFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketIdFault',
            'PacketIdsFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketIdsFault',
            'ids' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Ids',
            'CancelNotAllowedFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CancelNotAllowedFault',
            'NoPacketIdsFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\NoPacketIdsFault',
            'CustomBarcodeNotAllowedFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CustomBarcodeNotAllowedFault',
            'ShipmentNotFoundFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ShipmentNotFoundFault',
            'DateOutOfRangeFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DateOutOfRangeFault',
            'UnknownLabelFormatFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\UnknownLabelFormatFault',
            'IncorrectApiPasswordFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\IncorrectApiPasswordFault',
            'SenderNotExists' => '\\WpifyWoo\\PacketeraSDK\\StructType\\SenderNotExists',
            'ArgumentsFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ArgumentsFault',
            'InvalidEmailAddressFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\InvalidEmailAddressFault',
            'InvalidPhoneNumberFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\InvalidPhoneNumberFault',
            'DispatchOrderNotAllowedFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DispatchOrderNotAllowedFault',
            'DispatchOrderInvalidPdfFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DispatchOrderInvalidPdfFault',
            'TooLateToUpdateCodFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\TooLateToUpdateCodFault',
            'CodUpdateNotAllowedFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\CodUpdateNotAllowedFault',
            'DispatchOrderUnknownCodeFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\DispatchOrderUnknownCodeFault',
            'codes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\Codes',
            'NotSupportedFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\NotSupportedFault',
            'ExternalGatewayFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ExternalGatewayFault',
            'InvalidCourierNumber' => '\\WpifyWoo\\PacketeraSDK\\StructType\\InvalidCourierNumber',
            'AccessDeniedFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\AccessDeniedFault',
            'NoPacketCourierDataFault' => '\\WpifyWoo\\PacketeraSDK\\StructType\\NoPacketCourierDataFault',
            'NullableDate' => '\\WpifyWoo\\PacketeraSDK\\StructType\\NullableDate',
            'IsLiftagoAvailableDetail' => '\\WpifyWoo\\PacketeraSDK\\StructType\\IsLiftagoAvailableDetail',
            'ConsignmentPasswordResult' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ConsignmentPasswordResult',
            'packetCourierNumberV2Result' => '\\WpifyWoo\\PacketeraSDK\\StructType\\PacketCourierNumberV2Result',
            'StorageFileAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\StorageFileAttributes',
            'ListStorageFileAttributes' => '\\WpifyWoo\\PacketeraSDK\\StructType\\ListStorageFileAttributes',
            'StorageFiles' => '\\WpifyWoo\\PacketeraSDK\\StructType\\StorageFiles',
            'StorageFile' => '\\WpifyWoo\\PacketeraSDK\\StructType\\StorageFile',
        ];
    }
}
