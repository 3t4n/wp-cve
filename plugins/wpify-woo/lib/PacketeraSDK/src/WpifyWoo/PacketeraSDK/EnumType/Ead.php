<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\EnumType;

use WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for ead EnumType
 * @subpackage Enumerations
 */
class Ead extends AbstractStructEnumBase
{
    /**
     * Constant for value 'own'
     * @return string 'own'
     */
    const VALUE_OWN = 'own';
    /**
     * Constant for value 'create'
     * @return string 'create'
     */
    const VALUE_CREATE = 'create';
    /**
     * Constant for value 'carrier'
     * @return string 'carrier'
     */
    const VALUE_CARRIER = 'carrier';
    /**
     * Return allowed values
     * @uses self::VALUE_OWN
     * @uses self::VALUE_CREATE
     * @uses self::VALUE_CARRIER
     * @return string[]
     */
    public static function getValidValues(): array
    {
        return [
            self::VALUE_OWN,
            self::VALUE_CREATE,
            self::VALUE_CARRIER,
        ];
    }
}
