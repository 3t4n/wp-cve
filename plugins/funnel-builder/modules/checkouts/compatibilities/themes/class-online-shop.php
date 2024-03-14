<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Online_Shop {


	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'unhook_customizer_hooks' ] );

	}

	public function unhook_customizer_hooks() {


		remove_action( 'customize_register', 'online_shop_customize_register' );
		remove_action( 'customize_preview_init', 'online_shop_customize_preview_js' );
		remove_action( 'customize_controls_enqueue_scripts', 'online_shop_customize_controls_scripts' );

	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Online_Shop(), 'online-shop' );
