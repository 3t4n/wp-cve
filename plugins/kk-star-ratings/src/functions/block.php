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
 * @param string|array<string,mixed> $blockOrDeprecatedNamespace
 * @param array<string,mixed> $deprecatedPayload
 *
 * @return string|array<string,mixed>
 */
function block($blockOrDeprecatedNamespace, array $deprecatedPayload = [], string $deprecatedSlug = null)
{
    static $genAssetArgs;

    // Support addons depending on version <= 5.3.4
    if (! is_array($blockOrDeprecatedNamespace)) {
        return block_5_3_4(
            $blockOrDeprecatedNamespace,
            $deprecatedPayload,
            $deprecatedSlug
        );
    }

    $block = $blockOrDeprecatedNamespace;

    $genAssetArgs = $genAssetArgs ?: static function (string $slug, $srcOrOptions) {
        if (! $srcOrOptions) {
            return [];
        }

        $options = $srcOrOptions;
        if (! is_array($srcOrOptions)) {
            $options = ['src' => $srcOrOptions];
        }

        if (! isset($options['src'])) {
            // The 'src' key is required.
            return [];
        }

        return $options + compact('slug') + ['deps' => []] + [
            'version' => kksr('version'), //(string) filemtime($options['src']),
        ];
    };

    if (! isset($block['name'])) {
        // The 'name' is required.
        return [];
    }

    if (! isset($block['editor_script'])) {
        // The 'editor_script' is required.
        return [];
    }

    // Generate name.
    if (strpos($block['name'], '/') === false) {
        $block['name'] = prefix($block['name'], kksr('slug').'/');
    }

    // Fill missing values.
    $block += [
        'data' => [],
    ];

    // Construct meta.
    $block['meta'] = array_filter([
        'title' => $block['title'] ?? null,
        'api_version' => $block['api_version'] ?? null,
    ]) + ($block['meta'] ?? []) + [
        'api_version' => 2,
    ];

    // Generate title.
    if (! isset($block['meta']['title'])) {
        $title = explode('/', $block['name'], 2)[1];
        $block['meta']['title'] = ucwords(trim(preg_replace('/[^\w]+/', ' ', $title)));
    }

    // Change meta key case ('foo_bar' => 'fooBar').
    $block['meta'] = array_combine(array_map(function ($key) {
        return str_replace('_', '', lcfirst(ucwords($key, '_')));
    }, array_keys($block['meta'])), array_values($block['meta']));

    // Generate slug.
    $slug = trim(preg_replace('/[^\w]+/', '_', $block['name']), '_');

    // Register scripts and styles.
    $block['editor_script'] = $genAssetArgs("{$slug}_editor", $block['editor_script']);
    $editorScriptReqDeps = [kksr('slug').'-blocks', 'wp-element'];
    $block['editor_script']['deps'] = array_unique(array_merge($editorScriptReqDeps, $block['editor_script']['deps']));
    foreach ([
        ['wp_register_script', $block['editor_script']],
        ['wp_register_style', $block['editor_style'] = $genAssetArgs("{$slug}_editor", $block['editor_style'] ?? null)],
        ['wp_register_script', $block['script'] = $genAssetArgs($slug, $block['script'] ?? null)],
        ['wp_register_style', $block['style'] = $genAssetArgs($slug, $block['style'] ?? null)],
    ] as [$fn, $args]) {
        if ($args) {
            $fn($args['slug'], $args['src'], $args['deps'], $args['version']);
        }
    }

    // Finally, register the block.
    register_block_type($block['name'], array_filter([
        'api_version' => $block['meta']['apiVersion'],
        'attributes' => $block['attributes'] ?? [],
        'render_callback' => $block['render'] ?? null,
        'editor_script' => $block['editor_script']['slug'],
        'editor_style' => $block['editor_style'] ? $block['editor_style']['slug'] : null,
        'script' => $block['script'] ? $block['script']['slug'] : null,
        'style' => $block['style'] ? $block['style']['slug'] : null,
    ]));

    // Return the payload necessary for the js block api.
    return [
        'name' => $block['name'],
        'meta' => $block['meta'],
        'data' => $block['data'],
    ];
}
