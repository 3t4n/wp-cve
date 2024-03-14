<?php
namespace Photonic_Plugin\Options;

class Options {
	private static $instance;

	private function __construct() {
		require_once 'Defaults.php';
	}

	/**
	 * Build up the options for the settings pages.
	 */
	public function prepare_options() {
		global $photonic_setup_options;

		$photonic_setup_options = [];
		if (is_admin() && !empty($_REQUEST['page']) && in_array($_REQUEST['page'], ['photonic-options-manager', 'photonic-options'], true)) {
			require_once 'Option_Tab.php';

			require_once 'Generic.php';
			$options = Generic::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			require_once 'Flickr.php';
			$options = Flickr::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			require_once 'Google.php';
			$options = Google::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			require_once 'SmugMug.php';
			$options = SmugMug::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			require_once 'Instagram.php';
			$options = Instagram::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			require_once 'Zenfolio.php';
			$options = Zenfolio::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			require_once 'Lightbox.php';
			$options = Lightbox::get_instance()->get_options();
			$this->add_options($options, $photonic_setup_options);

			$defaults = Defaults::get_options();
			foreach ($photonic_setup_options as $default_option) {
				if (isset($default_option['id'])) {
					$default_option['std'] = $defaults[$default_option['id']];
				}
			}
		}
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Options();
		}
		return self::$instance;
	}

	/**
	 * @param $options array
	 * @param $photonic_setup_options array
	 */
	public function add_options($options, &$photonic_setup_options) {
		foreach ($options as $option) {
			$photonic_setup_options[] = $option;
		}
	}
}

Options::get_instance();
