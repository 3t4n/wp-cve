<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit6c48ce284123a1fe576616497b92a8f9
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

        spl_autoload_register(array('ComposerAutoloaderInit6c48ce284123a1fe576616497b92a8f9', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit6c48ce284123a1fe576616497b92a8f9', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit6c48ce284123a1fe576616497b92a8f9::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
