<?php

namespace CatFolder_Document_Gallery\Engine;

use CatFolder_Document_Gallery\Helpers\Helper;
use CatFolder_Document_Gallery\Utils\SingletonTrait;

class Shortcode {
	use SingletonTrait;

	private function __construct() {
		add_action( 'init', array( $this, 'register_shortcode' ) );
	}

	public function register_shortcode() {
		add_shortcode( 'catf_dg', array( $this, 'handle_custom_shortcode' ) );
	}

	public function handle_custom_shortcode( $attrs ) {
		if ( ! isset( $attrs['id'] ) ) {
			return;
		}

		$args = array(
			'shortcodeId' => $attrs['id'],
		);

		$attributes = Helper::get_shortcode_data( $args );

		$this->enqueue_scripts();

		ob_start();

		include CATF_DG_DIR . '/includes/Engine/Views/Table.php';

		return ob_get_clean();

	}

	private function enqueue_scripts() {
		wp_enqueue_script( 'catf-dg-datatables' );
		wp_enqueue_script( 'catf-dg-datatables-natural' );
		wp_enqueue_script( 'catf-dg-datatables-filesize' );
		wp_enqueue_script( 'catf-dg-datatables-responsive' );
		wp_enqueue_script( 'catf-dg-frontend', CATF_DG_URL . 'build/view.js', array( 'wp-i18n' ), CATF_DG_VERSION );

		wp_enqueue_style( 'catf-dg-datatables' );
		wp_enqueue_style( 'catf-dg-frontend' );
		wp_enqueue_style( 'catf-dg-datatables-responsive' );
	}
}
