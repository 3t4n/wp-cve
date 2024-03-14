<?php

class Owm_Migration_Action_Wp_Info extends Owm_Migration_Action {
	public static $action_key = 'wp_info';

	public function do_action() {
		return array(
			'is_multi_site' => defined( 'WP_ALLOW_MULTISITE' ) ? WP_ALLOW_MULTISITE : false,
			'is_enable_encrypt' => extension_loaded( 'openssl' ) || extension_loaded( 'mcrypt' )
		);
	}
}