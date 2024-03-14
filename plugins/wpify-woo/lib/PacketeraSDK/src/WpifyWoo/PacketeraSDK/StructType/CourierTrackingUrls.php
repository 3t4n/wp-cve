<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierTrackingUrls StructType
 * @subpackage Structs
 */
class CourierTrackingUrls extends AbstractStructBase
{
    /**
     * The courierTrackingUrl
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl[]
     */
    protected ?array $courierTrackingUrl = null;
    /**
     * Constructor method for CourierTrackingUrls
     * @uses CourierTrackingUrls::setCourierTrackingUrl()
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl[] $courierTrackingUrl
     */
    public function __construct(?array $courierTrackingUrl = null)
    {
        $this
            ->setCourierTrackingUrl($courierTrackingUrl);
    }
    /**
     * Get courierTrackingUrl value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl[]
     */
    public function getCourierTrackingUrl(): ?array
    {
        return $this->courierTrackingUrl;
    }
    /**
     * This method is responsible for validating the values passed to the setCourierTrackingUrl method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCourierTrackingUrl method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCourierTrackingUrlForArrayConstraintsFromSetCourierTrackingUrl(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $courierTrackingUrlsCourierTrackingUrlItem) {
            // validation for constraint: itemType
            if (!$courierTrackingUrlsCourierTrackingUrlItem instanceof \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl) {
                $invalidValues[] = is_object($courierTrackingUrlsCourierTrackingUrlItem) ? get_class($courierTrackingUrlsCourierTrackingUrlItem) : sprintf('%s(%s)', gettype($courierTrackingUrlsCourierTrackingUrlItem), var_export($courierTrackingUrlsCourierTrackingUrlItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The courierTrackingUrl property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set courierTrackingUrl value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl[] $courierTrackingUrl
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls
     */
    public function setCourierTrackingUrl(?array $courierTrackingUrl = null): self
    {
        // validation for constraint: array
        if ('' !== ($courierTrackingUrlArrayErrorMessage = self::validateCourierTrackingUrlForArrayConstraintsFromSetCourierTrackingUrl($courierTrackingUrl))) {
            throw new InvalidArgumentException($courierTrackingUrlArrayErrorMessage, __LINE__);
        }
        $this->courierTrackingUrl = $courierTrackingUrl;
        
        return $this;
    }
    /**
     * Add item to courierTrackingUrl value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls
     */
    public function addToCourierTrackingUrl(\WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl) {
            throw new InvalidArgumentException(sprintf('The courierTrackingUrl property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->courierTrackingUrl[] = $item;
        
        return $this;
    }
}
