<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Collection;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact\ConsentRecord;
class ConsentRecordCollection extends Collection
{
    protected function getExpectedClass() : ?string
    {
        return ConsentRecord::class;
    }
}
