<?php

namespace WilokeEmailCreator\Dashboard\Shared;


use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

trait GeneralHelper
{
    protected string $dashboardSlug = 'dashboard';
    protected string $authSlug      = 'auth-settings';

    protected function getDashboardSlug(): string
    {
        return strtolower(AutoPrefix::namePrefix($this->dashboardSlug));
    }
}
