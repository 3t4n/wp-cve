<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for UpdatePacketAttributes StructType
 * @subpackage Structs
 */
class UpdatePacketAttributes extends AbstractStructBase
{
    /**
     * The cod
     * Meta information extracted from the WSDL
     * - base: xsd:decimal
     * - fractionDigits: 2
     * - minOccurs: 0
     * @var float|null
     */
    protected ?float $cod = null;
    /**
     * Constructor method for UpdatePacketAttributes
     * @uses UpdatePacketAttributes::setCod()
     * @param float $cod
     */
    public function __construct(?float $cod = null)
    {
        $this
            ->setCod($cod);
    }
    /**
     * Get cod value
     * @return float|null
     */
    public function getCod(): ?float
    {
        return $this->cod;
    }
    /**
     * Set cod value
     * @param float $cod
     * @return \WpifyWoo\PacketeraSDK\StructType\UpdatePacketAttributes
     */
    public function setCod(?float $cod = null): self
    {
        // validation for constraint: float
        if (!is_null($cod) && !(is_float($cod) || is_numeric($cod))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($cod, true), gettype($cod)), __LINE__);
        }
        // validation for constraint: fractionDigits(2)
        if (!is_null($cod) && mb_strlen(mb_substr((string) $cod, false !== mb_strpos((string) $cod, '.') ? mb_strpos((string) $cod, '.') + 1 : mb_strlen((string) $cod))) > 2) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, the value must at most contain 2 fraction digits, %d given', var_export($cod, true), mb_strlen(mb_substr((string) $cod, mb_strpos((string) $cod, '.') + 1))), __LINE__);
        }
        $this->cod = $cod;
        
        return $this;
    }
}
