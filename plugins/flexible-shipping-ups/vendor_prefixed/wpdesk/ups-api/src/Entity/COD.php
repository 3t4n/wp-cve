<?php

namespace UpsFreeVendor\Ups\Entity;

use DOMDocument;
use DOMElement;
use UpsFreeVendor\Ups\NodeInterface;
class COD implements \UpsFreeVendor\Ups\NodeInterface
{
    /**
     * @var string
     */
    public $CODCode;
    /**
     * @var string
     */
    public $CODFundsCode;
    /**
     * @var CODAmount
     */
    public $CODAmount;
    /**
     * COD constructor.
     * @param null|\stdClass $response
     */
    public function __construct($response = null)
    {
        $this->CODAmount = new \UpsFreeVendor\Ups\Entity\CODAmount();
        if (null !== $response) {
            if (isset($response->CODCode)) {
                $this->CODCode = $response->CODCode;
            }
            if (isset($response->CODFundsCode)) {
                $this->CODFundsCode = $response->CODFundsCode;
            }
            if (isset($response->CODAmount)) {
                $this->CODAmount = new \UpsFreeVendor\Ups\Entity\CODAmount($response->CODAmount);
            }
        }
    }
    /**
     * @param DOMDocument|null $document
     * @return DOMElement
     */
    public function toNode(\DOMDocument $document = null)
    {
        if (null === $document) {
            $document = new \DOMDocument();
        }
        $node = $document->createElement('COD');
        if ($this->getCODCode()) {
            $node->appendChild($document->createElement('CODCode', $this->getCODCode()));
        }
        if ($this->getCODFundsCode()) {
            $node->appendChild($document->createElement('CODFundsCode', $this->getCODFundsCode()));
        }
        if ($this->getCODAmount()) {
            $node->appendChild($this->getCODAmount()->toNode($document));
        }
        return $node;
    }
    /**
     * @return string|null
     */
    public function getCODCode()
    {
        return $this->CODCode;
    }
    /**
     * @param string $CODCode
     * @return COD
     */
    public function setCODCode($CODCode)
    {
        $this->CODCode = $CODCode;
        return $this;
    }
    /**
     * @return string|null
     */
    public function getCODFundsCode()
    {
        return $this->CODFundsCode;
    }
    /**
     * @param string $CODFundsCode
     * @return COD
     */
    public function setCODFundsCode($CODFundsCode)
    {
        $this->CODFundsCode = $CODFundsCode;
        return $this;
    }
    /**
     * @return CODAmount|null
     */
    public function getCODAmount()
    {
        return $this->CODAmount;
    }
    /**
     * @param CODAmount $CODAmount
     * @return COD
     */
    public function setCODAmount(\UpsFreeVendor\Ups\Entity\CODAmount $CODAmount)
    {
        $this->CODAmount = $CODAmount;
        return $this;
    }
}
