<?php

/*
 * This file is part of bhittani/kk-star-ratings.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use function Bhittani\StarRating\functions\applying_filter;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

kksr(['core' => require __DIR__.'/config.php']);

require_once __DIR__.'/hooks.php';
require_once __DIR__.'/hydrate.php';

/* ===============================================================
We hook a deactivation here instead of hydrating so that we may
allow the plugin files to be residing under a different name.
=============================================================== */
register_deactivation_hook(KK_STAR_RATINGS, kksr('core.wp.functions.deactivate'));

/* ==============================================================
We aren't using `register_activation_hook` because it is buggy
and does not get called when the plugin is implictly updated.
The activation will be handled when the plugin is loaded.
============================================================== */
// register_activation_hook(KK_STAR_RATINGS, kksr('core.wp.functions.activate'));

/* ==============================================================
To be aware of the fact that 'the_content' filter
is being applied within the 'get_the_excerpt'
filter, we must ensure we set up a flag.
============================================================== */
add_filter('get_the_excerpt', function ($excerpt) {
    applying_filter(['get_the_excerpt' => true]);

    return $excerpt;
}, PHP_INT_MIN);

add_filter('get_the_excerpt', function ($excerpt) {
    applying_filter(['get_the_excerpt' => false]);

    return $excerpt;
}, PHP_INT_MAX);
