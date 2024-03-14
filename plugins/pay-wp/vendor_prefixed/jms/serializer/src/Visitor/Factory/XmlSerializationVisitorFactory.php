<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Visitor\Factory;

use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
use WPPayVendor\JMS\Serializer\XmlSerializationVisitor;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class XmlSerializationVisitorFactory implements \WPPayVendor\JMS\Serializer\Visitor\Factory\SerializationVisitorFactory
{
    /**
     * @var string
     */
    private $defaultRootName = 'result';
    /**
     * @var string
     */
    private $defaultVersion = '1.0';
    /**
     * @var string
     */
    private $defaultEncoding = 'UTF-8';
    /**
     * @var bool
     */
    private $formatOutput = \true;
    /**
     * @var string|null
     */
    private $defaultRootNamespace;
    public function getVisitor() : \WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface
    {
        return new \WPPayVendor\JMS\Serializer\XmlSerializationVisitor($this->formatOutput, $this->defaultEncoding, $this->defaultVersion, $this->defaultRootName, $this->defaultRootNamespace);
    }
    public function setDefaultRootName(string $name, ?string $namespace = null) : self
    {
        $this->defaultRootName = $name;
        $this->defaultRootNamespace = $namespace;
        return $this;
    }
    public function setDefaultVersion(string $version) : self
    {
        $this->defaultVersion = $version;
        return $this;
    }
    public function setDefaultEncoding(string $encoding) : self
    {
        $this->defaultEncoding = $encoding;
        return $this;
    }
    public function setFormatOutput(bool $formatOutput) : self
    {
        $this->formatOutput = (bool) $formatOutput;
        return $this;
    }
}
