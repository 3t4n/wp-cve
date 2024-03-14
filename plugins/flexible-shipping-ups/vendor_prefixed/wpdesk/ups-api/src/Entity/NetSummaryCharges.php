<?php

namespace UpsFreeVendor\Ups\Entity;

class NetSummaryCharges
{
    /**
     * @var Charges
     */
    public $GrandTotal;
    /**
     * @var Charges|null
     */
    public $TotalChargesWithTaxes;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->GrandTotal = new \UpsFreeVendor\Ups\Entity\Charges();
        if (null !== $response) {
            if (isset($response->GrandTotal)) {
                $this->GrandTotal = new \UpsFreeVendor\Ups\Entity\Charges($response->GrandTotal);
            }
            if (isset($response->TotalChargesWithTaxes)) {
                $this->TotalChargesWithTaxes = new \UpsFreeVendor\Ups\Entity\Charges($response->TotalChargesWithTaxes);
            }
        }
    }
}
