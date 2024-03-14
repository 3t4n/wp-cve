<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ResponseParsers;

use DhlVendor\Octolize\DhlExpress\RestApi\Traits\GetRawResponse;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Shipment;
class ShipmentResponseParser
{
    use GetRawResponse;
    private array $response;
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    public function parse() : \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Shipment
    {
        return new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Shipment($this->response['shipmentTrackingNumber'], $this->response['cancelPickupUrl'], $this->response['trackingUrl'], $this->response['dispatchConfirmationNumber'], $this->getLabelPdf($this->response), $this->response['warnings'] ?? [], $this->response['packages'] ?? [], $this->response['documents'] ?? [], $this->response['shipmentDetails'] ?? [], $this->response['shipmentCharges'] ?? []);
    }
    public function getLabelPdf(array $response) : string
    {
        $labelPdf = '';
        foreach ($response['documents'] as $document) {
            if ($document['typeCode'] === 'label' && $document['imageFormat'] === 'PDF') {
                $labelPdf = \base64_decode($document['content'], \true);
            }
        }
        return $labelPdf;
    }
}
