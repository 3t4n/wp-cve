<?php

spl_autoload_register(function ($fileName) {
    $filePath = explode('\\', $fileName);

    if (!isset($filePath)) {
        return;
    }

    $index = count($filePath) - 1;
    $classFile = $filePath[$index] . '.php';

    $qualifiedPath = trailingslashit(dirname(dirname(__FILE__)));

    for ($i = 1; $i < $index; $i++) {
        $qualifiedPath .= trailingslashit($filePath[$i]);
    }

    $qualifiedPath .= $classFile;

    if (file_exists($qualifiedPath)) {
        include_once $qualifiedPath;
    }
});
