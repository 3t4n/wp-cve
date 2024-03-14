<?php
/**
 * This file contains frontend for password less login.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$current_user_info = wp_get_current_user();

wp_enqueue_script( 'bootstrap_script', plugins_url( 'miniorange-login-security' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'bootstrap.min.js', '' ), array(), MO2F_VERSION, true );

global $momlsdb_queries;
$user_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $current_user_info->ID );
?>
<div class="mo2f_table_layout">

	<form name="f"  id="login_settings_form" method="post" action="">


		<input type="hidden" name="option" value="mo_auth_pwdlogin_settings_save" />
		<input type="hidden" name="mo_auth_pwdlogin_settings_save_nonce"
		value="<?php echo esc_attr( wp_create_nonce( 'mo-auth-pwdlogin-settings-save-nonce' ) ); ?>"/>                
		<div id="password_less">
			<h2>GO PASSWORDLESS</h2><hr><br>
			<h3><?php esc_html_e( 'Select Login Screen Options', 'miniorange-login-security' ); ?>
			<br><br>
			<span>
				<input type="submit" name="submit" value="<?php esc_attr_e( 'Save Settings', 'miniorange-login-security' ); ?>" style="float:right;" class="button button-primary button-large" > 
				<br>

				<input type=checkbox name="mo2f_login_policy" value="0" <?php checked( get_site_option( 'mo2f_login_policy' ) === 0 ); ?>>


				<?php esc_html_e( 'Login with 2nd Factor only ', 'miniorange-login-security' ); ?>
				<span style="color:red">(<?php esc_html_e( 'No password required.', 'miniorange-login-security' ); ?>)</span> &nbsp;


				<a class=" btn-link" data-toggle="collapse" id="showpreview1"  href="#preview1" onclick="mo2f_onClick(this.id)" aria-expanded="false"><?php esc_html_e( 'See preview', 'miniorange-login-security' ); ?></a>
				<br>
				<div class="mo2f_collapse" id="preview1" style="height:300px; display: none;">
					<div class="mo2f_align_center"><br>
						<img style="height:300px;" src="<?php echo esc_url( plugins_url( 'includes/images/WP_default_login_PL.png"', dirname( dirname( __FILE__ ) ) ) ); ?>">
					</div>
				</div> 
				<br>
				<br>
				<div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%; background-color: #bbccdd; border-radius: 2px; padding:2%;"><b><?php esc_html_e( 'Note:', 'miniorange-login-security' ); ?></b> <?php esc_html_e( 'Checking this option will add login with your phone button below default login form.', 'miniorange-login-security' ); ?></div>

				<br> 
				<input style="margin-left:6%;" type="checkbox" id="mo2f_loginwith_phone" name="mo2f_loginwith_phone" value="1" 
				<?php
				checked( get_site_option( 'mo2f_show_loginwith_phone' ) === 1 );
				?>
				/> 
				<?php esc_html_e( ' I want to hide default login form.', 'miniorange-login-security' ); ?> 
				&nbsp;
				<a class=" btn-link" data-toggle="collapse" id="showpreview2"  href="#preview2" onclick="mo2f_onClick(this.id)" aria-expanded="false"><?php esc_html_e( 'See preview', 'miniorange-login-security' ); ?></a>
				<br>
				<div class="mo2f_collapse" id="preview2" style="height:300px; display: none;">
					<div class="mo2f_align_center"><br>
						<img style="height:300px;" src="<?php echo esc_url( plugins_url( 'includes/images/WP_hide_default_PL.png"', dirname( dirname( __FILE__ ) ) ) ); ?>">
					</div>
				</div> 
				<br>
				<br><div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic; background-color: #bbccdd; border-radius: 2px; padding:2%;"><b><?php esc_html_e( 'Note:', 'miniorange-login-security' ); ?></b> <?php esc_html_e( 'Checking this option will hide default login form and just show login with your phone. ', 'miniorange-login-security' ); ?></div>


			</div>
		</form>
	</div>

	<script>
		function mo2f_onClick($mo2f_id) {
			if($mo2f_id==='showpreview1')
				var mo2f_element = jQuery('#preview1')[0];
			else
				var mo2f_element = jQuery('#preview2')[0];

				if (mo2f_element.style.display === "none") {
					mo2f_element.style.display = "block";

				} else {
					mo2f_element.style.display = "none";
				}

			}

	</script>





