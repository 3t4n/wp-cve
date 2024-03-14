<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException;
class CustomerTypeCode
{
    private const ALLOWED_KEYWORDS = ["business", "direct_consumer", "government", "other", "private", "reseller"];
    private string $typeCode;
    /**
     * @param string $typeCode
     * @throws InvalidArgumentException
     */
    public function __construct(string $typeCode)
    {
        $this->typeCode = $typeCode;
        $this->validate($this->typeCode);
    }
    private function validate(string $typeCode) : void
    {
        if (!\in_array($typeCode, self::ALLOWED_KEYWORDS, \true)) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException("Wrong customer type code used. Allowed terms: " . \implode(', ', self::ALLOWED_KEYWORDS));
        }
    }
    public function __toString() : string
    {
        return $this->typeCode;
    }
}
