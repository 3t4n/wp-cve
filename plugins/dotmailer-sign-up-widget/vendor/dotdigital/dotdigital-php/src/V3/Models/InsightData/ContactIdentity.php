<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\InsightData;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\AbstractSingletonModel;
class ContactIdentity extends AbstractSingletonModel
{
    /**
     * @var string
     */
    protected string $identifier;
    /**
     * @var string
     */
    protected string $value;
}
