<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Collection;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact\Preference;
class PreferenceCollection extends Collection
{
    protected function getExpectedClass() : ?string
    {
        return Preference::class;
    }
}
