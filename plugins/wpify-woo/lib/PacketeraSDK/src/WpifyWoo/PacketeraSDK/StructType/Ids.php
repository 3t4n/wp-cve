<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ids StructType
 * @subpackage Structs
 */
class Ids extends AbstractStructBase
{
    /**
     * The packetId
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var string[]
     */
    protected array $packetId;
    /**
     * Constructor method for ids
     * @uses Ids::setPacketId()
     * @param string[] $packetId
     */
    public function __construct(array $packetId)
    {
        $this
            ->setPacketId($packetId);
    }
    /**
     * Get packetId value
     * @return string[]
     */
    public function getPacketId(): array
    {
        return $this->packetId;
    }
    /**
     * This method is responsible for validating the values passed to the setPacketId method
     * This method is willingly generated in order to preserve the one-line inline validation within the setPacketId method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validatePacketIdForArrayConstraintsFromSetPacketId(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $idsPacketIdItem) {
            // validation for constraint: itemType
            if (!is_string($idsPacketIdItem)) {
                $invalidValues[] = is_object($idsPacketIdItem) ? get_class($idsPacketIdItem) : sprintf('%s(%s)', gettype($idsPacketIdItem), var_export($idsPacketIdItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The packetId property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set packetId value
     * @throws InvalidArgumentException
     * @param string[] $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\Ids
     */
    public function setPacketId(array $packetId): self
    {
        // validation for constraint: array
        if ('' !== ($packetIdArrayErrorMessage = self::validatePacketIdForArrayConstraintsFromSetPacketId($packetId))) {
            throw new InvalidArgumentException($packetIdArrayErrorMessage, __LINE__);
        }
        $this->packetId = $packetId;
        
        return $this;
    }
    /**
     * Add item to packetId value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\Ids
     */
    public function addToPacketId(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The packetId property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->packetId[] = $item;
        
        return $this;
    }
}
