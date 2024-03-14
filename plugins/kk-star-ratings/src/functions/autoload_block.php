<?php

/*
 * This file is part of bhittani/kk-star-ratings.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\StarRating\functions;

use Closure;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

/** @return array<string,mixed> */
function autoload_block(string $fileOrDir): array
{
    if (! file_exists($fileOrDir)) {
        // Neither a block file nor a directory.
        return [];
    }

    $path = rtrim($fileOrDir, '\/').'/';
    $blockFile = "{$path}block.php";

    if (is_file($fileOrDir)) {
        $path = dirname($fileOrDir).'/';
        $blockFile = $fileOrDir;
    }

    if (! is_file($blockFile)) {
        // Could not locate the block file.
        return [];
    }

    $block = require $blockFile;
    $payload = $block;

    if ($block instanceof Closure) {
        $isDebugMode = defined('WP_DEBUG') && WP_DEBUG;
        $payload = $block($isDebugMode);
    }

    if (! $payload) {
        // Could not locate the block.php file.
        return [];
    }

    if (array_diff(['name', 'editor_script'], array_keys($payload))) {
        // The required keys are not available.
        return [];
    }

    if (strpos($payload['name'], '/') === false) {
        $payload['name'] = prefix($payload['name'], kksr('slug').'/');
    }

    $payload['attributes'] = ($payload['attributes'] ?? [])
        + autoload_array("{$path}attributes.php");

    $payload['data'] = ($payload['data'] ?? [])
        + autoload_array("{$path}data.php");

    $payload['meta'] = ($payload['meta'] ?? [])
        + autoload_array("{$path}meta.php");

    if (! isset($payload['render'])) {
        $renderFile = "{$path}render.php";

        if (is_file($renderFile)) {
            $payload['render'] = require $renderFile;
        }
    }

    return $payload;
}
