<?php
/**
 * This is setting view component file.
 *
 * @package broken-link-finder/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="moblc_next_divided_layout" style="display: block;">
	<div class="moblc_setting_layout">
			<div class="debug-file-text">
				<input type="hidden" id="moblc_nonce_enable_debug_log" name="moblc_nonce_enable_debug_log"
					value="<?php echo esc_attr( wp_create_nonce( 'moblc-nonce-enable-debug-log' ) ); ?>"/>
				<h3>Debug Log</h3>
				<br>
				<hr style="margin-top:-1%;">
				<p><i>If you enable this checkbox, the plugin log will be enable.</i>
					</label>
					<label class="moblc_switch" style="float:right">
						<input type="checkbox" onChange="moblc_debug_log()" id="moblc_debug_log_id"
							name="moblc_enable_debug_log"
							value="<?php esc_attr( get_site_option( 'moblc_debug_log' ) ); ?>"<?php checked( get_site_option( 'moblc_debug_log' ) === '1' ); ?>/>
						<span class="moblc_slider moblc_round"></span>
					</label>
				</p>
			</div>
		<?php if ( get_site_option( 'moblc_debug_log' ) === '1' ) { ?>
			<div class="moblc_debug-file-button">
					<form name="f" method="post" action="" id="moblc_download_log_file">
						<input type="submit" class="moblc_button moblc_button1" value="Download log file"
							id="moblc_debug_form" name="moblc_debug_form">
						<input type="button" class="moblc_button moblc_button1" value="Delete log file"
							id="moblc_debug_delete_form" name="moblc_debug_delete_form">
						<input type="hidden" id="moblc_download_log" name="moblc_nonce_download_log"
							value="<?php echo esc_attr( wp_create_nonce( 'moblc-nonce-download-log' ) ); ?>"/>
						<input type="hidden" id="moblc_download_log" name="option" value="log_file_download"/>
					</form>
					<form name="f" method="post" action="" id="moblc_delete_log_file">
						<input type="hidden" id="moblc_delete_log" name="moblc_nonce_delete_log"
							value="<?php echo esc_attr( wp_create_nonce( 'moblc-nonce-delete-log' ) ); ?>"/>
						<input type="hidden" id="moblc_delete_logs" name="option" value="log_file_delete"/>
					</form>
			</div>
		<?php } ?>
		<br>
	</div>
</div>

<script>

	function moblc_debug_log() {
		var data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_enable_disable_debug_log',
			'nonce': jQuery('#moblc_nonce_enable_debug_log').val(),
			'moblc_enable_debug_log': jQuery('#moblc_debug_log_id').is(":checked"),
		};
		jQuery.post(ajaxurl, data, function (response) {
			var response = response.replace(/\s+/g, ' ').trim();
			if (response == 'true') {
				setTimeout(() => {
					location.reload();
				}, 1000);
				jQuery('.moblc_debug-file-button').css("visibility", "visible");
				moblc_success_msg("Plugin log is now enabled.");
			} else {
				setTimeout(() => {
					location.reload();
				}, 1000);
				jQuery('.moblc_debug-file-button').css("visibility", "hidden");
				moblc_error_msg("Plugin log is now disabled.");
			}
		});
	}


	jQuery('#moblc_debug_delete_form').click(function () {

		var data = {
			'action': 'moblc_broken_link_checker',
			'option': 'moblc_delete_log_file',
			'nonce': jQuery('#moblc_delete_log').val(),

		};
		jQuery.post(ajaxurl, data, function (response) {
			var response = response.replace(/\s+/g, ' ').trim();
			if (response == "true") {
				moblc_success_msg("Log file deleted.");
			} else {
				moblc_error_msg("Log file is not available.");
			}
		});
	});

</script>
