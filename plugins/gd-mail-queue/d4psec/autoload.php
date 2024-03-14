<?php

define('D4P_DEFUSE_CRYPTO_BASEDIR', __DIR__.'/encryption/');

require_once(__DIR__.'/compatibility/random.php');

spl_autoload_register(function($class) {
    $prefix = 'Defuse\\Crypto';

    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = D4P_DEFUSE_CRYPTO_BASEDIR.str_replace(array('\\', '_'), '/', $relative_class).'.php';

    if (file_exists($file)) {
        require $file;
    }
});
