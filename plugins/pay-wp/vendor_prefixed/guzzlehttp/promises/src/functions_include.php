<?php

namespace WPPayVendor;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WPPayVendor\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
