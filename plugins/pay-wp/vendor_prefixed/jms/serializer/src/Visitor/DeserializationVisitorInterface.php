<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Visitor;

use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\VisitorInterface;
/**
 * Interface for visitors.
 *
 * This contains the minimal set of values that must be supported for any
 * output format.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Asmir Mustafic <goetas@gmail.com>
 */
interface DeserializationVisitorInterface extends \WPPayVendor\JMS\Serializer\VisitorInterface
{
    /**
     * @param mixed $data
     * @param array $type
     *
     * @return null
     */
    public function visitNull($data, array $type);
    /**
     * @param mixed $data
     * @param array $type
     */
    public function visitString($data, array $type) : ?string;
    /**
     * @param mixed $data
     * @param array $type
     */
    public function visitBoolean($data, array $type) : ?bool;
    /**
     * @param mixed $data
     * @param array $type
     */
    public function visitDouble($data, array $type) : ?float;
    /**
     * @param mixed $data
     * @param array $type
     */
    public function visitInteger($data, array $type) : ?int;
    /**
     * Returns the class name based on the type of the discriminator map value
     *
     * @param mixed $data
     */
    public function visitDiscriminatorMapProperty($data, \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata) : string;
    /**
     * @param mixed $data
     * @param array $type
     *
     * @return array<mixed>
     */
    public function visitArray($data, array $type) : array;
    /**
     * Called before the properties of the object are being visited.
     *
     * @param array $type
     */
    public function startVisitingObject(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, object $data, array $type) : void;
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function visitProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $metadata, $data);
    /**
     * Called after all properties of the object have been visited.
     *
     * @param mixed $data
     * @param array $type
     */
    public function endVisitingObject(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, $data, array $type) : object;
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function getResult($data);
}
