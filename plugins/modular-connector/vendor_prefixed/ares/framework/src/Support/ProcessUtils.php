<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Support;

use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Modular\ConnectorDependencies\Symfony\Component\Finder\Finder;
/** @internal */
class ProcessUtils
{
    /**
     * Reucrsively get all classes in the given directory
     *
     * @param string $directory
     * @param string $namespace
     * @param array $excluded
     * @return array
     */
    public static function loadClasses(string $directory, string $namespace) : Collection
    {
        $files = \Modular\ConnectorDependencies\collect();
        try {
            $tmp = (new Finder())->in($directory)->files();
        } catch (DirectoryNotFoundException $e) {
            return $files;
        }
        $namespace = \rtrim($namespace, '\\');
        foreach ($tmp as $command) {
            if (!$command->getRelativePath()) {
                $className = $namespace . '\\' . $command->getFilenameWithoutExtension();
            } else {
                $path = \str_replace('/', '\\', $command->getRelativePath());
                $className = $namespace . '\\' . $path . '\\' . $command->getFilenameWithoutExtension();
            }
            if (!\class_exists($className)) {
                continue;
            }
            $reflectionClass = new \ReflectionClass($className);
            if (!($reflectionClass->isInterface() || $reflectionClass->isAbstract() || $reflectionClass->isTrait())) {
                $files->push($className);
            }
        }
        return $files;
    }
}
