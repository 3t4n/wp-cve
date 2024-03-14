<?php

namespace {
    // Don't redefine the functions if included multiple times.
    if (!\function_exists('Isolated\\Blue_Media\\Isolated_Guzzlehttp\\GuzzleHttp\\Promise\\promise_for')) {
        require __DIR__ . '/functions.php';
    }
}
