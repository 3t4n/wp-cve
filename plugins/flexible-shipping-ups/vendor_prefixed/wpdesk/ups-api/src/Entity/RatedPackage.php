<?php

namespace UpsFreeVendor\Ups\Entity;

class RatedPackage
{
    public $Weight;
    public $BillingWeight;
    public $TransportationCharges;
    public $ServiceOptionsCharges;
    public $TotalCharges;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->BillingWeight = new \UpsFreeVendor\Ups\Entity\BillingWeight();
        $this->TransportationCharges = new \UpsFreeVendor\Ups\Entity\Charges();
        $this->ServiceOptionsCharges = new \UpsFreeVendor\Ups\Entity\Charges();
        $this->TotalCharges = new \UpsFreeVendor\Ups\Entity\Charges();
        $this->Weight = '0.0';
        if (null !== $response) {
            if (isset($response->Weight)) {
                $this->Weight = $response->Weight;
            }
            if (isset($response->BillingWeight)) {
                $this->BillingWeight = new \UpsFreeVendor\Ups\Entity\BillingWeight($response->BillingWeight);
            }
            if (isset($response->TransportationCharges)) {
                $this->TransportationCharges = new \UpsFreeVendor\Ups\Entity\Charges($response->TransportationCharges);
            }
            if (isset($response->ServiceOptionsCharges)) {
                $this->ServiceOptionsCharges = new \UpsFreeVendor\Ups\Entity\Charges($response->ServiceOptionsCharges);
            }
            if (isset($response->TotalCharges)) {
                $this->TotalCharges = new \UpsFreeVendor\Ups\Entity\Charges($response->TotalCharges);
            }
        }
    }
}
