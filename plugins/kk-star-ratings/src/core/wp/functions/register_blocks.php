<?php

/*
 * This file is part of bhittani/kk-star-ratings.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\StarRating\core\wp\functions;

use function Bhittani\StarRating\core\functions\filter;
use function Bhittani\StarRating\functions\block;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

function register_blocks(): void
{
    $blocks = array_filter(filter('blocks', []));

    if ($blocks) {
        $isDebugMode = defined('WP_DEBUG') && WP_DEBUG;

        $jsBlockApiScriptSlug = kksr('slug').'-blocks';

        wp_register_script(
            $jsBlockApiScriptSlug,
            kksr('core.url').'public/js/blocks'
                .($isDebugMode ? '' : '.min').'.js',
            ['wp-blocks'],
            kksr('version')
        );

        $payloads = [];

        foreach ($blocks as $block) {
            $payload = block($block);

            if ($payload) {
                $payloads[$payload['name']] = $payload;
            }
        }

        wp_localize_script(
            $jsBlockApiScriptSlug,
            preg_replace('/[^\w]+/', '_', $jsBlockApiScriptSlug),
            $payloads
        );
    }
}
