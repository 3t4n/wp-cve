<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3898dc134a7dac7168eb79eb691cc186
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TimothyBJacobs\\WPMailDebugger\\' => 30,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TimothyBJacobs\\WPMailDebugger\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3898dc134a7dac7168eb79eb691cc186::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3898dc134a7dac7168eb79eb691cc186::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3898dc134a7dac7168eb79eb691cc186::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
