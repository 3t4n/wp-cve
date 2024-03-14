<?php


defined( 'ABSPATH' ) || exit();

class Dracula_Blocks {
	/**
	 * @var null
	 */
	protected static $instance = null;

	public function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	public function register_block() {
		register_block_type( DRACULA_PATH . '/blocks/build/switch', [
			'render_callback' => [ $this, 'render_toggle_switch' ],
		] );
	}

	public function render_toggle_switch( $attributes, $content ) {

		$style = ! empty( $attributes['style'] ) ? $attributes['style'] : 1;
		$id  = ! empty( $attributes['data']['id'] ) ? $attributes['data']['id'] : '';

		return do_shortcode( sprintf( '[dracula_toggle style="%s" id="%s"]', $style, $id ) );
	}

	/**
	 * @return Dracula_Blocks|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

Dracula_Blocks::instance();


