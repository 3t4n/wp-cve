<?php

namespace WpifyWooDeps;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WpifyWooDeps\\GuzzleHttp\\describe_type')) {
    require __DIR__ . '/functions.php';
}
