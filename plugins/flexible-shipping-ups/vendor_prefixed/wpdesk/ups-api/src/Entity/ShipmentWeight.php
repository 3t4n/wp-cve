<?php

namespace UpsFreeVendor\Ups\Entity;

use DOMDocument;
use DOMElement;
use UpsFreeVendor\Ups\NodeInterface;
class ShipmentWeight implements \UpsFreeVendor\Ups\NodeInterface
{
    private $unitOfMeasurement;
    private $weight;
    public function __construct($response = null)
    {
        $this->setUnitOfMeasurement(new \UpsFreeVendor\Ups\Entity\UnitOfMeasurement());
        if (null !== $response) {
            if (isset($response->UnitOfMeasurement)) {
                $this->setUnitOfMeasurement(new \UpsFreeVendor\Ups\Entity\UnitOfMeasurement($response->UnitOfMeasurement));
            }
            if (isset($response->Weight)) {
                $this->setWeight($response->Weight);
            }
        }
    }
    /**
     * @param null|DOMDocument $document
     *
     * @return DOMElement
     */
    public function toNode(\DOMDocument $document = null)
    {
        if (null === $document) {
            $document = new \DOMDocument();
        }
        $node = $document->createElement('ShipmentWeight');
        $node->appendChild($document->createElement('Weight', $this->getWeight()));
        $node->appendChild($this->getUnitOfMeasurement()->toNode($document));
        return $node;
    }
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
    public function getWeight()
    {
        return $this->weight;
    }
    public function setUnitOfMeasurement(\UpsFreeVendor\Ups\Entity\UnitOfMeasurement $unitOfMeasurement)
    {
        $this->unitOfMeasurement = $unitOfMeasurement;
    }
    public function getUnitOfMeasurement()
    {
        return $this->unitOfMeasurement;
    }
}
