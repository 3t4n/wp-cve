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

			if ( isset( $_POST['moove_gdpr_modal_powered_by_disable'] ) ) :
				$value = intval( $_POST['moove_gdpr_modal_powered_by_disable'] );
			else :
				$value = 0;
			endif;

			if ( isset( $_POST[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] ) ) :
				if ( 0 === strlen( trim( sanitize_text_field( wp_unslash( $_POST[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] ) ) ) ) ) :
					$value = 1;
				else :
					$value = 0;
				endif;
			endif;

			$restricted_keys = array(
				'moove_gdpr_floating_button_enable',
				'moove_gdpr_modal_powered_by_disable',
				'moove_gdpr_save_settings_button_enable',
				'moove_gdpr_close_button_enable',
				'moove_gdpr_colour_scheme',				
				'gdpr_close_button_bhv_redirect',
			);

			$gdpr_options['moove_gdpr_modal_powered_by_disable'] = $value;

			foreach ( $_POST as $form_key => $form_value ) :
				if ( 'moove_gdpr_info_bar_content' === $form_key ) :
					$value                                  = wpautop( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key . $wpml_lang ] = $value;
				elseif ( 'moove_gdpr_modal_strictly_secondary_notice' . $wpml_lang === $form_key ) :
					$value                     = wpautop( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key ] = $value;					
				elseif ( ! in_array( $form_key, $restricted_keys ) ) :
					$value                     = sanitize_text_field( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key ] = $value;
				elseif ( 'gdpr_gs_buttons_order' === $form_key ) :
					$value 										 	= json_decode( wp_unslash( $form_value ), true );
					$allowed_values 					 	= array( 'enable', 'reject', 'save', 'close' );
					$buttons_order 							= array();
					if ( is_array( $value ) ) :
						foreach ( $value as $button_type ) :
							if ( in_array( $button_type, $allowed_values ) ) :
								$buttons_order[] = $button_type;
							endif;
						endforeach;
					endif;
					$buttons_order = $buttons_order ? $buttons_order : $allowed_values;
					$gdpr_options[ $form_key ] = json_encode( $buttons_order );
				endif;
			endforeach;

			// Cookie Banner Save Settings Button.
			$moove_save_settings_enable = '0';
			if ( isset( $_POST['moove_gdpr_save_settings_button_enable'] ) ) :
				$moove_save_settings_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_save_settings_button_enable'] = $moove_save_settings_enable;

			// Cookie Banner Enable All Button.
			$moove_enable_all_button_enable = '0';
			if ( isset( $_POST['moove_gdpr_enable_all_button_enable'] ) ) :
				$moove_enable_all_button_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_enable_all_button_enable'] = $moove_enable_all_button_enable;

			// Cookie Banner Reject All Button.
			$moove_reject_all_button_enable = '0';
			if ( isset( $_POST['moove_gdpr_reject_all_button_enable'] ) ) :
				$moove_reject_all_button_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_reject_all_button_enable'] = $moove_reject_all_button_enable;

			// Cookie Banner Close Button.
			$moove_gs_close_enable = '0';
			if ( isset( $_POST['moove_gdpr_cb_close_button_enable'] ) ) :
				$moove_gs_close_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_cb_close_button_enable'] = $moove_gs_close_enable;

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

$buttons_order 				= isset( $gdpr_options['gdpr_gs_buttons_order'] ) ? json_decode( $gdpr_options['gdpr_gs_buttons_order'], true ) : array( 'enable', 'reject', 'save', 'close' );
if ( ! isset( $gdpr_options['moove_gdpr_cb_close_button_enable'] ) ) :
	if ( ! in_array( 'close', $buttons_order ) ) :
		$buttons_order[] = 'close';
	endif;
	$gdpr_options['moove_gdpr_cb_close_button_enable'] = '1';
endif;

?>
<form action="<?php esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=general-settings' ) ); ?>" method="post" id="moove_gdpr_tab_general_settings">
	<h2><?php esc_html_e( 'Cookie Settings Screen', 'gdpr-cookie-compliance' ); ?></h2>
	<hr />
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="moove_gdpr_plugin_layout"><?php esc_html_e( 'Choose your layout', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<input name="moove_gdpr_plugin_layout" type="radio" value="v1" id="moove_gdpr_plugin_layout_v1" <?php echo isset( $gdpr_options['moove_gdpr_plugin_layout'] ) ? ( 'v1' === $gdpr_options['moove_gdpr_plugin_layout'] ? 'checked' : '' ) : 'checked'; ?> class="on-off">
					<label for="moove_gdpr_plugin_layout_v1">
						<?php esc_html_e( 'Tabs layout', 'gdpr-cookie-compliance' ); ?>
					</label> 
					<span class="separator"></span>

					<input name="moove_gdpr_plugin_layout" type="radio" value="v2" id="moove_gdpr_plugin_layout_v2" <?php echo isset( $gdpr_options['moove_gdpr_plugin_layout'] ) ? ( 'v2' === $gdpr_options['moove_gdpr_plugin_layout'] ? 'checked' : '' ) : ''; ?> class="on-off">
					<label for="moove_gdpr_plugin_layout_v2">
						<?php esc_html_e( 'One page layout', 'gdpr-cookie-compliance' ); ?>
					</label>
					<?php do_action( 'gdpr_cc_moove_gdpr_plugin_layout_settings' ); ?>

				</td>
			</tr>

			<tr class="gdpr-sortable-buttons-wrap">
				<td colspan="2">
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Button Setup', 'gdpr-cookie-compliance' ) ?></h4>
					<p class="description"><i><?php esc_html_e( 'You can change the order by drag & drop', 'gdpr-cookie-compliance' ) ?></i></p><br>
					<input type="hidden" name="gdpr_gs_buttons_order" class="gdpr-buttons-order-inpval" value='<?php echo json_encode( $buttons_order, true ); ?>'>
					<div class="gdpr-sortable-buttons">
						<?php 
							foreach ( $buttons_order as $button_type ) : 
								if ( 'save' === $button_type ) :
									?>
										<div class="gdpr-sortable-button" data-type="save">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_save_settings_button_enable"><?php esc_html_e( 'Save Settings button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_save_settings_button_enable" id="moove_gdpr_save_settings_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_save_settings_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_save_settings_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_save_settings_button_enable'] ) ? 'checked' : '' ) ) : 'checked'; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
														</td>
													</tr>

													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_save_settings_button_enable">
														<th scope="row">
															<label for="moove_gdpr_modal_save_button_label"><?php esc_html_e( 'Save Settings - Button Label', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<input name="moove_gdpr_modal_save_button_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_modal_save_button_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_modal_save_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_modal_save_button_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_modal_save_button_label' . $wpml_lang ] ) : esc_html__( 'Save Changes', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!-- .gdpr-sortable-button -->
									<?php
								elseif ( 'enable' === $button_type ) :
									?>
										<div class="gdpr-sortable-button" data-type="enable">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_enable_all_button_enable"><?php esc_html_e( 'Enable All button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_enable_all_button_enable" id="moove_gdpr_enable_all_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_enable_all_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_enable_all_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_enable_all_button_enable'] ) ? 'checked' : '' ) ) : 'checked'; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
														</td>
													</tr>

													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_enable_all_button_enable">
														<th scope="row">
															<label for="moove_gdpr_modal_allow_button_label"><?php esc_html_e( 'Enable All - Button Label', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<input name="moove_gdpr_modal_allow_button_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_modal_allow_button_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_modal_allow_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_modal_allow_button_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_modal_allow_button_label' . $wpml_lang ] ) : esc_html__( 'Enable All', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!-- .gdpr-sortable-button -->
									<?php
								elseif ( 'reject' === $button_type ) :
									?>
										<div class="gdpr-sortable-button" data-type="reject">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_reject_all_button_enable"><?php esc_html_e( 'Reject All button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_reject_all_button_enable" id="moove_gdpr_reject_all_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_reject_all_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_reject_all_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_reject_all_button_enable'] ) ? '' : '' ) ) : ''; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
														</td>
													</tr>

													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_reject_all_button_enable">
														<th scope="row">
															<label for="moove_gdpr_modal_allow_button_label"><?php esc_html_e( 'Reject All - Button Label', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<input name="moove_gdpr_modal_reject_button_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_modal_reject_button_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_modal_reject_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_modal_reject_button_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_modal_reject_button_label' . $wpml_lang ] ) : esc_html__( 'Reject All', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!-- .gdpr-sortable-button -->
									<?php
								elseif ( 'close' === $button_type ) :
									?>
										<div class="gdpr-sortable-button ui-state-disabled" data-type="close" style="padding-bottom: 0;">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_cb_close_button_enable"><?php esc_html_e( 'Close button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_cb_close_button_enable" id="moove_gdpr_cb_close_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_cb_close_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_cb_close_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_cb_close_button_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
															
														</td>
													</tr>													
												</tbody>
											</table>
										</div>
									<?php
								endif;
							endforeach; 
						?>
				</td>
			</tr>
			<!-- .gdpr-sortable-buttons -->

			<tr>
				<th scope="row">
					<label for="moove_gdpr_modal_enabled_checkbox_label">
						<?php esc_html_e( 'Checkbox Labels', 'gdpr-cookie-compliance' ); ?>
					</label>
				</th>
				<td>
					<table >
						<tr>
							<td style="padding: 0;">
								<input name="moove_gdpr_modal_enabled_checkbox_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_modal_enabled_checkbox_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_modal_enabled_checkbox_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_modal_enabled_checkbox_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_modal_enabled_checkbox_label' . $wpml_lang ] ) : esc_html__( 'Enabled', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
							<td style="padding: 0;">
								<input name="moove_gdpr_modal_disabled_checkbox_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_modal_disabled_checkbox_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_modal_disabled_checkbox_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_modal_disabled_checkbox_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_modal_disabled_checkbox_label' . $wpml_lang ] ) : esc_html__( 'Disabled', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
							</td>
						</tr>
					</table>
					<br />
					
				</td>

			</tr>

			<tr>
				<th scope="row">
					<label><?php esc_html_e( 'Powered by GDPR', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<span class="powered-by-label">
						<label for="moove_gdpr_modal_powered_by_label"><?php esc_html_e( 'Default label', 'gdpr-cookie-compliance' ); ?>:</label>
						<input name="moove_gdpr_modal_powered_by_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_modal_powered_by_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] ) ? esc_attr( $gdpr_options[ 'moove_gdpr_modal_powered_by_label' . $wpml_lang ] ) : 'Powered by'; ?>" class="regular-text">
					</span>

					<input name="moove_gdpr_modal_powered_by_disable" type="hidden" <?php echo isset( $gdpr_options['moove_gdpr_modal_powered_by_disable'] ) ? ( intval( $gdpr_options['moove_gdpr_modal_powered_by_disable'] ) === 1 ? 'checked' : '' ) : ''; ?> id="moove_gdpr_modal_powered_by_disable" value="<?php echo isset( $gdpr_options['moove_gdpr_modal_powered_by_disable'] ) ? ( intval( $gdpr_options['moove_gdpr_modal_powered_by_disable'] ) === 1 ? '1' : '0' ) : '0'; ?>">
				</td>
			</tr>
			<?php do_action( 'gdpr_cc_general_modal_settings' ); ?>
		</tbody>
	</table>

	<br />
	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance' ); ?></button>

	<?php do_action( 'gdpr_cc_general_buttons_settings' ); ?>
</form>
