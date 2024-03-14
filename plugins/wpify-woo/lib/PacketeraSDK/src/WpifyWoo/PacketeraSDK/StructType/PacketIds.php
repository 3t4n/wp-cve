<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketIds StructType
 * @subpackage Structs
 */
class PacketIds extends AbstractStructBase
{
    /**
     * The id
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var string[]
     */
    protected array $id;
    /**
     * Constructor method for PacketIds
     * @uses PacketIds::setId()
     * @param string[] $id
     */
    public function __construct(array $id)
    {
        $this
            ->setId($id);
    }
    /**
     * Get id value
     * @return string[]
     */
    public function getId(): array
    {
        return $this->id;
    }
    /**
     * This method is responsible for validating the values passed to the setId method
     * This method is willingly generated in order to preserve the one-line inline validation within the setId method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateIdForArrayConstraintsFromSetId(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $packetIdsIdItem) {
            // validation for constraint: itemType
            if (!is_string($packetIdsIdItem)) {
                $invalidValues[] = is_object($packetIdsIdItem) ? get_class($packetIdsIdItem) : sprintf('%s(%s)', gettype($packetIdsIdItem), var_export($packetIdsIdItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The id property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set id value
     * @throws InvalidArgumentException
     * @param string[] $id
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIds
     */
    public function setId(array $id): self
    {
        // validation for constraint: array
        if ('' !== ($idArrayErrorMessage = self::validateIdForArrayConstraintsFromSetId($id))) {
            throw new InvalidArgumentException($idArrayErrorMessage, __LINE__);
        }
        $this->id = $id;
        
        return $this;
    }
    /**
     * Add item to id value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIds
     */
    public function addToId(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The id property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->id[] = $item;
        
        return $this;
    }
}
