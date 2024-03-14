<?php

/*
 * This file is part of bhittani/kk-star-ratings.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\StarRating\core\wp\actions;

use function Bhittani\StarRating\core\functions\action;
use function Bhittani\StarRating\core\functions\filter;
use function Bhittani\StarRating\core\functions\lock;
use function Bhittani\StarRating\core\functions\option;
use function Bhittani\StarRating\functions\cast;
use function Bhittani\StarRating\functions\sanitize;
use function Bhittani\StarRating\functions\to_shortcode;
use Exception;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

class WpAjaxKkStarRatingsRaceException extends Exception
{
    public function __construct(?string $message = null, int $code = 403)
    {
        $message = $message ?: __('Please try again later.', 'kk-star-ratings');
        parent::__construct($message, $code);
    }
}

function wp_ajax_kk_star_ratings()
{
    $payload = sanitize($_POST['payload'] ?? []);
    $payload['id'] = intval($payload['id'] ?? 0);
    $payload['slug'] = $payload['slug'] ?? 'default';
    $payload['best'] = intval($payload['best'] ?? option('stars'));
    $payload['_legend'] = $payload['_legend'] ?? option('legend');
    $rating = intval($_POST['rating'] ?? 0);

    $lock = lock(__FUNCTION__."_{$payload['id']}_{$payload['slug']}", 5);

    try {
        if (! check_ajax_referer(__FUNCTION__, 'nonce', false)) {
            throw new Exception(__('This action is forbidden.', 'kk-star-ratings'), 403);
        }

        try {
            $lock->acquire();
        } catch (Exception $e) {
            throw new WpAjaxKkStarRatingsRaceException();
        }

        if (filter('validate', null, $payload['id'], $payload['slug'], $payload) === false) {
            $lock->release();
            throw new Exception(__('A rating can not be accepted at the moment.', 'kk-star-ratings'));
        }

        if (! $rating) {
            $lock->release();
            throw new Exception(__('A rating is required to cast a vote.', 'kk-star-ratings'));
        }

        if ($rating < 1 || $rating > $payload['best']) {
            throw new Exception(sprintf(__('The rating value must be between %1$d and %2$d.', 'kk-star-ratings'), 1, $payload['best']));
        }

        $outOf5 = cast($rating, 5, $payload['best']);

        action('save', $outOf5, $payload['id'], $payload['slug'], [
            'count' => (int) filter('count', null, $payload['id'], $payload['slug']),
            'ratings' => (float) filter('ratings', null, $payload['id'], $payload['slug']),
        ] + $payload);

        $payload['legend'] = $payload['_legend'];

        unset($payload['count'], $payload['score']);

        $html = trim(do_shortcode(to_shortcode(kksr('slug'), $payload)));

        wp_die($html, 201);
    } catch (Exception $e) {
        // $lock->release();

        header('Content-Type: application/json; charset=utf-8');

        wp_die(json_encode(['error' => $e->getMessage()]), $e->getCode() ?: 406);
    } catch (WpAjaxKkStarRatingsRaceException $e) {
        wp_die(json_encode(['error' => $e->getMessage()]), $e->getCode() ?: 403);
    }
}
