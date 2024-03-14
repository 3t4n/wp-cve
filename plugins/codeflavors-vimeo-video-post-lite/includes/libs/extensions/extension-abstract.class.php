<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Extension_Abstract
 * @package Vimeotheque\Extensions
 * @since 2.1
 * @ignore
 */
class Extension_Abstract {
	/**
	 * @var string
	 */
	private $dirname;

	/**
	 * @var string
	 */
	private $filename;

	/**
	 * The plugin name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var array|null
	 */
	private $plugin_data = null;

	/**
	 * Is add-on a PRO add-on
	 *
	 * @var bool
	 */
	private $pro_addon = false;

	/**
	 * @var int|false
	 */
	private $file_id = false;

	/**
	 * Set the plugin slug
	 *
	 * @param string $slug
	 */
	protected function set_slug( $slug ){
		$path_parts = pathinfo( $slug );
		$this->dirname = $path_parts['dirname'];
		$this->filename = $path_parts['basename'];
	}

	/**
	 * @param string $name
	 */
	protected function set_name( $name ){
		$this->name = $name;
	}

	/**
	 * @param string $description
	 */
	protected function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * Setter for PRO add-on
	 */
	public function set_pro_addon() {
		$this->pro_addon = true;
	}

	/**
	 * @param int $file_id
	 */
	public function set_file_id( $file_id ) {
		$this->file_id = $file_id;
	}

	/**
	 * Returns the plugin file relative path
	 *
	 * @return string
	 */
	public function get_file() {
		return $this->dirname . '/' . $this->filename;
	}

	/**
	 * Returns plugin slug
	 *
	 * @return string
	 */
	public function get_slug(){
		return $this->dirname;
	}

	/**
	 * Returns the activation URL
	 *
	 * @param false $redirect_to
	 *
	 * @return string
	 */
	public function activation_url( $redirect_to = false ){
		$action = 'activate';
		$nonce_action = $action . '-plugin_' . $this->get_file();

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->get_file()
				],
				admin_url( 'plugins.php' )
			),
			$nonce_action
		);
	}

	/**
	 * Returns deactivation URL
	 *
	 * @param false $redirect_to
	 *
	 * @return string
	 */
	public function deactivation_url( $redirect_to = false ){
		$action = 'deactivate';
		$nonce_action = $action . '-plugin_' . $this->get_file();

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->get_file()
				],
				admin_url( 'plugins.php' )
			),
			$nonce_action
		);
	}

	/**
	 * Returns installation URL
	 *
	 * @return string
	 */
	public function install_url(){
		$action = 'install-plugin';
		$nonce_action = $action . '_' . $this->dirname;

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->dirname
				],
				admin_url( 'update.php' )
			),
			$nonce_action
		);
	}

	/**
	 * Returns upgrade URL
	 *
	 * @return string
	 */
	public function upgrade_url(){
		$action = 'upgrade-plugin';
		$nonce_action = $action . '_' . $this->get_file();

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->get_file()
				],
				admin_url( 'update.php' )
			),
			$nonce_action
		);
	}

	/**
	 * Method must be overriden for PRO extensions
	 *
	 * @return false
	 */
	public function is_pro_addon(){
		return $this->pro_addon;
	}

	/**
	 * Returns the plugin name
	 *
	 * @return string
	 */
	public function get_name(){
		return $this->name;
	}

	/**
	 * Returns the plugin data, if installed. If plugin is not installed
	 * returns false
	 *
	 * @uses get_plugin_data()
	 * @uses trailingslashit()
	 *
	 * @return array|false
	 */
	public function get_plugin_data(){
		if( null !== $this->plugin_data ){
			return $this->plugin_data;
		}

		$plugin_file = trailingslashit( WP_PLUGIN_DIR ) . $this->get_file();
		if( file_exists( $plugin_file ) ){
			$this->plugin_data = get_plugin_data( $plugin_file );
		}

		return $this->plugin_data;
	}

	/**
	 * Returns the plugin data if the plugin is installed or false if not installed
	 *
	 * @return bool
	 */
	public function is_installed(){
		return $this->get_plugin_data() ? true : false;
	}

	/**
	 * Get plugin description
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Returns whether the plugin is active or not.
	 *
	 * @uses is_plugin_active()
	 *
	 * @return bool
	 */
	public function is_activated(){
		return is_plugin_active( $this->get_file() );
	}

	/**
	 * Override in concrete implementation if neccessary
	 *
	 * @return false|int
	 */
	public function get_file_id(){
		return $this->file_id;
	}
}