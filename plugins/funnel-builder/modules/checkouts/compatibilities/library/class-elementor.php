<?php

#[AllowDynamicProperties] 

  class WFACP_Compatibility_elementor {
	private static $ins = null;

	private function __construct() {
		add_action( 'elementor/theme/register_conditions', [ $this, 'register_conditions' ] );
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function register_conditions( $conditions_manager ) {
		require __DIR__ . '/page-builder/class-elementor-exit-intent.php';
		$new_condition = new ElementorPro\Modules\ThemeBuilder\Conditions\WFACP_Elementor_exit_intent( [
			'post_type' => WFACP_Common::get_post_type_slug(),
		] );
		$conditions_manager->get_condition( 'singular' )->register_sub_condition( $new_condition );
	}
}

add_action( 'plugins_loaded', function () {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}
	WFACP_Compatibility_elementor::get_instance();

}, 0 );