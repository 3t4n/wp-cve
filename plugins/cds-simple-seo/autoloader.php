<?php

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

spl_autoload_register(function ($class) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) .'.php';
	if (file_exists($file)) {
        require $file;
    }
});

?>