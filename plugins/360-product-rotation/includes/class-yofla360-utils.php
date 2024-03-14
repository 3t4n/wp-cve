<?php

if (!defined("ABSPATH")) {
	exit;
}

/**
 * Class YoFLA360Utils
 *
 * Utilities for integrating a 360 view
 *
 */
class YoFLA360Utils
{

	/** @var YoFLA360Utils The single instance of the class */
	protected static $_instance = null;

	/**
	 * Main YoFLA360Utils Instance
	 *
	 * Ensures only one instance of YoFLA360Utils is loaded or can be loaded.
	 *
	 * @static
	 * @return YoFLA360Utils Main instance
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
	}


	/**
	 * Checks if images directory is created by 3DRT Setup Utility or not
	 *
	 * @param $src the path to directory with 360 view data, relative to wordpress uploads directory
	 * @return bool
	 */
	public function is_created_with_desktop_application($src)
	{
		//if src is a absolute url path
		if ($this->is_url_path($src)) {
			return false;
		}

		$product_path_full = $this->get_full_product_path($src);

		$rotatetool_js_file_full_path = $product_path_full . 'rotatetool.js';
		$config_file_full_path = $product_path_full . 'config.js';


		if (file_exists($rotatetool_js_file_full_path) && file_exists($config_file_full_path)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks if images directory is created by the "next 360" product creator software
	 *
	 * @param $src the path to directory with 360 view data, relative to wordpress uploads directory
	 * @return bool
	 */
	public function is_created_with_next360($src){

		if(!$src){
			return false;
		}

		//if src is a absolute url path
		if ($this->is_url_path($src)) {
			return false;
		}

		$product_path_full = $this->get_full_product_path($src);

		$rotatetool_js_file_full_path = $product_path_full . 'yofla360.js';

		if (file_exists($rotatetool_js_file_full_path) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Src for cloud based vies is in format: [360 src="FOTWV4X7B;6uxvZguiN;1"]
	 *
	 * @param $src
	 * @return bool
	 */
	public function is_created_with_next360_cloud($src){
		$t = explode(';',$src);
		if(is_array($t) && sizeof($t) == 3){
			return true;
		}
		else{
			return false;
		}
	}


	/**
	 * Returns the full system url to the folder with 360 content
	 *
	 * The folder resides within wp uploads folder
	 *
	 * @param $src String Is the path relative to the wp uploads folder
	 * @return string
	 */
	public function get_full_product_path($src)
	{
		$src = trim($src, '/');
		$wp_uploads = wp_upload_dir();
		$product_path_full = trailingslashit(trailingslashit($wp_uploads['basedir']) . $src);
		return $product_path_full;
	}

	/**
	 * Returns the path to the product, relative to the uploads/yofla360 folder
	 *
	 * @param $src String
	 * @return string
	 */
	public function get_relative_product_path($src)
	{
		$src = trim($src, '/'); //remove starting '/'
		$src = str_replace(YOFLA_360_PRODUCTS_FOLDER . '/', '', $src); //remove starting yofla360/
		return $src;
	}


	/**
	 * Returns URL to the yofla360 folder within WP uploads
	 *
	 * @return string
	 */
	public function get_products_url()
	{
		$wp_uploads = wp_upload_dir();
		$uploads_url = trailingslashit($wp_uploads["baseurl"]);
		$products_url = $uploads_url . YOFLA_360_PRODUCTS_FOLDER . '/';
		return $products_url;
	}

	/**
	 * Returns URL to the 360 view (value of path attribute)
	 *
	 * @param $viewData YoFLA360Viewdata
	 * @return string
	 */
	public function get_product_url($viewData)
	{
		if ($this->is_url_path($viewData->src)) {
			return $viewData->src;
		} else {
			$src = trim($viewData->src, '/');
			return $this->get_uploads_url() . $src . '/';
		}
	}

	/**
	 * Returns URL to the WP uploads folder
	 *
	 * @return string
	 */
	public function get_uploads_url()
	{
		$uploads_url = trailingslashit($this->fn_get_upload_dir_var('baseurl'));
		return $uploads_url;
	}

	/**
	 * Get the upload URL/path in right way (works with SSL).
	 *
	 * @param $param string "basedir" or "baseurl"
	 * @param string $subfolder
	 * @return string
	 */
	public function fn_get_upload_dir_var($param, $subfolder = '')
	{
		$upload_dir = wp_upload_dir();
		$url = $upload_dir[$param];
		if ($param === 'baseurl' && is_ssl()) {
			$url = str_replace('http://', 'https://', $url);
		}
		return $url . $subfolder;
	}

	/**
	 * Returns system path to the yofla360 folder within WP uploads
	 *
	 * @return string
	 */
	public function get_products_path()
	{
		$wp_uploads = wp_upload_dir();
		return trailingslashit($wp_uploads['basedir']) . YOFLA_360_PRODUCTS_FOLDER . '/';
	}


	/**
	 * Returns true if provided string is an url
	 *
	 * @param $str
	 * @return bool
	 */
	public function is_url_path($str)
	{
		if ((strpos($str, 'http') === 0) || (strpos($str, '//') === 0)) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Returns the url of an iframe for provided view
	 *
	 * @see  construct_iframe_content
	 * @param $viewData YoFLA360ViewData
	 * @return string
	 */
	public function get_iframe_url($viewData)
	{
		//check required src
		if (empty($viewData->src)) {
			YoFLA360()->add_error("Src attribute not provided!");
			return false;
		}

		//config file is present or absolute path
		if ($this->is_created_with_desktop_application($viewData->src) || $this->is_url_path($viewData->src)) {
			//pass short code parameters to iframe content creator url
			$data = array(
				'src' => $viewData->src,
				'product_name' => $viewData->name,
				'ga_label' => $viewData->get_ga_label(),
				'ga_category' => $viewData->ga_category,
				'ga_tracking_id' => ($viewData->ga_enabled && $viewData->ga_tracking_id) ? $viewData->ga_tracking_id : false
			);

			$iframe_url = YOFLA_360_PLUGIN_URL . 'includes/iframe.php?' . http_build_query($data);
			return $iframe_url;
		} // config.js and rotatetool.js not in file system
		elseif($this->is_created_with_next360($viewData->src)){
			return $viewData->get_product_url().'iframe.html';
		}
		elseif($this->is_created_with_next360_cloud($viewData->src)){
			return $this->get_next360cloud_iframe_url($viewData->src);
		}
		else {

			$configUrl = $this->get_product_url($viewData).'config.js';

			if( $this->webItemExists($configUrl) ){
				//pass short code parameters to iframe content creator url
				$data = array(
					'src' => $this->get_product_url($viewData),
					'product_name' => $viewData->name,
					'ga_label' => $viewData->get_ga_label(),
					'ga_category' => $viewData->ga_category,
					'ga_tracking_id' => ($viewData->ga_enabled && $viewData->ga_tracking_id) ? $viewData->ga_tracking_id : false
				);
				$iframe_url = YOFLA_360_PLUGIN_URL . 'includes/iframe.php?' . http_build_query($data);
			}
			else{
				// just images are uploaded
				$iframe_url = $this->get_iframe_url_with_generated_config_file($viewData);
			}

			return $iframe_url;
		}

	}


	/**
	 * Returns url for the config.js file for product.
	 *
	 * If the config.js does, not exist, create it.
	 *
	 * @param $viewData YoFLA360Viewdata
	 * @return string
	 */
	public function get_config_url($viewData)
	{

		//check if absolute url is set
		if ($this->is_url_path($viewData->src)) {
			return trailingslashit($viewData->src) . 'config.js';
		}


		$config_path = $this->get_full_product_path($viewData->src) . 'config.js';

		if (file_exists($config_path)) {
			return $viewData->get_product_url() . 'config.js';
		} //generate config file
		else {
			$this->_init_rotate_tool_library();

			//if cache attribute is set to true, use the cache
			if ($viewData->is_cache_enabled) {
				Rotate_Tool::$is_cache_disabled = false;
			}

			$config_url = Rotate_Tool::get_config_url_for_product($this->get_relative_product_path($viewData->src));

			return $config_url;
		}

	}

	/**
	 * Currently, this function is only called when embedding using div & using just images.
	 * The theme falls backs to pure-white.
	 *
	 * Later, it will be possible to specify theme in shortcode
	 * Later, there will be a themes section in plugin.
	 *
	 */
	public function get_theme_url()
	{
		$url = YOFLA_360_PLUGIN_URL . 'includes/yofla_3drt/themes/pure-white';
		return $url;
	}


	/**
	 * Returns url to a generated iframe.html page, that has contains link to generated config.js file
	 *
	 * This function is a wrapper for the PHP Lib (https://www.yofla.com/3d-rotate/support/plugins/php-lib-for-360-product-view/)
	 *
	 * @param $viewData YoFLA360ViewData
	 * @return string
	 */
	public function get_iframe_url_with_generated_config_file($viewData)
	{
		//include library
		$this->_init_rotate_tool_library();

		$product_path_full = $this->get_full_product_path($viewData->src);
		$product_path_relative = $this->get_relative_product_path($viewData->src);

		//check path
		if (!file_exists($product_path_full)) {
			YoFLA360()->add_error('Path does not exist: ' . $product_path_full);
			return false;
		}


		//if cache attribute is stt to true, use the cache
		if ($viewData->is_cache_enabled) {
			Rotate_Tool::$is_cache_disabled = false;
		}

		//generate config.js, always, let wordpress cache plugin do the "hard job"
		$settings = Rotate_Tool::get_cascading_settings_for_directory($product_path_full);

		//generate & save config file
		$config_content = Rotate_Tool::get_config_file_content($product_path_full, $settings);

		if ($config_content === false) {
			YoFLA360()->add_error('Failed to generate config file for: ' . $product_path_full);
			return false;
		}

		//enhance settings with google analytics data & with data from shortcode
		$settings['ga_enabled'] = $viewData->ga_enabled;
		$settings['ga_tracking_id'] = $viewData->ga_tracking_id;
		$settings['ga_category'] = $viewData->ga_category;
		$settings['ga_label'] = $viewData->get_ga_label();
		$settings['product_name'] = $viewData->name;

		//set "cloud" rotatetool.js, if set
		if ($rotatetool_js_url = $this->get_rotatetool_js_url($viewData)) {
			$settings["system"]["rotatetoolUrl"] = $rotatetool_js_url;
		}

		//generate iframe.html
		Rotate_Tool::get_page_for_iframe($product_path_relative, $settings);

		//get iframe.html page url
		$iframe_url = Rotate_Tool::get_cached_iframe_page_url($product_path_relative);

		return $iframe_url;
	}


	/**
	 * Returns custom rotatetool.js url, if set
	 *
	 * @param $viewData YoFLA360Viewdata
	 * @return string
	 */
	public function get_rotatetool_js_url($viewData)
	{

		return $viewData->get_rotatetool_js_src();

	}

	/**
	 * Ensures provided value ends with px or %
	 *
	 * @param $value
	 * @return string
	 */
	public function format_size_for_styles($value)
	{
		$value = trim($value);
		if (substr($value, -1) == '%') {
			return $value;
		}

		$value = preg_replace('#[^0-9]#', '', $value);
		return $value . 'px';
	}


	/**
	 * Generates the content of the iframe source page
	 *
	 * Currently all parameters that might be overridden in the shortcode are
	 * passed as GET parameters.
	 *
	 * In the future, if iframe embed will be still supported, the parameters
	 * should be stored & read from DB
	 *
	 * @see  get_iframe_url
	 * @return string
	 */
	public function construct_iframe_content()
	{
		//setup view data
		$viewData = new YoFLA360ViewData();
		$viewData->process_get_parameters();

		$path = '';
		if (isset($_GET['product_url'])) {
			$path = urldecode($_GET['product_url']);
		}

		$path = $viewData->get_product_url();

		//initiate google analytics event tracking values
		$ga_enabled = "";
		$ga_label = "";
		$ga_category = "";
		$ga_tracking_id = "";

		if (isset($_GET['ga_tracking_id']) && strlen($_GET['ga_tracking_id']) > 10) {
			$ga_enabled = "true";
			$ga_tracking_id = (isset($_GET['ga_tracking_id'])) ? $this->sanitizeGetParameter($_GET['ga_tracking_id']) : "";
			$ga_label = (isset($_GET['ga_label'])) ? $this->sanitizeGetParameter($_GET['ga_label']) : "";
			$ga_category = (isset($_GET['ga_category'])) ? $this->sanitizeGetParameter($_GET['ga_category']) : "";
		} else {
			$ga_enabled = "false";
		}


		if (isset($_GET['product_name'])) {
			$product_name = $this->sanitizeGetParameter($_GET['product_name']);
		} else {
			$product_name = '360 view';
		}

		//construct rotatetool.js path
		$rotatetool_js_src = $this->get_rotatetool_js_url($viewData);

		//load template
		$template_file_path = 'template-iframe.tpl';
		$template_string = file_get_contents($template_file_path);

		$values = array(
			'{themeUrl}' => $viewData->get_theme_url(),
			'{rotatetool_js_src}' => $rotatetool_js_src,
			'{path}' => $path,
			'{title}' => $product_name,
			'{ga_enabled}' => $ga_enabled,
			'{ga_tracking_id}' => $ga_tracking_id,
			'{ga_label}' => $ga_label,
			'{ga_category}' => $ga_category,
		);
		$new_html = strtr($template_string, $values);

		return $new_html;
	}

	/**
	 * Returns list of directories in specified path, along with info if they contain images for 360 views
	 *
	 * @param bool $scan_images
	 * @return array
	 */
	public function get_yofla360_directories_list($scan_images = true)
	{
		$path = $this->get_products_path();

		if ($scan_images) {
			$this->_init_rotate_tool_library();
		}

		$directories_list = array();
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$file_path = trailingslashit($path) . $file;
					if (is_dir($file_path) && $file != YOFLA_360_TRASH_FOLDER_NAME && $file != YOFLA_360_CACHE_FOLDER_NAME) {
						if ($scan_images) {
							$directories_list[] = array(
								"name" => $file,
								"data" => Rotate_Tool::get_images_list($file_path)
							);
						} else {
							$directories_list[] = array("name" => $file);
						}
					}
				}
			}
			closedir($handle);
		}

		//sort by name
		$names = array();
		foreach ($directories_list as $key => $row) {
			$names[$key] = $row['name'];
		}
		array_multisort($names, SORT_ASC, $directories_list);

		return $directories_list;
	}

	/**
	 * If paths exists, appends a number so path is unique (does not exist)
	 *
	 * Used e.g. when moving a directory to trash
	 *
	 * @param $destination
	 * @return string
	 */
	public function get_safe_destination($destination)
	{
		$counter = 1;
		$result = $destination;
		while (file_exists($result)) {
			$result = $destination . '_' . $counter;
			$counter++;
		}
		return $result;
	}


	/**
	 * The 3DRT Library is a PHP lib for embedding views without using the 3DRT Setup Utility
	 *
	 */
	private function _init_rotate_tool_library()
	{
		include_once(YOFLA_360_PLUGIN_PATH . 'includes/yofla_3drt/lib/yofla/Rotate_Tool.php');

		$system_url = plugins_url() . "/360-product-rotation/includes/yofla_3drt/";
		Rotate_Tool::set_system_url($system_url);
		Rotate_Tool::$products_path = $this->get_products_path();
		Rotate_Tool::$products_url = $this->get_products_url();
		Rotate_Tool::$is_cache_disabled = true;
		Rotate_Tool::set_cache_url($this->get_products_url() . YOFLA_360_CACHE_FOLDER_NAME);
		Rotate_Tool::set_cache_path($this->get_products_path() . YOFLA_360_CACHE_FOLDER_NAME);
	}

	public function addHttp($url)
	{
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}

	/**
	 * Clear cache
	 *
	 * @return bool
	 */
	public function clear_cache()
	{
		$dir = $this->get_products_path() . YOFLA_360_CACHE_FOLDER_NAME;
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($files as $file) {
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		return rmdir($dir);
	}

	/**
	 * Check if an item exists out there in the "ether".
	 *
	 * @param string $url - preferably a fully qualified URL
	 * @return boolean - true if it is out there somewhere
	 */
	public function webItemExists($url) {
		if (($url == '') || ($url == null)) { return false; }
		$response = wp_remote_head( $url, array( 'timeout' => 5 ) );
		$accepted_status_codes = array( 200, 301, 302 );
		if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Do not allow special chars in get parameters
	 *
	 * @param $str
	 * @return string
	 */
	public function sanitizeGetParameter($str) {
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * List of projectes from server, returna array of projects data
	 *
	 * @param $lk
	 * @return array|mixed|object|string
	 */
	public function get_cloud_projects_by_lk($lk){

		$url = YOFLA_PRODUCTS_URL_CLOUD;

		$response =  wp_remote_post($url,array('body' => array('licenseKey'=>$lk) ) );

		if (is_wp_error($response)) {
			$error = $response->get_error_message();
			$msg = '<div id="message" class="error"><p>' . 'Error communicating with server! ' . $error . '</p>';
			return $msg;
		}

		if ($response && isset($response['body'])) {
			$body = $response['body'];
			return json_decode($body, true);
		}

		return [];
	}

	/**
	 * Returns Cloud Based iframe embed URL
	 *
	 * @param $src
	 * @return string
	 */
	public function get_next360cloud_iframe_url($src){
		$e = explode(';',$src);
		$accountId = $e[0];
		$projectId = $e[1];
		$versionNumber = $e[2];
		return YOFLA_CDN_URL_CLOUD."prod/$accountId/$projectId/v$versionNumber/iframe.html";
	}
}//class
