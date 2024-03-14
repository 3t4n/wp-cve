<?php

namespace UpsFreeVendor\Ups\Entity;

use DOMDocument;
use DOMElement;
use UpsFreeVendor\Ups\NodeInterface;
/**
 * Class SimpleRate
 * @package Ups\Entity
 */
class SimpleRate implements \UpsFreeVendor\Ups\NodeInterface
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $description;
    /**
     * @param null $parameters
     */
    public function __construct($parameters = null)
    {
        if (null !== $parameters) {
            if (isset($parameters->code)) {
                $this->setCode($parameters->code);
            }
            if (isset($parameters->description)) {
                $this->setDescription($parameters->description);
            }
        }
    }
    /**
     * @param null|DOMDocument $document
     *
     * @TODO: this seem to be awfully incomplete
     *
     * @return DOMElement
     */
    public function toNode(\DOMDocument $document = null)
    {
        if (null === $document) {
            $document = new \DOMDocument();
        }
        $node = $document->createElement('SimpleRate');
        if ($this->getCode()) {
            $node->appendChild($document->createElement('Code', $this->getCode()));
        }
        if ($this->getDescription()) {
            $node->appendChild($document->createElement('Description', $this->getDescription()));
        }
        return $node;
    }
    /**
     * @param string $code
     *
     * @return SimpleRate
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
