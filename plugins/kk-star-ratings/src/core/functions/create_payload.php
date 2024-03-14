<?php

/*
 * This file is part of bhittani/kk-star-ratings.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\StarRating\core\functions;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

/**
 * @param int|string|array|object|WP_POST|null $idOrPostOrPayload
 *
 * @return array<string,mixed>
 */
function create_payload($idOrPostOrPayload = null): array
{
    $payload = [];

    if (is_array($idOrPostOrPayload)) {
        $payload = $idOrPostOrPayload;
    } elseif (is_object($idOrPostOrPayload)) {
        $payload['id'] = $idOrPostOrPayload->ID;
    } elseif ($idOrPostOrPayload) {
        $payload['id'] = $idOrPostOrPayload;
    }

    return $payload;
}
