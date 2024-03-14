<?php

/**
 * All the API requests are handled here.
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Core/Classes
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Aarambha_DS_API
 * 
 * Handles all the API requests
 */

class Aarambha_DS_API
{
    /**
     * Single class instance.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var object
     */
    private static $instance = null;

    /**
     * Sets the API url
     * 
     * @var string
     */
    private $apiUrl = '';

    /**
     * Creates the single class instance
     *
     * @class Aarambha_DS_API
     * 
     * @version 1.0.0
     * @since 1.0.0
     * 
     * @return object Aarambha_DS_API
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->apiUrl = AARAMBHA_DS_API_URL;
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Aarambha_DS_API::getInstance()
     *
     * @since 1.0.0
     * @access private
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        /* We do nothing here! */
    }

    /**
     * You cannot clone this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * Adds the endpoint to the API URL.
     * 
     * @param string $endpoint 
     * 
     * @return string Api url with the appended endpoint.
     */
    private function addEndpoint($endpoint)
    {
        return $this->apiUrl . "{$endpoint}";
    }

    /**
     * Query the Demo server.
     *
     * @uses wp_remote_get() To perform an HTTP request.
     *
     * @since 1.0.0
     *
     * @param  string $url API request URL, including the request method, parameters, & file type.
     * @param  array  $args The arguments passed to `wp_remote_get`.
     * 
     * @return array|WP_Error  The HTTP response.
     */
    public function request($url, $args)
    {
        $default = [];

        $args = wp_parse_args($args, $default);

        $apiUrl = add_query_arg($args, $url);

        $response = wp_remote_get($apiUrl);

        // Check the response code and message.
        $response_code    = wp_remote_retrieve_response_code($response);
        $response_message = wp_remote_retrieve_response_message($response);

        // Set some debugging information.
        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header($response, 'cf-ray');
        $debugging_information['response_server'] = wp_remote_retrieve_header($response, 'server');

        if (!empty($response->errors) && isset($response->errors['http_request_failed'])) {

            return new WP_Error(
                'http_error',
                esc_html(current($response->errors['http_request_failed'])),
                $debugging_information
            );
        }

        if (200 !== $response_code && !empty($response_message)) {

            // If response code is not 200 and response message is empty.
            return new WP_Error(
                $response_code,
                $response_message,
                $debugging_information
            );
        } elseif (200 !== $response_code) {

            // Perhaps received other response codes than expected.
            return new WP_Error(
                $response_code,
                __('An unknown API error occurred.', 'aarambha-demo-sites'),
                $debugging_information
            );
        } else {

            $body = json_decode(wp_remote_retrieve_body($response), true);

            if (null === $body) {

                return [
                    'success' => false,
                    'data' => __('No data received. May be data is malformed.', 'aarambha-demo-sites')
                ];
            }

            $data = [
                'success' => true,
                'data' => $body
            ];

            return $data;
        }
    }

    /**
     * Query the author themes.
     * 
     * @return 
     */
    public function themes()
    {
        if (!function_exists('themes_api')) {
            require_once ABSPATH . 'wp-admin/includes/theme.php';
        }

        // use themes_api to query for author themes in WP.ORG
        $response = themes_api(
            'query_themes',
            [
                'author' => AARAMBHA_DS_AUTHOR_SLUG,
                'fields' =>  ['template' => true]
            ]
        );

        $authorThemes = [];

        if (($response->info)['results']) {
            $themes = $response->themes;

            foreach ($themes as $theme) {
                array_push($authorThemes, $theme->slug);
            }

            // cache the result for 7 days.
            set_site_transient('aarambha_ds_author_themes', $authorThemes, 7 * DAY_IN_SECONDS);
        }

        return $authorThemes;
    }

    /**
     * Get the demo categories.
     * 
     * @return array.
     */
    public function categories()
    {
        $categories = get_site_transient('aarambha_ds_demo_categories');

        if ($categories) {
            return $categories;
        }

        $apiUrl = $this->addEndpoint('theme/categories');
        $theme  = aarambha_ds_get_theme();

        $body   = $this->request($apiUrl, ['theme' => $theme]);

        if (!is_wp_error($body)) {
            return $body['data'];
        }

        return [];
    }

    /**
     * Get the demos.
     * 
     * @return array
     */
    public function demos()
    {
        $theme  = aarambha_ds_get_theme();

        $key    = "aarambha_ds_{$theme}_demos";

        $demos = get_site_transient($key);

        if ($demos) {
            return $demos;
        }

        $apiUrl = $this->addEndpoint('lists');

        $body   = $this->request($apiUrl, ['theme' => $theme]);

        if (!is_wp_error($body) && $body['success']) {
            set_site_transient($key, $body['data'], WEEK_IN_SECONDS);

            return $body['data'];
        }

        return [];
    }

    /**
     * Get individual demo.
     * 
     * @return array
     */
    public function demo($slug, $type = 'free')
    {
        $theme    = aarambha_ds_get_theme();
        $key      = "aarambha_ds_{$theme}_demo_{$slug}";
        $demo     = get_site_transient($key);

        $response = [];

        if ($demo) {
            // Cached information.
            $response['success'] = true;
            $response['data']    = $demo['data'];

            return $response;
        }

        $apiUrl = $this->addEndpoint('list');
        $demo   = $this->request($apiUrl, ['demo' => $slug, 'type' => $type]);

        if (is_wp_error($demo)) {
            $response['success'] = false;
            $response['title']   = 'Sorry!';
            $response['message'] = $demo->get_error_message();
        } else {
            $data = $demo['data'];

            $response['success'] = true;
            $response['data']    = $data['data'];

            set_site_transient($key, $data, WEEK_IN_SECONDS);
        }

        return $response;
    }

    public function deferredDownload($args)
    {
        if (empty($args)) {
            return '';
        }

        $default = ['deffered_download' => true, 'action' => 'install-plugin'];

        $args = wp_parse_args($args, $default);

        return add_query_arg($args, esc_url(Aarambha_DS()->getPageUrl()));
    }

    /**
     * Download the file.
     * 
     * @param array $args
     * 
     * @return string.
     */
    public function download($args)
    {
        $apiUrl  = $this->addEndpoint('file');
        $fileKey = $args['slug'];
        $file    = "{$fileKey}.zip";

        $args['file'] = $file;

        return add_query_arg($args, $apiUrl);
    }

    /**
     * Download url.
     * 
     * @param array $args
     * 
     * @return string Download URL.
     */
    public function downloadUrl($args)
    {
        $apiUrl  = $this->addEndpoint('file');

        return add_query_arg($args, $apiUrl);
    }
}
