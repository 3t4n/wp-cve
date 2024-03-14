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
 * @param array<string,mixed> $payload
 */
function block_5_3_4(string $namespace, array $payload = [], string $slug = null): string
{
    $slug = $slug ?: ($payload['slug'] ?? trim(preg_replace('/[^\w]+/', '_', $namespace), '_'));

    wp_register_script(
        $slug,
        $payload['script'],
        $payload['dependencies'] ?? [],
        $payload['version'] ?? kksr('version')
    );

    register_block_type($namespace, array_filter([
        'api_version' => 2,
        'attributes' => $payload['attributes'] ?? [],
        'editor_script' => $slug,
        'render_callback' => $payload['block'] ?? null,
    ]));

    return $slug;
}
