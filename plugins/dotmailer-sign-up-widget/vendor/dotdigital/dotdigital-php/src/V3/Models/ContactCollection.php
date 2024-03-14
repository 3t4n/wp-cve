<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Collection;
class ContactCollection extends Collection
{
    protected function getExpectedClass() : ?string
    {
        return Contact::class;
    }
}
