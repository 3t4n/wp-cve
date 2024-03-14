<?php

namespace UpsFreeVendor\Ups\Entity;

class RateResponse
{
    public $RatedShipment;
    public function __construct($response = null)
    {
        $this->RatedShipment = [];
        if (null !== $response) {
            if (isset($response->RatedShipment)) {
                if (\is_array($response->RatedShipment)) {
                    foreach ($response->RatedShipment as $ratedShipment) {
                        $this->RatedShipment[] = new \UpsFreeVendor\Ups\Entity\RatedShipment($ratedShipment);
                    }
                } else {
                    $this->RatedShipment[] = new \UpsFreeVendor\Ups\Entity\RatedShipment($response->RatedShipment);
                }
            }
        }
    }
}
