<?php

namespace UpsFreeVendor\Ups\Entity\Tradeability;

use UpsFreeVendor\DomDocument;
use UpsFreeVendor\DomElement;
use UpsFreeVendor\Ups\NodeInterface;
class QueryRequest implements \UpsFreeVendor\Ups\NodeInterface
{
    /**
     * @var Shipment
     */
    private $shipment;
    /**
     * @var bool
     */
    private $suppressQuestionIndicator = \false;
    /**
     * @param null|DOMDocument $document
     *
     * @return DOMElement
     */
    public function toNode(\UpsFreeVendor\DomDocument $document = null)
    {
        if (null === $document) {
            $document = new \UpsFreeVendor\DomDocument();
        }
        $node = $document->createElement('QueryRequest');
        if ($this->getShipment() !== null) {
            $node->appendChild($this->getShipment()->toNode($document));
        }
        $node->appendChild($document->createElement('SuppressQuestionIndicator', $this->isSuppressQuestionIndicator() ? 'Y' : 'N'));
        return $node;
    }
    /**
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }
    /**
     * @param Shipment $shipment
     * @return QueryRequest
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
        return $this;
    }
    /**
     * @return boolean
     */
    public function isSuppressQuestionIndicator()
    {
        return $this->suppressQuestionIndicator;
    }
    /**
     * @param boolean $suppressQuestionIndicator
     * @return QueryRequest
     */
    public function setSuppressQuestionIndicator($suppressQuestionIndicator)
    {
        $this->suppressQuestionIndicator = $suppressQuestionIndicator;
        return $this;
    }
}
