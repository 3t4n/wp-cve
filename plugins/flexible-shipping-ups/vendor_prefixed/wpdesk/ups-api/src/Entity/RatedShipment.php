<?php

namespace UpsFreeVendor\Ups\Entity;

class RatedShipment
{
    public $Service;
    public $RateShipmentWarning;
    public $BillingWeight;
    public $TransportationCharges;
    public $ServiceOptionsCharges;
    public $TotalCharges;
    public $GuaranteedDaysToDelivery;
    public $ScheduledDeliveryTime;
    public $RatedPackage;
    public $SurCharges;
    public $TimeInTransit;
    /**
     * @var NegotiatedRates|null
     */
    public $NegotiatedRates;
    public function __construct($response = null)
    {
        $this->Service = new \UpsFreeVendor\Ups\Entity\Service();
        $this->BillingWeight = new \UpsFreeVendor\Ups\Entity\BillingWeight();
        $this->TransportationCharges = new \UpsFreeVendor\Ups\Entity\Charges();
        $this->ServiceOptionsCharges = new \UpsFreeVendor\Ups\Entity\Charges();
        $this->TotalCharges = new \UpsFreeVendor\Ups\Entity\Charges();
        $this->RatedPackage = [];
        $this->SurCharges = [];
        if (null !== $response) {
            if (isset($response->Service)) {
                $this->Service->setCode($response->Service->Code);
            }
            if (isset($response->RatedShipmentWarning)) {
                $this->RateShipmentWarning = $response->RatedShipmentWarning;
            }
            if (isset($response->BillingWeight)) {
                $this->BillingWeight = new \UpsFreeVendor\Ups\Entity\BillingWeight($response->BillingWeight);
            }
            if (isset($response->GuaranteedDaysToDelivery)) {
                $this->GuaranteedDaysToDelivery = $response->GuaranteedDaysToDelivery;
            }
            if (isset($response->ScheduledDeliveryTime)) {
                $this->ScheduledDeliveryTime = $response->ScheduledDeliveryTime;
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
            if (isset($response->RatedPackage)) {
                if (\is_array($response->RatedPackage)) {
                    foreach ($response->RatedPackage as $ratedPackage) {
                        $this->RatedPackage[] = new \UpsFreeVendor\Ups\Entity\RatedPackage($ratedPackage);
                    }
                } else {
                    $this->RatedPackage[] = new \UpsFreeVendor\Ups\Entity\RatedPackage($response->RatedPackage);
                }
            }
            if (isset($response->SurCharges)) {
                if (\is_array($response->SurCharges)) {
                    foreach ($response->SurCharges as $surCharges) {
                        $this->SurCharges[] = new \UpsFreeVendor\Ups\Entity\Charges($surCharges);
                    }
                } else {
                    $this->SurCharges[] = new \UpsFreeVendor\Ups\Entity\Charges($response->SurCharges);
                }
            }
            if (isset($response->TimeInTransit)) {
                $this->TimeInTransit = new \UpsFreeVendor\Ups\Entity\RateTimeInTransitResponse($response->TimeInTransit);
            }
            if (isset($response->NegotiatedRates)) {
                $this->NegotiatedRates = new \UpsFreeVendor\Ups\Entity\NegotiatedRates($response->NegotiatedRates);
            }
        }
    }
    public function getServiceName()
    {
        return $this->Service->getName();
    }
}
