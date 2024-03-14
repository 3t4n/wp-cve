<?php

namespace OctolizeShippingNoticesVendor\Psr\Clock;

use DateTimeImmutable;
interface ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable Object
     */
    public function now() : \DateTimeImmutable;
}
