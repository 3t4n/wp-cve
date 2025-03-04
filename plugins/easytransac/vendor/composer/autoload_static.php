<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitda4f1f60331b1408697aac7cba711f48
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EasyTransac\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EasyTransac\\' => 
        array (
            0 => __DIR__ . '/..' . '/easytransac/easytransac-sdk-php/sdk/EasyTransac',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitda4f1f60331b1408697aac7cba711f48::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitda4f1f60331b1408697aac7cba711f48::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitda4f1f60331b1408697aac7cba711f48::$classMap;

        }, null, ClassLoader::class);
    }
}
