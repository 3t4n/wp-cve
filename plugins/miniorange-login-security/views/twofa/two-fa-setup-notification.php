<?php
/**
 * This file is used to show notifiction in the plugin.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * The method is used to display notification in the plugin .
 *
 * @param object $user used to get customer email and id.
 * @return void
 */
function momls_display_test_2fa_notification( $user ) {
	global $momlsdb_queries;
	$mo2f_configured_2_f_a_method = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );

	if ( get_site_option( 'is_onprem' ) ) {

		$mo2f_configured_2_f_a_method = get_user_meta( $user->ID, 'currentMethod', true );
		update_user_meta( $user->ID, $mo2f_configured_2_f_a_method, 1 );

	}
	wp_print_scripts( 'jquery' );
	?>
	<div id="twoFAtestAlertModal" class="modal" role="dialog">
		<div class="mo2f_modal-dialog">
			<!-- Modal content-->
			<div class="modal-content" style="width:660px !important;">
			<div class="mo2f_align_center">
				<div class="modal-header">
					<h2 class="mo2f_modal-title" style="color: var(--mo2f-theme-color);">2FA Setup Successful.</h2>
					<span type="button" id="test-methods" class="modal-span-close" data-dismiss="modal">&times;</span>
				</div>
				<div class="mo2f_modal-body">
					<p style="font-size:14px;"><b><?php echo esc_html( $mo2f_configured_2_f_a_method ); ?> </b> has been set as your 2-factor authentication method.
						<br><br>Please test the login flow once with 2nd factor in another browser or in an incognito window of the
						same browser to ensure you don't get locked out of your site.</p>
				</div>
				<div class="mo2f_modal-footer">
					<button type="button" id="test-methods-button" class="mo_wpsn_button mo_wpsn_button1" data-dismiss="modal">Got it!</button>
				</div>
			</div>
			</div>
		</div>
	</div>

	<script>
		jQuery('#twoFAtestAlertModal').css('display', 'block');
		jQuery('#test-methods').click(function(){
			jQuery('#twoFAtestAlertModal').css('display', 'none');
		});
		jQuery('#test-methods-button').click(function(){
			jQuery('#twoFAtestAlertModal').css('display', 'none');
		});
	</script>
<?php }
?>
