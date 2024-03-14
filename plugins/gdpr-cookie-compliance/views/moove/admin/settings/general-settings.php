<?php
/**
 * General Settings Doc Comment
 *
 * @category  Views
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$gdpr_default_content = new Moove_GDPR_Content();
$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
$gdpr_options         = get_option( $option_name );
$wpml_lang            = $gdpr_default_content->moove_gdpr_get_wpml_lang();
$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();
if ( isset( $_POST ) && isset( $_POST['moove_gdpr_nonce'] ) ) :
	$nonce = sanitize_key( $_POST['moove_gdpr_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_nonce_field' ) ) :
		die( 'Security check' );
	else :
		if ( is_array( $_POST ) ) :

			if ( isset( $_POST['gdpr_force_reload'] ) ) :
				$gfr = intval( $_POST['gdpr_force_reload'] );
			else :
				$gfr = 0;
			endif;
			$gdpr_options['gdpr_force_reload'] = $gfr;

			if ( isset( $_POST['script_insertion_method'] ) ) :
				$sim = intval( $_POST['script_insertion_method'] );
			else :
				$sim = 0;
			endif;
			$gdpr_options['script_insertion_method'] = $sim;

			if ( isset( $_POST['gdpr_cookie_removal'] ) ) :
				$gcr = intval( $_POST['gdpr_cookie_removal'] );
			else :
				$gcr = 0;
			endif;
			$gdpr_options['gdpr_cookie_removal'] = $gcr;

			$restricted_keys = array(
				'moove_gdpr_modal_powered_by_disable',
				'gdpr_force_reload',
				'script_insertion_method',
				'gdpr_cookie_removal'
			);
			
			foreach ( $_POST as $form_key => $form_value ) :
				if ( ! in_array( $form_key, $restricted_keys ) ) :
					$value                     = sanitize_text_field( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key ] = $value;
				endif;
			endforeach;

			update_option( $option_name, $gdpr_options );
			$gdpr_options = get_option( $option_name );
		endif;
		do_action( 'gdpr_cookie_filter_settings' );
		?>
		<script>
			jQuery('#moove-gdpr-setting-error-settings_updated').show();
		</script>
		<?php
	endif;
endif;

/**
 * Reset Settings
 */
if ( isset( $_POST ) && isset( $_POST['moove_gdpr_reset_nonce'] ) ) :
	$nonce = sanitize_key( $_POST['moove_gdpr_reset_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'moove_gdpr_reset_nonce_field' ) ) :
		die( 'Security check' );
	else :
		if ( isset( $_POST['gdpr_reset_settings'] ) && intval( $_POST['gdpr_reset_settings'] )  === 1 ) :
			$gdpr_content 	= new Moove_GDPR_Content();
			$option_name 		= $gdpr_content->moove_gdpr_get_option_name();
			$option_key     = $gdpr_content->moove_gdpr_get_key_name();
			update_option( $option_name, array() );
			gdpr_delete_option();
			if ( function_exists( 'update_site_option' ) ) :
				delete_site_option( $option_key );
			else :
				delete_option( $option_key );
			endif;
			$gdpr_options         = get_option( $option_name );
			$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();			
		endif;
	endif;
endif;

$gdpr_force_reload 	= isset( $gdpr_options['gdpr_force_reload'] ) && intval( $gdpr_options['gdpr_force_reload'] ) >= 0 ? intval( $gdpr_options['gdpr_force_reload'] ) : apply_filters( 'gdpr_force_reload', false );
$script_insertion_method 	= isset( $gdpr_options['script_insertion_method'] ) && intval( $gdpr_options['script_insertion_method'] ) >= 0 ? intval( $gdpr_options['script_insertion_method'] ) : apply_filters( 'gdpr_cc_prevent_ajax_script_inject', true );

$static_cookie_removal 		= isset( $gdpr_options['gdpr_cookie_removal'] ) && intval( $gdpr_options['gdpr_cookie_removal'] ) >= 0 ? intval( $gdpr_options['gdpr_cookie_removal'] ) : ! apply_filters( 'gdpr_ajax_cookie_removal', false );


?>
<form action="<?php esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=general-settings' ) ); ?>" method="post" id="moove_gdpr_tab_general_settings">
	<h2><?php esc_html_e( 'General Settings', 'gdpr-cookie-compliance' ); ?></h2>
	<hr />
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="gdpr_force_reload"><?php esc_html_e( 'Force Reload', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="gdpr_force_reload" id="gdpr_force_reload" <?php echo $gdpr_force_reload ? 'checked' : ''; ?> value="1" >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>

					<p class="description">
						<?php esc_html_e( 'Choose if you’d like the page to reload when user accepts cookies. If you choose not to, your analytical software will not count the current page visit as the cookies will be loaded during the next page load only.', 'gdpr-cookie-compliance' ); ?>
					</p>
					<!-- .description -->
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="script_insertion_method"><?php esc_html_e( 'Script Insertion Method', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="script_insertion_method" id="script_insertion_method" <?php echo $script_insertion_method ? 'checked' : ''; ?> value="1" >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Static', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Dynamic', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>

					<p class="description">
						<?php esc_html_e( 'Recommended default method is Static. Switch to dynamic only if you experience issues with static method (dynamic way uses more server resources)', 'gdpr-cookie-compliance' ); ?>
					</p>
					<!-- .description -->
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="gdpr_cookie_removal"><?php esc_html_e( 'Cookie Removal', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="gdpr_cookie_removal" id="gdpr_cookie_removal" <?php echo $static_cookie_removal ? 'checked' : ''; ?> value="1" >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Static', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Dynamic', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>

					<p class="description">
						<?php esc_html_e( 'Recommended default method is Static. Switch to dynamic for advanced cookie removal method if needed (uses more server performance)', 'gdpr-cookie-compliance' ); ?>
					</p>
					<!-- .description -->
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="moove_gdpr_consent_expiration"><?php esc_html_e( 'Consent expiry', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
				
					<span style="margin-right: 5px;"><?php esc_html_e( 'Consent expires after', 'gdpr-cookie-compliance' ); ?></span>
					<input name="moove_gdpr_consent_expiration" min="0" step="1" type="number" id="moove_gdpr_consent_expiration" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_consent_expiration' ] ) && intval( $gdpr_options[ 'moove_gdpr_consent_expiration' ] ) >= 0 ? esc_attr( $gdpr_options[ 'moove_gdpr_consent_expiration' ] ) : '365'; ?>" style="width: 80px;">
					<span style="margin-left: 5px;"><?php esc_html_e( 'days', 'gdpr-cookie-compliance' ); ?>.</span>
				
					<p class="description">
						<?php esc_html_e( '(Enter 0 if you want the consent to expire at the end of the current browsing session.)', 'gdpr-cookie-compliance' ); ?>
					</p>
				</td>
			</tr>

			<?php do_action( 'gdpr_cc_general_modal_settings' ); ?>
		</tbody>
	</table>

	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance' ); ?></button>

	<button type="button" class="button button-primary button-reset-settings"><?php esc_html_e( 'Reset Settings', 'gdpr-cookie-compliance' ); ?></button>

	<?php do_action( 'gdpr_cc_general_buttons_settings' ); ?>
</form>

<div class="gdpr-admin-popup gdpr-admin-popup-reset-settings" style="display: none;">
	<span class="gdpr-popup-overlay"></span>
	<div class="gdpr-popup-content">
		<div class="gdpr-popup-content-header">
			<a href="#" class="gdpr-popup-close"><span class="dashicons dashicons-no-alt"></span></a>
		</div>
		<!--  .gdpr-popup-content-header -->
		<div class="gdpr-popup-content-content">
			<form action="<?php esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=general-settings' ) ); ?>" method="post">
				<?php wp_nonce_field( 'moove_gdpr_reset_nonce_field', 'moove_gdpr_reset_nonce' ); ?>
				<h4><strong><?php esc_html_e( 'Please confirm that you would like to reset the plugin settings to the default state', 'gdpr-cookie-compliance' ); ?> </strong></h4><p><strong><?php esc_html_e( 'This action will remove all of your custom modifications and settings', 'gdpr-cookie-compliance' ); ?></strong></p>
				<input type="hidden" value="1" name="gdpr_reset_settings" />
				<button class="button button-primary button-reset-settings-confirm" type="submit">
					<?php esc_html_e( 'Reset plugin to default state', 'gdpr-cookie-compliance' ); ?>
				</button>
			</form>
		</div>
		<!--  .gdpr-popup-content-content -->    
	</div>
	<!--  .gdpr-popup-content -->
</div>
<!--  .gdpr-admin-popup -->
