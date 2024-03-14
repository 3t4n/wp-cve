<?php

namespace UpsFreeVendor\Ups\Entity;

use DateTime;
use Exception as BaseException;
class TimeInTransitRequest
{
    /**
     * @var
     */
    private $transitFrom;
    /**
     * @var
     */
    private $transitTo;
    /**
     * @var
     */
    private $shipmentWeight;
    /**
     * @var
     */
    private $totalPackagesInShipment;
    /**
     * @var
     */
    private $invoiceLineTotal;
    /**
     * @var
     */
    private $pickupDate;
    /**
     * @var bool
     */
    private $documentsOnlyIndicator = \false;
    public function __construct()
    {
        $this->setTransitFrom(new \UpsFreeVendor\Ups\Entity\AddressArtifactFormat());
        $this->setTransitTo(new \UpsFreeVendor\Ups\Entity\AddressArtifactFormat());
        $this->setShipmentWeight(new \UpsFreeVendor\Ups\Entity\ShipmentWeight());
        $this->setInvoiceLineTotal(new \UpsFreeVendor\Ups\Entity\InvoiceLineTotal());
        $this->setPickupDate(new \DateTime());
    }
    public function setDocumentsOnlyIndicator()
    {
        $this->documentsOnlyIndicator = \true;
    }
    /**
     * @return bool
     */
    public function getDocumentsOnlyIndicator()
    {
        return $this->documentsOnlyIndicator;
    }
    /**
     * @param DateTime $date
     */
    public function setPickupDate(\DateTime $date)
    {
        $this->pickupDate = $date;
    }
    /**
     * @return mixed
     */
    public function getPickupDate()
    {
        return $this->pickupDate;
    }
    /**
     * @param AddressArtifactFormat $address
     */
    public function setTransitFrom(\UpsFreeVendor\Ups\Entity\AddressArtifactFormat $address)
    {
        $this->transitFrom = $address;
    }
    /**
     * @return mixed
     */
    public function getTransitFrom()
    {
        return $this->transitFrom;
    }
    /**
     * @param AddressArtifactFormat $address
     */
    public function setTransitTo(\UpsFreeVendor\Ups\Entity\AddressArtifactFormat $address)
    {
        $this->transitTo = $address;
    }
    /**
     * @return mixed
     */
    public function getTransitTo()
    {
        return $this->transitTo;
    }
    /**
     * @param ShipmentWeight $shipmentWeight
     */
    public function setShipmentWeight(\UpsFreeVendor\Ups\Entity\ShipmentWeight $shipmentWeight)
    {
        $this->shipmentWeight = $shipmentWeight;
    }
    /**
     * @return mixed
     */
    public function getShipmentWeight()
    {
        return $this->shipmentWeight;
    }
    /**
     * @param $amount
     *
     * @throws BaseException
     */
    public function setTotalPackagesInShipment($amount)
    {
        if (!\is_int($amount) || $amount < 0) {
            throw new \Exception('Amount of packages should be integer and above 0');
        }
        $this->totalPackagesInShipment = $amount;
    }
    /**
     * @return mixed
     */
    public function getTotalPackagesInShipment()
    {
        return $this->totalPackagesInShipment;
    }
    /**
     * @param InvoiceLineTotal $invoiceLineTotal
     */
    public function setInvoiceLineTotal(\UpsFreeVendor\Ups\Entity\InvoiceLineTotal $invoiceLineTotal)
    {
        $this->invoiceLineTotal = $invoiceLineTotal;
    }
    /**
     * @return mixed
     */
    public function getInvoiceLineTotal()
    {
        return $this->invoiceLineTotal;
    }
}
