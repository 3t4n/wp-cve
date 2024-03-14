<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Extensions
 * @package Vimeotheque\Extensions
 * @since 2.1
 * @ignore
 */
class Extensions {
	/**
	 * Holds all available extensions
	 *
	 * @var Extension_Interface[]
	 */
	private $extensions = [];
	/**
	 * @var Remote_Loader
	 */
	private $remote_loader;

	/**
	 * Extensions constructor. Loads all available plugin extensions.
	 */
	public function __construct(){

		$this->register_extension(
			new Extension(
				'vimeotheque-debug/index.php',
				__( 'Vimeotheque Debug', 'codeflavors-vimeo-video-post-lite' ),
				__(
					'Creates and outputs an activity log which stores the debug messages emitted by Vimeotheque when import actions are taken.' ,
					'codeflavors-vimeo-video-post-lite'
				)
			)
		);

		$this->remote_loader = new Remote_Loader(
			'https://vimeotheque.com',
			2,
			$this
		);
	}

	/**
	 * Registers and stores an extension.
	 *
	 * @param Extension_Interface $extension
	 */
	public function register_extension( Extension_Interface $extension ){
		$this->extensions[ $extension->get_slug() ] = $extension;
	}

	/**
	 * @return Extension_Interface[]
	 */
	public function get_registered_extensions() {
		return $this->extensions;
	}

	/**
	 * @param $slug
	 *
	 * @return false|Extension_Interface
	 */
	public function get_extension( $slug ){
		if( isset( $this->extensions[ $slug ] ) ){
			return $this->extensions[ $slug ];
		}

		return false;
	}

	/**
	 * @return Remote_Loader
	 */
	public function get_remote_loader() {
		return $this->remote_loader;
	}
}