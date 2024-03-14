<?php

namespace Modular\ConnectorDependencies\Illuminate\Support;

use Modular\ConnectorDependencies\Carbon\Carbon as BaseCarbon;
use Modular\ConnectorDependencies\Carbon\CarbonImmutable as BaseCarbonImmutable;
/** @internal */
class Carbon extends BaseCarbon
{
    /**
     * {@inheritdoc}
     */
    public static function setTestNow($testNow = null)
    {
        BaseCarbon::setTestNow($testNow);
        BaseCarbonImmutable::setTestNow($testNow);
    }
}
