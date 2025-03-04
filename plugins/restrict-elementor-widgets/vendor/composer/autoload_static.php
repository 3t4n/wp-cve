<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit42540594b80d8ebf804915257e38f522
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Codexpert\\Restrict_Elementor_Widgets\\' => 37,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Codexpert\\Restrict_Elementor_Widgets\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Codexpert\\Plugin\\Base' => __DIR__ . '/..' . '/codexpert/plugin/src/Base.php',
        'Codexpert\\Plugin\\Fields' => __DIR__ . '/..' . '/codexpert/plugin/src/Fields.php',
        'Codexpert\\Plugin\\Metabox' => __DIR__ . '/..' . '/codexpert/plugin/src/Metabox.php',
        'Codexpert\\Plugin\\Notice' => __DIR__ . '/..' . '/codexpert/plugin/src/Notice.php',
        'Codexpert\\Plugin\\Settings' => __DIR__ . '/..' . '/codexpert/plugin/src/Settings.php',
        'Codexpert\\Plugin\\Setup' => __DIR__ . '/..' . '/codexpert/plugin/src/Setup.php',
        'Codexpert\\Plugin\\Table' => __DIR__ . '/..' . '/codexpert/plugin/src/Table.php',
        'Codexpert\\Plugin\\Widget' => __DIR__ . '/..' . '/codexpert/plugin/src/Widget.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Pluggable\\Marketing\\Deactivator' => __DIR__ . '/..' . '/pluggable/marketing/src/Deactivator.php',
        'Pluggable\\Marketing\\Feature' => __DIR__ . '/..' . '/pluggable/marketing/src/Feature.php',
        'Pluggable\\Marketing\\Survey' => __DIR__ . '/..' . '/pluggable/marketing/src/Survey.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit42540594b80d8ebf804915257e38f522::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit42540594b80d8ebf804915257e38f522::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit42540594b80d8ebf804915257e38f522::$classMap;

        }, null, ClassLoader::class);
    }
}
