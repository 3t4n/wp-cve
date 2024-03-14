<?php
/*
 * jssor slider admin api
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}


/**
 * @link   https://www.jssor.com
 * @author jssor
 */
class Jssor_Slider_Admin_Controller {
    const SUCCESS = 0;
    const ERROR_NO_AUTHENTICATE = 1;
    const ERROR_NO_PERMISSION = 2;
    const ERROR_EXISTED = 3;

    public function process_request() {
        $method = $this->request_param('method');
        if (empty($method)) {
            $this->render_json_response(
                array(
                    'error' => 2,
                    'status' => 'error',
                    'message' => 'Unsupported operation.'
                )
            );
        }
        if (!method_exists($this, $method)) {
            $this->render_json_response(
                array(
                    'error' => 2,
                    'status' => 'error',
                    'message' => 'Not found.'
                )
            );
        }

        $this->$method();
    }

    /**
     * save slider content
     *
     * @return void
     */
    public function save() {
        $this->output_headers();

        $this->check_valid_request('wjssl-save');

        if ($_SERVER['HTTP_CONTENT_ENCODING'] == 'jslzwutf16binary') {
            Jssor_Slider_Dispatcher::load_once('includes/utils/class-wjssl-lzw.php');
            $compressor = new WjsslLZW();
            $_POST['data'] = $compressor->decompress(@file_get_contents('php://input'));
        } else {
            $_POST['data'] = $this->strip_magic_quotes($_POST['data']);
        }

        if (empty($_POST['data'])) {
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => 'Post data is empty',
            ));
        }

        $slider_path = $this->get_slider_path();
        if(!wp_mkdir_p($slider_path['abs_dir'])){
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => 'Permission denied to create directory.'
            ));
        }

        $data_obj = json_decode($_POST['data']);

        if (empty($data_obj->content)) {
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => 'Request is invalid.'
            ));
        }

        $slider_json_model = $data_obj->content;
        $slider_json_text = null;

        unset($data_obj);

        if(is_object($slider_json_model)) {
            $slider_json_text = json_encode($slider_json_model);
        }
        else {
            $slider_json_text = $slider_json_model;
            $slider_json_model = json_decode($slider_json_text);
        }

        //$slider_name = sanitize_text_field($this->request_param('filename'));
        $slider_name = Jssor_Slider_Bll::to_safe_slider_file_name($this->request_param('filename'));
        $slider_data = Jssor_Slider_Dal::get_slider_data_by_file_name($slider_name, $error_message);

        if(!is_null($error_message)) {
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => $error_message
            ));
        }

        //convert @Import tag image urls to local image urls
        $standard_to_local_converter = new Jssor_Slider_Converter_Standard_To_Local($slider_json_model);
        $standard_to_local_converter->convert_resource_urls();

        if (empty($slider_data)) {
            //create new slider
            $slider_data = Jssor_Slider_Bll::create_new_slider($slider_json_model, $slider_name, $error_message);

            if(is_null($slider_data)) {
                if(empty($error_message)) {
                    $error_message = 'Save slider error.';
                }

                $this->render_json_response(array(
                    'error' => 2,
                    'status' => 'error',
                    'message' => $error_message
                ));
            }
        }
        else {
            //overwrite existing slider
            $can_overwrite = intval($this->request_param('overwrite'));

            if (empty($can_overwrite)) {
                $this->render_json_response(array(
                    'error' => 3,
                    'status' => 'error',
                    'message' => 'The slider exists already.'
                ));
            }

            $standard_to_local_converter = new Jssor_Slider_Converter_Standard_To_Local($slider_json_model);
            $standard_to_local_converter->convert_resource_urls();

            $new_slider_data = Jssor_Slider_Bll::save_existing_slider($slider_json_model, $slider_data, $error_message);

            if(is_null($new_slider_data)) {
                if(empty($error_message)) {
                    $error_message = 'Save slider error.';
                }

                $this->render_json_response(array(
                    'error' => 2,
                    'status' => 'error',
                    'message' => $error_message
                ));
            }
        }

        $this->render_json_response(array('error' => 0, 'status' => 'ok'));
    }

    public function retrieve() {
        $this->output_headers();

        //if ($_SERVER["REQUEST_TYPE"] === "OPTIONS") { // special CORS track
        //exit; // no need to do anything else for OPTIONS request
        //}

        $this->check_valid_request('wjssl-retrieve');

        $slider_id = intval($this->request_param('id'));
        $slider_data = Jssor_Slider_Dal::get_slider_data_by_id($slider_id, $error_message);

        if (is_null($slider_data)) {

            if(is_null($error_message)) {
                $error_message = __('The slider %s is not found.', 'jssor-slider');
                $error_message = wp_sprintf($error_message, strval($slider_id));
            }

            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => $error_message
            ));
        }

        $file_path = $slider_data['file_path'];
        if(empty($file_path)) {
            $file_path = Jssor_Slider_Bll::get_template_slider_file_path();
        }

        $upload = wp_upload_dir();
        $full_file_path = $upload['basedir'] . '/' . ltrim($file_path, '/');

        if(!file_exists($full_file_path)) {
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => 'Slider file does not exist or has been removed.'
            ));
        }

        $this->send_file_content($full_file_path, 1);
        exit();
    }

    /**
     * download jssor resource file
     *
     * @return array | true
     */
    public function getjssorres()
    {
        //http://localhost/?jssorextver=3.8.0&method=getjssorres&size=220x160&url=https%3A%2F%2Fwww.jssor.com%2Fdemos%2Fimg%2Fgallery%2F980x380%2F009.jpg

        $url = sanitize_text_field($this->request_param('url'));
        $url = esc_url_raw($url);

        if(empty($url)) {
            //bad request
            @status_header(400);
            exit();
        }

        $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($url);

        if($jssor_res_info->is_valid) {

            //1 day client cache
            //max-age: 86400
            $max_age = 86400;

            $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($jssor_res_info->local_url);
            $file_exists = $local_res_info->exists();
            $file_path = $local_res_info->local_path;

            if(!$file_exists) {
                //reolve jssorres for authorized as admin user only
                if(is_user_logged_in() && current_user_can('manage_options') && WP_Jssor_Slider_Utils::can_generate_metadata($local_res_info)) {
                    //allows admin to download resource and generate metadata

                    //1 second client cache for admin
                    $max_age = 1;

                    $file_exists = $jssor_res_info->ensure();

                    if(!$file_exists) {
                        $url_info_array = WP_Jssor_Slider_Utils::parse_url($url);

                        if(WP_Jssor_Slider_Utils::is_valid_resource($url_info_array))
                        {
                            //download image
                            $file_exists = WP_Jssor_Slider_Utils::download_jssor_res($jssor_res_info->remote_url, $jssor_res_info->local_path);
                        }
                    }

                    if($file_exists) {
                        //ensure metadata
                        WP_Jssor_Slider_Utils::ensure_metadata($local_res_info, $attach_id);
                    }
                }
                else {
                    //send file from install path directly
                    $file_exists = $jssor_res_info->installed();
                    $file_path = $jssor_res_info->install_path;
                }
            }

            if($file_exists) {
                $this->send_file($file_path, $max_age);
                return;
            }
            else {
                //not found
                @status_header(404);
                exit();
            }
        }
        else {
            //redirect to original url
            //status_header(301); //redirect permanently
            @status_header(302); //redirect once
            @header('Location: ' . $url);
            exit;
        }
    }
    /**
     * handle crop image http request
     *
     * @return void
     */
    public function crop_img()
    {
        #region new code

        //http://localhost/?jssorextver=3.8.0&method=crop_img&size=220x160&url=https%3A%2F%2Fwww.jssor.com%2Fdemos%2Fimg%2Fgallery%2F980x380%2F009.jpg
        //http://localhost/?jssorextver=3.2.0&method=crop_img&size=220x160&url=https%3A%2F%2Fwww.jssor.com%2Fdemos%2Fimg%2Fgallery%2F1300x500%2F001.jpg

        $image_url = sanitize_text_field($this->request_param('url'));
        $image_url = esc_url_raw($image_url);
        $crop_size = explode('x', sanitize_text_field($this->request_param('size')));

        if(empty($image_url) || empty($crop_size) || (count($crop_size) != 2)) {
            //bad request
            @status_header(400);
            exit();
        }

        $local_path = null;
        $local_res_info = null;

        $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($image_url);

        //map url to local path
        if($jssor_res_info->is_valid) {
            $local_path = $jssor_res_info->local_path;
            $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($jssor_res_info->local_url);
        }
        else {
            //to do handle external resource
            //$extenal_res_info = WP_Jssor_Slider_Utils::to_external_res_info($image_url);
            //if($extenal_res_info->is_valid) {
            //    $local_path = $extenal_res_info->local_path;
            //    $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($extenal_res_info->local_url);
            //}
            //else
            {
                $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($image_url);
                $local_path = $local_res_info->local_path;
            }
        }

        //is not a valid local url
        if(!$local_res_info->is_valid) {
            if($jssor_res_info->is_valid) {
                //bad request, the url can't map to local path
                @status_header(404);
                exit();
            }
            else {
                //redirect to original url
                //status_header(301); //redirect permanently
                @status_header(302); //redirect once
                @header('Location: ' . $image_url);
                exit;
            }
        }

        $exists = $local_res_info->exists();

        if(!$exists) {
            if($jssor_res_info->is_valid) {
                //copy from installed package
                $exists = $jssor_res_info->ensure();

                if(!$exists) {
                    //prevent unathorized user from downloading resource
                    if(is_user_logged_in() && current_user_can('manage_options')) {
                        //download image
                        $exists = WP_Jssor_Slider_Utils::download_jssor_res($jssor_res_info->remote_url, $jssor_res_info->local_path);
                    }
                }
            }
            //to do handle external resource
            //elseif ($extenal_res_info->is_valid) {
            //    //prevent unathorized user from downloading resource
            //    if(is_user_logged_in() && current_user_can('manage_options')) {
            //        $exists = WP_Jssor_Slider_Utils::download_jssor_res($extenal_res_info->remote_url, $extenal_res_info->local_path);
            //    }
            //}
        }

        //ensure thumbnail
        if($exists) {
            list($crop_w, $crop_h) = $crop_size;
            $thumb_path = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->local_path, $crop_w, $crop_h);

            //1 day client cache
            //max-age: 86400
            $max_age = 86400;

            //prevent unathorized user from writing metadata
            if(is_user_logged_in() && current_user_can('manage_options')) {

                //1 second client cache for admin
                $max_age = 1;

                //check exists of thumbnail
                if(!file_exists($thumb_path)) {
                    //ensure metadata
                    $metadata = WP_Jssor_Slider_Utils::ensure_metadata($local_res_info, $attach_id);

                    if($metadata != null) {
                        //ensure thumbnail
                        $sizes = array($crop_w . 'x' . $crop_h => array('width' => (int)$crop_w, 'height' => (int)$crop_h, 'crop' => true));
                        WP_Jssor_Slider_Utils::ensure_thumbnails($local_res_info, $attach_id, $metadata, $sizes);
                    }
                }
            }

            //send thumb file or original file
            if(file_exists($thumb_path)) {
                return $this->send_file($thumb_path, $max_age);
            }
            else {
                return $this->send_file($local_path, $max_age);
            }
        }

        @status_header(400);
        exit();

        #endregion
    }

    public function getmyfontlibrary()
    {
        $user_object = WP_Jssor_Slider_Utils::read_user_object("myfontlibrary", $file_path);

        $file_path_to_send = $file_path;

        if(is_null($user_object) || !isset($file_path) || empty($file_path))
        {
            $file_path_to_send = WP_JSSOR_SLIDER_PATH . 'public/component/font.manager.myfonts.default.js';
        }

        $this->send_file_content($file_path_to_send, 1);
    }

    public function savemyfontlibrary()
    {
        $this->output_headers();

        $this->check_valid_request('wjssl-savemyfontlibrary');

        $data = null;

        try {
            if ($_SERVER['HTTP_CONTENT_ENCODING'] == 'jslzwutf16binary') {
                Jssor_Slider_Dispatcher::load_once('includes/utils/class-wjssl-lzw.php');
                $compressor = new WjsslLZW();
                $data = $compressor->decompress(@file_get_contents('php://input'));
            } else {
                $data = $this->strip_magic_quotes($_POST['data']);
            }

            if($data != null)
            {
                $data_object = json_decode($data);
                WP_Jssor_Slider_Utils::save_user_object("myfontlibrary", $data_object->content);

                $this->render_json_response(array('error' => 0, 'status' => 'ok'));
            }
            else {
                $this->render_json_response(array('error' => 1, 'message' => 'Failed to save my font library.'));
            }
        }
        catch(Exception $e)
        {
            $this->render_json_response(array('error' => 1, 'message' => $e->getMessage()));
        }
    }

    /**
     * send file as http response
     *
     * @return void
     */
    private function send_file($file_path, $client_cache_time = 86400)
    {
        if (file_exists($file_path)) {
            $filemtime = filemtime($file_path);
            $filegmtime = gmdate('D, d M Y H:i:s', $filemtime) .' GMT';

            //$client_cache_time = 24 * 60 * 60;
            @header('Cache-Control: public, max-age=' . $client_cache_time);
            @header('Expires: '.gmdate('D, d M Y H:i:s',time() + $client_cache_time) .' GMT');
            @header('Last-Modified: '. $filegmtime);

            if (
                isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
                &&
                ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $filegmtime)
            )
            {
                @status_header(304);
                exit();
            }

            if (function_exists('mime_content_type')) {
                $mime_type = mime_content_type($file_path);
            } else {
                $mime_type = WP_Jssor_Slider_Utils::get_mime_content_type($file_path);
            }

            @status_header(200);
            @header('content-type:' . $mime_type);
            readfile($file_path);
        } else {
            @status_header(404);
        }
        exit();
    }

    /**
     * send file content as http response with content type 'text/plan'
     *
     * @return void
     */
    private function send_file_content($file_path, $client_cache_time = 86400)
    {
        if (@file_exists($file_path)) {
            $filemtime = filemtime($file_path);
            $filegmtime = gmdate('D, d M Y H:i:s', $filemtime) .' GMT';

            //$client_cache_time = 24 * 60 * 60;
            @header('Cache-Control: public, max-age=' . $client_cache_time);
            @header('Expires: '.gmdate('D, d M Y H:i:s',time() + $client_cache_time) .' GMT');
            @header('Last-Modified: '. $filegmtime);

            if (
                isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
                &&
                ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $filegmtime)
            )
            {
                @status_header(304);
                exit();
            }

            @header('content-type:text/plain');

            $content = @file_get_contents($file_path);

            if($content === false) {
                @status_header(404);
            }
            else {
                @status_header(200);
                echo $content;
            }
        } else {
            @status_header(404);
        }
        exit();
    }

    protected function strip_magic_quotes($value)
    {
        return stripslashes($value);
        //if (get_magic_quotes_gpc()) {
        //return stripslashes($value);
        //} else {
        //return $value;
        //}
    }

    /**
     * check if request is valid
     *
     * @return array | true
     */
    protected function check_valid_request($nonce_name)
    {
        if (!is_user_logged_in()) {
            $this->render_json_response(array(
                'error' => 1,
                'status' => 'error',
                'message' => 'Login Required! Please <a class="text-warning" href="' . admin_url() . '" target="_blank">login</a> and then try again.'
            ));
        }

        if (!current_user_can('manage_options')) {
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => 'Permission Denied!'
            ));
        }
        $nonce = sanitize_text_field($this->request_param('nonce'));
        if (empty($nonce) || !wp_verify_nonce($nonce, $nonce_name)) {
            $this->render_json_response(array(
                'error' => 2,
                'status' => 'error',
                'message' => 'The request is invalid.'
            ));
        }
        return true;
    }

    /**
     * common response headers
     *
     * @return void
     */
    protected function output_headers()
    {
        $headers = $this->common_headers();
        foreach ($headers as $key => $value) {
            @header("$key: $value");
        }

        /** Allow for cross-domain requests (from the front end). */
        send_origin_headers();
    }


    /**
     * undocumented function
     *
     * @return void
     */
    protected function common_headers()
    {
        //$http_origin            = $_SERVER['HTTP_ORIGIN'];
        //$allowed_http_origins   = array(
        //    'http://jssor.com'   ,
        //    'http://www.jssor.com'  ,
        //    'https://jssor.com'   ,
        //    'https://www.jssor.com'  ,
        //);
        //if ($http_origin && in_array($http_origin, $allowed_http_origins)) {
        //    // do nothing
        //} else {
        //    $http_origin = 'http://www.jssor.com';
        //}

        return array(
            //'Access-Control-Expose-Headers' => 'x-json',
            //'Access-Control-Max-Age' => 1728000,
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, HEAD',
            //'Access-Control-Allow-Origin' => $http_origin,
            'Access-Control-Allow-Credentials' => 'true'
        );
    }

    /**
     * slider path
     *
     * @return array
     */
    protected function get_slider_path($slider_id = 0)
    {
        $upload_dir = wp_upload_dir();

        $file_dir = WP_Jssor_Slider_Globals::UPLOAD_SLIDER . '/'
            . date('Y/m', time());
        $abs_file_dir = $upload_dir['basedir'] . $file_dir;

        $file_path = $file_dir . '/'. $slider_id . '.slider';
        $abs_path = $upload_dir['basedir'] . $file_path;

        return array(
            'rel_path' => $file_path,
            'rel_dir' => $file_dir,
            'abs_path' => $abs_path,
            'abs_dir' => $abs_file_dir,
        );
    }

    private function request_param($key) {
        return empty($_GET[$key]) ? '' : $_GET[$key];
    }

    private function render_json_response($result, $http_status = 200) {
        if (!headers_sent()) {
            @status_header($http_status);
            @header("Content-Type: application/json; charset=UTF-8", true);
        }
        echo json_encode($result); exit;

    }
}
