<?php

namespace UpsFreeVendor\Ups;

use DOMDocument;
use DOMElement;
use Exception;
use SimpleXMLElement;
use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\RateResponse;
use UpsFreeVendor\Ups\Entity\Shipment;
/**
 * RateTimeInTransit API Wrapper.
 */
class RateTimeInTransit extends \UpsFreeVendor\Ups\Rate
{
    /**
     * @param $rateRequest
     *
     * @throws Exception
     *
     * @return RateResponse
     */
    public function getRateTimeInTransit($rateRequest)
    {
        if ($rateRequest instanceof \UpsFreeVendor\Ups\Entity\Shipment) {
            $shipment = $rateRequest;
            $rateRequest = new \UpsFreeVendor\Ups\Entity\RateRequest();
            $rateRequest->setShipment($shipment);
        }
        $this->requestOption = 'Ratetimeintransit';
        return $this->sendRequest($rateRequest);
    }
    /**
     * @param $rateRequest
     *
     * @throws Exception
     *
     * @return RateResponse
     */
    public function shopRatesTimeInTransit($rateRequest)
    {
        if ($rateRequest instanceof \UpsFreeVendor\Ups\Entity\Shipment) {
            $shipment = $rateRequest;
            $rateRequest = new \UpsFreeVendor\Ups\Entity\RateRequest();
            $rateRequest->setShipment($shipment);
        }
        $this->requestOption = 'Shoptimeintransit';
        return $this->sendRequest($rateRequest);
    }
}
