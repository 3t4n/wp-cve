<?php
namespace Thim_EL_Kit\Elementor;

use Thim_EL_Kit\SingletonTrait;

class Library {
	use SingletonTrait;

	public function __construct() {
		$this->includes();

		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 100 );
	}

	public function includes() {
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/library/class-rest-api.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/library/class-import.php';
	}

	public function enqueue_editor_scripts() {
		$file_info = include THIM_EKIT_PLUGIN_PATH . 'build/library.asset.php';

		wp_enqueue_script( 'thim-elementor-kit-elementor-library', THIM_EKIT_PLUGIN_URL . 'build/library.js', $file_info['dependencies'], $file_info['version'], true );
		wp_enqueue_style( 'thim-elementor-kit-elementor-library', THIM_EKIT_PLUGIN_URL . 'build/library.css', array(), THIM_EKIT_VERSION );

		$theme = wp_get_theme();

		if ( is_child_theme() ) {
			$theme = wp_get_theme( $theme->parent()->template );
		}

		wp_localize_script(
			'thim-elementor-kit-elementor-library',
			'ThimElementorLibrary',
			array(
				'logo'   => THIM_EKIT_PLUGIN_URL . 'build/libraries/logo.png',
				'postID' => get_the_ID(),
				'theme'  => $theme->get( 'TextDomain' ),
			)
		);
	}
}

Library::instance();
