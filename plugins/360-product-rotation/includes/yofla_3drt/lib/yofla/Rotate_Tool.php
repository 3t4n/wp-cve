<?php
/**
 * Rotate_Tool
 *
 * Script for embedding a full 360 degree product view into a webpage, providing just the  product images
 *
 * TLDR: <?php echo Rotate_Tool::get_iframe('jewels/neck/jewel-03');?>
 *
 * Support: http://www.yofla.com/3d-rotate/contact
 *
 * @author Matus Laco, www.yofla.com, matus@yofla.com
 * @version 0.2.7
 * @updated 25. May 2019
 * @since May 2014
 * @copyright Matus Laco, www.yofla.com
 * @license GPLv2 for WordPress Plugin
 * @license Commerical license needed for other usages
 *
 * Prerequisites:
 *  1) Product Images
 *     Place product photos into a directory within the "products folder",
 *     e.g. "products/backpack".
 *
 *     It is possible to use a separate set of  high-resolution images. Then use these folder names
 *     - "products/backpack/images" for normal images (e.g. 400x300)
 *     - "products/backpack/imageslarge" for hi-res images (e.g. 1024x768)
 *
 * Full documentation:
 * https://www.yofla.com/3d-rotate/
 *
 *
 */

include_once "Rotate_Config.php";
include_once "Rotate_Utils.php";

class Rotate_Tool
{
    const SCRIPT_VERSION = '0.2.6';
    const SYSTEM_DIRECTORY_NAME = 'yofla_3drt'; //the directory where the templates,lib,player_files subdirs are
    const PRODUCTS_DIRECTORY_NAME = 'products';
    const IMAGES_DIRECTORY = 'images';
    const IMAGES_DIRECTORY_LARGE = 'imageslarge';
    const ASSETS_DIRECTORY = 'assets';

    public static $system_path;   //full system path to "system directory"
    public static $cache_path;    //full system path to "cache directory"
    public static $system_url;    //url of the "system directory"
    public static $products_path; //full system path to "products directory"
    public static $products_url;  //url to products directory
    public static $cache_url;    //url to cache directory
    public static $settings;      //array of defaults from settings.ini
    public static $errors = array();        //for error logging
    public static $products_list = array(); //list of available products
    public static $is_cache_disabled = false;
    public static $rotatetool_js_src = false;

    private static $_is_initialized = false;
    private static $_reserved_directory_names = array(
        self::IMAGES_DIRECTORY,
        self::IMAGES_DIRECTORY_LARGE,
        self::ASSETS_DIRECTORY);
    private static $_products_csv_path = null; //full system path to products csv

    private static $_options = array();

    public static function disable_cache()
    {
        self::$is_cache_disabled = true;
    }


    /**
     * Initializes the Class with defaults
     */
    private static function _initialize()
    {
        //set the full system path to the system directory
        self::_check_system_path();
        
        //set web root
        self::set_system_url();

        //set default cache dirs
        self::set_cache_path();

        //set default cache url
        self::set_cache_url();

        //load settings
        self::_load_settings();

        //set paths to products directory
        self::_check_products_path();

        //check cache folders
        self::_check_cache_directories();
        
        //set vars
        self::$_products_csv_path = self::$cache_path."products.dat";
        
        //set initialized
        self::$_is_initialized = true;
    }


    /**
     * Checks if sytem_directory is set, if not, uses the calee script path
     */
    private static function _check_system_path()
    {
        //set system path if it is not already set
        if (self::$system_path === NULL)
        {
            $path = dirname(__FILE__); //get system path to current file
            $pos = strpos($path,'/lib/yofla'); //get position of the lib subdirectory system directory
            if($pos) $path = substr($path,0,$pos); //remove path after /lib subdirectory, this ensures we have the "system path"
            self::set_system_path($path);
        }
    }

    /**
     * Checks if full system path to products directory is set
     */
    private static function _check_products_path()
    {
        if(self::$system_path === NULL) return FALSE;

        if (!self::$products_path)
        {
            self::$products_path = self::$system_path.self::PRODUCTS_DIRECTORY_NAME."/";
        }

        if (!self::$products_url)
        {
            self::$products_url = self::$system_url.self::PRODUCTS_DIRECTORY_NAME."/";
        }
    }

    /**
     * if cache folder is deleted, try to recreate it
     */
    private static function _check_cache_directories()
    {

        if ( !isset( self::$cache_path ) )
        {
            self::$cache_path = self::$system_path.'cache/';
        }

       $cache_dir_paths = array();
       $cache_dir_paths[] = self::$cache_path;
       $cache_dir_paths[] = self::$cache_path.'configs';
       $cache_dir_paths[] = self::$cache_path.'pages';

       foreach($cache_dir_paths as $path)
       {
           if (!file_exists($path))
           {
               //todo check if sucessfull
               @mkdir($path, 0777, true);
           }
       }
    }

    /**
     * Loads the script settings from the settings.ini file
     */
    private static function _load_settings()
    {

        //settings.ini in products folder takes precedense
        $ini_file = self::$products_path.'settings.ini';
        if(!file_exists($ini_file))  $ini_file = self::$system_path.'settings.ini';

        self::$settings = parse_ini_file($ini_file,true);

        //inject class variable setting, if set
        if(self::$rotatetool_js_src !== false)
        {
            self::$settings['system']['rotatetoolUrl'] = self::$rotatetool_js_src;
        }

        //set products path, if set
        if(isset(self::$settings['system']['productsPath']))
        {
            self::set_products_path(self::$settings['system']['productsPath']);
        }

        //set products url, if set
        if(isset(self::$settings['system']['productsUrl']))
        {
            self::set_products_url(self::$settings['system']['productsUrl']);
        }
    }

    /**
     * Check whether the class initialization has run
     */
    private static function _check_initialized()
    {
        if (self::$_is_initialized === false)
        {
            self::_initialize();
        }
        //return false if intialization failed
        return self::$system_path !== NULL;
    }

    /**
     * Sets the url path to the "system directory"
     *
     * @param null $url
     */
    public static function set_system_url($url = null)
    {
        //do not override system_url if set before
        if($url == null && isset(self::$system_url)) $url = self::$system_url;

        if (is_null($url))
        {
            //assume no url rewrite is in place and the url is the same as the physical location of the files
            $path_raw = substr(dirname(__FILE__), strlen($_SERVER[ 'DOCUMENT_ROOT' ])); //get path after domain, to current script
            $pos = strpos($path_raw,'/lib/yofla'); //get position where the "system directory ends"
            $path = substr($path_raw,0,$pos); //remove the part after "system directory"
            $path= ltrim ($path, '/');
            $system_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/'.$path;
        }
        else
        {
            //url is set from parameter
            $system_url = $url;
        }

        //set value
        self::$system_url = Rotate_Tool::_add_trailing_slash($system_url);
    }

    /**
     * Sets the url path to the "products directory"
     *
     * @param null $url
     */
    public static function set_products_url($url = null)
    {
        if (is_null($url))
        {
            //no action
        }
        else
        {
            //set value
            self::$products_url = Rotate_Tool::_add_trailing_slash($url);
        }
    }

    /**
     * Sets the url path to the "cache directory"
     *
     * @param string $url
     */
    public static function set_cache_url($url = '')
    {
        if ( $url == '')
        {
            //set default value if not already set
            if ( !isset(self::$cache_url) )
            {
                self::$cache_url = Rotate_Tool::_add_trailing_slash(self::$system_url.'cache');
            }
        }
        else
        {
            //set value
            self::$cache_url = Rotate_Tool::_add_trailing_slash($url);
        }
    }

    /**
     * Sets the full system path to the main yofla_3drt directory
     *
     * @param $path
     */
    public static function set_system_path($path = null)
    {

        //do not overwrite system_path if set before
        if($path == null && isset(self::$system_path)) $path = self::$system_path;

        if(is_dir($path))
        {
            if (function_exists('realpath') AND @realpath($path) !== FALSE)
            {
                self::$system_path = Rotate_Tool::_add_trailing_slash(realpath($path));
            }
            else
            {
                self::$system_path = Rotate_Tool::_add_trailing_slash(path);
            }
        }
        else
        {
            self::$errors[] = 'Provided path "'.$path.'" for yofla_3drt system directory is not valid!'."\n";
        }
    }

    /**
     * Sets the full system path to the products directory
     *
     * @param $path
     */
    public static function set_products_path($path = null)
    {

        //do not overwrite products_path if set before
        if($path == null && isset(self::$products_path)) $path = self::$products_path;

        if(is_dir($path))
        {
            if (function_exists('realpath') AND @realpath($path) !== FALSE)
            {
                self::$products_path = Rotate_Tool::_add_trailing_slash(realpath($path));
            }
            else
            {
                self::$products_path = Rotate_Tool::_add_trailing_slash(path);
            }
        }
        else
        {
            self::$errors[] = 'Provided path "'.$path.'" for yofla_3drt path directory is not valid!'."\n";
        }
    }

    /**
     * Sets the full system path to the cache directory
     *
     * @param $path
     */
    public static function set_cache_path($path = null)
    {
        if($path)
        {
            self::$cache_path = self::_add_trailing_slash($path);
        }
        self::_check_cache_directories();
    }

    /**
     * Returns an array with products, structure for each array entry:
     * dir_name
     * path
     * iframe_url
     * config_url
     */
    public static function get_products_list()
    {
       //check if paths are set
       if (self::_check_initialized() === FALSE) return FALSE;

       if (file_exists(self::$_products_csv_path))
       {
           $products_list = Rotate_Utils::file_to_array(self::$_products_csv_path);
           return $products_list;
       }
       else
       {
           self::scan();
           return self::$products_list;
       }
    }


    /**
     * Scans the products directory for products,
     * generates config.js for each directory found (and stores it in cache)
     * generates iframe content html page for each product (and stores it in cache)
     * generates and stores a map of products and config.js files (in cache/products.csv)
     *
     * saves map to class products_list variable
     *
     * When running this function, all cached configs,pages, products.dat is regenerated
     *
     * @return array|bool
     */
    public static function scan()
    {
        //check if paths are set
        if (self::_check_initialized() === FALSE) return FALSE;

        //scan directories recursively
        self::_scan_directory(self::$products_path);

        //save to cache
        Rotate_Utils::array_to_file(self::$products_list,self::$_products_csv_path);


        return (self::$products_list);
    }//function scan

    /**
     * Scans directory and it's subdirectories for product images (and optional settings.ini file)
     * and stores the result in the class variable Rotate_Tool::$products_list  as array.
     * 
     * @param $path
     * @param $settings
     */
    private static function _scan_directory($path,$settings = NULL)
    {
        //fix path
        $path = self::_add_trailing_slash($path);

        //set default settings
        if($settings === NULL) $settings = self::$settings;
        //get local settings for this directory
        $settings_local = self::_get_directory_settings($path);

        //merge settings
        if($settings_local) $settings = Rotate_Utils::merge_settings($settings, $settings_local);

        //read dir
        $dir = dir($path);

        //loop dir contest
        while(false !== ($entry = $dir->read())) {
            //skip hidden files
            if($entry[0] == ".") continue;
            //construct path
            $filename = $path.$entry;
            //process dirs
            if(is_dir($filename))
            {
                //exclude reserved directories
                if (in_array($entry,self::$_reserved_directory_names) == FALSE)
                {
                    self::_scan_directory($filename,$settings);
                    self::_add_directory_to_products_list($filename,$settings);
                }
            }
        }

    }


    /**
     * If provided path is a valid products directory, it
     * 1) generates config.js ( gets stored in cache)
     * 2) adds entry in class variable products_list
     *
     * @param string $path The full system path
     * @param $settings The parent settings
     */
    private static function _add_directory_to_products_list($path,$settings = null)
    {

        if(self::_has_directory_product_images($path))
        {

           $path_relative =  self::_get_relative_path_from_full_path($path);
           $dir_name = basename($path_relative);

           //force creating cached config
           self::get_config_file_content($path,$settings);
           //force creating cached iframe pages
           self::get_page_for_iframe($path_relative);

           $iframe_url = self::get_iframe_url($path_relative);
           $config_url = self::get_cached_config_url($path_relative);

           //get local settings for product info or other info
           $settings_local = self::_get_directory_settings($path);

           $name = (isset($settings_local["product"]["name"])) ? $settings_local["product"]["name"] : $dir_name;


           $entry = array(
               "dir_name" => $dir_name,
               "path" => $path_relative,
               "iframe_url" => $iframe_url,
               "config_url" => $config_url,
               "product_name" => $name,
           );

           //add to products list
           self::$products_list[] = $entry;

        }
    }


    /**
     * Returns iframe embed code with product rotation
     *
     * @param string $path The relative path to the directory with images (relative to products folder)
     * @param array $options The options as associative array to create the iframe and rotation with.
     * @return string The iframe code with working rotation
     */
    public static function get_iframe($path,$options=null)
    {
        //check if class initialized
        if (self::_check_initialized() === FALSE) return '3DRT initialization failed';

        //store previous options
        self::_store_options();

        //update cache setting, if set
        if(isset($options['cache']) && $options['cache'] === false) self::$is_cache_disabled = true;

        //check and set the products path
        $full_path = self::_getSystemPathToProduct($path,$options);
        if (!file_exists($full_path)) return 'Error: Path "'.$path.'" does not exist!';

        //set products url, if set
        if(isset($options['system']['productsUrl'])) self::$products_url = self::_add_trailing_slash($options['system']['productsUrl']);

        //get settings
        $settings_product = self::get_cascading_settings_for_directory($full_path);


        //consturct iframe url - important step!
        $iframe_url = self::get_iframe_url($path,$options);


        $width = $settings_product["player"]["width"];
        $height = $settings_product["player"]["height"];
        $iframe_style = $settings_product["player"]["iframeStyle"];

        //override width/height, if set
        if(isset($options["width"])) $width = intval($options["width"]);
        if(isset($options["height"])) $height = intval($options["height"]);

        $iframe = array();
        $iframe["width"] = "{$width}px";
        $iframe["height"] = "{$height}px";
        $iframe["src"] = $iframe_url;

        $values = array();
        $values["{script_version}"] = self::SCRIPT_VERSION;
        $values["{name}"] = "3drt-iframe";
        $values["{width}"] = $iframe["width"];
        $values["{height}"] = $iframe["height"];
        $values["{src}"] = $iframe["src"];
        $values["{class}"] = "yofla_360_iframe";
        $values["{style}"] = $iframe_style;

        //get iframe embed code
        $content =  self::_get_template_output('iframe_tag.tpl',$values);

        //restore settings
        self::_restore_options();

        //return content
        return $content;
    }

    /**
     * Returns the url of the html page that is used inside of the iframe embed code as src parameter
     *
     * @param $path
     * @param null $options Are the optional options, that override the settings specified in settings ini
     * @return string
     */
    public static function get_iframe_url($path,$options = null)
    {
        //check if paths are set
        if (self::_check_initialized() === FALSE) return '';

        $is_cache_disabled  = (isset($options['cache']) && $options['cache'] == false) || self::$is_cache_disabled;

        $path_to_cached_page = self::_get_cached_iframe_page_system_path($path);

        if (file_exists($path_to_cached_page) && $is_cache_disabled == false)
        {
             //no action, file exists and cache is enabled
        }
        else //generate cached iframe page
        {
            //get settings
            if($options) $settings = Rotate_Utils::merge_settings(self::$settings,$options);

            self::get_page_for_iframe($path,$settings);
        }

        //return url
        return self::get_cached_iframe_page_url($path);

    }//end get_iframe_url


    /**
     * Returns embed code as div (not as iframe)
     *
     * @param string $path The relative path to the directory with images (relative to products folder)
     * @param array $options The options as associative array to construct the embed code
     * @return string The iframe code with working rotation
     */
    public static function get_div_embed($path,$options=null)
    {

        //check if class initialized
        if (self::_check_initialized() === FALSE) return '3DRT initialization failed';

        //store previous options
        self::_store_options();

        //update cache setting, if set
        if(isset($options['cache']) && $options['cache'] === false) self::$is_cache_disabled = true;

        //check and set the products path
        $full_path = self::_add_trailing_slash(self::_getSystemPathToProduct($path,$options));
        if (!file_exists($full_path)) return 'Error: Path "'.$path.'" does not exist!';

        //set products url, if set
        if(isset($options['system']['productsUrl'])) self::$products_url = self::_add_trailing_slash($options['system']['productsUrl']);


        //template data
        $values = array();
        $values["{script_version}"] = self::SCRIPT_VERSION;
        $values["{config_file}"] = self::get_config_url_for_product($path,$options);
        $values["{theme_url}"]   = self::_get_theme_url($options);
        $values["{style}"]      = ($options['style']) ? $options['style'] : '';
        $values["{divId}"]      = ($options['id']) ? $options['id'] : 'yofla360_';
        $values["{class}"]      = ($options['class']) ? $options['class'] : '';
        $values["{path}"]       = "";

        //check if config.js is present (3drt setup utility uploaded content)
        if(file_exists($full_path.'config.js')){
            $values["{path}"]  = self::$products_url.$path;
            $values["{config_file}"] = ""; //reset if path is set
            if(!isset($settings["system"]["theme"])){
                  $values["{theme_url}"] = ""; //reset if path if not set custom theme url in options
            }
        }

        //get iframe embed code
        $content =  self::_get_template_output('div_tag.tpl',$values);

        //restore settings
        self::_restore_options();

        //return content
        return $content.PHP_EOL;
    }

    /**
     * Returns the location
     *
     * @return string
     */
    public static function get_rotatetooljs_url()
    {
        //check if class initialized
        if (self::_check_initialized() === FALSE) return '3DRT initialization failed';
        return self::_get_rotatetool_url();
    }


    private static function _get_cached_iframe_page_system_path($path)
    {
        $filename = self::_get_cached_iframe_page_filename($path);
        $path = self::$cache_path."pages/".$filename;
        return $path;
    }

    private static function _get_cached_iframe_page_filename($path)
    {
        $filename = str_replace("/","_",$path)."_iframe.html";
        return $filename;
    }

    /**
     * Returns the url of cached iframe.html for given product
     *
     * @param $path string The relative path to the products directory
     * @return string
     */
    public static function get_cached_iframe_page_url($path)
    {
        $filename = self::_get_cached_iframe_page_filename($path);
        $url = self::$cache_url."pages/".$filename;
        return $url;
    }

    /**
     * Returns the html content of the page, that is hosted within the
     * iframe embed code. Stores it in cache also.
     *
     * @param string $path The relative path to main products folder
     * @param array $settings
     * @return string
     */
    public static function get_page_for_iframe($path,$settings=NULL)
    {
        //check if paths are set
        if (self::_check_initialized() === FALSE) return '';

        //set settings
        $settings = ($settings === NULL) ? self::$settings : $settings;

        $rotatetool_url = self::_get_rotatetool_url($settings);
        $theme_url = self::_get_theme_url($settings);

        $config_url = self::get_config_url_for_product($path,$settings);

        $values = array();
        $values["{generator}"] = '3D Rotate Tool :: PHP Script by YoFLA.com, Version: '.self::SCRIPT_VERSION;
        $values["{rotatetool.js}"] = $rotatetool_url;
        $values["{config_file}"] = $config_url;
        $values["{theme_url}"] = $theme_url;
        $values["{title}"] = '360 product view';
        $values["{path}"]  = "";
        $values["{ga_data}"]  = "{}";

        if(isset($settings["ga_enabled"]) && isset ($settings['ga_tracking_id'] ))
        {
            $ga_data_format = '{"isEnabled":"true", "trackingId":"%s", "label":"%s", "category":"%s"}';
            $values["{ga_data}"]  = sprintf($ga_data_format,$settings["ga_tracking_id"], $settings["ga_label"],$settings["ga_category"]);
        }

        if( isset($settings['product_name']) )
        {
            $values["{title}"] = $settings['product_name'];
        }

        //check if config.js is present (3drt setup utility uploaded content)
        //and ajdust variables
        $full_path = self::_add_trailing_slash(self::_getSystemPathToProduct($path,$settings));
        if(file_exists($full_path.'config.js')){
            $values["{path}"]  = self::$products_url.$path;
            $values["{config_file}"] = ""; //reset if path is set
            if(!isset($settings["system"]["theme"])){
                $values["{theme_url}"] = ""; //reset if path if not set custom theme url in options
            }
        }

        $content = self::_get_template_output('page_for_iframe.tpl',$values);

        //store content to cache
        $cache_filename = self::_get_cached_iframe_page_system_path($path);
        Rotate_Utils::write_file($cache_filename,$content);

        return $content;
    }


    public static function serve_page_for_iframe()
    {
        //check if paths are set
        if (self::_check_initialized() === FALSE) return '';

        //check if GET path parameter is set
        if (self::_check_path_parameter() === FALSE)
        {
            self::_serve_error("Path parameter not set!");
            return;
        }

        //get path
        $path = urldecode($_GET["p"]);

        //todo write serve function
        die(self::get_page_for_iframe($path));
    }



    /**
     * Generates config file and provides url for that file. If cache is on,
     * returns cached file, if exists.
     *
     * @param $path Is relative path to the products folder
     * @param null $settings
     * @return string
     * @throws Exception
     */
    public static function get_config_url_for_product($path,$settings=null)
    {

        //check if paths are set
        if (self::_check_initialized() === FALSE) return '';

        //full path to product
        $full_path = Rotate_Tool::_add_trailing_slash(self::$products_path.$path);

        //check if config.js was provided in the folder
        if(file_exists($full_path.'config.js'))
        {
            $url = self::$products_url.Rotate_Tool::_add_trailing_slash($path).'config.js';
            return $url;
        }

        //check cache
        $cached_config_path_full = self::_get_cached_config_path_full($path);

        //serve cached version
        if(file_exists($cached_config_path_full) && self::$is_cache_disabled === false)
        {
            //url for cached version of previously generated config.js
            return self::get_cached_config_url($path);
        }
        //force creating cached config js
        else
        {

            if($settings)
            {
                $settings_product = $settings;
            }
            else{
                //get settings
                $settings_product = self::get_cascading_settings_for_directory($full_path);
            }

            //create (and cache) config file content
            $config_content = self::get_config_file_content($full_path,$settings_product);

            if(!$config_content || strlen($config_content) < 10)
            {
                $error = "Errors: ";
                $error .= implode('|',self::$errors);
                throw new Exception($error);
            }

            //return cached url
            return self::get_cached_config_url($path);
        }
    }

    /**
     * Returns the url of cached config.js for given product
     *
     * @param $path string The relative path to the products directory
     * @return string
     */
    public static function get_cached_config_url($path)
    {
        $url = self::$cache_url.'configs/'.self::_get_cached_config_filename($path);
        return $url;
    }
    


    /**
     * Called externally from get_config_file.php
     *
     */
    public static function generate_config_file()
    {
        //check if paths are set
        if (self::_check_initialized() === FALSE)
        {
            self::_serve_error("Initialization Failed!");
            return;
        }

        //check if GET path parameter is set
        if (self::_check_path_parameter() === FALSE)
        {
            self::_serve_error("Path parameter not set!");
            return;
        }

        //get path parameter
        $relative_path = urldecode($_GET["p"]);

        //construct system path to directory
        $path = self::$products_path.$relative_path;

        // get (and cache) content of configFile
        $config_content = self::get_config_file_content($path,self::get_cascading_settings_for_directory($path));

        //error when fetching images list
        if($config_content === FALSE)
        {
            self::_serve_error("No images found in: $path");
            return;
        }


        // serve file
        self::_serve_file($config_content,'application/javascript');
    }

    /**
     * Returns the content of the config.js file, based on provided path to
     * product directory with images, stores also to cache
     *
     * @param string $path The full system path to product directory
     * @param null $settings The parent settings to inherit from
     * @return bool|string
     */
    public static function get_config_file_content($path,$settings = NULL)
    {

        //check if paths are set
        if (self::_check_initialized() === FALSE) {
            self::$errors[] = "get_config_file_content() : error in _check_initialized(): $path";
            return FALSE;
        }


        //get list of images
        $images_list = self::get_images_list($path);


        //error when fetching images list
        if($images_list === FALSE)
        {
            self::$errors[] = "get_config_file_content() : error fetching image list using path: $path";
            return FALSE;
        }

        $path_relative = self::_get_relative_path_from_full_path($path);

        //create config file
        $config = new Rotate_Config();

        //set paths
        $config->products_path = self::$products_path;
        $config->products_url  = self::$products_url;

		$config->product_id   = 'yofla360_'.preg_replace('/[^a-z0-9]/i','_',trim($path_relative,'/'));

        //set images
        $config->images_list = $images_list;

        //set settings default settings, if provided settings as parameter are not set
        $config->settings = ($settings === NULL) ? self::$settings : $settings;

        //get json representation of the config file
        $output = $config->get_config_json();


        //cache config
        $path_relative = self::_get_relative_path_from_full_path($path);
        $config_full_path = self::_get_cached_config_path_full($path_relative); //tag
        Rotate_Utils::write_file($config_full_path,$output);

        return $output;
    }


    /**
     * Returns full system path to the location (inclusive filename) of the cached
     * config.js file for given product.
     *
     * @param $path string The relative path of the product, relative to the products directory
     * @return string
     */
    private static function _get_cached_config_path_full($path)
    {
        $filename = self::_get_cached_config_filename($path);
        $full_path = self::$cache_path.'configs/'.$filename;
        return $full_path;
    }


    /**
     * Returns the cached config.js filename, that is crated based on the relative path
     *
     * @param $path string The relative path for the product, relative to the products directory
     * @return string
     */
    private static function _get_cached_config_filename($path)
    {
        $path = Rotate_Tool::_add_trailing_slash($path);
        $filename = str_replace('/','_',$path).'_config.js';
        return $filename;
    }



    /**
     * Returns an associative array of images (imageslarge) in given directory.
     * Return format array("images"=>array(),"imageslarge"=>array(), "settings"=>array()).
     *
     * If given directory does not have the images,imageslarge subdirectories,
     * the give directory is scanned
     *
     * Returns also parsed settings.ini file, if found in directory
     *
     * @param $path Is the absolute system path to the products directory
     * @return array
     */
    public static function get_images_list($path)
    {

        //init return value
        $images_list = array("images"=>NULL,"imageslarge"=>NULL,"settings"=>NULL);

        //exit if param is not a dir
        if(!is_dir($path))
        {
            self::$errors[] = "get_images_list() : provided path ($path) is not a valid directory!";
            return FALSE;
        }

        //add trailing slash to path if missing
        $path = Rotate_Tool::_add_trailing_slash($path);

        //check if images directory exists
        $images_directory = $path."images";
        if(is_dir($images_directory)) $images_list["images"] = self::get_images_in_directory($images_directory);

        //check if images large directory exists
        $imageslarge_directory = $path."imageslarge";
        if(is_dir($imageslarge_directory)) $images_list["imageslarge"] = self::get_images_in_directory($imageslarge_directory);
        
        // check if images original directory exists 
		$imagesOriginal_directory = $path."imagesOriginal";
		if(is_dir($imagesOriginal_directory)) {
			$images_list["imagesOriginal"] = self::get_images_in_directory($imagesOriginal_directory);
			$images_list["images"] = $images_list["imagesOriginal"];
		}

        //get images list in directory
        if ($images_list["images"] === NULL)
        {
        	// other path
            $images_list["images"] = self::get_images_in_directory($path);
        }

        //check settings.ini file
        $images_list["settings"] = self::_get_directory_settings($path);

        return $images_list;
    }

    /**
     * Returns an array of images in given directory
     *
     * @param $path
     * @return array|bool
     */
    public static function get_images_in_directory($path)
    {
        //validate parameter
        if (!is_dir($path)) return NULL;

        //add trailing slash to path if missing
        $path = Rotate_Tool::_add_trailing_slash($path);

        //init vars
        $result = array();

        //read dir
        $dir = dir($path);
        //loop dir contest
        while(false !== ($entry = $dir->read())) {
            //skip hidden files
            if($entry[0] == ".") continue;
            $filename = "$path$entry";
            //skip dirs
            if(is_dir($filename)) continue;
            //add image
            if(self::is_file_supported_image($filename)) $result[] = $filename;
        }

        //return
        return $result;
    }

    /**
     * Checks if file is a supported image. Supported extensions : jpg,jpeg,gif,png,bmp
     *
     * @param $path
     * @return bool
     */
    public static function is_file_supported_image($path)
    {
       $supported_extensions = array('jpg','jpeg','gif','png','bmp');
       if(is_file($path)){
           $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
           return in_array( $extension, $supported_extensions);
       }
       else
       {
           return FALSE;
       }
    }

    /**
     * Returns a template string with replaced template variables
     *
     * @param $template_filename
     * @param $values
     * @return string
     */
    private static function _get_template_output($template_filename,$values)
    {

        //construct path
        $template_file_path = self::$system_path.'templates/'.$template_filename;

        //check template file path
        if(is_file($template_file_path) === FALSE)
        {
            self::$errors[] = 'Template File '.$template_file_path.' not found'."\n";
            return '';
        }

        //load template
        $template_string = file_get_contents($template_file_path);

        //modify template
        $new_html = strtr($template_string,$values);

        //return
        return $new_html;
    }


    /**
     * Adds trailing slash to provided string/path
     *
     * @param $path
     * @return string
     */
    private static function _add_trailing_slash($path)
    {
        //add trailing slash to path if missing
        if(substr($path, -1) != "/") $path .= "/";
        return $path;
    }


    /**
     * Checks if script is called with correct get parameter
     *
     * @return bool
     */
    private static function _check_path_parameter()
    {
       if(isset($_GET["p"]))
       {
           return TRUE;
       }
       else
       {
           return FALSE;
       }
    }

    /**
     * Checks if provided direcotry contains product images
     * 
     * @param $path
     * @return bool
     */
    private static function _has_directory_product_images($path)
    {
       $images_list = self::get_images_list($path);
        
       if($images_list && isset($images_list["images"]) && sizeof($images_list["images"]) > 0 )
       {
           return TRUE;
       }
       else
       {
           return FALSE; 
       }
    }

    /**
     * Returns parsed settings.ini file in given directory, if it exists
     *
     * @param $path
     * @return array|bool
     */
    private static function _get_directory_settings($path)
    {
       $path_fixed = self::_add_trailing_slash($path);
       $path_settings = $path_fixed.'settings.ini';
       if(is_file($path_settings))
       {
           $settings = parse_ini_file($path_settings,TRUE);
           return $settings;
       }
       else
       {
           return NULL;
       }
    }

    /**
     * Returns relative path to products directory from full system path
     *
     * @param $path
     * @return string
     */
    private static function _get_relative_path_from_full_path($path)
    {
       return substr($path,strlen(self::$products_path));
    }


    /**
     * Scan all parent directories until main system directory for settings.ini and construct
     * directory-specific settings array based on settings.ini files found along the path
     *
     * @param string $path The full system path
     * @return array
     */
    public static function  get_cascading_settings_for_directory($path)
    {

        //path relative to products directory
        $relative_path = self::_get_relative_path_from_full_path($path);

        //get directories on path as array
        $dirs_array = explode("/",$relative_path);

        //default settings
        $actual_settings = self::$settings;

        for ($i=0; $i<sizeof($dirs_array); $i++)
        {
            $dirs_to_add = $i+1;

            $relative_path_array = array();

            for($j=1; $j<=$dirs_to_add;$j++)
            {
                $relative_path_array[] =  $dirs_array[$j-1];
            }

            $relative_path = implode('/',$relative_path_array);

            $full_system_path  = self::$products_path.$relative_path;
            
            $settings = self::_get_directory_settings($full_system_path);
            if(is_array($settings))
            {
                //merge settings
                $new_settings =  Rotate_Utils::merge_settings($actual_settings, $settings);
                $actual_settings = $new_settings;
            }
        }

        return $actual_settings;
    }


    /**
     * Store options that can be overriden by custom set options in e.g. get_iframe
     * method
     *
     */
    private static function _store_options()
    {
        self::$_options['was_cache'] = self::$is_cache_disabled;
        self::$_options['was_products_path'] = self::$products_path;
        self::$_options['was_products_url'] = self::$products_url;
    }

    /**
     *
     */
    private static function _restore_options()
    {
        self::$is_cache_disabled = self::$_options['was_cache'];
        self::$products_path = self::$_options['was_products_path'];
        self::$products_url = self::$_options['was_products_url'];
    }


    /**
     * Returns the full system path to product - to dir with images
     *
     * @param $path
     * @param null $options
     * @return string
     */
    private static function _getSystemPathToProduct($path,$options = null)
    {
        if(isset($options['system']['productsPath']))
        {
            $products_path = self::_add_trailing_slash($options['system']['productsPath']);
            self::$products_path = $products_path;
            $full_path = $products_path.$path;
        }
        else
        {
            $full_path = self::$products_path.$path;
        }
        return $full_path;
    }

    private static function _get_rotatetool_url($settings = null)
    {

        //set settings
        $settings = ($settings === NULL) ? self::$settings : $settings;

        //get rotatetool.js location
        if($settings["system"]["rotatetoolUrl"])
        {
            $rotatetool_url =  $settings["system"]["rotatetoolUrl"];
        }
        else
        {
            //default location
            $rotatetool_url = self::$system_url.'player_files/rotatetool.js';
        }

        return $rotatetool_url;
    }

    private static function _get_theme_url($settings = null)
    {
        if(isset($settings["system"]["themeUrl"]))
        {
           return ($settings["system"]["themeUrl"]);
        }
            
        //get theme url
        if(isset($settings["system"]["theme"]))
        {
            $theme_url = self::$system_url.'themes/'.$settings["system"]["theme"];
        }
        else
        {
            //default location
            $theme_url = self::$system_url.'themes/default';
        }

        return $theme_url;
    }


    /**
     * Outputs file to the browser
     *
     * @param $content
     * @param $content_type
     */
    private static function _serve_file($content,$content_type = NULL)
    {
        if ($content_type) header('Content-Type: '.$content_type);
        die($content);
    }

    private static function _serve_error($message)
    {
        die($message);
    }

}//class
