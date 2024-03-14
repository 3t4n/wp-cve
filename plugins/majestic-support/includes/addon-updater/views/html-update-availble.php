<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!empty($this->addon_installed_array)){
	$new_transient_flag = 0;
	$response = get_transient('ms_addon_update_flag');
	if(!$response){
		$response = $this->MJTC_getPluginLatestVersionData();
		set_transient('ms_addon_update_flag',$response,HOUR_IN_SECONDS * 6);
		$new_transient_flag = 1;
	}
	if(!empty($response)){
		foreach ($this->addon_installed_array as $addon) {
			if(!isset($response[$addon])){
				continue;
			}
			$plugin_file_path = content_url().'/plugins/'.$addon.'/'.$addon.'.php';

			$plugin_data = get_plugin_data($plugin_file_path);
			$transient_val = get_transient('dismiss-ms-addon-update-notice-'.$addon);
			if($new_transient_flag == 1){
				delete_transient('dismiss-ms-addon-update-notice-'.$addon);
			}
			if(!$transient_val){
				if (version_compare( $response[$addon], $plugin_data['Version'], '>' ) ) { ?>
					<div class="updated">
						<p class="wpjm-updater-dismiss" style="float:right;"><a href="<?php echo esc_url( add_query_arg( 'dismiss-ms-addon-update-notice-' . sanitize_title( $addon ), '1' ) ); ?>"><?php __( 'Hide notice','majestic-support' ); ?></a></p>
						<p><?php printf( wp_kses('<a href="%s">New Version is avaible</a> for "%s".', admin_url('plugins.php'), esc_html( $plugin_data['Name'] ), MJTC_ALLOWED_TAGS) ); ?></p>
					</div>
				<?php }
			}
		}
	}

}

if(get_option( 'ms-addon-key-error-message', '' ) != ''){
	$html = '<div class="notice notice-error is-dismissible"><p>'. esc_html(get_option( 'ms-addon-key-error-message')) .'</p></div>';
	echo wp_kses($html, MJTC_ALLOWED_TAGS);
	delete_option( 'ms-addon-key-error-message' );
}
?>
