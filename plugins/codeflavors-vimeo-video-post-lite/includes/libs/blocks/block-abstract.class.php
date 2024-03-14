<?php


namespace Vimeotheque\Blocks;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Block_Abstract
 * @package Vimeotheque\Blocks
 * @ignore
 */
class Block_Abstract {

	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * @var array
	 */
	private $styles = [];

	/**
	 * @var \WP_Block_Type
	 */
	private $wp_block_type;

	/**
	 * Stores the handle for the block main script file
	 *
	 * @var string
	 */
	private $block_script_handle;

	/**
	 * Stylesheet editor handle
	 *
	 * @var string
	 */
	private $editor_style_handle;
	/**
	 * Front-end styling handle
	 *
	 * @var string
	 */
	private $style_handle;

	/**
	 * Set block as active (true) or inactive (false)
	 *
	 * @var bool
	 */
	private $is_active = true;

	/**
	 * Block_Abstract constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Registers block script
	 *
	 * @param $handle
	 * @param $block
	 *
	 * @return mixed
	 */
	protected function register_script( $handle, $block ){

		global $pagenow;

		$dependencies = [
			'wp-blocks',
			'wp-element',
			'wp-i18n',
			'wp-components',
            'lodash'
		];

		// In WP 5.8, the dependency into the widgets page is 'wp-edit-widgets'
		$dependencies[] = 'widgets.php' === $pagenow ? 'wp-edit-widgets' : 'wp-editor';

		wp_register_script(
			$handle,
			VIMEOTHEQUE_URL . 'assets/back-end/js/apps/block-editor/' . $block . '/app.build.js',
			$dependencies
		);

		$this->block_script_handle = $handle;

		return $handle;

	}

	/**
	 * @param $handle
	 * @param $block
	 * @param bool $editor_style
	 *
	 * @return mixed
	 */
	protected function register_style( $handle, $block, $editor_style = false ){
		$file = $editor_style ? 'editor.css' : 'style.css';
		wp_register_style(
			$handle,
			VIMEOTHEQUE_URL . 'assets/back-end/js/apps/block-editor/' . $block . '/' . $file
		);

		if( $editor_style ){
			$this->editor_style_handle = $handle;
		}else{
			$this->style_handle = $handle;
		}

		return $handle;
	}

	/**
	 * @param $name
	 * @param array $args
	 *
	 * @return mixed|\WP_Block_Type
	 */
	protected function register_block_type(  $name, $args = array() ){
		$this->wp_block_type = register_block_type( $name, $args );
		return $this->wp_block_type;
	}

	/**
	 * Deactivate the block
	 */
	public function deactivate(){
		if( $this->is_active() ) {
			unregister_block_type( $this->wp_block_type );
			$this->is_active = false;
		}
	}

	/**
	 * @return \WP_Block_Type
	 */
	public function get_wp_block_type(){
		return $this->wp_block_type;
	}

	/**
	 * @return Plugin
	 */
	public function get_plugin(){
		return $this->plugin;
	}

	/**
	 * Returns block main script handle
	 *
	 * @return string
	 */
	public function get_script_handle(){
		return $this->block_script_handle;
	}

	/**
	 * @return string
	 */
	public function get_editor_style_handle() {
		return $this->editor_style_handle;
	}

	/**
	 * @return string
	 */
	public function get_style_handle() {
		return $this->style_handle;
	}

	/**
	 * @return bool
	 */
	public function is_active(){
		return $this->is_active;
	}
}