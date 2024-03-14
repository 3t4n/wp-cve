<?php

if( !defined( 'ABSPATH') ) exit();

/**
 * @link   https://www.jssor.com
 * @author Neil.zhou
 * @author jssor
 */
class WP_Jssor_Slider_Globals
{
    const TABLE_SLIDERS = 'jssor_slider_sliders';
    const TABLE_TRANSACTIONS = 'jssor_slider_trans';

    const TRANSACTION_TYPE_DELETE_SLIDER_CLEANUP = 1;
    const TRANSACTION_TYPE_RENAME_SLIDER_CLEANUP = 2;

    const DIR_RESOURCES = '/resources';
    const DIR_RESOURCES_UPLOAD = '/resources/upload';
    const DIR_RESOURCES_TEMPLATE = '/resources/upload/template';
    const DIR_RESOURCES_THEME = '/resources/upload/theme';
    const DIR_RESOURCES_SCRIPT = '/resources/upload/script';
    const DIR_CUSTOM_IMPORT= '/interface/custom/import';

    const UPLOAD_DIR = '/jssor-slider';
    const UPLOAD_GENCODES = '/jssor-slider/gencodes';
    const UPLOAD_GENSLIDER_HTML = '/jssor-slider/genslider_html';
    const UPLOAD_THUMBNAILS = '/jssor-slider/thumbnails';
    const UPLOAD_TEMP = '/jssor-slider/temp';

    const UPLOAD_SLIDER = '/jssor-slider/slider';
    const UPLOAD_HTML = '/jssor-slider/html';
    const UPLOAD_USER = '/jssor-slider/user';

    const UPLOAD_JSSOR_COM = '/jssor-slider/jssor.com';
    const UPLOAD_TEMPLATE = '/jssor-slider/jssor.com/template';
    const UPLOAD_THEME = '/jssor-slider/jssor.com/theme';
    const UPLOAD_SCRIPTS = '/jssor-slider/jssor.com/script';

    const URL_JSSOR = 'https://www.jssor.com';

    const URL_JSSOR_ACTIVATE = '/api2/activation.ashx?method=activate';
    const URL_JSSOR_DEACTIVATE = '/api2/activation.ashx?method=deactivate';
    const URL_JSSOR_GENCODE = '/api2/jssor_slider_coding.ashx?method=gencode';
    const URL_JSSOR_IMPORT = '/api2/jssor_slider_repository.ashx?method=GetSliderDocument';

    const REQUIREMENTS_MIN_UPLOAD_FILE_SIZE = 2097152;//2 * 1024 * 1024;
    const REQUIREMENTS_MIN_POST_FILE_SIZE = 8388608;//8 * 1024 * 1024;

    public static function URL_JSSOR_SECURE() {
        return self::URL_JSSOR;
    }

    public static function get_jssor_preview_slider_url($slider_id, $slider_filename)
    {
        $site_url = WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url();

        return $site_url.'?jssorextver=' . WP_JSSOR_SLIDER_VERSION . '&jssor_extension=preview_slider&id='.$slider_id.'&filename='.urlencode($slider_filename);
    }

    public static function get_jssor_slider_thumb_sizes()
    {
        return array(
            'jssor-grid-thumb' => array('width' => 220, 'height' => 160, 'crop' => true)
            //'jssor-list-thumb' => array('width' => 80, 'height' => 31, 'crop' => true)
            );
    }

    public static function get_jssor_wordpress_site_url()
    {
        $site_url = site_url();
        $site_url = trailingslashit($site_url);
        return $site_url;
    }

    public static function get_jssor_wordpress_site_info()
    {
        $siteInfo = new WP_Jssor_Slider_Site_Info ();

        $siteInfo->instid = get_option('wp_jssor_slider_instance_id', '');
        $siteInfo->hosturl = WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url();

        return $siteInfo;
    }

    public static function get_jssor_wordpress_admin_info()
    {
        $adminInfo = new WP_Jssor_Slider_Admin_Info ();

        $hosturl = WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url();
        $adminurl = admin_url();
        $adminurl = trailingslashit($adminurl);
        $pluginurl = WP_JSSOR_SLIDER_URL;
        $pluginurl = trailingslashit($pluginurl);

        $adminInfo->instid = get_option('wp_jssor_slider_instance_id', '');
        $adminInfo->hosturl = $hosturl;
        $adminInfo->adminurl = $adminurl;
        $adminInfo->pluginurl = $pluginurl;
        $adminInfo->importsliderurl = $hosturl . sprintf('?jssorextver=%s&jssor_extension=import_slider_with_progress', WP_JSSOR_SLIDER_VERSION);
        $adminInfo->mediabrowserurl = $hosturl . WP_JSSOR_MEDIA_BROWSER_URL;
        $adminInfo->actcode = get_option('wjssl_actcode');

        return $adminInfo;
    }

    public static function get_jssor_wordpress_updates_info()
    {
        //version info
        $latest_version = get_option('wjssl-latest-version', WP_JSSOR_SLIDER_VERSION);
        $stable_version = get_option('wjssl-stable-version', '1.2.3');
        $beta_version = get_option('wjssl-beta-version', WP_JSSOR_SLIDER_VERSION);
        $stable_update_available = version_compare(WP_JSSOR_SLIDER_VERSION, $stable_version, '<');
        $latest_update_available = version_compare(WP_JSSOR_SLIDER_VERSION, $latest_version, '<');
        $beta_update_available = version_compare(WP_JSSOR_SLIDER_VERSION, $beta_version, '<');

        return array(
                'version' => WP_JSSOR_SLIDER_VERSION,
                'stable_version' => $stable_version,
                'latest_version' => $latest_version,
                'beta_version' => $beta_version,
                'stable_update_available' => $stable_update_available,
                'latest_update_available' => $latest_update_available,
                'beta_update_available' => $beta_update_available,
                'update_available' => $stable_update_available || $latest_update_available || $beta_update_available
            );
    }

    /**
     * remove utf-8 bom header
     *
     * @return string
     */
    public static function trim_bom($contents)
    {
        $rest = $contents;
        $charset[1] = substr($contents, 0, 1);
        $charset[2] = substr($contents, 1, 1);
        $charset[3] = substr($contents, 2, 1);
        if(ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
            $rest = substr($contents, 3);
        }
        return $rest;
    }

}

#region common classes

class WP_Jssor_Slider_API_Info
{
    public $error = 0;
    public $message = null;
}

class WP_Jssor_Slider_Site_Info
{
    public $instid = null;
    public $hosturl = null;
}

class WP_Jssor_Slider_Admin_Info
{
    public $instid = null;
    public $hosturl = null;
    public $adminurl = null;
    public $pluginurl = null;
    public $importsliderurl = null;
    public $mediabrowserurl = null;
    public $actcode = null;
}

class Wjssl_Jssor_Res_Info extends Wjssl_External_Res_Info
{
    //path comes with install package
    public $install_path;

    public function ensure()
    {
        $success = false;

        if($this->is_valid) {
            if(file_exists($this->local_path)) {
                $success = true;
            }
            else if(file_exists($this->install_path)) {
                $success = copy($this->install_path, $this->local_path);
            }
        }

        return $success;
    }

    public function installed()
    {
        return file_exists($this->install_path);
    }
}

class Wjssl_External_Res_Info {
    public $is_valid = false;

    public $remote_url;

    //url to local server
    public $local_url;

    //relative path
    public $rel_path;

    //path on local server
    public $local_path;


    //relative path to upload dir
    public $upload_rel_path;

    public function ensure()
    {
        $success = false;

        if($this->is_valid) {
            if(file_exists($this->local_path)) {
                $success = true;
            }
        }

        return $success;
    }

}

class Wjssl_Local_Res_Info
{
    public $path_array = null;

    public $is_valid = false;

    public $under_upload_dir = false;

    //url to local server
    public $local_url = null;

    //path of local url
    public $path;

    //path on local server
    public $local_path = null;

    //extention
    public $ext = null;

    //relative path to upload dir
    public $upload_rel_path = null;

    //unique url to identify media
    public $upload_key_url = null;

    //unique key to upload media
    public $upload_key = null;

    public function exists() {
        return $this->is_valid && file_exists($this->local_path);
    }

    public function get_url_part($part) {
        if(empty($path_array))
            return null;

        return $path_array[$part];
    }
}

#endregion

#region exceptions

class ExtensionMissingException extends Exception {
}

class FileNotFoundException extends Exception {
}

class IllegalArgumentException extends Exception {
}

class WPErrorException extends Exception
{
    protected $wp_error = null;

    public function __construct($wp_error = null, $previous = NULL)
    {
        if (empty($wp_error)) {
            $wp_error = new WP_Error();
        }
        $this->wp_error = $wp_error;
        $message = $wp_error->get_error_message();
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return WP_Error
     */
    public function getWPError()
    {
        return $this->wp_error;
    }
}

#endregion
