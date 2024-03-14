<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for StatusRecords StructType
 * @subpackage Structs
 */
class StatusRecords extends AbstractStructBase
{
    /**
     * The record
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var \WpifyWoo\PacketeraSDK\StructType\StatusRecord[]
     */
    protected array $record;
    /**
     * Constructor method for StatusRecords
     * @uses StatusRecords::setRecord()
     * @param \WpifyWoo\PacketeraSDK\StructType\StatusRecord[] $record
     */
    public function __construct(array $record)
    {
        $this
            ->setRecord($record);
    }
    /**
     * Get record value
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord[]
     */
    public function getRecord(): array
    {
        return $this->record;
    }
    /**
     * This method is responsible for validating the values passed to the setRecord method
     * This method is willingly generated in order to preserve the one-line inline validation within the setRecord method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateRecordForArrayConstraintsFromSetRecord(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $statusRecordsRecordItem) {
            // validation for constraint: itemType
            if (!$statusRecordsRecordItem instanceof \WpifyWoo\PacketeraSDK\StructType\StatusRecord) {
                $invalidValues[] = is_object($statusRecordsRecordItem) ? get_class($statusRecordsRecordItem) : sprintf('%s(%s)', gettype($statusRecordsRecordItem), var_export($statusRecordsRecordItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The record property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\StatusRecord, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set record value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\StatusRecord[] $record
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecords
     */
    public function setRecord(array $record): self
    {
        // validation for constraint: array
        if ('' !== ($recordArrayErrorMessage = self::validateRecordForArrayConstraintsFromSetRecord($record))) {
            throw new InvalidArgumentException($recordArrayErrorMessage, __LINE__);
        }
        $this->record = $record;
        
        return $this;
    }
    /**
     * Add item to record value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\StatusRecord $item
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecords
     */
    public function addToRecord(\WpifyWoo\PacketeraSDK\StructType\StatusRecord $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\StatusRecord) {
            throw new InvalidArgumentException(sprintf('The record property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\StatusRecord, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->record[] = $item;
        
        return $this;
    }
}
