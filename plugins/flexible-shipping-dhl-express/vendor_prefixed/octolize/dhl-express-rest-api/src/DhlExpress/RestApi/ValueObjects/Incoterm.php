<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException;
class Incoterm
{
    private const ALLOWED_KEYWORDS = [
        'EXW',
        // ExWorks
        'FCA',
        // Free Carrier
        'CPT',
        // Carriage Paid To
        'CIP',
        // Carriage and Insurance Paid To
        'DPU',
        // Delivered at Place Unloaded
        'DAP',
        // Delivered at Place
        'DDP',
        // Delivered Duty Paid
        'FAS',
        // Free Alongside Ship
        'FOB',
        // Free on Board
        'CFR',
        // Cost and Freight
        'CIF',
    ];
    private string $incoterm;
    /**
     * @param string $incoterm
     * @throws InvalidArgumentException
     */
    public function __construct(string $incoterm)
    {
        $this->incoterm = $incoterm;
        $this->validate($this->incoterm);
    }
    private function validate(string $incoterm) : void
    {
        if (!\in_array($incoterm, self::ALLOWED_KEYWORDS, \true)) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException("Wrong Incoterm used. Allowed terms: " . \implode(', ', self::ALLOWED_KEYWORDS));
        }
    }
    public function __toString() : string
    {
        return $this->incoterm;
    }
}
