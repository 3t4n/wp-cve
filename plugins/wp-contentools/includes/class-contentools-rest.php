<?php

/**
 * The rest functionality of the plugin.
 *
 * @package    Contentools
 * @subpackage Contentools/admin
 *
 * @link  https://growthhackers.com/workflow
 * @since 1.0.0
 */

/**
 * The rest functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contentools
 * @subpackage Contentools/admin
 * @author     Contentools <wordpress-plugin@contentools.com>
 */
class Contentools_Rest
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    array    $actions    Actions availables on rest api.
     */
    private $actions = array('publish-post', 'find-post', 'list-posts', 'list-categories', 'list-users', 'publish-media');

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string    $plugin_name       The name of this plugin.
     * @param string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register rest routes for api requests.
     *
     * @since 1.0.0
     */
    public function register_routes()
    {

        foreach ($this->actions as $action) {

            register_rest_route(
                $this->plugin_name . '/v' . substr($this->version, 0, strpos($this->version, '.')),
                '/' . $action,
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'action_' . str_replace('-', '_', $action)),
                    'permission_callback' => array($this, 'authenticate'),
                )
            );

        }

    }

    /**
     * Register rest endpoints for direct requests.
     *
     * @since 1.0.0
     */
    public function register_endpoints()
    {

        foreach ($this->actions as $action) {

            add_rewrite_endpoint($this->plugin_name . '-' . $action, EP_ROOT);

        }

    }

    /**
     * Intercept request to process.
     *
     * @since 1.0.0
     */
    public function intercept_request()
    {

        global $wp_query;

        foreach ($this->actions as $action) {

            if (isset($wp_query->query_vars[$this->plugin_name . '-' . $action])) {

                $method = 'action_' . str_replace('-', '_', $action);

                if ($this->authenticate()) {

                    $response = $this->$method();

                } else {

                    $response = new WP_Error('rest_forbidden', __('Sorry, you are not allowed to do that.'), array('status' => 401));

                }

                $this->response($response);

            }
        }

        return;

    }

    /**
     * Perform authentication.
     *
     * @since 1.0.0
     */
    public function authenticate()
    {

        $options = get_option($this->plugin_name);

        if ($json = json_decode(file_get_contents('php://input', true))) {

            foreach ($json as $id => $value) {

                if (!isset($_POST[$id])) {
                    $_POST[$id] = $value;
                }

            }

        }

        $post_token = sanitize_text_field($_POST['token']);

        return (($post_token && isset($options['token']) && $post_token == $options['token']) ? true : false);

    }

    /**
     * Perform authentication.
     *
     * @since 1.0.0
     */
    public function response($response)
    {

        if (is_wp_error($response)) {

            $code = $response->get_error_code();

            $message = $response->get_error_message($code);

            $data = $response->get_error_data($code);

            $status = isset($data['status']) ? $data['status'] : 400;

            $json = array('code' => $code, 'message' => $message, 'data' => $data);

        } else {

            $status = $response->get_status();

            $json = $response->get_data();

        }

        $statuses = array(
            100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK', 201 => 'Created', 202 => 'Accepted',
            203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content',
            300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Moved Temporarily', 303 => 'See Other',
            304 => 'Not Modified', 305 => 'Use Proxy', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required',
            403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required', 408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone',
            411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway',
            503 => 'Service Unavailable', 504 => 'Gateway Time-out', 505 => 'HTTP Version not supported',
        );

        header((isset($_SERVER['SERVER_PROTOCOL']) ? sanitize_text_field($_SERVER['SERVER_PROTOCOL']) : 'HTTP/1.0') . ' ' . $status . ' ' . $statuses[$status]);

        header('Content-Type:application/json; charset=UTF-8');

        echo json_encode($json);

        exit;

    }

    /**
     * Set Headers.
     *
     * @since 1.0.0
     */
    public function set_headers()
    {

        @header('X-WP-Contentools: true');
        @header('Access-Control-Allow-Origin: *');
        @header('Access-Control-Expose-Headers: X-WP-Contentools');
        @header('Access-Control-Allow-Methods: GET, POST');
        @header('X-Frame-Options: https://go.contentools.com/');

    }

    /**
     * Determine current user.
     *
     * @since 1.0.0
     */
    public function determine_current_user($user)
    {

        global $wp_json_basic_auth_error;

        $wp_json_basic_auth_error = null;

        // Don't authenticate twice
        if (!empty($user)) {

            return $user;

        }

        if (!isset($_SERVER['PHP_AUTH_USER']) && (isset($_SERVER['HTTP_AUTHORIZATION']) || isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))) {

            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {

                $header = sanitize_text_field($_SERVER['HTTP_AUTHORIZATION']);

            } else {

                $header = sanitize_text_field($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);

            }

            if (!empty($header)) {
                $php_auth_user = sanitize_user($_SERVER['PHP_AUTH_USER']);

                list($php_auth_user, $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($header, 6)));

            }

        }

        // Check that we're trying to authenticate
        if (!isset($_SERVER['PHP_AUTH_USER'])) {

            return $user;

        }

        $username = sanitize_user($_SERVER['PHP_AUTH_USER']);
        $password = $_SERVER['PHP_AUTH_PW'];

        remove_filter('determine_current_user', array($this, 'determine_current_user'), 10);

        $user = wp_authenticate($username, $password);

        add_filter('determine_current_user', array($this, 'determine_current_user'), 10);

        if (is_wp_error($user)) {

            $wp_json_basic_auth_error = $user;

            return null;

        }

        $wp_json_basic_auth_error = true;

        return $user->ID;

    }

    /**
     * Rest authentication errors.
     *
     * @since 1.0.0
     */
    public function rest_authentication_errors($error)
    {

        if (!empty($error)) {

            return $error;

        }

        global $wp_json_basic_auth_error;

        return $wp_json_basic_auth_error;

    }

    /**
     * Perform action publish-post.
     *
     * @since 1.0.0
     */
    public function action_publish_post($request = null)
    {

        $post = array();

        if (isset($_POST["id"])) {
            $post['ID'] = sanitize_key($_POST['id']);
        }

        if (isset($_POST["title"])) {
            $post['post_title'] = wp_filter_post_kses($_POST['title']);
        }

        if (isset($_POST["author"])) {
            $post['post_author'] = sanitize_key($_POST['author']);
        }

        if (isset($_POST["content"])) {
            $post['post_content'] = wp_filter_post_kses($_POST['content']);
        }

        if (isset($_POST["excerpt"])) {
            $post['post_excerpt'] = wp_filter_post_kses($_POST['excerpt']);
        }

        if (isset($_POST["slug"])) {
            $post['post_name'] = sanitize_title_with_dashes($_POST['slug']);
        }

        if (isset($_POST["type"])) {
            $post['post_type'] = sanitize_text_field($_POST['type']);
        }

        if (isset($_POST["status"])) {
            $post['post_status'] = sanitize_text_field($_POST['status']);
        }

        if (isset($_POST["categories"])) {
            $post['post_category'] = sanitize_category($_POST['categories']);
        }

        if (isset($_POST["featured_media"])) {
            $post['featured_media'] = sanitize_key($_POST['featured_media']);
        }

        $id = $post['ID'] && is_numeric($post['ID']) ? wp_update_post(wp_slash($post), true) : wp_insert_post(wp_slash($post), true);

        if (!is_wp_error($id)) {

            if (isset($_POST["featured_media"])) {
                update_post_meta($id, '_thumbnail_id', sanitize_key($_POST['featured_media']));
            }

            $post = get_post($id);

            $post = array(
                'id' => intval($post->ID),
                'author' => $post->post_author,
                'date' => $post->post_date,
                'date_gmt' => $post->post_date_gmt,
                'modified' => $post->post_modified,
                'modified_gmt' => $post->post_modified_gmt,
                'guid' => array('raw' => $post->guid, 'rendered' => apply_filters('get_the_guid', $post->guid, $post->ID)),
                'slug' => $post->post_name,
                'title' => array('raw' => $post->post_title, 'rendered' => get_the_title($post->ID)),
                'content' => array('raw' => $post->post_content, 'rendered' => apply_filters('the_content', $post->post_content)),
                'excerpt' => array('raw' => $post->post_excerpt, 'rendered' => apply_filters('get_the_excerpt', $post->post_excerpt, $post)),
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'type' => $post->post_type,
                'status' => $post->post_status,
                'link' => get_permalink($post->ID),
                'featured_media' => get_post_thumbnail_id($post->ID),
                'featured_image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
                'meta' => array(),
                'categories' => wp_get_post_categories($post->ID),
                'tags' => wp_get_post_terms($post->ID),
            );

            return new WP_REST_Response($post, 200);

        } else {

            return $id;

        }

    }

    /**
     * Perform action find-post.
     *
     * @since 1.0.0
     */
    public function action_find_post($request = null)
    {

        $post_id = sanitize_key($_POST['id']);

        if (is_numeric($post_id) && $post = get_post($post_id)) {

            $post = array(
                'id' => intval($post->ID),
                'author' => $post->post_author,
                'date' => $post->post_date,
                'date_gmt' => $post->post_date_gmt,
                'modified' => $post->post_modified,
                'modified_gmt' => $post->post_modified_gmt,
                'guid' => array('raw' => $post->guid, 'rendered' => apply_filters('get_the_guid', $post->guid, $post->ID)),
                'slug' => $post->post_name,
                'title' => array('raw' => $post->post_title, 'rendered' => get_the_title($post->ID)),
                'content' => array('raw' => $post->post_content, 'rendered' => apply_filters('the_content', $post->post_content)),
                'excerpt' => array('raw' => $post->post_excerpt, 'rendered' => apply_filters('get_the_excerpt', $post->post_excerpt, $post)),
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'type' => $post->post_type,
                'status' => $post->post_status,
                'link' => get_permalink($post->ID),
                'featured_media' => get_post_thumbnail_id($post->ID),
                'featured_image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
                'meta' => array(),
                'categories' => wp_get_post_categories($post->ID),
                'tags' => wp_get_post_terms($post->ID),
            );

            list($post['date'], $post['date_gmt']) = rest_get_date_with_gmt($post['date']);
            list($post['modified'], $post['modified_gmt']) = rest_get_date_with_gmt($post['modified']);

            return new WP_REST_Response($post, 200);

        } else {

            return new WP_Error('not_found', __('Post not found.'), array('status' => 404));

        }

    }

    /**
     * Perform action list-posts.
     *
     * @since 1.0.0
     */
    public function action_list_posts($request = null)
    {

        $posts = get_posts($_POST);

        if ($posts) {
            foreach ($posts as $id => $post) {

                $post = array(
                    'id' => intval($post->ID),
                    'author' => $post->post_author,
                    'date' => $post->post_date,
                    'date_gmt' => $post->post_date_gmt,
                    'modified' => $post->post_modified,
                    'modified_gmt' => $post->post_modified_gmt,
                    'guid' => array('raw' => $post->guid, 'rendered' => apply_filters('get_the_guid', $post->guid, $post->ID)),
                    'slug' => $post->post_name,
                    'title' => array('raw' => $post->post_title, 'rendered' => get_the_title($post->ID)),
                    'content' => array('raw' => $post->post_content, 'rendered' => apply_filters('the_content', $post->post_content)),
                    'excerpt' => array('raw' => $post->post_excerpt, 'rendered' => apply_filters('get_the_excerpt', $post->post_excerpt, $post)),
                    'comment_status' => $post->comment_status,
                    'ping_status' => $post->ping_status,
                    'type' => $post->post_type,
                    'status' => $post->post_status,
                    'link' => get_permalink($post->ID),
                    'featured_media' => get_post_thumbnail_id($post->ID),
                    'featured_image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
                    'meta' => array(),
                    'categories' => wp_get_post_categories($post->ID),
                    'tags' => wp_get_post_terms($post->ID),
                );

                list($post['date'], $post['date_gmt']) = rest_get_date_with_gmt($post['date']);
                list($post['modified'], $post['modified_gmt']) = rest_get_date_with_gmt($post['modified']);

                $posts[$id] = $post;

            }
        }

        return new WP_REST_Response($posts, 200);

    }

    /**
     * Perform action list-categories.
     *
     * @since 1.0.0
     */
    public function action_list_categories($request = null)
    {

        if (empty($_POST['hide_empty'])) {
            $_POST['hide_empty'] = false;
        }

        $categories = get_categories($_POST);

        if ($categories) {
            foreach ($categories as $id => $category) {

                $categories[$id] = array(
                    'id' => intval($category->term_id),
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'taxonomy' => $category->taxonomy,
                );

            }
        }

        return new WP_REST_Response($categories, 200);

    }

    /**
     * Perform action list-users.
     *
     * @since 1.0.0
     */
    public function action_list_users($request = null)
    {

        $users = get_users($_POST);

        if ($users) {
            foreach ($users as $id => $user) {

                $users[$id] = array(
                    'id' => intval($user->ID),
                    'name' => $user->data->display_name,
                    'slug' => $user->data->user_nicename,
                    'email' => $user->data->user_email,
                    'roles' => array_values($user->roles),
                );

            }
        }

        return new WP_REST_Response($users, 200);

    }

    /**
     * Perform action publish-media.
     *
     * @since 1.0.0
     */
    public function action_publish_media($request = null)
    {

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        if (isset($_FILES['file'])) {

            $file = wp_handle_upload($_FILES['file'], array('test_form' => false));

            if (isset($file['error'])) {

                return new WP_Error('upload_error', $file['error'], array('status' => 500));

            }

        } elseif (isset($_POST['file'])) {

            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'ico', 'bmp', 'webp');

            $image_extension = pathinfo($_POST['file'][0], PATHINFO_EXTENSION);

            $image_size = wp_getimagesize($_POST['file'][1]);

            if (!in_array($image_extension, $allowed_extensions) || empty($image_size)) {

                return new WP_Error('upload_error', __('Image extension not allowed.'), array('status' => 500));

            }

            $tempnam = wp_tempnam(sanitize_file_name($_POST['file'][0]));

            $fp = fopen($tempnam, 'w+');

            if (!$fp) {

                return new WP_Error('upload_error', __('Could not open file handle.'), array('status' => 500));

            }

            fwrite($fp, $_POST['file'][1]);

            fclose($fp);

            $file = array(
                'error' => null,
                'tmp_name' => $tempnam,
                'name' => sanitize_file_name($_POST['file'][0]),
                'type' => sanitize_text_field($_POST['file'][2]),
                'size' => filesize($tempnam),
            );

            $file = wp_handle_sideload($file, array('test_form' => false));

            if (isset($file['error'])) {

                return new WP_Error('upload_error', $file['error'], array('status' => 500));

            }

        } else {

            return new WP_Error('upload_no_data', __('No data supplied.'), array('status' => 400));

        }

        $image_meta = wp_read_image_metadata($file['file']);

        $attachment = array('file' => $file['file'], 'post_mime_type' => $file['type'], 'guid' => esc_url($file['url']), 'post_type' => 'attachment', 'post_status' => 'inherit');

        if (isset($_POST["slug"])) {
            $attachment['post_name'] = sanitize_title_with_dashes($_POST['slug']);
        }

        if (isset($_POST["title"])) {
            $attachment['post_title'] = sanitize_title($_POST['title']);
        } elseif (!empty($image_meta['title']) && trim($image_meta['title'])) {
            $attachment['post_title'] = sanitize_text_field($image_meta['title']);
        } else {
            $attachment['post_title'] = preg_replace('/\.[^.]+$/', '', basename($attachment['file']));
        }

        if (isset($_POST["author"])) {
            $attachment['post_author'] = sanitize_key($_POST['author']);
        }

        if (isset($_POST["description"])) {
            $attachment['post_content'] = sanitize_text_field($_POST['description']);
        }

        if (isset($_POST["caption"])) {
            $attachment['post_excerpt'] = sanitize_text_field($_POST['caption']);
        }

        if (isset($_POST["alt_text"])) {
            $attachment['alt_text'] = sanitize_text_field($_POST['alt_text']);
        } elseif (!empty($image_meta['caption']) && trim($image_meta['caption'])) {
            $attachment['post_excerpt'] = sanitize_text_field($image_meta['caption']);
        }

        if (isset($_POST["post"])) {
            $attachment['post_parent'] = sanitize_key($_POST['post']);
        }

        $id = wp_insert_post(wp_slash($attachment), true);

        if (!is_wp_error($id)) {

            wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $attachment['file']));

            if (isset($_POST['alt_text'])) {
                update_post_meta($id, '_wp_attachment_image_alt', sanitize_text_field($_POST['alt_text']));
            }

            $post = get_post($id);

            $post = array(
                'id' => intval($post->ID),
                'date' => $post->post_date,
                'date_gmt' => $post->post_date_gmt,
                'guid' => array('raw' => $post->guid, 'rendered' => apply_filters('get_the_guid', $post->guid, $post->ID)),
                'modified' => $post->post_modified,
                'modified_gmt' => $post->post_modified_gmt,
                'slug' => $post->post_name,
                'status' => $post->post_status,
                'type' => $post->post_type,
                'link' => get_permalink($post->ID),
                'title' => array('raw' => $post->post_title, 'rendered' => get_the_title($post->ID)),
                'author' => $post->post_author,
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'template' => get_page_template_slug($post->ID),
                'meta' => array(),
                'description' => array('raw' => $post->post_content, 'rendered' => apply_filters('the_content', $post->post_content)),
                'caption' => array('raw' => $post->post_excerpt, 'rendered' => apply_filters('get_the_excerpt', $post->post_excerpt, $post)),
                'alt_text' => get_post_meta($post->ID, '_wp_attachment_image_alt', true),
                'media_type' => wp_attachment_is_image($post->ID) ? 'image' : 'file',
                'mime_type' => $post->post_mime_type,
                'media_details' => wp_get_attachment_metadata($post->ID),
                'post' => !empty($post->post_parent) ? intval($post->post_parent) : null,
                'source_url' => wp_get_attachment_url($post->ID),
            );

            list($post['date'], $post['date_gmt']) = rest_get_date_with_gmt($post['date']);
            list($post['modified'], $post['modified_gmt']) = rest_get_date_with_gmt($post['modified']);

            if (!empty($post['media_details']['sizes'])) {

                foreach ($post['media_details']['sizes'] as $size => &$size_data) {

                    if (isset($size_data['mime-type'])) {
                        $size_data['mime_type'] = $size_data['mime-type'];
                        unset($size_data['mime-type']);
                    }

                    $image_src = wp_get_attachment_image_src($post['id'], $size);
                    if (!$image_src) {
                        continue;
                    }

                    $size_data['source_url'] = $image_src[0];
                }

                $full_src = wp_get_attachment_image_src($post['id'], 'full');

                if (!empty($full_src)) {

                    $data['media_details']['sizes']['full'] = array(
                        'file' => wp_basename($full_src[0]),
                        'width' => $full_src[1],
                        'height' => $full_src[2],
                        'mime_type' => $post['mime_type'],
                        'source_url' => $full_src[0],
                    );

                }

            }

            return new WP_REST_Response($post, 200);

        } else {

            return $id;

        }

    }

}
