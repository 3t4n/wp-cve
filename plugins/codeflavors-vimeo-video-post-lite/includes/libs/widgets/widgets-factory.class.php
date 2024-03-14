<?php

namespace Vimeotheque\Widgets;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @ignore
 */
class Widgets_Factory{
/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Widgets_Factory constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );
		add_action('admin_print_scripts-widgets.php', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Widgets registration
	 */
	public function register_widgets(){
		if( $this->is_public() ){
			register_widget( __NAMESPACE__ . '\Categories_Widget' );
			register_widget( __NAMESPACE__ . '\Playlist_Widget' );
		}
	}

	/**
	 * Enqueue assets
	 */
	public function enqueue_assets(){
		if( $this->is_public() ){
			wp_enqueue_script(
				'cvm-video-edit',
				VIMEOTHEQUE_URL . 'assets/back-end/js/video-edit.js',
				[ 'jquery' ],
				'1.0'
			);

			wp_enqueue_style(
				'cvm-widget-style',
				VIMEOTHEQUE_URL . 'assets/back-end/css/widget.css'
			);
		}
	}

	/**
	 * @return bool
	 */
	private function is_public(){
		$options = $this->plugin->get_options();
		return $options['public'];
	}
}