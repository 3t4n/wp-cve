<?php
namespace Vimeotheque\Playlist\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @ignore
 */
final class Theme {
	/**
	 * Theme main file
	 *
	 * @var string
	 */
	private $file;
	/**
	 * Theme name
	 *
	 * @var string
	 */
	private $name;
	/**
	 * Theme URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * Theme absolute path
	 *
	 * @var string
	 */
	private $path;
	/**
	 * @var string
	 */
	private $folder_name;


	/**
	 * Theme constructor.
	 *
	 * @param string $file theme file absolute path
	 * @param $name
	 */
	public function __construct( $file, $name ) {
		$this->file = $file;
		$this->name = $name;

		$this->url = plugin_dir_url( $file );
		$this->path = plugin_dir_path( $file );
		$this->folder_name = basename( dirname( $file ) );
	}

	/**
	 * @return string
	 */
	public function get_style_url(){
		return $this->get_url() . 'assets/stylesheet.css';
	}

	/**
	 * @return string
	 */
	public function get_js_url(){
		return $this->get_url() . 'assets/script.js';
	}

	/**
	 * @return string
	 */
	public function get_theme_name(){
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function get_folder_name() {
		return $this->folder_name;
	}
}