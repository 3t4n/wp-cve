<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * @link   https://www.jssor.com
 * @author Neil.zhou
 * @author jssor
 */
class WP_Jssor_Slider_Utils {
    const REG_CSS_CONTAINS_URL = '/url(\s)*\(/i';

    public static function is_curl_enabled() {
        $php_version = phpversion();
        return (version_compare($php_version, '5.3.0') >= 0) && function_exists('curl_version');
    }

    public static function is_ssl_enabled() {
        $php_version = phpversion();
        return (version_compare($php_version, '5.3.0') >= 0) && extension_loaded('openssl');
    }

    public static function to_accessible_jssor_url($url) {
        $accessible_url = $url;

        if(!empty($url) && !WP_Jssor_Slider_Utils::is_ssl_enabled()) {
            $accessible_url = preg_replace('/^https:\/\//i', 'http://', $url);
        }

        return $accessible_url;
    }

    /**
     * Returns a GUIDv4 string
     *
     * Uses the best cryptographically secure method
     * for all supported pltforms with fallback to an older,
     * less secure version.
     *
     * @param bool $trim
     * @return string
     */
    public static function create_guid($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
            substr($charid,  0,  8).$hyphen.
            substr($charid,  8,  4).$hyphen.
            substr($charid, 12,  4).$hyphen.
            substr($charid, 16,  4).$hyphen.
            substr($charid, 20, 12).
            $rbrace;
        return $guidv4;
    }

    public static function parse_url( $url ) {
        $parts = @parse_url( $url );
        if ( ! $parts ) {
            // < PHP 5.4.7 compat, trouble with relative paths including a scheme break in the path
            if ( '/' == $url[0] && false !== strpos( $url, '://' ) ) {
                // Since we know it's a relative path, prefix with a scheme/host placeholder and try again
                if ( ! $parts = @parse_url( 'placeholder://placeholder' . $url ) ) {
                    return $parts;
                }
                // Remove the placeholder values
                unset( $parts['scheme'], $parts['host'] );
            } else {
                return $parts;
            }
        }

        // < PHP 5.4.7 compat, doesn't detect schemeless URL's host field
        if ( !isset( $parts['host'] ) && '//' == substr( $url, 0, 2 ) ) {
            $path_parts = explode( '/', substr( $parts['path'], 2 ), 2 );
            $parts['host'] = $path_parts[0];
            if ( isset( $path_parts[1] ) ) {
                $parts['path'] = '/' . $path_parts[1];
            } else {
                unset( $parts['path'] );
            }
        }

        return $parts;
    }

    public static function trim_jssor_media_info_from_url($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return $url;
        }

        $array = WP_Jssor_Slider_Utils::parse_url($url);
        $port_suffix = '';
        if(!empty($array['port'])) {
            $port_suffix = ':' . $array['port'];
        }
        $path = isset($array['path']) ? $array['path'] : '';
        $url = $array['scheme'] . '://' . $array['host'] . $port_suffix . $path;

        if (isset($array['query'])) {
            $query_array = wp_parse_args($array['query']);
            unset($query_array['jssorext']);
            unset($query_array['extsite']);
            unset($query_array['extmedia']);
            $query_str = build_query( $query_array);
        } else {
            $query_str = '';
        }

        if (!empty($query_str)) {
            $url .= '?' . $query_str;
        }
        return $url;
    }

    public static function get_jssor_media_info_from_url($url) {

        if (filter_var($url, FILTER_VALIDATE_URL) !== FALSE) {
            $array = WP_Jssor_Slider_Utils::parse_url($url);

            if (isset($array['query'])) {
                $media_info = new stdClass();
                $query_array = wp_parse_args($array['query']);

                foreach($query_array as $key => $value) {
                    if(!empty($value)) {
                        $media_info->$key = json_decode($value);
                    }
                }

                return $media_info;
            }
        }

        return null;
    }

    /**
     * retrieve the image id from the given image url
     * to do check use cases
     * @since: 1.0
     */
    private static function get_image_id_by_upload_key_url($image_url) {
        global $wpdb;

        $attachment_id = 0;

        if(!empty($image_url)) {
            $media_info = WP_Jssor_Slider_Utils::get_jssor_media_info_from_url($image_url);
            if(!empty($media_info) && !empty($media_info->extsite) && isset($media_info->extsite->instid) && $media_info->extsite->instid == get_option('wp_jssor_slider_instance_id', ''))
            {
                if(!empty($media_info->extmedia->id))
                {
                    return $media_info->extmedia->id;
                }
            }

            $image_url = WP_Jssor_Slider_Utils::trim_jssor_media_info_from_url($image_url);

            if(function_exists('attachment_url_to_postid')){
                $attachment_id = attachment_url_to_postid($image_url); //0 if failed
            }
            if (0 == $attachment_id){
                //for WP < 4.0.0

                // Get the upload directory paths
                $upload_dir_paths = wp_upload_dir();

                // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
                if ( false !== strpos( $image_url, $upload_dir_paths['baseurl'] ) ) {

                    // If this is the URL of an auto-generated thumbnail, get the URL of the original image
                    $image_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_url );

                    // Remove the upload path base directory from the attachment URL
                    $image_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $image_url );

                    // Finally, run a custom database query to get the attachment ID from the modified attachment URL
                    $post_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $image_url ) );

                    if(isset($post_id))
                    {
                        $attachment_id = $post_id;
                    }
                }
            }
        }

        return $attachment_id;
    }

    public static function get_image_id_by_local_res(Wjssl_Local_Res_Info $local_res_info)
    {
        $attach_id = 0;

        if($local_res_info->is_valid && $local_res_info->under_upload_dir && strlen($local_res_info->upload_key) < 256) {
            $attach_id = WP_Jssor_Slider_Utils::get_image_id_by_upload_key_url($local_res_info->upload_key_url);
        }

        return $attach_id;
    }

    public static function is_css_contains_url($css_text) {
        return !empty($css_text) && preg_match(WP_Jssor_Slider_Utils::REG_CSS_CONTAINS_URL, $css_text);
    }

    /*
     * 'http://localhost/wp-content/uploads/dir/filename.jpg'
     * 'http://localhost/wp-content/uploads/dir/filename-220x160.jpg'
     */
    private static function get_image_id_by_url($url) {
        $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($url);
        return WP_Jssor_Slider_Utils::get_image_id_by_local_res($local_res_info);
    }

    public static function get_upload_folder_writable() {
        $dir = wp_upload_dir();
        return wp_is_writable($dir['basedir'].'/');
    }

    public static function get_upload_max_filesize_byte() {
        $upload_max_filesize = ini_get('upload_max_filesize');
        $upload_max_filesize_byte = wp_convert_hr_to_bytes($upload_max_filesize);

        return $upload_max_filesize_byte;
    }

    public static function get_post_max_size_byte() {
        $post_max_size = ini_get('post_max_size');
        $post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);

        return $post_max_size_byte;
    }

    public static function get_gd_library_installed() {
        return extension_loaded('gd') && function_exists('gd_info');
    }

    public static function is_dir_empty($dir) {
        if (!is_readable($dir)) return null;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry !== '.' && $entry !== '..') {
                closedir($handle);
                return false;
            }
        }
        closedir($handle);
        return true;
    }

    public static function read_user_object($object_type, &$file_path) {
        $upload = wp_upload_dir();
        $user_object_dir_path = $upload['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_USER;

        $file_path_1 = $user_object_dir_path . '/_' . $object_type . '._1_';

        $user_object = null;

        try {
            if(@file_exists($file_path_1)) {
                $content = @file_get_contents($file_path_1);
                if($content !== false) {
                    $user_object = json_decode($content);
                    $file_path = $file_path_1;
                }
            }
        }
        catch(Exception $e1) {
            try {
                $file_path_2 = $user_object_dir_path . '/_' . $object_type . '._2_';;

                if(@file_exists($file_path_1)) {
                    $content = @file_get_contents($file_path_2);
                    if($content !== false) {
                        $user_object = json_decode($content);
                        $file_path = $file_path_2;
                    }
                }
            }
            catch(Exception $e2) {
                //read my font library failed, do nothing
            }
        }

        return $user_object;
    }

    public static function save_user_object($object_type, $user_object) {
        $upload = wp_upload_dir();
        $user_object_dir_path = $upload['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_USER;
        if (!wp_mkdir_p($user_object_dir_path)) {
            $error_message = "Failed create directory $user_object_dir_path.";
            throw new Exception($error_message);
        }

        $content = wp_json_encode($user_object);
        $file_path_1 = $user_object_dir_path . '/_' . $object_type . '._1_';
        if(!@file_put_contents($file_path_1, $content)) {
            $error_message = "Failed to save my font library.";
            throw new Exception($error_message);
        }

        try {
            $file_path_2 = $user_object_dir_path . '/_' . $object_type . '._2_';
            @file_put_contents($file_path_2, $content);
        }
        catch(Exception $e) {
            //save backup failed, do nothing
        }
    }

    public static function get_jssor_wordpress_status_info() {
        global $wp_version;

        //requirements info
        $can_connect = true;

        $upload_max_filesize = ini_get('upload_max_filesize');
        $upload_max_filesize_byte = WP_Jssor_Slider_Utils::get_upload_max_filesize_byte();
        $post_max_size = ini_get('post_max_size');
        $post_max_size_byte = WP_Jssor_Slider_Utils::get_post_max_size_byte();

        $upload_folder_writeable = WP_Jssor_Slider_Utils::get_upload_folder_writable();
        $upload_max_filesize_problem = ($upload_max_filesize_byte < WP_Jssor_Slider_Globals::REQUIREMENTS_MIN_UPLOAD_FILE_SIZE); //2M
        $post_max_size_problem = ($post_max_size_byte < WP_Jssor_Slider_Globals::REQUIREMENTS_MIN_POST_FILE_SIZE);  //8M

        $gd_installed = WP_Jssor_Slider_Utils::get_gd_library_installed();

        $status = array(
            'updates' => WP_Jssor_Slider_Globals::get_jssor_wordpress_updates_info(),
            'instver' => $wp_version,
            'can_connect' => $can_connect,
            'upload_max_filesize' => $upload_max_filesize,
            'upload_max_filesize_byte' => $upload_max_filesize_byte,
            'post_max_size' => $post_max_size,
            'post_max_size_byte' => $post_max_size_byte,
            'writable_problem' => !$upload_folder_writeable,
            'upload_max_filesize_problem' => $upload_max_filesize_problem,
            'post_max_size_problem' => $post_max_size_problem,
            'gd_library_problem' => !$gd_installed
        );

        return $status;
    }

    #region res utils

    /**
     * Normalise a file path string so that it can be checked safely.
     *
     * Attempt to avoid invalid encoding bugs by transcoding the path. Then
     * remove any unnecessary path components including '.', '..' and ''.
     *
     * @param $path string
     * The path to normalise.
     * @param $encoding string
     * The name of the path iconv() encoding.
     * @return string
     * The path, normalised.
     */
    public static function normalize_path($path) {
        // Attempt to avoid path encoding problems.
        if (function_exists('wp_normalize_path')) {
            $path = wp_normalize_path($path);
        } else {
            $path = self::_wp_normalize_path($path);
        }

        //check if path contains '/../' or '/./' or '//'
        if(preg_match('/[\.\/]+\//', $path)) {
            // Process the components
            $parts = explode('/', $path);
            $safe = array();
            foreach ($parts as $idx => $part) {
                if (empty($part) && ($idx > 0) && ($idx < count($parts) - 1) || ('.' == $part)) {
                    continue;
                } elseif ('..' == $part) {
                    array_pop($safe);
                    continue;
                } else {
                    $safe[] = $part;
                }
            }
            // Return the "clean" path
            $path = implode('/', $safe);
        }

        return $path;
    }

    private static function _wp_normalize_path($path)
    {
        $path = str_replace( '\\', '/', $path );
        $path = preg_replace( '|(?<=.)/+|', '/', $path );
        if ( ':' === substr( $path, 1, 1 ) ) {
            $path = ucfirst( $path );
        }
        return $path;
    }

    public static function get_mime_content_type($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        //$ext = strtolower(array_pop(explode('.',$filename)));
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if($ext) {
            $ext = strtolower($ext);

            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            }
            elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $filename);
                finfo_close($finfo);
                return $mimetype;
            }
        }

        return 'application/octet-stream';
    }

    public static function is_valid_resource($url_array)
    {
        if (is_string($url_array)) {
            $url_array = WP_Jssor_Slider_Utils::parse_url($url_array);
        }
        if (isset( $url_array['scheme'] ) && $url_array['scheme'] != 'http' && $url_array['scheme'] != "https") {
            return false;
        }
        $ext = pathinfo($url_array['path'], PATHINFO_EXTENSION);
        if(empty($ext)) {
            return false;
        }
        $ext = strtolower($ext);
        $allowed_exts = array(
            'jpg', 'jpeg', 'png', 'gif', 'psd', 'tiff', 'bmp', 'svg', 'txt', 'text', 'css', 'html', 'slider'
        );
        return in_array($ext, $allowed_exts);
    }

    public static function is_import_tag_url($url) {
        return stripos($url, '@Import/') === 0;
    }

    public static function import_url_to_jssorres_url($url) {
        $url = substr($url, 8);
        $template = '%s?jssorextver=%s&method=getjssorres&url=%s';
        return wp_sprintf($template, WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url(), WP_JSSOR_SLIDER_VERSION, urlencode($url));
    }

    public static function format_crop_img_url($url, $width, $height) {
        $url = str_replace('@Import/', '', $url);
        $template = '%s?jssorextver=%s&method=crop_img&size=%sx%s&url=%s';
        return wp_sprintf($template, WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url(), WP_JSSOR_SLIDER_VERSION, $width, $height, urlencode($url));
    }

    #endregion

    #region jssor res handling

    public static function is_self_site_url($url) {
        $is_self_site_url = true;

	    $site_url = WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url();

	    $site_url_info = WP_Jssor_Slider_Utils::parse_url( $site_url );
	    $src_path_info = WP_Jssor_Slider_Utils::parse_url( $url );

        if(!isset($site_url_info['host'])) {
		    $is_self_site_url = false;
        }
        else if(isset($src_path_info['host'])) {
            if(strtolower($src_path_info['host']) != strtolower($site_url_info['host'])) {
                $is_self_site_url = false;
            }
        }
        else if(!isset($site_url_info['port']) && isset($src_path_info['port'])) {
		    $is_self_site_url = false;
        }
        else if(isset($site_url_info['port']) && !isset($src_path_info['port'])) {
		    $is_self_site_url = false;
        }
        else if(isset($site_url_info['port']) && isset($src_path_info['port']) && isset($site_url_info['port']) != isset($src_path_info['port'])) {
		    $is_self_site_url = false;
        }
	    else if ( 0 !== stripos( $src_path_info['path'], $site_url_info['path'] )) {
		    $is_self_site_url = false;
	    }

        return $is_self_site_url;
    }

    private static function to_site_rel_path($url) {
        $rel_path = null;

	    $site_url = WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url();
	    $path = $url;

	    $site_url_info = WP_Jssor_Slider_Utils::parse_url( $site_url );
	    $src_path_info = WP_Jssor_Slider_Utils::parse_url( $url );

	    //force the protocols to match if needed
	    if ( isset( $src_path_info['scheme'] ) && ( $src_path_info['scheme'] !== $site_url_info['scheme'] ) ) {
		    $path = str_replace( $src_path_info['scheme'], $site_url_info['scheme'], $path );
	    }

	    if ( 0 === stripos( $path, $site_url )) {
		    $rel_path = substr( $path, strlen( $site_url ) );
            $rel_path = WP_Jssor_Slider_Utils::normalize_path($rel_path);
	    }

        return $rel_path;
    }

    private static function to_upload_rel_path($url) {
        $rel_path = null;

	    $upload_dir = wp_upload_dir();
	    $path = $url;

	    $upload_url_info = WP_Jssor_Slider_Utils::parse_url( $upload_dir['baseurl'] );
	    $src_path_info = WP_Jssor_Slider_Utils::parse_url( $url );

	    //force the protocols to match if needed
	    if ( isset( $src_path_info['scheme'] ) && ( $src_path_info['scheme'] !== $upload_url_info['scheme'] ) ) {
		    $path = str_replace( $src_path_info['scheme'], $upload_url_info['scheme'], $path );
	    }

	    if ( 0 === stripos( $path, $upload_dir['baseurl'] . '/' ) ) {

		    $rel_path = substr( $path, strlen( $upload_dir['baseurl'] . '/' ) );
            $rel_path = WP_Jssor_Slider_Utils::normalize_path($rel_path);
	    }

        return $rel_path;
    }
    /**
     * Used by WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute, Jssor_Slider_Bll::resolve_persistent_image_url
     * @param string $url
     * @return Wjssl_Jssor_Res_Info
     */
    public static function to_jssor_res_info($url)
    {
        $jssor_res_info = new Wjssl_Jssor_Res_Info();

        $path_array = WP_Jssor_Slider_Utils::parse_url($url);

        if(WP_Jssor_Slider_Utils::is_valid_resource($path_array)) {
            if(isset($path_array['host'])) {
                $host = $path_array['host'];

                if(strcasecmp($host, "jssor.com") == 0 || strcasecmp($host, "www.jssor.com") == 0)
                {
                    $upload_dir = wp_upload_dir();
                    $jssor_path = $upload_dir['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM;
                    $jssor_url = $upload_dir['baseurl'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM;

                    $rel_path = WP_Jssor_Slider_Utils::normalize_path($path_array['path']);
                    $rel_path = ltrim($rel_path, '/');

                    $file_path = $jssor_path . '/' . $rel_path;
                    $file_url = $jssor_url . '/' . $rel_path;

                    $jssor_res_info->is_valid = true;
                    $jssor_res_info->remote_url = $url;
                    $jssor_res_info->local_url = $file_url;

                    $jssor_res_info->local_path = $file_path;
                    $jssor_res_info->install_path = realpath(WP_JSSOR_SLIDER_PATH . WP_Jssor_Slider_Globals::DIR_RESOURCES_UPLOAD) . '/' . $rel_path;

                    $jssor_res_info->rel_path = $rel_path;
                    $jssor_res_info->upload_rel_path = ltrim(WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/' . $rel_path, '/');
                }
            }
        }

        return $jssor_res_info;
    }

    /**
     * Used by WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute, Jssor_Slider_Bll::resolve_persistent_image_url
     * @param string $url
     * @return Wjssl_Local_Res_Info
     */
    public static function to_local_res_info($url) {
        $local_res_info = new Wjssl_Local_Res_Info();

        $ext = null;
        $formated_url = null;

        $path_array = WP_Jssor_Slider_Utils::parse_url($url);
        $local_res_info->path_array = $path_array;

        #region format url

        if(isset($path_array['path']) && !empty($path_array['path'])) {
            $ext = pathinfo($path_array['path'], PATHINFO_EXTENSION);

            if(isset($ext) && !empty($ext)) {
                $ext = strtolower($ext);
                $local_res_info->ext = $ext;

                $scheme = null;

                if(isset($path_array['scheme'])) {
                    $scheme = strtolower($path_array['scheme']);
                }

                if(empty($scheme) || $scheme == 'http' || $scheme == 'https') {
                    if(!isset($path_array['host'])) {
                        if(strpos($path_array['path'], '/') === 0) {
                            $site_path_array = WP_Jssor_Slider_Utils::parse_url(WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url());
                            $scheme = $site_path_array['scheme'];
                            $path_array['host'] = $site_path_array['host'];

                            if(isset($site_path_array['port'])) {
                                $path_array['port'] = $site_path_array['port'];
                            }
                        }
                    }

                    #region populate url

                    $formated_url = '';

                    if(is_null($scheme)) {
                        $formated_url .= 'http';
                    }
                    else {
                        $formated_url .= $scheme;
                    }

                    $formated_url .= '://' . $path_array['host'];

                    if(isset($path_array['port'])) {
                        $formated_url .= ':' . $path_array['port'];
                    }

                    $path_array['path'] = WP_Jssor_Slider_Utils::normalize_path($path_array['path']);
                    $formated_url .= $path_array['path'];

                    #endregion
                }
            }
        }

        #endregion

        if(!is_null($formated_url) && WP_Jssor_Slider_Utils::is_self_site_url($formated_url))
        {
            $upload_rel_path = WP_Jssor_Slider_Utils::to_upload_rel_path($formated_url);
            $site_rel_path = WP_Jssor_Slider_Utils::to_site_rel_path($formated_url);

            $under_upload_dir = isset($upload_rel_path) && !empty($upload_rel_path);

            $local_res_info->is_valid = true;
            $local_res_info->under_upload_dir = $under_upload_dir;

            $local_res_info->local_url = $formated_url;
            $local_res_info->path = $path_array['path'];
            $local_res_info->local_path = ABSPATH . $site_rel_path;
            $local_res_info->ext == $ext;

            if($under_upload_dir)
            {
                $upload_key = strtolower($upload_rel_path);

                $local_res_info->upload_rel_path = $upload_rel_path;
                $local_res_info->upload_key = $upload_key;
                $upload_dir = wp_upload_dir();
                $local_res_info->upload_key_url = $upload_dir['baseurl'] . '/' . $upload_key;
            }
        }

        return $local_res_info;
    }

    public static function to_external_res_info($url)
    {
        $external_res_info = new Wjssl_External_Res_Info();
        if(WP_Jssor_Slider_Utils::is_self_site_url($url))
        {
            return $external_res_info;
        }

        $path_array = WP_Jssor_Slider_Utils::parse_url($url);

        if(WP_Jssor_Slider_Utils::is_valid_resource($path_array)) {
            $host = $path_array['host'];

            $upload_dir = wp_upload_dir();
            $jssor_path = $upload_dir['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM;
            $jssor_url = $upload_dir['baseurl'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM;

            $rel_path = WP_Jssor_Slider_Utils::normalize_path($path_array['path']);
            $rel_path = ltrim($rel_path, '/');
            if (false !== strpos($rel_path, 'wp-content/uploads')) {
                $rel_path = substr($rel_path, strpos($rel_path, 'wp-content/uploads') + 18);
            }
            $file_path = $jssor_path . '/' . $rel_path;
            $file_url = $jssor_url . '/' . $rel_path;

            $external_res_info->is_valid = true;
            $external_res_info->remote_url = $url;
            $external_res_info->local_url = $file_url;

            $external_res_info->local_path = $file_path;

            $external_res_info->rel_path = $rel_path;
            $external_res_info->upload_rel_path = ltrim(WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/' . $rel_path, '/');

        }

        return $external_res_info;
    }

    public static function get_thumb_path($path, $width, $height) {
        $dir = pathinfo($path, PATHINFO_DIRNAME);

        return $dir . '/' . WP_Jssor_Slider_Utils::get_thumb_filename($path, $width, $height);
    }

    public static function get_thumb_filename($path, $width, $height) {
        $file_name = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return strtolower(WP_Jssor_Slider_Utils::normalize_path($file_name . '-' . $width . 'x' . $height . '.' . $extension));
    }

    public static function download_jssor_res($remote_url, $local_path) {

        $dir = dirname($local_path);

        if (!wp_mkdir_p($dir)) {
            $error = 'Permission denied to create directory.';
            throw new Exception($error);
        }

        $rawdata = null;

        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
        if(WP_Jssor_Slider_Utils::is_curl_enabled()) {
            #region curl

            set_time_limit(60);

            //Here is the file we are downloading, replace spaces with %20
            $ch = curl_init(str_replace(" ", "%20", $accessible_url));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            // write curl response to file
            //curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            // get curl response
            $rawdata = curl_exec($ch);

            $error = curl_error($ch);

            curl_close($ch);

            #endregion
        }
        else {
            $args = array(
                'timeout'     => 50,
                'sslverify'   => false
            );

            $result = wp_remote_get($accessible_url, $args);

            if(! is_wp_error( $result ) || wp_remote_retrieve_response_code( $result ) === 200) {
                $rawdata = $result['body'];
            }
        }

        if(!is_null($rawdata)) {
            $fp = fopen($local_path,'w');
            fwrite($fp, $rawdata);
            fclose($fp);

            return true;
        }

        return false;
    }

    public static function can_generate_metadata(Wjssl_Local_Res_Info $local_res_info) {

        $can_generate_metadata = false;

        if($local_res_info->is_valid && $local_res_info->under_upload_dir) {

            $allowed_exts = array(
                'jpg', 'jpeg', 'png', 'gif', 'tiff', 'bmp', 'svg'
            );

            $can_generate_metadata = in_array($local_res_info->ext, $allowed_exts);
        }

        return $can_generate_metadata;
    }

    /**
     * return image metadata, or null if not available
     *
     * @return array|null
     */
    public static function ensure_metadata(Wjssl_Local_Res_Info $local_res_info, &$attach_id) {
        $attach_data = null;
        $attach_id = 0;

        if(WP_Jssor_Slider_Utils::can_generate_metadata($local_res_info) && strlen($local_res_info->upload_key) < 256 && $local_res_info->exists() && is_file($local_res_info->local_path)/* && filesize($local_res_info->local_path) > 0*/) {
            //get attachment id by well formated unique key url.
            $attach_id = WP_Jssor_Slider_Utils::get_image_id_by_local_res($local_res_info);

            if($attach_id == 0) {
                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype( basename( $local_res_info->local_path ), null );
                $current_time = current_time('mysql');

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $local_res_info->upload_key,    //unique id of the attachment
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($local_res_info->local_path)),
                    'post_status'    => 'inherit',
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_type' => 'attachment',
                    'post_parent' => '',
                    'post_content' => '',
                    'post_author' => 1
                    //'post_date' => current_time('mysql'),
                    //'post_date_gmt' => current_time('mysql'),
                    //'post_modified' => current_time('mysql'),
                    //'post_modified_gmt' => current_time('mysql')
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment($attachment, $local_res_info->local_path);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $local_res_info->local_path );

                if($attach_data) {
                    wp_update_attachment_metadata( $attach_id, $attach_data );
                }
            }
            else {
                $attach_data = wp_get_attachment_metadata($attach_id);
            }
        }

        return $attach_data;
    }

    public static function can_generate_thumbnail(Wjssl_Local_Res_Info $local_res_info) {

        $can_generate_thumbnail = false;

        if($local_res_info->is_valid && $local_res_info->under_upload_dir) {

            //$file_type = wp_check_filetype(strtolower(basename($local_res_info->local_path)));
            //$mime_type = $file_type['type'];

            $allowed_exts = array(
                'jpg', 'jpeg', 'png', 'gif', 'tiff', 'bmp'/*, 'svg'*/
            );

            $can_generate_thumbnail = in_array($local_res_info->ext, $allowed_exts);
        }

        return $can_generate_thumbnail;
    }

    public static function ensure_thumbnails(Wjssl_Local_Res_Info $local_res_info, $attach_id, &$metadata, $sizes) {
        $sizes_to_generate = array();
        $sizes_to_add = array();
        $sizes_generation_failed = array();
        $changed = false;

        if(!empty($metadata) && WP_Jssor_Slider_Utils::can_generate_thumbnail($local_res_info) && strlen($local_res_info->upload_key) < 256 && $local_res_info->exists() && is_file($local_res_info->local_path) && filesize($local_res_info->local_path) > 0) {

            #region check thumbnails to generate, or add to metadata

            foreach($sizes as $key => $size) {
                $thumb_key = $size['width'] . 'x' . $size['height'];

                $thumb_file_path = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->local_path, $size['width'], $size['height']);
                $thumb_file_name = WP_Jssor_Slider_Utils::get_thumb_filename($local_res_info->local_path, $size['width'], $size['height']);

                if(!isset($metadata->sizes[$thumb_key])) {
                    $file_type = wp_check_filetype($thumb_file_name);
                    $sizes_to_add[$key] = $sizes_to_add[$thumb_key] = array('file' => $thumb_file_name, 'mime-type' => $file_type['type'], 'width' => $size['width'], 'height' => $size['height']);
                }

                if(!file_exists($thumb_file_path)) {
                    $sizes_to_generate[$thumb_key] = $size;
                }
            }

            #endregion

            #region generate thumbnails that doesn't exist

            if(!empty($sizes_to_generate)) {
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                #region multi resize in one go

                $image = wp_get_image_editor($local_res_info->local_path);

                if(!is_wp_error($image)) {
                    $sizes_generated = $image->multi_resize($sizes_to_generate);
                }

                #endregion

                #region resize one by one

                //foreach($sizes_to_generate as $key => $size) {
                //    $thumb_path = WP_Jssor_Slider_Utils::get_thumb_path($local_path, $size['width'], $size['height']);

                //    $resp = null;

                //    $image = wp_get_image_editor($local_path);
                //    if(is_wp_error($image)) {
                //        $resp = $image;
                //    }
                //    else {
                //        $image->resize($size['width'], $size['height'], true);
                //        $resp = $image->save($thumb_path);
                //    }

                //    if(is_wp_error($resp)) {
                //        $sizes_generation_failed[$key] = $size;
                //    }
                //}

                #endregion
            }

            #endregion

            #region save changes made

            foreach($sizes_to_add as $key => $size) {
                if(!isset($sizes_generation_failed[$key])) {
                    $thumb_file_path = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->local_path, $size['width'], $size['height']);
                    if(file_exists($thumb_file_path)) {
                        $metadata['sizes'][$key] = $size;
                        $changed = true;
                    }
                }
            }

            if($changed) {
                wp_update_attachment_metadata($attach_id, $metadata);
            }

            #endregion
        }

        return $changed && !empty($sizes_generation_failed);
    }

    #endregion
}
