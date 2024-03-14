<?php

defined('ABSPATH') || exit;

/**
 * Handle Remote API requests.
 *
 */
class Layouts_Divi_Remote {

    protected static $lfd_instance = NULL;

    const TRANSIENT_TEMPLATE = 'et_pb_layout';
    const TRANSIENT_CATEGORY = 'layout_category';
    const TEMPLATES = 'https://layoutsfordivibuilder.com/wp-json/layoutsfordivi/v1/templates';
    const CATEGORIES = 'https://layoutsfordivibuilder.com/wp-json/layoutsfordivi/v1/categories';

    public function __construct() {
        $this->hooks();
    }

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function lfd_get_instance() {
        if (NULL === self::$lfd_instance) {
            self::$lfd_instance = new self;
        }

        return self::$lfd_instance;
    }

    /**
     * API template URL.
     * Holds the URL for getting a single template data.
     *
     * @var string API template URL.
     */
    private static $template_url = 'https://layoutsfordivibuilder.com/wp-json/layoutsfordivi/v1/template/byid/?id=%d';
    private static $image_url = 'https://layoutsfordivibuilder.com/wp-json/layoutsfordivi/v1/image/byid/?id=%d';

    /**
     * Initialize
     */
    public function hooks() {
        add_action('wp_ajax_handle_sync', array($this, 'template_sync'));
        add_action('wp_ajax_nopriv_handle_sync', array($this, 'template_sync'));
    }

    /**
     * Get a sync templates list.
     * @return mixed|\WP_Error
     */
    public function template_sync() {

        $response = $this->templates_list($force_update = true);
        $response = $this->categories_list($force_update = true);

        if ($response) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    /**
     * Get a templates list.
     * @return mixed|\WP_Error
     */
    public function templates_list($force_update = false) {

        $response = get_transient(self::TRANSIENT_TEMPLATE);

        if (!$response || $force_update) {

            $request = wp_remote_request(self::TEMPLATES);

            // Check Error not exist
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::TRANSIENT_TEMPLATE, $response, 12 * HOUR_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }

        return $response;
    }

    /**
     * Get a templates categories.
     * @return mixed|\WP_Error
     */
    public function categories_list($force_update = false) {
        $response = get_transient(self::TRANSIENT_CATEGORY);

        if (!$response || $force_update) {

            $request = wp_remote_request(self::CATEGORIES);

            // Check Error not exist
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::TRANSIENT_CATEGORY, $response, 1 * HOUR_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        return $response;
    }

    /**
     * Get a single template content.
     *
     * @param int $template_id Template ID.
     * @return mixed|\WP_Error
     */
    public function get_template_content($template_id) {
        $url = sprintf(self::$template_url, $template_id);

        $response = wp_remote_request($url);

        if (is_wp_error($response)) {
            return $response;
        }

        $response_code = (int) wp_remote_retrieve_response_code($response);
        if (200 !== $response_code) {
            return new \WP_Error('response_code_error', sprintf('The request returned with a status code of %s.', $response_code));
        }

        $template_content = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($template_content['message']) && !empty($template_content['message'])) {
            return $template_content['message'];
        }

        if (isset($template_content['error'])) {
            return new \WP_Error('response_error', $template_content['error']);
        }

        if (empty($template_content['title']) && empty($template_content['template'])) {
            return new \WP_Error('template_data_error', 'An invalid data was returned.');
        }

        return $template_content;
    }

    /**
     * Get a single Image.
     *
     * @param int $media_id Media ID.
     * @return mixed|\WP_Error
     */
    public function get_media_image($media_id) {
        $url = sprintf(self::$image_url, $media_id);
        $response = wp_remote_request($url);
        if (is_wp_error($response)) {
            return $response;
        }

        $response_code = (int) wp_remote_retrieve_response_code($response);
        if (200 !== $response_code) {
            return new \WP_Error('response_code_error', sprintf('The request returned with a status code of %s.', $response_code));
        }

        $image_array = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($image_array['msg']) && !empty($image_array['msg']) && $image_array['msg'] == 'Missing Attachment') {
            return $image_array['msg'];
        }

        if (isset($image_array) && !empty($image_array)) {
            return $image_array['image_url'];
        }

        return $image_array;
    }

}

new Layouts_Divi_Remote();
