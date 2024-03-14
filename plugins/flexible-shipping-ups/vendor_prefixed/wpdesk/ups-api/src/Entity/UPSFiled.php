<?php

namespace UpsFreeVendor\Ups\Entity;

use DOMDocument;
use DOMElement;
use UpsFreeVendor\Ups\NodeInterface;
class UPSFiled implements \UpsFreeVendor\Ups\NodeInterface
{
    /**
     * @var POA
     */
    private $poa;
    /**
     * @param null|object $attributes
     */
    public function __construct($attributes = null)
    {
        if (null !== $attributes) {
            if (isset($attributes->POA)) {
                $this->setPOA(new \UpsFreeVendor\Ups\Entity\POA($attributes->POA));
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
        $node = $document->createElement('UPSFiled');
        $poa = $this->getPOA();
        if (isset($poa)) {
            $node->appendChild($poa->toNode($document));
        }
        return $node;
    }
    /**
     * @return POA
     */
    public function getPOA()
    {
        return $this->poa;
    }
    /**
     * @return string
     */
    public function setPOA(\UpsFreeVendor\Ups\Entity\POA $poa)
    {
        $this->poa = $poa;
        return $this;
    }
}
