<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\V2\Resources;

use Dotdigital_WordPress_Vendor\Dotdigital\Resources\AbstractResource;
use Dotdigital_WordPress_Vendor\Dotdigital\V2\Models\DataFieldList;
class DataFields extends AbstractResource
{
    public const RESOURCE_BASE = '/data-fields';
    /**
     * @return DataFieldList
     * @throws \Http\Client\Exception
     * @throws \Exception
     */
    public function show()
    {
        return new DataFieldList($this->get(self::RESOURCE_BASE));
    }
}
