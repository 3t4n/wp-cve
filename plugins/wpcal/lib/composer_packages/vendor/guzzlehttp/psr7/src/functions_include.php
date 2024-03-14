<?php

namespace WPCal\ComposerPackages;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WPCal\\ComposerPackages\\GuzzleHttp\\Psr7\\str')) {
    require __DIR__ . '/functions.php';
}
