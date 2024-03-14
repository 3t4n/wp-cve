<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Tracker {

	private static $instance;

	protected $config;

	protected $pushes;

	public $settings_placement = 'footer';

	public $pageview_placement = 'footer';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		$this->pushes = array();
		/*
		$api_level = intel_api_level();

		$a = explode('//', WP_SITEURL);
		$systemHost = $a[1];
		$cmsHostpath = $a[1] . '/';
		//$pageTitle = is_admin() ? '' : wp_title('', 0);
		$pageTitle = '';

		$this->config = array(
			'debug' => 0,
			// cmsHostpath, modulePath & apiPath are not standard io settings. They are used
			// exclusivly by intel module js.
			'cmsHostpath' => $cmsHostpath,
			'modulePath' => INTEL_URL,
			'libPath' => 'TODO',
			'systemPath' => 'TODO',
			'systemHost' => $systemHost,
			'systemBasepath' => 'TODO',
			'srl' => 'TODO',
			'pageTitle' => $pageTitle,
			'trackAnalytics' => 1, // this is set in intel_js_alter if ga script exists
			'trackAdhocCtas' => ($api_level == 'pro') ? 'track-cta' : '',
			'trackAdhocEvents' => 'track-event',
			'trackForms' => array(),
			'trackRealtime' => 0,
			'isLandingpage' => 0,
			'scorings' => array(),
			'gaGoals' => array(),
			//'scorings' => intel_get_scorings('js_setting'), //TODO
			'storage' => array(
				'page' => array(
					'analytics' => array(),
				),
				'session' => array(
					'analytics' => array(),
				),
				'visitor' => array(
					'analytics' => array(),
				),
			),
		);
		*/
	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new Intel_Tracker();
		}
		return self::$instance;
	}

	public function get_intel_pushes() {
		return intel_get_flush_page_intel_pushes();
	}

	public function get_intel_pushes_js($options = array()) {
		$pushes = self::get_intel_pushes();

		$out = '';

		foreach ($pushes as $key => $value) {
			$out .= "  io('$key', " . json_encode($value) . ");\n";
		}

		return $out;
	}

	public function get_pushes_script($options = array()) {
		$out = '';
		$out .= '<script>' . "\n";
		if (!empty($options['prefix'])) {
			$out .= $options['prefix'];
		}
		$out .= $this->get_intel_pushes_js($options);
		if (!empty($options['suffix'])) {
			$out .= $options['suffix'];
		}
		$out .= '</script>' . "\n";
		return $out;
  }

	public function setConfig($prop, $value) {
		$this->config[$prop] = $value;
	}

	public function getConfig($prop = '', $default = null) {
		if (!$prop) {
			return $this->config;
		}
		elseif (!empty($this->config[$prop])) {
			return $this->config[$prop];
		}
		else {
			return $default;
		}
	}


	public function addPush($push, $index = '') {
		if (!empty($push['method'])) {
			$method = $push['method'];
			unset($push['method']);
		}
		else {
			$method = array_shift($push);
		}

		if ($method == 'set') {
			$index = $push[0];
			$value = $push[1];
		}
		elseif ($method == 'event') {
			$index = count($this->pushes[$method]);
			$value = $push[0];
		}
		else {
			$index = count($this->pushes[$method]);
			$value = $push;
		}

		$this->pushes[$method][$index] = $value;
	}

	public function getPushes($flush = false) {
		$ret = $this->pushes;
		if ($flush) {
			$this->pushes = array();
		}
		return $ret;
	}

	public function enqueue_intel_scripts() {
		// don't add scripts if in framework mode
    if (intel_is_framework()) {
      return;
    }
    // add intel_scripts
		$scripts = intel()->intel_script_info();
		$enabled = get_option('intel_intel_scripts_enabled', array());
		foreach ($scripts AS $key => $script) {
			if (!empty($enabled[$key]) || (!isset($enabled[$key]) && !empty($script['enabled']))) {
				wp_enqueue_script('intel_script_' . $key, $script['path']);
			}
		}
	}

	/**
	 * Generates tracking code
	 */
	public function tracking_head($is_admin = 0) {
    $is_framework = intel_is_framework();
    if (!intel_is_installed('min') && !$is_framework) {
      return '';
    }
		$io_name = 'io';

		// check if intel should embed ga tracking code
		$embed_ga_tracking_code = get_option('intel_embed_ga_tracking_code', '');
		if ($embed_ga_tracking_code) {
			print intel_get_ga_js_embed($embed_ga_tracking_code);
		}

		$script = '';

		$script .= $this->tracking_code_js();

		if ($this->settings_placement == 'head') {
			$script .= "\n" . $this->tracking_settings_js();
		}

    if (!$is_framework) {
      if ($this->pageview_placement == 'head') {
        $script .= "\n" . "io('pageview');";
      }
    }

		print '<script>' . $script . '</script>';

		//if (!$is_admin) {
			$this->enqueue_intel_scripts();
		//}

		return;
	}

	public function tracking_footer($is_admin = 0) {
    $is_framework = intel_is_framework();
		if (!intel_is_installed('min') && !$is_framework) {
			return '';
		}
		$io_name = 'io';


		$script = '';

		if ($this->settings_placement != 'head') {
			$script .= "\n" . $this->tracking_settings_js();
		}
		else {
			// if settings processed in head, embed any pushes after page_alter was
			// run
			$script .= $this->get_intel_pushes_js();
		}

    //intel_page_footer_alter();

		if (!$is_framework) {
      if ($this->pageview_placement != 'head') {
        $script .= "\n" . "io('pageview');";
      }
    }

		//$script .= "$io_name('set', intel_settings.intel.pushes.set);\n";
		//if (!empty($js_settings['intel']['pushes']['events'])) {
		//	$script .= "$io_name('event', intel_settings.intel.pushes.event);\n";
		//}

		if ($script) {
			print '<script>' . $script . '</script>';
		}
	}

	public function tracking_admin_head() {
		$this->tracking_head(1);
		return;
		// TODO for now I am just embeding the _ioq object without config and
		// push commands. Need to develop config for admin side. E.g. most front
		// side intel events aren't relevant to admin side.
		$script = '';
		$script .= $this->tracking_code_js();
		print '<script>' . $script . '</script>';
		//$this->tracking_head();
	}

	public function tracking_admin_footer() {
		$this->tracking_footer(1);
	}

	public function tracking_code_js() {
		$script = '';
		$script .= intel_get_js_embed('l10i', 'local');

		return $script;
	}

	public function tracking_settings_js() {
		$io_name = 'io';

    $is_framework = intel_is_framework();

		$page = array();
		intel_page_alter($page);

		$js_settings = intel()->get_js_settings();

		$script = '';
		//$script .= "var wp_intel = wp_intel || {}; wp_intel.settings = " . json_encode($js_settings) . ";\n";
		$script .= "var wp_intel = wp_intel || { 'settings': {}, 'behaviors': {}, 'locale': {} };\n";
		$script .= "jQuery.extend(wp_intel.settings, " . json_encode($js_settings) . ");\n";
		if (!$is_framework) {
      $script .= "$io_name('setConfig', wp_intel.settings.intel.config);\n";
      if (isset($js_settings['intel']['pushes']) && is_array($js_settings['intel']['pushes'])) {
        foreach ($js_settings['intel']['pushes'] as $cm => $push) {
          if (0 && $cm == 'setUserId') {
            $script .= $io_name . '("' . $cm . '","' . $push[0][0];
            if (!empty($push[0][1])) {
              $script .= '","' . $push[0][1];
            }
            $script .= '");' . "\n";
          } else {
            $script .= "$io_name('$cm', wp_intel.settings.intel.pushes['$cm']);\n";
          }
        }
      }
    }

		//$script .= "$io_name('pageview');\n";

		return $script;
	}



}
