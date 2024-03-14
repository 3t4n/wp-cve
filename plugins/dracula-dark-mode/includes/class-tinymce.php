<?php

defined( 'ABSPATH' ) || exit();

class Dracula_TinyMCE {
	/**
	 * @var null
	 */
	protected static $instance = null;

	public function __construct() {
		
		add_filter( 'mce_css', [ $this, 'enqueue_css' ] );
		add_filter( 'mce_buttons', [ $this, 'add_buttons' ] );
		add_filter( 'mce_external_plugins', [ $this, 'add_plugins' ] );
	}

	public function add_buttons( $buttons ) {
		$buttons[] = 'dracula_toggle';

		return $buttons;
	}

	public function add_plugins( $plugins ) {
		$plugins['dracula_tinymce_js'] = DRACULA_ASSETS . '/js/tinymce.js';

		return $plugins;
	}


	public function enqueue_css( $mce_css ) {
		if ( ! empty( $mce_css ) ) {
			$mce_css .= ',';
		}

		$mce_css .= DRACULA_ASSETS . '/css/tinymce.css';

		return $mce_css;
	}

	/**
	 * @return Dracula_TinyMCE|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

Dracula_TinyMCE::instance();