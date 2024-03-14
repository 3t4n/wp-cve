<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations;

interface Migrator
{
    public function migrate() : void;
}
