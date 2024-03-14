<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for codes StructType
 * @subpackage Structs
 */
class Codes extends AbstractStructBase
{
    /**
     * The code
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var string[]
     */
    protected array $code;
    /**
     * Constructor method for codes
     * @uses Codes::setCode()
     * @param string[] $code
     */
    public function __construct(array $code)
    {
        $this
            ->setCode($code);
    }
    /**
     * Get code value
     * @return string[]
     */
    public function getCode(): array
    {
        return $this->code;
    }
    /**
     * This method is responsible for validating the values passed to the setCode method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCode method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCodeForArrayConstraintsFromSetCode(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $codesCodeItem) {
            // validation for constraint: itemType
            if (!is_string($codesCodeItem)) {
                $invalidValues[] = is_object($codesCodeItem) ? get_class($codesCodeItem) : sprintf('%s(%s)', gettype($codesCodeItem), var_export($codesCodeItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The code property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set code value
     * @throws InvalidArgumentException
     * @param string[] $code
     * @return \WpifyWoo\PacketeraSDK\StructType\Codes
     */
    public function setCode(array $code): self
    {
        // validation for constraint: array
        if ('' !== ($codeArrayErrorMessage = self::validateCodeForArrayConstraintsFromSetCode($code))) {
            throw new InvalidArgumentException($codeArrayErrorMessage, __LINE__);
        }
        $this->code = $code;
        
        return $this;
    }
    /**
     * Add item to code value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\Codes
     */
    public function addToCode(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The code property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->code[] = $item;
        
        return $this;
    }
}
