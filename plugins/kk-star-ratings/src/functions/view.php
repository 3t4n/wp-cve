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

use InvalidArgumentException;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

/**
 * Render a view template.
 *
 * Since 5.3.1, a slug is also accepted which would cause an error
 * for third party code that relied on the previous definition.
 * To mitigate the error, we check on the 4th parameter for null.
 *
 * @param string|array|null $pathOrPayload
 *
 * @throws InvalidArgumentException if the template can not be located
 */
function view(?string $slugOrBase, string $baseOrPath, $pathOrPayload = null, array $payload = null): string
{
    $__slug = $slugOrBase;
    $__base = $baseOrPath;
    $__path = $pathOrPayload;
    $__payload = $payload;

    if (is_null($payload)) {
        $__slug = null;
        $__base = $slugOrBase;
        $__path = $baseOrPath;
        $__payload = $pathOrPayload;
    }

    unset($slugOrBase, $baseOrPath, $pathOrPayload, $payload);

    $resolve = function (?string $slug, string $base, string $path): string {
        if (is_file($path)) {
            return $path;
        }

        $path = ltrim($path, '\/');
        $directory = kksr('slug');
        if ($slug && ($sanitizedSlug = ltrim(strip_prefix($slug, kksr('nick')), '-'))) {
            $directory .= "/{$sanitizedSlug}";
        }
        $parentTheme = get_template_directory()."/{$directory}/{$path}";
        $childTheme = get_stylesheet_directory()."/{$directory}/{$path}";

        if (is_file($childTheme)) {
            return $childTheme;
        }

        if (is_file($parentTheme)) {
            return $parentTheme;
        }

        $template = "{$base}/{$path}";

        if (is_file($template)) {
            return $template;
        }

        throw new InvalidArgumentException("The template '{$path}' could not be located at '{$template}'");
    };

    $__view = function (string $path, array $payload = []) use ($__payload, $__slug, $__base) {
        return view($__slug, $__base, $path, array_merge($__payload, $payload));
    };
    $__kksr = 'kksr';
    $__base = rtrim($__base, '\/');
    $__dusk = kksr('functions.dusk_attr');
    $__template = $resolve($__slug, $__base, $__path);

    unset($resolve);

    extract($__payload);

    ob_start();

    require $__template;

    return ob_get_clean();
}
