<?php

namespace UpsFreeVendor\Octolize\Ups\RestApi;

use UpsFreeVendor\Psr\Log\NullLogger;
use UpsFreeVendor\Ups\Entity\Shipment;
use UpsFreeVendor\Ups\Entity\ShipmentRequestLabelSpecification;
use UpsFreeVendor\Ups\Entity\ShipmentRequestReceiptSpecification;
class Shipping extends \UpsFreeVendor\Ups\Shipping
{
    use ObjectProperties;
    /**
     * @var false|mixed
     */
    private $is_testing;
    /**
     * @var RestApiClient
     */
    private $client;
    public function __construct($client, $is_testing = \false)
    {
        $this->client = $client;
        $this->is_testing = $is_testing;
    }
    public function confirm($validation, \UpsFreeVendor\Ups\Entity\Shipment $shipment, \UpsFreeVendor\Ups\Entity\ShipmentRequestLabelSpecification $labelSpec = null, \UpsFreeVendor\Ups\Entity\ShipmentRequestReceiptSpecification $receiptSpec = null)
    {
        $this->client->setLogger($this->logger ?? new \UpsFreeVendor\Psr\Log\NullLogger());
        $response = $this->client->shipment_send($this->prepare_payload($shipment));
        return $response->ShipmentResponse->ShipmentResults;
    }
    public function void($shipmentData)
    {
        $this->client->setLogger($this->logger ?? new \UpsFreeVendor\Psr\Log\NullLogger());
        return $this->client->shipment_void($shipmentData['shipmentId']);
    }
    public function recoverLabel($trackingData, $labelSpecification = null, $labelDelivery = null, $translate = null)
    {
        $this->client->setLogger($this->logger ?? new \UpsFreeVendor\Psr\Log\NullLogger());
        $payload = ["LabelRecoveryRequest" => ["LabelSpecification" => ["HTTPUserAgent" => "Mozilla/4.5", "LabelImageFormat" => ["Code" => $labelSpecification['imageFormat']]], "TrackingNumber" => $trackingData]];
        return $this->client->shipment_recover_label($payload)->LabelRecoveryResponse;
    }
    private function prepare_payload(\UpsFreeVendor\Ups\Entity\Shipment $shipment) : array
    {
        $payload = ['ShipmentRequest' => ['Shipment' => $this->prepare_object_properties($shipment)]];
        $payload['ShipmentRequest']['Shipment']['Package'] = $payload['ShipmentRequest']['Shipment']['Packages'];
        unset($payload['ShipmentRequest']['Shipment']['Packages']);
        $payload = $this->prepare_shipper($payload);
        $payload = $this->prepare_ship_from($payload);
        $payload = $this->prepare_ship_to($payload);
        $shipment_charge = ['Type' => '01', 'BillShipper' => $payload['ShipmentRequest']['Shipment']['PaymentInformation']['Prepaid']['BillShipper']];
        $payload['ShipmentRequest']['Shipment']['PaymentInformation']['ShipmentCharge'] = $shipment_charge;
        unset($payload['ShipmentRequest']['Shipment']['PaymentInformation']['Prepaid']);
        unset($payload['ShipmentRequest']['Shipment']['Service']['Services']);
        $payload = $this->prepare_packages($payload);
        $payload = $this->prepare_negotiated_rates($payload);
        return $payload;
    }
    private function prepare_ship_from(array $payload) : array
    {
        $payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine'] = [$payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine1']];
        unset($payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine1']);
        if (isset($payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine2'])) {
            $payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine'][] = $payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine2'];
            unset($payload['ShipmentRequest']['Shipment']['ShipFrom']['Address']['AddressLine2']);
        }
        $payload['ShipmentRequest']['Shipment']['ShipFrom']['Name'] = $payload['ShipmentRequest']['Shipment']['ShipFrom']['CompanyName'];
        unset($payload['ShipmentRequest']['Shipment']['ShipFrom']['CompanyName']);
        return $payload;
    }
    private function prepare_ship_to(array $payload) : array
    {
        $payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine'] = [$payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine1']];
        unset($payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine1']);
        if (isset($payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine2'])) {
            $payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine'][] = $payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine2'];
            unset($payload['ShipmentRequest']['Shipment']['ShipTo']['Address']['AddressLine2']);
        }
        $phone = ['Number' => $payload['ShipmentRequest']['Shipment']['ShipTo']['PhoneNumber']];
        $payload['ShipmentRequest']['Shipment']['ShipTo']['Phone'] = $phone;
        return $payload;
    }
    private function prepare_shipper(array $payload) : array
    {
        $payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine'] = [$payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine1']];
        unset($payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine1']);
        if ($payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine2']) {
            $payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine'][] = $payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine2'];
            unset($payload['ShipmentRequest']['Shipment']['Shipper']['Address']['AddressLine2']);
        }
        $phone = ['Number' => $payload['ShipmentRequest']['Shipment']['Shipper']['PhoneNumber']];
        $payload['ShipmentRequest']['Shipment']['Shipper']['Phone'] = $phone;
        return $payload;
    }
    private function prepare_packages(array $payload) : array
    {
        foreach ($payload['ShipmentRequest']['Shipment']['Package'] as $key => $package) {
            $payload['ShipmentRequest']['Shipment']['Package'][$key]['Packaging'] = $payload['ShipmentRequest']['Shipment']['Package'][$key]['PackagingType'];
            unset($payload['ShipmentRequest']['Shipment']['Package'][$key]['PackagingType']);
            if (isset($payload['ShipmentRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeliveryConfirmation']['DcisType'])) {
                $payload['ShipmentRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeliveryConfirmation']['DCISType'] = (string) $package['PackageServiceOptions']['DeliveryConfirmation']['DcisType'];
                unset($payload['ShipmentRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeliveryConfirmation']['DcisType']);
            }
        }
        return $payload;
    }
    private function prepare_negotiated_rates(array $payload) : array
    {
        if (isset($payload['ShipmentRequest']['Shipment']['RateInformation'], $payload['ShipmentRequest']['Shipment']['RateInformation']['NegotiatedRatesIndicator'])) {
            $payload['ShipmentRequest']['Shipment']['ShipmentRatingOptions'] = $payload['ShipmentRequest']['Shipment']['ShipmentRatingOptions'] ?? [];
            $payload['ShipmentRequest']['Shipment']['ShipmentRatingOptions']['NegotiatedRatesIndicator'] = '1';
            unset($payload['ShipmentRequest']['Shipment']['RateInformation']['NegotiatedRatesIndicator']);
        }
        return $payload;
    }
}
