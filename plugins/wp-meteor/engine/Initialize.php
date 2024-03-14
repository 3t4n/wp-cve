<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Engine;

use WP_Meteor\Engine;

/**
 * Plugin Name Initializer
 */
class Initialize
{

	/**
	 * List of class to initialize.
	 *
	 * @var array
	 */
	public $classes = array();

	/**
	 * Instance of this Lmf_Is_Methods.
	 *
	 * @var object
	 */
	protected $is = null;

	/**
	 * Composer autoload file list.
	 *
	 * @var \Composer\Autoload\ClassLoader
	 */
	private $composer;

	/**
	 * The Constructor that load the entry classes
	 *
	 * @param \Composer\Autoload\ClassLoader $composer Composer autoload output.
	 * @since 1.0.0
	 */
	public function __construct(\Composer\Autoload\ClassLoader $composer)
	{
		$this->is       = new Engine\Is_Methods;
		$this->composer = $composer;

		$extraAttrs = [];
		if (!empty($_SERVER["HTTP_CF_RAY"]) || !empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$extraAttrs[] = 'data-cfasync="false"';
		}
		if (defined('LSCWP_V')) {
			$extraAttrs[] = 'data-no-optimize="1"';
			$extraAttrs[] = 'data-no-defer';
		}
		if (defined('PHASTPRESS_VERSION')) {
			$extraAttrs[] = 'data-phast-no-defer';
		}
		if (defined('SWIFT_PERFORMANCE_VER')) {
			$extraAttrs[] = 'data-dont-merge';
		}
		define('WPMETEOR_EXTRA_ATTRS', join(" ", $extraAttrs));

		$this->get_classes('Internals');
		$this->get_classes('Integrations');
		$this->get_classes('Blocker');

		if ($this->is->request('ajax')) {
			$this->get_classes('Ajax');
			$this->get_classes('Rest');
		}

		if ($this->is->request('backend')) {
			$this->get_classes('Backend');
		}

		if ($this->is->request('frontend')) {
			$this->get_classes('Frontend');
		}

		$this->load_classes();
	}

	/**
	 * Initialize all the classes.
	 *
	 * @since 1.0.0
	 */
	private function load_classes()
	{
		$this->classes = \apply_filters('wp_meteor_classes_to_execute', $this->classes);
		$loadedObjects = [];
		foreach ($this->classes as $class) {
			if (!preg_match('/\\Base$/', $class)) {
				$loadedObjects[] = new $class;
			}
		}

		foreach ($loadedObjects as $object) {
			try {
				$object->initialize();
			} catch (\Throwable $err) {
				\do_action('wp_meteor_initialize_failed', $err);

				if (WP_DEBUG) {
					throw new \Exception($err->getMessage());
				}
			}
		}
	}

	/**
	 * Based on the folder loads the classes automatically using the Composer autoload to detect the classes of a Namespace.
	 *
	 * @param string $namespace Class name to find.
	 * @since 1.0.0
	 * @return array Return the classes.
	 */
	private function get_classes(string $namespace)
	{
		$prefix    = $this->composer->getPrefixesPsr4();
		$classmap  = $this->composer->getClassMap();
		$namespace = 'WP_Meteor\\' . $namespace;

		// In case composer has autoload optimized
		if (isset($classmap['WP_Meteor\\Engine\\Initialize'])) {
			$classes = \array_keys($classmap);

			foreach ($classes as $class) {
				if (0 !== \strncmp((string) $class, $namespace, \strlen($namespace))) {
					continue;
				}

				$this->classes[] = $class;
			}

			return $this->classes;
		}

		$namespace .= '\\';

		// In case composer is not optimized
		if (isset($prefix[$namespace])) {
			$folder    = $prefix[$namespace][0];
			$php_files = $this->scandir($folder);
			$this->find_classes($php_files, $folder, $namespace);

			if (!WP_DEBUG) {
				\wp_die(\esc_html__('WP Meteor is on production environment with missing `composer dumpautoload -o` that will improve the performance on autoloading itself.', WPMETEOR_TEXTDOMAIN));
			}

			return $this->classes;
		}

		return $this->classes;
	}

	/**
	 * Get php files inside the folder/subfolder that will be loaded.
	 * This class is used only when Composer is not optimized.
	 *
	 * @param string $folder Path.
	 * @since 1.0.0
	 * @return array List of files.
	 */
	private function scandir(string $folder)
	{
		$temp_files = \scandir($folder);
		$files  = array();

		if (\is_array($temp_files)) {
			$files = $temp_files;
		}

		return \array_diff($files, array('..', '.', 'index.php'));
	}

	/**
	 * Load namespace classes by files.
	 *
	 * @param array  $php_files List of files with the Class.
	 * @param string $folder Path of the folder.
	 * @param string $base Namespace base.
	 * @since 1.0.0
	 */
	private function find_classes(array $php_files, string $folder, string $base)
	{
		foreach ($php_files as $php_file) {
			$class_name = \substr($php_file, 0, -4);
			$path       = $folder . '/' . $php_file;

			if (\is_file($path)) {
				$this->classes[] = $base . $class_name;

				continue;
			}

			// Verify the Namespace level
			if (\substr_count($base . $class_name, '\\') < 2) {
				continue;
			}

			if (!\is_dir($path) || \strtolower($php_file) === $php_file) {
				continue;
			}

			$sub_php_files = $this->scandir($folder . '/' . $php_file);
			$this->find_classes($sub_php_files, $folder . '/' . $php_file, $base . $php_file . '\\');
		}
	}
}
