<?php

namespace LassoLiteVendor;

// Don't redefine the functions if included multiple times.
if (!\function_exists('LassoLiteVendor\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
