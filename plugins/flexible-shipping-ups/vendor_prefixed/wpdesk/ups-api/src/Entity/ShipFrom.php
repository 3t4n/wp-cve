<?php

namespace UpsFreeVendor\Ups\Entity;

use DOMDocument;
use DOMElement;
use UpsFreeVendor\Ups\NodeInterface;
class ShipFrom extends \UpsFreeVendor\Ups\Entity\Shipper implements \UpsFreeVendor\Ups\NodeInterface
{
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
        $node = $document->createElement('ShipFrom');
        if ($this->getCompanyName()) {
            $node->appendChild($document->createElement('CompanyName', $this->getCompanyName()));
        }
        if ($this->getAttentionName()) {
            $node->appendChild($document->createElement('AttentionName', $this->getAttentionName()));
        }
        $address = $this->getAddress();
        if (isset($address)) {
            $node->appendChild($address->toNode($document));
        }
        return $node;
    }
}
