<?php

namespace UpsFreeVendor\Ups\Entity;

class TrackingCandidate
{
    public $TrackingNumber;
    public $DestinationPostalCode;
    public $DestinationCountryCode;
    public $PickupDateRange;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        if (isset($response->TrackingNumber)) {
            $this->TrackingNumber = $response->TrackingNumber;
        }
        if (isset($response->DestinationPostalCode)) {
            $this->DestinationPostalCode = $response->DestinationPostalCode;
        }
        if (isset($response->DestinationCountryCode)) {
            $this->DestinationCountryCode = $response->DestinationCountryCode;
        }
        if (isset($response->PickupDateRange)) {
            $this->PickupDateRange = new \UpsFreeVendor\Ups\Entity\PickupDateRange($response->PickupDateRange);
        }
    }
}
