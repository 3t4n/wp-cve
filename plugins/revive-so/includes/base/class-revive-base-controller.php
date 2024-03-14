<?php 
/**
 * Base controller class.
 *
 */

/**
 * Base Controller class.
 */
class REVIVESO_BaseController
{
	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	public $plugin_path;

	/**
	 * Plugin URL.
	 *
	 * @var string
	 */
	public $plugin_url;

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	public $plugin;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Plugin tag.
	 *
	 * @var string
	 */
	public $tag;
	
	/**
     * The constructor.
     */
	public function __construct() {
		$this->plugin_path = REVIVESO_PATH;
		$this->plugin_url = REVIVESO_URL;
		$this->plugin = REVIVESO_BASENAME;
		$this->version = REVIVESO_VERSION;
		$this->name = 'Revive.so';
		$this->tag = apply_filters('reviveso_base_controller_tag', '' );
	}
}
