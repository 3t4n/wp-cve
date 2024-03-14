<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\WPDesk\Migrations\Finder\GlobFinder;
use ShopMagicVendor\WPDesk\Migrations\Version\AlphabeticalComparator;
use ShopMagicVendor\WPDesk\Migrations\Version\Comparator;
use ShopMagicVendor\WPDesk\Migrations\Version\Version;
use ShopMagicVendor\WPDesk\Migrations\Version\WpdbMigrationFactory;
class WpdbMigrator implements Migrator
{
    /** @var \wpdb */
    private $wpdb;
    /** @var MigrationsRepository */
    private $migrations_repository;
    /** @var Comparator */
    private $comparator;
    /** @var LoggerInterface */
    private $logger;
    /** @var string */
    private $option_name;
    /** @param string[] $migration_directories */
    public static function from_directories(array $migration_directories, string $option_name) : self
    {
        global $wpdb;
        $logger = new WpdbLogger($option_name . '_log');
        return new self($wpdb, $option_name, new FilesystemMigrationsRepository($migration_directories, new GlobFinder(), new WpdbMigrationFactory($wpdb, $logger), new AlphabeticalComparator()), new AlphabeticalComparator(), $logger);
    }
    /** @param class-string<AbstractMigration>[] $migration_class_names */
    public static function from_classes(array $migration_class_names, string $option_name) : self
    {
        global $wpdb;
        $logger = new WpdbLogger($option_name . '_log');
        return new self($wpdb, $option_name, new ArrayMigrationsRepository($migration_class_names, new WpdbMigrationFactory($wpdb, $logger), new AlphabeticalComparator()), new AlphabeticalComparator(), $logger);
    }
    public function __construct(\wpdb $wpdb, string $option_name, MigrationsRepository $migrations_repository, Comparator $comparator, LoggerInterface $logger)
    {
        $this->wpdb = $wpdb;
        $this->option_name = $option_name;
        $this->migrations_repository = $migrations_repository;
        $this->comparator = $comparator;
        $this->logger = $logger;
    }
    private function get_current_version() : Version
    {
        return new Version(get_option($this->option_name, ''));
    }
    private function needs_migration() : bool
    {
        $migrations = $this->migrations_repository->get_migrations();
        $last_migration = \end($migrations);
        if ($last_migration === \false) {
            return \false;
        }
        if ($this->comparator->compare($last_migration->get_version(), $this->get_current_version())) {
            return \true;
        }
        return \false;
    }
    public function migrate() : void
    {
        require_once \ABSPATH . 'wp-admin/includes/upgrade.php';
        if (!$this->needs_migration()) {
            return;
        }
        $this->logger->info('DB update start');
        try {
            $this->do_migrate();
        } catch (\Throwable $e) {
            // @phpstan-ignore-next-line
            $error_msg = \sprintf('Error while upgrading a database: %s', $this->wpdb->last_error);
            $this->logger->error($error_msg);
            \trigger_error(\esc_html($error_msg), \E_USER_WARNING);
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
        }
        $this->logger->info('DB update finished');
    }
    private function do_migrate() : void
    {
        $current_version = $this->get_current_version();
        foreach ($this->migrations_repository->get_migrations() as $migration) {
            if ($this->comparator->compare($migration->get_version(), $this->get_current_version()) > 0) {
                $this->logger->info(\sprintf('DB update %s:%s', $current_version, $migration->get_version()));
                $success = $migration->get_migration()->up();
                if ($success) {
                    $this->logger->info(\sprintf('DB update %s:%s -> ', $current_version, $migration->get_version()) . 'OK');
                    update_option($this->option_name, (string) $migration->get_version(), \true);
                } else {
                    throw new \RuntimeException();
                }
            }
        }
    }
}
