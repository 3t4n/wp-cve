<?php

class idivi_post_settings_Deactivator {

	/**
	 * Deactivate the plugin.
	 */
	public static function deactivate() {
    $user_id = get_current_user_id();
	update_user_option( $user_id, "idivi-dismiss", '' );
		
}

}


?>
