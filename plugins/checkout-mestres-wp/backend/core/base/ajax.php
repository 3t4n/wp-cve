<?php
add_action( 'wp_ajax_cwmpChangeFuncionalidade', 'cwmpChangeFuncionalidade' );
add_action( 'wp_ajax_nopriv_cwmpChangeFuncionalidade', 'cwmpChangeFuncionalidade' );
function cwmpChangeFuncionalidade(){
	if(current_user_can('manage_options')){
		$option_name = 'cwmp_' . $_POST['cwmp_id'];
		$status = get_option($option_name);
		switch($status){
			case "S":
				update_option($option_name, "N");
				echo CWMP_PLUGIN_ADMIN_URL.'assets/images/mwp-ico-off.png';
				break;
			case "N":
				update_option($option_name, "S");
				echo CWMP_PLUGIN_ADMIN_URL.'assets/images/mwp-ico-on.png';
				break;
			default:
				update_option($option_name, "S");
				echo CWMP_PLUGIN_ADMIN_URL.'assets/images/mwp-ico-on.png';
		}
		wp_die();
	}
}