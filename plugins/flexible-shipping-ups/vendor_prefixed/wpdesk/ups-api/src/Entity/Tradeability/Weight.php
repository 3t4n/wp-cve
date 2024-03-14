<?php

namespace UpsFreeVendor\Ups\Entity\Tradeability;

use DOMDocument;
use DOMElement;
use UpsFreeVendor\Ups\NodeInterface;
/**
 * Class Weight
 */
class Weight implements \UpsFreeVendor\Ups\NodeInterface
{
    /**
     * @var UnitOfMeasurement
     */
    private $unitOfMeasurement;
    /**
     * @var int
     */
    private $value;
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
        $node = $document->createElement('Weight');
        // Required
        $node->appendChild($document->createElement('Value', $this->getValue()));
        // Optional
        if ($this->getUnitOfMeasurement() instanceof \UpsFreeVendor\Ups\Entity\Tradeability\UnitOfMeasurement) {
            $node->appendChild($this->getUnitOfMeasurement()->toNode($document));
        }
        return $node;
    }
    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * @param int $value
     * @return Quantity
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    /**
     * @return UnitOfMeasurement
     */
    public function getUnitOfMeasurement()
    {
        return $this->unitOfMeasurement;
    }
    /**
     * @param UnitOfMeasurement $unitOfMeasurement
     * @return Quantity
     */
    public function setUnitOfMeasurement(\UpsFreeVendor\Ups\Entity\Tradeability\UnitOfMeasurement $unitOfMeasurement)
    {
        $this->unitOfMeasurement = $unitOfMeasurement;
        return $this;
    }
}
