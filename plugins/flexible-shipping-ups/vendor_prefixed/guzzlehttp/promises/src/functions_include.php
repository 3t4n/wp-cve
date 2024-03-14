<?php

namespace UpsFreeVendor;

// Don't redefine the functions if included multiple times.
if (!\function_exists('UpsFreeVendor\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
