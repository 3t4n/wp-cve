<?php

namespace ShopMagicVendor\WPDesk\Migrations\Version;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
interface MigrationFactory
{
    public function create_version(string $migration_class) : AbstractMigration;
}
