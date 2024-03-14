<?php
/*
Plugin Name: WP Wizard Cloak
Plugin URI: http://www.wpwizardcloak.com/
Description: WP Wizard Cloak Lite is the best link management and cloaking plugin for WordPress. WP Wizard Cloak makes it easy to cloak and track your links, split test offers, and with the pro version you can hide your referrers from dishonest merchants and affiliate networks!
Version: 1.0.1
Author: Soflyy
*/
/**
 * Plugin root dir with forward slashes as directory separator regardless of actuall DIRECTORY_SEPARATOR value
 * @var string
 */
define('PMLC_ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));
/**
 * Plugin root url for referencing static content
 * @var string
 */
define('PMLC_ROOT_URL', rtrim(plugin_dir_url(__FILE__), '/'));
/**
 * Plugin prefix for making names unique (be aware that this variable is used in conjuction with naming convention,
 * i.e. in order to change it one must not only modify this constant but also rename all constants, classes and functions which
 * names composed using this prefix)
 * @var string
 */
define('PMLC_PREFIX', 'pmlc_');

/**
 * Main plugin file, Introduces MVC pattern
 *
 * @singletone
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
final class PMLC_Plugin {
	/**
	 * Singletone instance
	 * @var PMLC_Plugin
	 */
	protected static $instance;

	/**
	 * Plugin options
	 * @var array
	 */
	protected $options = array();

	/**
	 * Plugin root dir
	 * @var string
	 */
	const ROOT_DIR = PMLC_ROOT_DIR;
	/**
	 * Plugin root URL
	 * @var string
	 */
	const ROOT_URL = PMLC_ROOT_URL;
	/**
	 * Prefix used for names of shortcodes, action handlers, filter functions etc.
	 * @var string
	 */
	const PREFIX = PMLC_PREFIX;
	/**
	 * Plugin file path
	 * @var string
	 */
	const FILE = __FILE__;

	/**
	 * Return singletone instance
	 * @return PMLC_Plugin
	 */
	static public function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Common logic for requestin plugin info fields
	 */
	public function __call($method, $args) {
		if (preg_match('%^get(.+)%i', $method, $mtch)) {
			$info = get_plugin_data(self::FILE);
			if (isset($info[$mtch[1]])) {
				return $info[$mtch[1]];
			}
		}
		throw new Exception("Requested method " . get_class($this) . "::$method doesn't exist.");
	}

	/**
	 * Get path to plagin dir relative to wordpress root
	 * @param bool[optional] $noForwardSlash Whether path should be returned withot forwarding slash
	 * @return string
	 */
	public function getRelativePath($noForwardSlash = false) {
		$wp_root = str_replace('\\', '/', ABSPATH);
		return ($noForwardSlash ? '' : '/') . str_replace($wp_root, '', self::ROOT_DIR);
	}

	/**
	 * Check whether plugin is activated as network one
	 * @return bool
	 */
	public function isNetwork() {
		if ( !is_multisite() )
		return false;

		$plugins = get_site_option('active_sitewide_plugins');
		if (isset($plugins[plugin_basename(self::FILE)]))
			return true;

		return false;
	}

	/**
	 * Check whether permalinks is enabled
	 * @return bool
	 */
	public function isPermalinks() {
		global $wp_rewrite;

		return $wp_rewrite->using_permalinks();
	}

	/**
	 * Return prefix for plugin database tables
	 * @return string
	 */
	public function getTablePrefix() {
		global $wpdb;
		return ($this->isNetwork() ? $wpdb->base_prefix : $wpdb->prefix) . self::PREFIX;
	}

	/**
	 * Class constructor containing dispatching logic
	 * @param string $rootDir Plugin root dir
	 * @param string $pluginFilePath Plugin main file
	 */
	protected function __construct() {
		// regirster autoloading method
		if (function_exists('__autoload') and ! in_array('__autoload', spl_autoload_functions())) { // make sure old way of autoloading classes is not broken
			spl_autoload_register('__autoload');
		}
		spl_autoload_register(array($this, '__autoload'));

		// load safe_glob helper explicitly since all futher loading logic depends on it
		require_once self::ROOT_DIR . '/helpers/safe_glob.php';

		// register helpers
		if (is_dir(self::ROOT_DIR . '/helpers')) foreach (safe_glob(self::ROOT_DIR . '/helpers/*.php', GLOB_RECURSE | GLOB_PATH) as $filePath) {
			require_once $filePath;
		}

		// init plugin options
		$this->options = get_option(get_class($this) . '_Options');

		register_activation_hook(self::FILE, array($this, '__activation'));

		// register action handlers
		if (is_dir(self::ROOT_DIR . '/actions')) foreach (safe_glob(self::ROOT_DIR . '/actions/*.php', GLOB_RECURSE | GLOB_PATH) as $filePath) {
			require_once $filePath;
			$function = $actionName = basename($filePath, '.php');
			if (preg_match('%^(.+?)[_-](\d+)$%', $actionName, $m)) {
				$actionName = $m[1];
				$priority = intval($m[2]);
			} else {
				$priority = 10;
			}
			add_action($actionName, self::PREFIX . str_replace('-', '_', $function), $priority, 99); // since we don't know at this point how many parameters each plugin expects, we make sure they will be provided with all of them (it's unlikely any developer will specify more than 99 parameters in a function)
		}

		// register filter handlers
		if (is_dir(self::ROOT_DIR . '/filters')) foreach (safe_glob(self::ROOT_DIR . '/filters/*.php', GLOB_RECURSE | GLOB_PATH) as $filePath) {
			require_once $filePath;
			$function = $actionName = basename($filePath, '.php');
			if (preg_match('%^(.+?)[_-](\d+)$%', $actionName, $m)) {
				$actionName = $m[1];
				$priority = intval($m[2]);
			} else {
				$priority = 10;
			}
			add_filter($actionName, self::PREFIX . str_replace('-', '_', $function), $priority, 99); // since we don't know at this point how many parameters each plugin expects, we make sure they will be provided with all of them (it's unlikely any developer will specify more than 99 parameters in a function)
		}

		// register shortcodes handlers
		foreach (safe_glob(self::ROOT_DIR . '/shortcodes/*.php', GLOB_RECURSE | GLOB_PATH) as $filePath) {
			$tag = strtolower(str_replace('/', '_', preg_replace('%^' . preg_quote(self::ROOT_DIR . '/shortcodes/', '%') . '|\.php$%', '', $filePath)));
			add_shortcode($tag, array($this, 'shortcodeDispatcher'));
		}

		// register admin page pre-dispatcher
		add_action('admin_init', array($this, '__adminInit'));

		add_filter('http_request_args', array($this, '__infoApiAuth'), PHP_INT_MAX, 2);
	}

	/**
	 * pre-dispatching logic for admin page controllers
	 */
	public function __adminInit() {
		$input = new PMLC_Input();
		$page = strtolower($input->getpost('page', ''));
		if (preg_match('%^' . preg_quote(str_replace('_', '-', self::PREFIX), '%') . '([\w-]+)$%', $page)) {
			$this->adminDispatcher($page, strtolower($input->getpost('action', 'index')));
		}
	}

	/**
	 * Append authentication info when requesting info
	 */
	public function __infoApiAuth($args, $url) {
		if (dirname($url) == dirname(PMLC_Plugin::getInstance()->getOption('info_api_url'))) {
			isset($args['headers']) or $args['headers'] = array();
			$args['headers']['Authorization'] = 'Basic d29yZHByZXNzOnNVMnhYZGFhRmswaQ==';
		}
		return $args;
	}

	/**
	 * Dispatch shorttag: create corresponding controller instance and call its index method
	 * @param array $args Shortcode tag attributes
	 * @param string $content Shortcode tag content
	 * @param string $tag Shortcode tag name which is being dispatched
	 * @return string
	 */
	public function shortcodeDispatcher($args, $content, $tag) {
		$controllerName = self::PREFIX . preg_replace('%(^|_).%e', 'strtoupper("$0")', $tag); // capitalize first letters of class name parts and add prefix
		$controller = new $controllerName();
		if ( ! $controller instanceof PMLC_Controller) {
			throw new Exception("Shortcode `$tag` matches to a wrong controller type.");
		}
		ob_start();
		$controller->index($args, $content);
		return ob_get_clean();
	}

	/**
	 * Dispatch admin page: call corresponding controller based on get parameter `page`
	 * The method is called twice: 1st time as handler `parse_header` action and then as admin menu item handler
	 * @param string[optional] $page When $page set to empty string ealier buffered content is outputted, otherwise controller is called based on $page value
	 */
	public function adminDispatcher($page = '', $action = 'index') {
		static $buffer = NULL;
		if ('' === $page) {
			if (is_null($buffer)) {
				throw new Exception('There is no previousely buffered content to display.');
			}
			echo $buffer;
		} else {			

			// capitalize prefix and first letters of class name parts	
			if (function_exists('preg_replace_callback')){
				$controllerName = preg_replace_callback('%(^' . preg_quote(self::PREFIX, '%') . '|_).%', array($this, "replace_callback"),str_replace('-', '_', $page));
			}
			else{
				$controllerName =  preg_replace('%(^' . preg_quote(self::PREFIX, '%') . '|_).%e', 'strtoupper("$0")', str_replace('-', '_', $page)); 
			}

			if ( ! $this->isLicensed() and ! in_array($controllerName, array('PMLC_Admin_Help', 'PMLC_Admin_Home'))) {
				wp_redirect(add_query_arg('page', 'pmlc-admin-home', admin_url('admin.php')));
			}
			$actionName = str_replace('-', '_', $action);
			if (method_exists($controllerName, $actionName)) {

				if ( ! get_current_user_id() or ! current_user_can('manage_options')) {
				    // This nonce is not valid.
				    die( 'Security check' ); 

				} else {

					$this->_admin_current_screen = (object)array(
						'id' => $controllerName,
						'base' => $controllerName,
						'action' => $actionName,
						'is_ajax' => isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest',
						'is_network' => is_network_admin(),
						'is_user' => is_user_admin(),
					);
					add_filter('current_screen', array($this, 'getAdminCurrentScreen'));

					$controller = new $controllerName();
					if ( ! $controller instanceof PMLC_Controller_Admin) {
						throw new Exception("Administration page `$page` matches to a wrong controller type.");
					}
					ob_start();
					$controller->$action();
					$buffer = ob_get_clean();
					if ($this->_admin_current_screen->is_ajax) { // ajax request
						die($buffer); // stop processing since we want to output only what controller is randered, nothing in addition
					}
				
				}

			} else { // redirect to dashboard if requested page and/or action don't exist
				wp_redirect(admin_url());
				die();
			}
		}
	}

	public function replace_callback($matches){
		return strtoupper($matches[0]);
	}
	
	protected $_admin_current_screen = null;
	public function getAdminCurrentScreen()
	{
		return $this->_admin_current_screen;
	}

	/**
	 * Autoloader
	 * It's assumed class name consists of prefix folloed by its name which in turn corresponds to location of source file
	 * if `_` symbols replaced by directory path separator. File name consists of prefix folloed by last part in class name (i.e.
	 * symbols after last `_` in class name)
	 * When class has prefix it's source is looked in `models`, `controllers`, `shortcodes` folders, otherwise it looked in `core` or `library` folder
	 *
	 * @param string $className
	 * @return bool
	 */
	public function __autoload($className) {
		$is_prefix = false;
		$filePath = str_replace('_', '/', preg_replace('%^' . preg_quote(self::PREFIX, '%') . '%', '', strtolower($className), 1, $is_prefix)) . '.php';
		foreach ($is_prefix ? array('models', 'controllers', 'shortcodes', 'classes') :  array('libraries') as $subdir) {
			$path = self::ROOT_DIR . '/' . $subdir . '/' . $filePath;
			if (is_file($path)) {
				require $path;
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Get plugin option
	 * @param string[optional] $option Parameter to return, all array of options is returned if not set
	 * @return mixed
	 */
	public function getOption($option = NULL) {
		if (is_null($option)) {
			return $this->options;
		} else if (isset($this->options[$option])) {
			return $this->options[$option];
		} else {
			throw new Exception("Specified option is not defined for the plugin");
		}
	}
	/**
	 * Update plugin option value
	 * @param string $option Parameter name or array of name => value pairs
	 * @param mixed[optional] $value New value for the option, if not set than 1st parameter is supposed to be array of name => value pairs
	 * @return array
	 */
	public function updateOption($option, $value = NULL) {
		is_null($value) or $option = array($option => $value);
		if (array_diff_key($option, $this->options)) {
			throw new Exception("Specified option is not defined for the plugin");
		}
		$this->options = $option + $this->options;
		update_option(get_class($this) . '_Options', $this->options);

		return $this->options;
	}

	/**
	 * Plugin activation logic
	 */
	public function __activation() {
		// uncaught exception doesn't prevent plugin from being activated, therefore replace it with fatal error so it does
		set_exception_handler(create_function('$e', 'trigger_error($e->getMessage(), E_USER_ERROR);'));

		// create plugin options
		$option_name = get_class($this) . '_Options';
		$options_default = PMLC_Config::createFromFile(self::ROOT_DIR . '/config/options.php')->toArray();
		$this->options = array_intersect_key(get_option($option_name, array()), $options_default) + $options_default;
		$this->options = array_intersect_key($options_default, array_flip(array('info_api_url'))) + $this->options; // make sure hidden options apply upon plugin reactivation
		update_option($option_name, $this->options);

		// create/update required database tables
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		require self::ROOT_DIR . '/schema.php';
		dbDelta($plugin_queries);

		// [import GeoIPCountry database]
		$csv = NULL;
		is_file(PMLC_Plugin::ROOT_DIR . '/GeoIPCountryWhois.csv') and $csv = fopen(PMLC_Plugin::ROOT_DIR . '/GeoIPCountryWhois.csv', 'r'); // try raw file
		if (empty($csv) and function_exists('zip_open')) { // try zip archive directly from maxmind.com
			if (($zip = fopen('http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip', 'r'))) {
				$tmp_zip_name = tempnam(sys_get_temp_dir(), 'zip');
				if (($tmp_zip = fopen($tmp_zip_name, 'w'))) {
					if ( ! stream_copy_to_stream($zip, $tmp_zip)) {
						fclose($tmp_zip);
						unlink($tmp_zip_name);
					} else {
						fclose($tmp_zip);
						$csv = fopen('zip://' . $tmp_zip_name . '#GeoIPCountryWhois.csv', 'r');
					}
				}
				fclose($zip);
			}
		}
		if (empty($csv) and function_exists('gzopen')) { // try gz
			 is_file(PMLC_Plugin::ROOT_DIR . '/GeoIPCountryWhois.csv.gz') and ($csv = fopen('compress.zlib://' . PMLC_Plugin::ROOT_DIR . '/GeoIPCountryWhois.csv.gz', 'r'));
		}

		if ($csv) {
			global $wpdb;
			$record = new PMLC_GeoIPCountry_Record();
			$record->truncateTable();

			while ( ! feof($csv)) {
				$i = 0; $values = array();
				while (FALSE !== ($data = fgets($csv)) and $i < 10000) {
					$data = trim($data);
					if ('' != $data) {
						$values[] = '(' . $data . ')';
						$i++;
					}
				}
				if ($values) {
					$sql = 'INSERT INTO ' . $record->getTable() . ' (begin_ip, end_ip, begin_num, end_num, country, name) VALUES ' . implode(',', $values);
					$wpdb->query($sql);
				}
			}
			fclose($csv);
		}
		if ( ! empty($tmp_zip_name) and is_file($tmp_zip_name)) { // unlink temporary file used for uploading zip archive
			unlink($tmp_zip_name);
		}
		// [/import GeoIPCountry database]
	}

	/**
	 * @param string $license
	 * @param int $time
	 * @param string $status
	 * @return string
	 */
	protected function _hashLicense($license, $time, $status)
	{
		return md5(base_convert(preg_replace('%[^a-zA-Z]%', '0', $license) . $status, 36, 10) . $time);
	}
	/**
	 * Get currently set license
	 * @return string
	 */
	public function getLicense()
	{
		$license_opt = get_option(get_class($this) . '_License', array('license' => ''));
		return $license_opt['license'];
	}
	/**
	 * Check provided license and assign it to the current plugin copy if successful
	 * @param string $license
	 * @return bool, NULL indicates failed attempt to contact checking server
	 */
	public function setLicense($license)
	{
		if ($license) { // check non-empty license
			$domain = preg_replace('%^www\.%', '', strtolower(parse_url(site_url(), PHP_URL_HOST)));
			$url = sprintf("http://www.wpwizardcloak.com/wp-content/plugins/deliv/checklicense.php?handshake=zc8h3hfnsdadscxvnnh9x8ady7h&licensekey=%s&domain=%s", urlencode($license), urlencode($domain));
			$result = wp_remote_get($url);
			if (is_wp_error($result)) { // failed to reach license checking server
				return NULL;
			} elseif ('valid' == $result['body']) {
				// ok
			} elseif ('invalid' == $result['body']) {
				return FALSE;
			} else { // server exists but not license checking script resided there since it returns result in unexpected format
				return NULL;
			}
		}
		// save licence
		$time = time();
		$license_opt = array(
			'license' => $license,
			'updated' => $time,
			'recheck' => $time,
			'hashstr' => $this->_hashLicense($license, $time, 'valid'),
		);
		update_option(get_class($this) . '_License', $license_opt);
		return true;
	}
	/**
	 * Whether plugin is licensed
	 */
	public function isLicensed()
	{
		$license_opt = get_option(get_class($this) . '_License', array());

		// disable license functionality
		return true;

		return isset($license_opt['license'], $license_opt['updated'], $license_opt['hashstr'])
			and $license_opt['license'] // license is set
			and $this->_hashLicense($license_opt['license'], $license_opt['updated'], 'valid') == $license_opt['hashstr'] // hash string is fine (correspond to valid state and `updated` time specified)
			and $license_opt['updated'] + 14 * 86400 > time(); // last successful check operation not older than 2 weeks
	}
	/**
	 * Perform recurring license check
	 */
	public function recurringLicenseCheck()
	{
		$license_opt = get_option(get_class($this) . '_License', array());
		if (isset($license_opt['license'], $license_opt['updated'], $license_opt['hashstr'])
			and $license_opt['license'] // license is set
			and $this->_hashLicense($license_opt['license'], $license_opt['updated'], 'valid') == $license_opt['hashstr'] //  hash string is fine (correspond to valid state and `updated` time specified)
			and $license_opt['updated'] + (empty($license_opt['recheck']) ? 6 : 24 * 7) * 3600 < time() // re-check is required (every 6h when last operation hasn't reached checking server or weekly)
			) {

			$checking_result = $this->setLicense($license_opt['license']);
			if ( ! $checking_result) {
				$time = time();
				$license_opt = array(
					'license' => $license_opt['license'],
					'updated' => $license_opt['updated'],
					'recheck' => $time,
					'hashstr' => $this->_hashLicense($license_opt['license'], $license_opt['updated'], is_null($checking_result) ? 'valid' : 'error'),
				);
				update_option(get_class($this) . '_License', $license_opt);
			}
		}
	}
}

PMLC_Plugin::getInstance()->recurringLicenseCheck();
