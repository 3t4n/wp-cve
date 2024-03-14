<?php
/**
 * Posts.php
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 * @since     2.0.2
 */

namespace Ashleyfae\ExpandingArchives\Api\v1;

use Ashleyfae\ExpandingArchives\ValueObjects\Month;

class Posts
{

    /**
     * Registers the endpoint with WordPress.
     *
     * Example route: expanding-archives/v1/posts/2022/2/
     *
     * @since 2.0.2
     *
     * @return void
     */
    public function register(): void
    {
        register_rest_route(
            'expanding-archives/v1',
            '/posts/(?P<year>\d{4})/(?P<month>\d{1,2})',
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'list'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'year'  => [
                        'description'       => 'Year to retrieve posts in.',
                        'type'              => 'integer',
                        'required'          => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        },
                        'sanitize_callback' => function ($param, $request, $key) {
                            return absint($param);
                        },
                    ],
                    'month' => [
                        'description'       => 'Month to retrieve posts in.',
                        'type'              => 'integer',
                        'required'          => true,
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        },
                        'sanitize_callback' => function ($param, $request, $key) {
                            return absint($param);
                        },
                    ],
                ],
            ]
        );
    }

    /**
     * Lists posts in the provided year + month.
     *
     * @since 2.0.2
     *
     * @param  \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function list(\WP_REST_Request $request): \WP_REST_Response
    {
        $posts = (new Month($request->get_param('year'), $request->get_param('month')))
            ->getPosts();

        return new \WP_REST_Response(
            array_map([$this, 'formatPost'], $posts)
        );
    }

    /**
     * Formats an individual post to only include the data we need in the API response.
     * (title and link)
     *
     * @since 2.0.2
     *
     * @param  \WP_Post  $post
     *
     * @return array
     */
    protected function formatPost(\WP_Post $post): array
    {
        return [
            'title' => $post->post_title,
            'link'  => get_permalink($post),
        ];
    }

}
