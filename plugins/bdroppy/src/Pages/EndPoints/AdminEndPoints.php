<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\Init\Core;

class AdminEndPoints
{
    protected $core;

    public function __construct(Core $core)
    {
        $this->core = $core;
        new DashboardEndPoints($core);
        new OrderEndPoints($core);
        new LoginEndPoints($core);
        new CatalogEndPoints($core);
        new CategoryMappingEndPoints($core);
        new SettingEndPoints($core);
    }

}