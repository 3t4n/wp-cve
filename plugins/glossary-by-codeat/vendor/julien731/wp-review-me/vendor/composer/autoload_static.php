<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit998e3602ec0da726cb246c55bdc36177
{
    public static $files = array (
        'c14057a02afc95b84dc5bf85d98c5b66' => __DIR__ . '/..' . '/julien731/wp-dismissible-notices-handler/handler.php',
        'a50f2d2ba04e0c6b552331bf2bdeba41' => __DIR__ . '/../..' . '/review.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit998e3602ec0da726cb246c55bdc36177::$classMap;

        }, null, ClassLoader::class);
    }
}
