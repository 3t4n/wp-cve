<?php

namespace WPCal\ComposerPackages;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WPCal\\ComposerPackages\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
