<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit94455ba3b32d57a23bf4d4877520ef4e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit94455ba3b32d57a23bf4d4877520ef4e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit94455ba3b32d57a23bf4d4877520ef4e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit94455ba3b32d57a23bf4d4877520ef4e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
