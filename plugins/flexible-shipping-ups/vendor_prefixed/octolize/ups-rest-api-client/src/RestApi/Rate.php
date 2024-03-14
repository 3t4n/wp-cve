<?php

namespace UpsFreeVendor\Octolize\Ups\RestApi;

use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\RateResponse;
class Rate
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
    /**
     * @var mixed|true
     */
    private $is_tax_enabled;
    /**
     * @var mixed
     */
    private $logger;
    /**
     * @var false|mixed
     */
    private $time_in_transit;
    public function __construct($client, $logger, $is_testing = \false, $is_tax_enabled = \true, $time_in_transit = \false)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->is_testing = $is_testing;
        $this->is_tax_enabled = $is_tax_enabled;
        $this->time_in_transit = $time_in_transit;
    }
    public function shopRates(\UpsFreeVendor\Ups\Entity\RateRequest $rate_request) : \UpsFreeVendor\Ups\Entity\RateResponse
    {
        $this->client->setLogger($this->logger);
        $response = $this->client->rating('Shop', $this->prepare_payload($rate_request), $this->time_in_transit ? 'timeintransit' : '');
        return new \UpsFreeVendor\Ups\Entity\RateResponse($this->prepare_response($response->RateResponse));
    }
    public function getRate(\UpsFreeVendor\Ups\Entity\RateRequest $rate_request) : \UpsFreeVendor\Ups\Entity\RateResponse
    {
        $this->client->setLogger($this->logger);
        $response = $this->client->rating('Rate', $this->prepare_payload($rate_request));
        return new \UpsFreeVendor\Ups\Entity\RateResponse($this->prepare_response($response->RateResponse));
    }
    private function prepare_response(\stdClass $response) : \stdClass
    {
        foreach ($response->RatedShipment as $key => $shipment) {
            if (isset($shipment->NegotiatedRateCharges)) {
                $shipment->NegotiatedRates = new \stdClass();
                $shipment->NegotiatedRates->NetSummaryCharges = new \stdClass();
                $shipment->NegotiatedRates->NetSummaryCharges->GrandTotal = $shipment->NegotiatedRateCharges->TotalCharge;
            }
        }
        return $response;
    }
    private function prepare_payload(\UpsFreeVendor\Ups\Entity\RateRequest $rate_request) : array
    {
        $payload = ['RateRequest' => $this->prepare_object_properties($rate_request)];
        $payload['RateRequest']['Shipment']['Package'] = $payload['RateRequest']['Shipment']['Packages'];
        unset($payload['RateRequest']['Shipment']['Packages']);
        unset($payload['RateRequest']['Shipment']['Service']['Services']);
        $payload = $this->prepare_delivery_confirmation($payload);
        $payload = $this->prepare_negotiated_rates($payload);
        $payload = $this->prepare_declared_value($payload);
        return $payload;
    }
    private function prepare_declared_value(array $payload) : array
    {
        foreach ($payload['RateRequest']['Shipment']['Package'] as $key => $package) {
            if (isset($payload['RateRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['InsuredValue'])) {
                $payload['RateRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeclaredValue'] = $payload['RateRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['InsuredValue'];
            }
        }
        return $payload;
    }
    private function prepare_delivery_confirmation(array $payload) : array
    {
        if (isset($payload['RateRequest']['Shipment']['ShipmentServiceOptions']['DeliveryConfirmation']['DcisType'])) {
            $payload['RateRequest']['Shipment']['ShipmentServiceOptions']['DeliveryConfirmation']['DCISType'] = (string) $payload['RateRequest']['Shipment']['ShipmentServiceOptions']['DeliveryConfirmation']['DcisType'];
            unset($payload['RateRequest']['Shipment']['ShipmentServiceOptions']['DeliveryConfirmation']['DcisType']);
        }
        foreach ($payload['RateRequest']['Shipment']['Package'] as $key => $package) {
            if (isset($payload['RateRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeliveryConfirmation']['DcisType'])) {
                $payload['RateRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeliveryConfirmation']['DCISType'] = (string) $package['PackageServiceOptions']['DeliveryConfirmation']['DcisType'];
                unset($payload['RateRequest']['Shipment']['Package'][$key]['PackageServiceOptions']['DeliveryConfirmation']['DcisType']);
            }
        }
        return $payload;
    }
    private function prepare_negotiated_rates(array $payload) : array
    {
        if (isset($payload['RateRequest']['Shipment']['RateInformation'], $payload['RateRequest']['Shipment']['RateInformation']['NegotiatedRatesIndicator'])) {
            $payload['RateRequest']['Shipment']['ShipmentRatingOptions'] = $payload['RateRequest']['Shipment']['ShipmentRatingOptions'] ?? [];
            $payload['RateRequest']['Shipment']['ShipmentRatingOptions']['NegotiatedRatesIndicator'] = '1';
            unset($payload['RateRequest']['Shipment']['RateInformation']['NegotiatedRatesIndicator']);
        }
        return $payload;
    }
}
