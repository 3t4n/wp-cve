<?php

namespace Modular\ConnectorDependencies;

// Don't redefine the functions if included multiple times.
if (!\function_exists('Modular\\ConnectorDependencies\\GuzzleHttp\\describe_type')) {
    require __DIR__ . '/functions.php';
}
