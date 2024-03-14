<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-templates
 */

namespace Vimeotheque\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Frontend_Scripts {

	/**
	 * Constructor
	 */
	public function __construct(){

		add_action(
			'wp_enqueue_scripts',
			[$this, 'enqueue_styles']
		);

		add_action(
			'wp_enqueue_scripts',
			[$this, 'enqueue_scripts']
		);

	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_styles(){
		$styles = $this->get_styles();
		if( $styles ){
			foreach ( $styles as $handle => $args ) {
				wp_enqueue_style(
					$handle,
					$args['src'],
					$args['deps'],
					$args['version'],
					$args['media']
				);
			}
		}
	}

	/**
	 * Return list of styles.
	 *
	 * @return mixed|void
	 */
	private function get_styles(){
		/**
		 * Allow style override.
		 *
		 * Allows overding the default template styles.
		 *
		 * @param array $styles - Array of styles.
		 */
		return apply_filters(
			'vimeotheque\templates\enqueue_styles',
			[
				'vimeotheque-styles' => [
					'src' => \Vimeotheque\Helper::get_url() . 'assets/front-end/css/vimeotheque.css',
					'deps' => '',
					'version' => \Vimeotheque\Helper::get_plugin_version(),
					'media' => 'all'
				]
			]
		);

	}

	/**
	 * Enqueue scripts if needed.
	 *
	 * @return void
	 */
	public function enqueue_scripts(){

		if( \Vimeotheque\Helper::is_video() ){
			if( current_theme_supports( 'vimeotheque-next-video-card' ) ){
				wp_enqueue_script(
					'vimeotheque-end-video-card',
					\Vimeotheque\Helper::get_url() . 'assets/front-end/js/load-next-video.js',
					['jquery','cvm-video-player'],
					\Vimeotheque\Helper::get_plugin_version(),
					true
				);
			}
		}

	}

}