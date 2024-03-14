<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations\Finder;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
interface MigrationFinder
{
    /**
     * @param string $directory
     * @return class-string<AbstractMigration>[]
     */
    public function find_migrations(string $directory) : array;
}
