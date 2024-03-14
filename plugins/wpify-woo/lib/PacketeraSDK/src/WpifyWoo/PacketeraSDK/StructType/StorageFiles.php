<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for StorageFiles StructType
 * @subpackage Structs
 */
class StorageFiles extends AbstractStructBase
{
    /**
     * The StorageFile
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * @var \WpifyWoo\PacketeraSDK\StructType\StorageFile[]
     */
    protected ?array $StorageFile = null;
    /**
     * Constructor method for StorageFiles
     * @uses StorageFiles::setStorageFile()
     * @param \WpifyWoo\PacketeraSDK\StructType\StorageFile[] $storageFile
     */
    public function __construct(?array $storageFile = null)
    {
        $this
            ->setStorageFile($storageFile);
    }
    /**
     * Get StorageFile value
     * @return \WpifyWoo\PacketeraSDK\StructType\StorageFile[]
     */
    public function getStorageFile(): ?array
    {
        return $this->StorageFile;
    }
    /**
     * This method is responsible for validating the values passed to the setStorageFile method
     * This method is willingly generated in order to preserve the one-line inline validation within the setStorageFile method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateStorageFileForArrayConstraintsFromSetStorageFile(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $storageFilesStorageFileItem) {
            // validation for constraint: itemType
            if (!$storageFilesStorageFileItem instanceof \WpifyWoo\PacketeraSDK\StructType\StorageFile) {
                $invalidValues[] = is_object($storageFilesStorageFileItem) ? get_class($storageFilesStorageFileItem) : sprintf('%s(%s)', gettype($storageFilesStorageFileItem), var_export($storageFilesStorageFileItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The StorageFile property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\StorageFile, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set StorageFile value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\StorageFile[] $storageFile
     * @return \WpifyWoo\PacketeraSDK\StructType\StorageFiles
     */
    public function setStorageFile(?array $storageFile = null): self
    {
        // validation for constraint: array
        if ('' !== ($storageFileArrayErrorMessage = self::validateStorageFileForArrayConstraintsFromSetStorageFile($storageFile))) {
            throw new InvalidArgumentException($storageFileArrayErrorMessage, __LINE__);
        }
        $this->StorageFile = $storageFile;
        
        return $this;
    }
    /**
     * Add item to StorageFile value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\StorageFile $item
     * @return \WpifyWoo\PacketeraSDK\StructType\StorageFiles
     */
    public function addToStorageFile(\WpifyWoo\PacketeraSDK\StructType\StorageFile $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\StorageFile) {
            throw new InvalidArgumentException(sprintf('The StorageFile property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\StorageFile, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->StorageFile[] = $item;
        
        return $this;
    }
}
