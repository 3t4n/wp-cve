<?php

class idivi_post_settings_Activator {

	/**
	 * Activate the plugin.
	 */
	public static function activate() {

		$current_theme = wp_get_theme();
	  if ( 'Divi' === $current_theme->get( 'Name' ) || 'Divi' === $current_theme->get( 'Template' ) ) {
	 	 return true;
	  }
     wp_redirect( site_url('wp-admin/plugins.php?failed=DiviIsNotActive') );
		 exit;

	//wp_die( __( 'You need to have Divi theme active! Divi Post Settings depends from Divi.' ) );

	}


}




 ?>
