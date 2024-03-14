<?php

namespace Modular\ConnectorDependencies;

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */
$uri = \urldecode(\parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH));
// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
$dir = __DIR__ . '/../../../' . \trim($uri, '/');
if ($uri !== '/' && !\is_dir($dir) && \file_exists($dir)) {
    return \false;
}
$path = \explode('/', \trim($_SERVER['PHP_SELF'], '/'))[0];
$load = \sprintf(__DIR__ . '/../../../%s/index.php', $path);
if (!\file_exists($load)) {
    return \false;
}
require_once $load;
