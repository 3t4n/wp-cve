<?php

namespace FluentConnect\App\Hooks\Handlers;

use FluentConnect\Database\DBMigrator;
use FluentConnect\Database\DBSeeder;

class ActivationHandler
{
    public function handle($network_wide = false)
    {
        DBMigrator::run($network_wide);
        DBSeeder::run();
    }
}
