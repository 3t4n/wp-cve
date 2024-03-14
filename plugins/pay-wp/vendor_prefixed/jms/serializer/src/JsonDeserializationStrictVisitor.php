<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer;

use WPPayVendor\JMS\Serializer\Exception\NonVisitableTypeException;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
use function is_float;
use function is_int;
use function is_string;
final class JsonDeserializationStrictVisitor extends \WPPayVendor\JMS\Serializer\AbstractVisitor implements \WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface
{
    /** @var JsonDeserializationVisitor */
    private $wrappedDeserializationVisitor;
    public function __construct(int $options = 0, int $depth = 512)
    {
        $this->wrappedDeserializationVisitor = new \WPPayVendor\JMS\Serializer\JsonDeserializationVisitor($options, $depth);
    }
    public function setNavigator(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface $navigator) : void
    {
        parent::setNavigator($navigator);
        $this->wrappedDeserializationVisitor->setNavigator($navigator);
    }
    /**
     * {@inheritdoc}
     */
    public function visitNull($data, array $type)
    {
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function visitString($data, array $type) : ?string
    {
        if (null === $data) {
            return null;
        }
        if (!\is_string($data)) {
            throw \WPPayVendor\JMS\Serializer\Exception\NonVisitableTypeException::fromDataAndType($data, $type);
        }
        return $data;
    }
    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, array $type) : ?bool
    {
        if (null === $data) {
            return null;
        }
        if (!\is_bool($data)) {
            throw \WPPayVendor\JMS\Serializer\Exception\NonVisitableTypeException::fromDataAndType($data, $type);
        }
        return $data;
    }
    /**
     * {@inheritdoc}
     */
    public function visitInteger($data, array $type) : ?int
    {
        if (null === $data) {
            return null;
        }
        if (!\is_int($data)) {
            throw \WPPayVendor\JMS\Serializer\Exception\NonVisitableTypeException::fromDataAndType($data, $type);
        }
        return $data;
    }
    /**
     * {@inheritdoc}
     */
    public function visitDouble($data, array $type) : ?float
    {
        if (null === $data) {
            return null;
        }
        if (!\is_float($data)) {
            throw \WPPayVendor\JMS\Serializer\Exception\NonVisitableTypeException::fromDataAndType($data, $type);
        }
        return $data;
    }
    /**
     * {@inheritdoc}
     */
    public function visitArray($data, array $type) : array
    {
        try {
            return $this->wrappedDeserializationVisitor->visitArray($data, $type);
        } catch (\WPPayVendor\JMS\Serializer\Exception\RuntimeException $e) {
            throw \WPPayVendor\JMS\Serializer\Exception\NonVisitableTypeException::fromDataAndType($data, $type, $e);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function visitDiscriminatorMapProperty($data, \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata) : string
    {
        return $this->wrappedDeserializationVisitor->visitDiscriminatorMapProperty($data, $metadata);
    }
    /**
     * {@inheritdoc}
     */
    public function startVisitingObject(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, object $object, array $type) : void
    {
        $this->wrappedDeserializationVisitor->startVisitingObject($metadata, $object, $type);
    }
    /**
     * {@inheritdoc}
     */
    public function visitProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $metadata, $data)
    {
        return $this->wrappedDeserializationVisitor->visitProperty($metadata, $data);
    }
    /**
     * {@inheritdoc}
     */
    public function endVisitingObject(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, $data, array $type) : object
    {
        return $this->wrappedDeserializationVisitor->endVisitingObject($metadata, $data, $type);
    }
    /**
     * {@inheritdoc}
     */
    public function getResult($data)
    {
        return $this->wrappedDeserializationVisitor->getResult($data);
    }
    public function getCurrentObject() : ?object
    {
        return $this->wrappedDeserializationVisitor->getCurrentObject();
    }
    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        return $this->wrappedDeserializationVisitor->prepare($data);
    }
}
