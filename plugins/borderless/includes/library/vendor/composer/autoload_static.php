<?php

namespace Composer\Autoload;

class ComposerStaticIniteBorderlessLibraryImporter
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LIBRARY\\' => 5,
        ),
        'A' => 
        array (
            'AwesomeMotive\\WPContentImporter2\\' => 33,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LIBRARY\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
        'AwesomeMotive\\WPContentImporter2\\' => 
        array (
            0 => __DIR__ . '/..' . '/awesomemotive/wp-content-importer-v2/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteBorderlessLibraryImporter::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteBorderlessLibraryImporter::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
