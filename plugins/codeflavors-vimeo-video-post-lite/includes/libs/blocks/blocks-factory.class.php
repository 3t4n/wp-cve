<?php


namespace Vimeotheque\Blocks;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Blocks_Factory
 * @package Vimeotheque\Blocks
 * @ignore
 */
class Blocks_Factory {

	/**
	 * @var Plugin
	 */
	private $plugin;
	/**
	 * @var Block_Abstract[] $blocks
	 */
	private $blocks = [];

	/**
	 * Blocks_Factory constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'init', [ $this, 'register_blocks' ], 11 );

	}

	/**
	 * Register plugin blocks
	 */
	public function register_blocks(){
		$this->blocks['video_position'] = new Video_Position( $this->plugin );
		$this->blocks['playlist'] = new Playlist( $this->plugin );
		$this->blocks['video'] = new Video( $this->plugin );
	}

	/**
	 * Unregisters a block
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	public function unregister_block( $key ){
		if( array_key_exists( $key, $this->blocks ) ){
			$this->blocks[ $key ]->deactivate();
			return true;
		}
	}

	/**
	 * Deactivate all blocks
	 */
	public function unregister_blocks(){
		foreach( $this->blocks as $block ){
			$block->deactivate();
		}
	}

	/**
	 * @return Block_Abstract[]
	 */
	public function get_blocks(){
		return $this->blocks;
	}

	/**
	 * @param string $key - block key
	 * @see Blocks_Factory::register_blocks() for details
	 *
	 * @return Block_Abstract
	 */
	public function get_block( $key ){
		if( array_key_exists( $key, $this->blocks ) ){
			return $this->blocks[ $key ];
		}else{
			trigger_error( sprintf('Block %s doesn not exist.', $key ), E_USER_NOTICE );
		}
	}
}