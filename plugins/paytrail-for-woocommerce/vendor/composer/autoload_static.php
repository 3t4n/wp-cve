<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit401940c81e518b55e2a7f7b15d75b125
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tests\\' => 6,
        ),
        'P' => 
        array (
            'Paytrail\\WooCommercePaymentGateway\\' => 35,
            'Paytrail\\SDK\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/paytrail/paytrail-php-sdk/tests',
        ),
        'Paytrail\\WooCommercePaymentGateway\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Paytrail\\SDK\\' => 
        array (
            0 => __DIR__ . '/..' . '/paytrail/paytrail-php-sdk/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit401940c81e518b55e2a7f7b15d75b125::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit401940c81e518b55e2a7f7b15d75b125::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit401940c81e518b55e2a7f7b15d75b125::$classMap;

        }, null, ClassLoader::class);
    }
}
