<?php

class QiAddonsForElementor_Framework_Shortcodes {
	private $shortcodes = array();

	public function __construct() {
		if ( isset( $_GET['elementor_updater'] ) && 'continue' === $_GET['elementor_updater'] ) {
			
			if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>' ) ) {
				add_action( 'elementor/widgets/register', array( $this, 'register' ), 5 ); // Permission 5 is set in order to include shortcode files before register '-elementor.php' files
			} else {
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'register' ), 5 ); // Permission 5 is set in order to include shortcode files before register '-elementor.php' files
			}
		} else {
			add_action( 'init', array( $this, 'register' ), 0 ); // Permission 0 is set in order to register shortcodes before widgets, because widgets using shortcodes options
		}
	}

	public function get_shortcodes() {
		return $this->shortcodes;
	}

	public function set_shortcodes( $base, $shortcode ) {
		$this->shortcodes[ $base ] = $shortcode;
	}

	public function get_shortcode( $base ) {
		$shortcodes = $this->get_shortcodes();

		if ( ! empty( $shortcodes ) && isset( $shortcodes[ $base ] ) ) {
			return $shortcodes[ $base ];
		}

		return false;
	}

	private function set_shortcode( QiAddonsForElementor_Framework_Shortcode $shortcode ) {
		$this->set_shortcodes( $shortcode->get_base(), $shortcode );
	}

	public function shortcode_exists( $base ) {
		return array_key_exists( $base, $this->get_shortcodes() );
	}

	public function add_shortcode( QiAddonsForElementor_Framework_Shortcode $shortcode ) {
		$key = $shortcode->get_base();

		if ( ! empty( $key ) ) {
			$this->set_shortcode( $shortcode );

			return $shortcode;
		}

		return false;
	}

	public function register() {
		do_action( 'qi_addons_for_elementor_action_framework_before_shortcodes_register' );

		$shortcodes = $this->get_shortcodes();

		if ( ! empty( $shortcodes ) && is_array( $shortcodes ) ) {
			ksort( $shortcodes );

			foreach ( $shortcodes as $shortcode ) {
				$shortcode->register();
			}
		}
		do_action( 'qi_addons_for_elementor_action_framework_after_shortcodes_register' );
	}
}
