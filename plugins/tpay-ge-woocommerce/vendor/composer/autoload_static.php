<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcd70912fad1e275c7d0c238a6a29414c
{
    public static $classMap = array (
        'PlugandPay\\TBC_Checkout\\Extras' => __DIR__ . '/../..' . '/includes/class-extras.php',
        'PlugandPay\\TBC_Checkout\\Gateway' => __DIR__ . '/../..' . '/includes/class-gateway.php',
        'PlugandPay\\TBC_Checkout\\Order_Actions' => __DIR__ . '/../..' . '/includes/class-order-actions.php',
        'PlugandPay\\TBC_Checkout\\Plugin_Factory' => __DIR__ . '/../..' . '/includes/class-plugin-factory.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitcd70912fad1e275c7d0c238a6a29414c::$classMap;

        }, null, ClassLoader::class);
    }
}
