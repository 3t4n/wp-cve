<?php 

namespace app\init;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

define('SSEO_CSS_PATH', dirname(dirname(__FILE__))) . 'css' . DIRECTORY_SEPARATOR;
define('SSEO_JS_PATH', dirname(dirname(__FILE__)) . 'js' . DIRECTORY_SEPARATOR);

class Enqueue {
    /**
     * Plugin version for enqueueing, etc.
     * The value is retrieved from the CDS_SSEO_VERSION constant.
     *
     * @since 2.0.0
     *
     * @var string
     */
	public $version = '';
	
	/**
 	 * Enqueues the CSS and JS files.
	 *
	 * @since  2.0.0
 	 */
	public function __construct() {
		$this->version = SSEO_VERSION;

		if (is_admin()) {
			wp_enqueue_style(
				'sseo_admin_style', 
				plugins_url('css/style.css', SSEO_CSS_PATH), 
				false, 
				$this->version
			);
			
			wp_enqueue_script(
				'sseo_script', 
				plugins_url('js/script.js', SSEO_JS_PATH), 
				['jquery'], 
				$this->version, 
				true
			);
			
			wp_enqueue_script(
				'sseo_quickedit', 
				plugins_url('js/quickedit.js', SSEO_JS_PATH), 
				false, 
				$this->version, 
				true);
		}
	}

}