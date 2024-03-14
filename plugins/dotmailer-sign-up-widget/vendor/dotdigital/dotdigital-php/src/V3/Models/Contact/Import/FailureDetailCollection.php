<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact\Import;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Collection;
class FailureDetailCollection extends Collection
{
    /**
     * @return string|null
     */
    protected function getExpectedClass() : ?string
    {
        return FailureDetail::class;
    }
}
