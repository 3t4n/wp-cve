<?php

namespace WPCal\GoogleAPI;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WPCal\\GoogleAPI\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
