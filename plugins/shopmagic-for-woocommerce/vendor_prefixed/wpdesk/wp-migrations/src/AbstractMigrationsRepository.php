<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations;

use ShopMagicVendor\WPDesk\Migrations\Version\Comparator;
use ShopMagicVendor\WPDesk\Migrations\Version\MigrationFactory;
use ShopMagicVendor\WPDesk\Migrations\Version\Version;
abstract class AbstractMigrationsRepository implements MigrationsRepository
{
    /** @var MigrationFactory */
    private $version_factory;
    /** @var AvailableMigration[] */
    protected $migrations = [];
    /** @var string[] */
    protected $migrations_source;
    /** @var Comparator */
    private $comparator;
    /**
     * @param string[]  $migrations_source
     */
    public function __construct(array $migrations_source, MigrationFactory $version_factory, Comparator $comparator)
    {
        $this->version_factory = $version_factory;
        $this->comparator = $comparator;
        $this->migrations_source = $migrations_source;
    }
    public function register_migration(string $migration_class_name) : void
    {
        $migration = $this->version_factory->create_version($migration_class_name);
        $version = new Version($migration_class_name);
        $this->migrations[(string) $version] = new AvailableMigration($version, $migration);
    }
    /** @return iterable<AvailableMigration> */
    public function get_migrations() : iterable
    {
        $this->load_migrations();
        $migrations = $this->migrations;
        \usort($migrations, function (AvailableMigration $a, AvailableMigration $b) : int {
            return $this->comparator->compare($a->get_version(), $b->get_version());
        });
        return $migrations;
    }
    protected abstract function load_migrations() : void;
}
