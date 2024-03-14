<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for IsLiftagoAvailableDetail StructType
 * @subpackage Structs
 */
class IsLiftagoAvailableDetail extends AbstractStructBase
{
    /**
     * The liftagoAvailable
     * @var bool|null
     */
    protected ?bool $liftagoAvailable = null;
    /**
     * Constructor method for IsLiftagoAvailableDetail
     * @uses IsLiftagoAvailableDetail::setLiftagoAvailable()
     * @param bool $liftagoAvailable
     */
    public function __construct(?bool $liftagoAvailable = null)
    {
        $this
            ->setLiftagoAvailable($liftagoAvailable);
    }
    /**
     * Get liftagoAvailable value
     * @return bool|null
     */
    public function getLiftagoAvailable(): ?bool
    {
        return $this->liftagoAvailable;
    }
    /**
     * Set liftagoAvailable value
     * @param bool $liftagoAvailable
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailableDetail
     */
    public function setLiftagoAvailable(?bool $liftagoAvailable = null): self
    {
        // validation for constraint: boolean
        if (!is_null($liftagoAvailable) && !is_bool($liftagoAvailable)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($liftagoAvailable, true), gettype($liftagoAvailable)), __LINE__);
        }
        $this->liftagoAvailable = $liftagoAvailable;
        
        return $this;
    }
}
