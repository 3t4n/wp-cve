<?php

namespace WPCal\GoogleAPI;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WPCal\\GoogleAPI\\GuzzleHttp\\uri_template')) {
    require __DIR__ . '/functions.php';
}
