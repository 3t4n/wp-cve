<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Visitor\Factory;

use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
use WPPayVendor\JMS\Serializer\XmlDeserializationVisitor;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class XmlDeserializationVisitorFactory implements \WPPayVendor\JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory
{
    /**
     * @var bool
     */
    private $disableExternalEntities = \true;
    /**
     * @var string[]
     */
    private $doctypeWhitelist = [];
    /**
     * @var int
     */
    private $options = 0;
    /**
     * @return XmlDeserializationVisitor
     */
    public function getVisitor() : \WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface
    {
        return new \WPPayVendor\JMS\Serializer\XmlDeserializationVisitor($this->disableExternalEntities, $this->doctypeWhitelist, $this->options);
    }
    public function enableExternalEntities(bool $enable = \true) : self
    {
        $this->disableExternalEntities = !$enable;
        return $this;
    }
    /**
     * @param string[] $doctypeWhitelist
     */
    public function setDoctypeWhitelist(array $doctypeWhitelist) : self
    {
        $this->doctypeWhitelist = $doctypeWhitelist;
        return $this;
    }
    public function setOptions(int $options) : self
    {
        $this->options = $options;
        return $this;
    }
}
