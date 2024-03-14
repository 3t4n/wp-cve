<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Visitor\Factory;

use WPPayVendor\JMS\Serializer\JsonDeserializationStrictVisitor;
use WPPayVendor\JMS\Serializer\JsonDeserializationVisitor;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class JsonDeserializationVisitorFactory implements \WPPayVendor\JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory
{
    /**
     * @var int
     */
    private $options = 0;
    /**
     * @var int
     */
    private $depth = 512;
    /**
     * @var bool
     */
    private $strict;
    public function __construct(bool $strict = \false)
    {
        $this->strict = $strict;
    }
    public function getVisitor() : \WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface
    {
        if ($this->strict) {
            return new \WPPayVendor\JMS\Serializer\JsonDeserializationStrictVisitor($this->options, $this->depth);
        }
        return new \WPPayVendor\JMS\Serializer\JsonDeserializationVisitor($this->options, $this->depth);
    }
    public function setOptions(int $options) : self
    {
        $this->options = $options;
        return $this;
    }
    public function setDepth(int $depth) : self
    {
        $this->depth = $depth;
        return $this;
    }
}
