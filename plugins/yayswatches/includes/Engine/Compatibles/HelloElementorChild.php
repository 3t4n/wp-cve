<?php
namespace Yay_Swatches\Engine\Compatibles;

use Yay_Swatches\Utils\SingletonTrait;

defined( 'ABSPATH' ) || exit;


class HelloElementorChild {
	use SingletonTrait;

	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );
	}

	public function wp_loaded() {
		if ( ! defined( 'HELLO_ELEMENTOR_CHILD_VERSION' ) ) {
			return;
		}
		add_filter( 'yay_swatches_jquery_params_args', array( $this, 'yay_swatches_jquery_params_args' ), 10, 1 );
	}

	public function yay_swatches_jquery_params_args( $params ) {
		if ( function_exists( 'hello_elementor_child_scripts_styles' ) ) {
			array_push( $params, 'wp-scripts' );
		}
		return $params;
	}

}
