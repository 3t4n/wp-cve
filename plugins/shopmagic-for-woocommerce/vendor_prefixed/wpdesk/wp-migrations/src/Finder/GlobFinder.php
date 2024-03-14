<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations\Finder;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
final class GlobFinder implements MigrationFinder
{
    public function find_migrations(string $directory) : array
    {
        $dir = \realpath($directory);
        if ($dir === \false) {
            return [];
        }
        $files = \glob(\rtrim($dir, '/') . '/Version*.php');
        if ($files === \false) {
            $files = [];
        }
        return $this->load_migrations($files);
    }
    /**
     * @param string[] $files
     *
     * @return class-string<AbstractMigration>[]
     * @throws \ReflectionException
     */
    private function load_migrations(array $files) : array
    {
        $included_files = [];
        foreach ($files as $file) {
            require_once $file;
            $real_file = \realpath($file);
            if (!$real_file) {
                continue;
            }
            $included_files[] = $real_file;
        }
        $classes = $this->load_migration_classes($included_files);
        $versions = [];
        foreach ($classes as $class) {
            $versions[] = $class->getName();
        }
        return $versions;
    }
    /**
     * @param string[] $included_files
     * @return \ReflectionClass<AbstractMigration>[]
     * @throws \ReflectionException
     */
    private function load_migration_classes(array $included_files) : array
    {
        $classes = [];
        foreach (\get_declared_classes() as $class) {
            $r = new \ReflectionClass($class);
            if (!$r->isSubclassOf(AbstractMigration::class)) {
                continue;
            }
            if (\in_array($r->getFileName(), $included_files, \true)) {
                $classes[] = $r;
            }
        }
        return $classes;
    }
}
