<?php

namespace UpsFreeVendor\Ups\Entity;

class LabelRecoveryResponse
{
    public $ShipmentIdentificationNumber;
    public $LabelResults;
    public $TrackingCandidate;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->LabelResults = new \UpsFreeVendor\Ups\Entity\LabelResults();
        if (null !== $response) {
            if (isset($response->ShipmentIdentificationNumber)) {
                $this->ShipmentIdentificationNumber = $response->ShipmentIdentificationNumber;
            }
            if (isset($response->LabelResults)) {
                $this->LabelResults = new \UpsFreeVendor\Ups\Entity\LabelResults($response->LabelResults);
            }
            if (isset($response->TrackingCandidate)) {
                $this->TrackingCandidate = new \UpsFreeVendor\Ups\Entity\TrackingCandidate($response->TrackingCandidate);
            }
        }
    }
}
