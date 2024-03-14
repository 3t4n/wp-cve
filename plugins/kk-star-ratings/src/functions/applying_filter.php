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

/**
 * @param string|array<string,bool> $filter
 *
 * @return bool|null
 */
function applying_filter($filter)
{
    if (! is_array($filter)) {
        return (bool) kksr("_.filters.{$filter}");
    }

    foreach ($filter as $tag => $bool) {
        kksr(["_.filters.{$tag}" => (bool) $bool]);
    }
}
