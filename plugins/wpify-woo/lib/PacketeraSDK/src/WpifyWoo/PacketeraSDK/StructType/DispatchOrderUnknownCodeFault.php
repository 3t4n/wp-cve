<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DispatchOrderUnknownCodeFault StructType
 * Meta information extracted from the WSDL
 * - type: tns:DispatchOrderUnknownCodeFault
 * @subpackage Structs
 */
class DispatchOrderUnknownCodeFault extends AbstractStructBase
{
    /**
     * The codes
     * @var \WpifyWoo\PacketeraSDK\StructType\Codes|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Codes $codes = null;
    /**
     * Constructor method for DispatchOrderUnknownCodeFault
     * @uses DispatchOrderUnknownCodeFault::setCodes()
     * @param \WpifyWoo\PacketeraSDK\StructType\Codes $codes
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\Codes $codes = null)
    {
        $this
            ->setCodes($codes);
    }
    /**
     * Get codes value
     * @return \WpifyWoo\PacketeraSDK\StructType\Codes|null
     */
    public function getCodes(): ?\WpifyWoo\PacketeraSDK\StructType\Codes
    {
        return $this->codes;
    }
    /**
     * Set codes value
     * @param \WpifyWoo\PacketeraSDK\StructType\Codes $codes
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault
     */
    public function setCodes(?\WpifyWoo\PacketeraSDK\StructType\Codes $codes = null): self
    {
        $this->codes = $codes;
        
        return $this;
    }
}
