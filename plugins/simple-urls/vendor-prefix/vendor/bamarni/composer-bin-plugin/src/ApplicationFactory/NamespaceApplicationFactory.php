<?php

declare (strict_types=1);
namespace LassoLiteVendor\Bamarni\Composer\Bin\ApplicationFactory;

use LassoLiteVendor\Composer\Console\Application;
interface NamespaceApplicationFactory
{
    public function create(Application $existingApplication) : Application;
}
