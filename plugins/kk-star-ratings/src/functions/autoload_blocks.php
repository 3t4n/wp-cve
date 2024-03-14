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

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

use DirectoryIterator;

/** @return array<string,array<string,mixed>> */
function autoload_blocks(string $path): array
{
    if (! is_dir($path)) {
        return [];
    }

    $autoloads = [];

    foreach (new DirectoryIterator($path) as $fileInfo) {
        if (! ($fileInfo->isDot() || $fileInfo->isFile())) {
            $block = autoload_block($fileInfo->getPathname());
            $autoloads[$block['name']] = $block;
        }
    }

    return $autoloads;
}
