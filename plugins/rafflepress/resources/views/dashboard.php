<?php
// translations
require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/views/backend-translations.php';


$rafflepress_api_token = get_option( 'rafflepress_api_token' );
$license_key           = get_option( 'rafflepress_api_key' );

$current_user = wp_get_current_user();
$name         = ',';
if ( ! empty( $current_user->user_firstname ) ) {
	$name = $current_user->user_firstname . ',';
}
$email = $current_user->user_email;

$timezones = rafflepress_lite_get_timezones();

// Pers
$per = array();
$active_license = false;

$license_name = '';


// Get notifications
$notifications = new RafflePress_Notifications();
$notifications = $notifications->get();

?>


<div id="rafflepress-vue-app"></div>
<script>

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_run_one_click_upgrade', 'rafflepress_lite_run_one_click_upgrade' ) ); ?>
var rafflepress_run_one_click_upgrade_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_upgrade_license', 'rafflepress_lite_upgrade_license' ) ); ?>
var rafflepress_upgrade_license_url = "<?php echo $ajax_url; ?>";


<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_plugin_nonce', 'rafflepress_lite_plugin_nonce' ) ); ?>
var rafflepress_plugin_nonce_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_get_plugins_list', 'rafflepress_lite_get_plugins_list' ) ); ?>
var rafflepress_get_plugins_list_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_install_addon', 'rafflepress_lite_install_addon' ) ); ?>
var rafflepress_get_install_addon_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_activate_addon', 'rafflepress_lite_activate_addon' ) ); ?>
var rafflepress_activate_addon_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_deactivate_addon', 'rafflepress_lite_deactivate_addon' ) ); ?>
var rafflepress_deactivate_addon_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_giveaway_datatable', 'rafflepress_lite_giveaway_datatable' ) ); ?>
var rafflepress_giveaway_datatable_url = "<?php echo $ajax_url; ?>";


<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_get_giveaway_list', 'rafflepress_lite_get_giveaway_list' ) ); ?>
var rafflepress_get_giveaway_list = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_duplicate_giveaway', 'rafflepress_lite_duplicate_giveaway' ) ); ?>
var rafflepress_duplicate_giveaway_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_archive_selected_giveaways', 'rafflepress_lite_archive_selected_giveaways' ) ); ?>
var rafflepress_archive_selected_giveaways = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_notification_dismiss', 'rafflepress_lite_notification_dismiss' ) ); ?>
var rafflepress_notification_dismiss = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_unarchive_selected_giveaways', 'rafflepress_lite_unarchive_selected_giveaways' ) ); ?>
var rafflepress_unarchive_selected_giveaways = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_delete_archived_giveaways', 'rafflepress_lite_delete_archived_giveaways' ) ); ?>
var rafflepress_delete_archived_giveaways = "<?php echo $ajax_url; ?>";

<?php
$save_settings_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_save_settings', 'rafflepress_lite_save_settings' ) );
?>
var rafflepress_save_settings_ajax_url = "<?php echo $save_settings_ajax_url; ?>";

<?php
$dismiss_settings_lite_cta_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_dismiss_settings_lite_cta', 'rafflepress_lite_dismiss_settings_lite_cta' ) );
?>
var rafflepress_dismiss_settings_lite_cta_url = "<?php echo $dismiss_settings_lite_cta_ajax_url; ?>";

<?php
$save_api_key_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_save_api_key', 'rafflepress_lite_save_api_key' ) );
?>
var rafflepress_save_api_key_ajax_url = "<?php echo $save_api_key_ajax_url; ?>";

<?php
$deactivate_api_key_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_deactivate_api_key', 'rafflepress_lite_deactivate_api_key' ) );
?>
var rafflepress_deactivate_api_key_ajax_url = "<?php echo $deactivate_api_key_ajax_url; ?>";

<?php
$end_giveaway_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_end_giveaway', 'rafflepress_lite_end_giveaway' ) );
?>
var rafflepress_end_giveaway_ajax_url = "<?php echo $end_giveaway_ajax_url; ?>";

<?php
$start_giveaway_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_start_giveaway', 'rafflepress_lite_start_giveaway' ) );
?>
var rafflepress_start_giveaway_ajax_url = "<?php echo $start_giveaway_ajax_url; ?>";

<?php
$start_giveaway_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_start_giveaway', 'rafflepress_lite_start_giveaway' ) );
?>
var rafflepress_start_giveaway_ajax_url = "<?php echo $start_giveaway_ajax_url; ?>";


<?php
$enable_disable_giveaway_ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_enable_disable_giveaway', 'rafflepress_lite_enable_disable_giveaway' ) );
?>
var rafflepress_enable_disable_giveaway_ajax_url = "<?php echo $enable_disable_giveaway_ajax_url; ?>";


<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_contestants_resend_email', 'rafflepress_lite_contestants_resend_email' ) ); ?>
var rafflepress_contestants_resend_email_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_contestants_datatable', 'rafflepress_lite_contestants_datatable' ) ); ?>
var rafflepress_contestants_datatable_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_confirm_selected_contestants', 'rafflepress_lite_confirm_selected_contestants' ) ); ?>
var rafflepress_confirm_selected_contestants_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_unconfirm_selected_contestants', 'rafflepress_lite_unconfirm_selected_contestants' ) ); ?>
var rafflepress_unconfirm_selected_contestants_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_invalid_selected_contestants', 'rafflepress_lite_invalid_selected_contestants' ) ); ?>
var rafflepress_invalid_selected_contestants_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_delete_invalid_contestants', 'rafflepress_lite_delete_invalid_contestants' ) ); ?>
var rafflepress_delete_invalid_contestants_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_entries_report_datatable', 'rafflepress_lite_entries_report_datatable' ) ); ?>
var rafflepress_entries_report_datatable_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_ps_results_datatable', 'rafflepress_lite_ps_results_datatable' ) ); ?>
var rafflepress_ps_results_datatable_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_entries_datatable', 'rafflepress_lite_entries_datatable' ) ); ?>
var rafflepress_entries_datatable_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_valid_selected_entries', 'rafflepress_lite_valid_selected_entries' ) ); ?>
var rafflepress_valid_selected_entries_url = "<?php echo $ajax_url; ?>";


<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_invalid_selected_entries', 'rafflepress_lite_invalid_selected_entries' ) ); ?>
var rafflepress_invalid_selected_entries_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_delete_invalid_entries', 'rafflepress_lite_delete_invalid_entries' ) ); ?>
var rafflepress_delete_invalid_entries_url = "<?php echo $ajax_url; ?>";

<?php $ajax_url = html_entity_decode( wp_nonce_url( 'admin-ajax.php?action=rafflepress_lite_pick_winners', 'rafflepress_lite_pick_winners' ) ); ?>
var rafflepress_pick_winners_url = "<?php echo $ajax_url; ?>"; 


<?php
$admin_url = admin_url();
$ajax_url  = html_entity_decode( wp_nonce_url( 'admin.php?page=rafflepress_lite&action=rafflepress_lite_export_contestants', 'rafflepress_lite_export_contestants' ) );
?>
var rafflepress_export_contestants_url = "<?php echo $ajax_url; ?>";

<?php
$admin_url = admin_url();
$ajax_url  = html_entity_decode( wp_nonce_url( 'admin.php?page=rafflepress_lite&action=rafflepress_lite_export_entries', 'rafflepress_lite_export_entries' ) );
?>
var rafflepress_export_entries_url = "<?php echo $ajax_url; ?>";


<?php $rafflepress_settings = get_option( 'rafflepress_settings' ); ?>
<?php
$rafflepress_api_key = get_option( 'rafflepress_api_key' );
if ( $rafflepress_api_key === false ) {
	$rafflepress_api_key = '';
}
?>
<?php $rafflepress_upgrade_link = rafflepress_lite_upgrade_link( '' ); ?>

<?php
$lmsg = get_option( 'rafflepress_api_message' );
if ( empty( $lmsg ) ) {
	$lmsg = '';
}
$lclass = 'alert-danger';
if ( rafflepress_lite_cu() ) {
	$lclass = 'alert-success';
}
?>



// settings: {
// 				button: false,
// 				lmsg: "",
// 				lclass: "",
// 				api_key: "",
// 				default_timezone: "UTC",
// 				slug: "rafflepress",
// 				updates: "none",
// 				updates_to: ""
// 			},

var rafflepress_data_admin =
	<?php
	echo json_encode(
		array(
			'notifications'             => $notifications,
			'api_token'                 => $rafflepress_api_token,
			'license_key'               => $license_key,
			'license_name'              => $license_name,
			'per'                       => $per,
			'active_license'            => $active_license,
			'page_path'                 => 'rafflepress_lite',
			'plugin_path'               => RAFFLEPRESS_PLUGIN_URL,
			'home_url'                  => home_url(),
			'upgrade_link'              => $rafflepress_upgrade_link,
			'timezones'                 => $timezones,
			'api_key'                   => $rafflepress_api_key,
			'name'                      => $name,
			'email'                     => $email,
			'lmsg'                      => $lmsg,
			'lclass'                    => $lclass,
			'settings'                  => json_decode( $rafflepress_settings ),
			'dismiss_settings_lite_cta' => get_option( 'rafflepress_dismiss_settings_lite_cta' ),
			'inline_help_articles'      => rafflepress_lite_fetch_inline_help_data(),
		)
	);
	?>
						;

var rafflepress_backend_translation_data = 
		<?php echo json_encode( $rp_backend_translations ); ?>;

</script>
