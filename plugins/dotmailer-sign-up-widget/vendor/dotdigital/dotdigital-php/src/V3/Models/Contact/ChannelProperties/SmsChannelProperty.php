<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact\ChannelProperties;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\AbstractSingletonModel;
class SmsChannelProperty extends AbstractSingletonModel
{
    /**
     * @var string|null
     */
    protected ?string $optInType;
    /**
     * @var string|null
     */
    protected ?string $status;
    /**
     * @var string|null
     */
    protected ?string $countryCode;
}
