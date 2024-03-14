<?php
/**
 * Banner Settings File Doc Comment
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
			$restricted_keys = array(
				'moove_gdpr_floating_button_enable',
				'moove_gdpr_infobar_visibility',
				'moove_gdpr_reject_button_enable',
				'moove_gdpr_accept_button_enable',
				'moove_gdpr_settings_button_enable',
				'moove_gdpr_close_button_enable',
				'moove_gdpr_colour_scheme',
				'gdpr_close_button_bhv_redirect',
				'gdpr_accesibility',
			);
			// Cookie Banner Visibility.
			$moove_gdpr_infobar_visibility = 'hidden';
			if ( isset( $_POST['moove_gdpr_infobar_visibility'] ) ) :
				$moove_gdpr_infobar_visibility = 'visible';
			endif;
			$gdpr_options['moove_gdpr_infobar_visibility'] = $moove_gdpr_infobar_visibility;

			// Cookie Banner Accept Button.
			$moove_gdpr_accept_enable = '0';
			if ( isset( $_POST['moove_gdpr_accept_button_enable'] ) ) :
				$moove_gdpr_accept_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_accept_button_enable'] = $moove_gdpr_accept_enable;

			// Cookie Banner Reject Button.
			$moove_gdpr_reject_enable = '0';
			if ( isset( $_POST['moove_gdpr_reject_button_enable'] ) ) :
				$moove_gdpr_reject_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_reject_button_enable'] = $moove_gdpr_reject_enable;

			// Cookie Banner Settings Button.
			$moove_gdpr_reject_enable = '0';
			if ( isset( $_POST['moove_gdpr_settings_button_enable'] ) ) :
				$moove_gdpr_reject_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_settings_button_enable'] = $moove_gdpr_reject_enable;

			// Cookie Banner Close Button.
			$moove_gdpr_close_enable = '0';
			if ( isset( $_POST['moove_gdpr_close_button_enable'] ) ) :
				$moove_gdpr_close_enable = '1';
			endif;
			$gdpr_options['moove_gdpr_close_button_enable'] = $moove_gdpr_close_enable;

			$gdpr_options['gdpr_close_button_bhv'] = 1;
			if ( '1' === $moove_gdpr_close_enable ) :
				if ( isset( $_POST['gdpr_close_button_bhv'] ) && intval( $_POST['gdpr_close_button_bhv'] ) ) :
					$gdpr_options['gdpr_close_button_bhv'] 				= intval( $_POST['gdpr_close_button_bhv'] );
					$gdpr_options['gdpr_close_button_bhv_redirect'] 	= isset( $_POST['gdpr_close_button_bhv_redirect'] ) ? sanitize_url( wp_unslash( $_POST['gdpr_close_button_bhv_redirect'] ) ) : '';
				endif;
			endif;

			// Cookie Banner Colour Scheme.
			$moove_gdpr_colour_scheme = '2';
			
			if ( isset( $_POST['moove_gdpr_colour_scheme'] ) ) :
				$moove_gdpr_colour_scheme = '1';
			endif;
			$gdpr_options['moove_gdpr_colour_scheme'] = $moove_gdpr_colour_scheme;

			// Cookie Banner Accesibility.
			$gdpr_accesibility = '0';
			
			if ( isset( $_POST['gdpr_accesibility'] ) ) :
				$gdpr_accesibility = '1';
			endif;
			$gdpr_options['gdpr_accesibility'] = $gdpr_accesibility;

			update_option( $option_name, $gdpr_options );

			foreach ( $_POST as $form_key => $form_value ) :
				if ( 'moove_gdpr_info_bar_content' === $form_key ) :
					$value                                  = wpautop( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key . $wpml_lang ] = $value;
				elseif ( 'moove_gdpr_modal_strictly_secondary_notice' . $wpml_lang === $form_key ) :
					$value                     = wpautop( wp_unslash( $form_value ) );
					$gdpr_options[ $form_key ] = $value;
				elseif ( 'gdpr_initialization_delay' === $form_key ) :
					$value                     = intval( $form_value );
					$gdpr_options[ $form_key ] = $value;
				elseif ( 'gdpr_bs_buttons_order' === $form_key ) :
					$value 										 	= json_decode( wp_unslash( $form_value ), true );
					$allowed_values 					 	= array( 'accept', 'reject', 'settings', 'close' );
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
				elseif ( ! in_array( $form_key, $restricted_keys ) ) :
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

$buttons_order 				= isset( $gdpr_options['gdpr_bs_buttons_order'] ) ? json_decode( $gdpr_options['gdpr_bs_buttons_order'], true ) : array('accept', 'reject', 'settings', 'close');

$initalization_delay 	= isset( $gdpr_options['gdpr_initialization_delay'] ) && intval( $gdpr_options['gdpr_initialization_delay'] ) >= 0 ? intval( $gdpr_options['gdpr_initialization_delay'] ) : apply_filters( 'gdpr_init_script_delay', 2000 );
?>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=banner-settings' ) ); ?>" method="post" id="moove_gdpr_tab_banner_settings">
	<?php wp_nonce_field( 'moove_gdpr_nonce_field', 'moove_gdpr_nonce' ); ?>
	<h2><?php esc_html_e( 'Cookie Banner Settings', 'gdpr-cookie-compliance' ); ?></h2>
	<hr />

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="moove_gdpr_infobar_visibility"><?php esc_html_e( 'Turn', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<!-- GDPR Rounded switch -->
					<label class="gdpr-checkbox-toggle">
						<input type="checkbox" name="moove_gdpr_infobar_visibility" <?php echo isset( $gdpr_options['moove_gdpr_infobar_visibility'] ) ? ( 'visible' === $gdpr_options['moove_gdpr_infobar_visibility'] ? 'checked' : '' ) : 'checked'; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'On', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Off', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<?php do_action( 'gdpr_cc_moove_gdpr_infobar_visibility_settings' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row" colspan="2" style="padding-bottom: 0;">
					<label for="moove_gdpr_info_bar_content"><?php esc_html_e( 'Cookie Banner Content', 'gdpr-cookie-compliance' ); ?></label>
				</th>
			</tr>
			<tr class="moove_gdpr_table_form_holder">
				<th colspan="2" scope="row">
					<?php
					$content = isset( $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ? maybe_unserialize( $gdpr_options[ 'moove_gdpr_info_bar_content' . $wpml_lang ] ) : false;
					if ( ! $content ) :
						$_content = '<p>' . esc_html__( 'We are using cookies to give you the best experience on our website.', 'gdpr-cookie-compliance' ) .'</p>';
						$_content .= '<p>' . sprintf( esc_html__( 'You can find out more about which cookies we are using or switch them off in [%s]settings[/%s].', 'gdpr-cookie-compliance' ), 'setting', 'setting' ) . '</p>';
						$content  = $_content;
					endif;
					?>
					<?php
					$settings = array(
						'media_buttons' => false,
						'editor_height' => 150,
						'teeny'         => false,
					);
					wp_editor( $content, 'moove_gdpr_info_bar_content', $settings );
					?>
					<p class="description">
					<?php
						$content = __( 'You can use the following shortcut to link the Cookie Settings Screen:', 'gdpr-cookie-compliance' );
						$content .= '<br><span><strong>[setting]</strong>';
						$content .= __( 'settings', 'gdpr-cookie-compliance' );
						$content .= '<strong>[/setting]</strong></span>';
						apply_filters( 'gdpr_cc_keephtml', $content, true );
					?>
					</p>
				</th>
			</tr>
			
			<tr>
				<td colspan="2" style="padding: 0;">
					<hr />
				</td>
			</tr>

			<tr class="gdpr-sortable-buttons-wrap">
				<td colspan="2">
					<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Button Setup', 'gdpr-cookie-compliance' ) ?></h4>
					<p class="description"><i><?php esc_html_e( 'You can change the order by drag & drop', 'gdpr-cookie-compliance' ) ?></i></p><br>
					<input type="hidden" name="gdpr_bs_buttons_order" class="gdpr-buttons-order-inpval" value='<?php echo json_encode( $buttons_order, true ); ?>'>
					<div class="gdpr-sortable-buttons">
						<?php 
							foreach ( $buttons_order as $button_type ) : 
								if ( 'accept' === $button_type ) :
									?>
										<div class="gdpr-sortable-button" data-type="accept">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_accept_button_enable"><?php esc_html_e( 'Accept button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_accept_button_enable" id="moove_gdpr_accept_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_accept_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_accept_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_accept_button_enable'] ) ? 'checked' : '' ) ) : 'checked'; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>											
														</td>
													</tr>
													<tr>
														<td colspan="2"><p class="description" id="moove_gdpr_accept_button_enable-description" ><?php esc_html_e( "Accept button allows users to accept all cookies.", 'gdpr-cookie-compliance' ); ?></p>
															<!--  .description --></td>
													</tr>
													
													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_accept_button_enable">
														<th scope="row">
															<label for="moove_gdpr_infobar_accept_button_label"><?php esc_html_e( 'Accept - Button Label', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<input name="moove_gdpr_infobar_accept_button_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_infobar_accept_button_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_infobar_accept_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_infobar_accept_button_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_infobar_accept_button_label' . $wpml_lang ] ) : esc_attr__( 'Accept', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
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
															<label for="moove_gdpr_reject_button_enable"><?php esc_html_e( 'Reject button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_reject_button_enable" id="moove_gdpr_reject_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_reject_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_reject_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_reject_button_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
															
															<!--  .description -->
														</td>
													</tr>

													<tr>
														<td colspan="2"><p class="description" id="moove_gdpr_reject_button_enable-description" ><?php esc_html_e( "Reject button allows users to reject all cookies.", 'gdpr-cookie-compliance' ); ?></p>
															<!--  .description --></td>
													</tr>

													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_reject_button_enable">
														<th scope="row">
															<label for="moove_gdpr_infobar_reject_button_label"><?php esc_html_e( 'Reject - Button Label', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<input name="moove_gdpr_infobar_reject_button_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_infobar_reject_button_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_infobar_reject_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_infobar_reject_button_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_infobar_reject_button_label' . $wpml_lang ] ) : esc_attr__( 'Reject', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!-- .gdpr-sortable-button -->
									<?php
								elseif ( 'settings' === $button_type ) :
									?>
										<div class="gdpr-sortable-button" data-type="settings">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_settings_button_enable"><?php esc_html_e( 'Settings button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_settings_button_enable" id="moove_gdpr_settings_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_settings_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_settings_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_settings_button_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
															
															<!--  .description -->
														</td>
													</tr>
													<tr>
														<td colspan="2"><p class="description" id="moove_gdpr_settings_button_enable-description" ><?php esc_html_e( "Settings button opens up the Cookie Settings Screen.", 'gdpr-cookie-compliance' ); ?></p>
															<!--  .description --></td>
													</tr>

													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_settings_button_enable">
														<th scope="row">
															<label for="moove_gdpr_infobar_settings_button_label"><?php esc_html_e( 'Settings - Button Label', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<input name="moove_gdpr_infobar_settings_button_label<?php echo esc_attr( $wpml_lang ); ?>" type="text" id="moove_gdpr_infobar_settings_button_label" value="<?php echo isset( $gdpr_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ) && $gdpr_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ? esc_attr( $gdpr_options[ 'moove_gdpr_infobar_settings_button_label' . $wpml_lang ] ) : esc_attr__( 'Settings', 'gdpr-cookie-compliance' ); ?>" class="regular-text">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!-- .gdpr-sortable-button -->
									<?php
								elseif ( 'close' === $button_type ) :
									?>
										<div class="gdpr-sortable-button" data-type="close">
											<table>
												<tbody>
													<tr>
														<th scope="row">
															<label for="moove_gdpr_close_button_enable"><?php esc_html_e( 'Close button', 'gdpr-cookie-compliance' ); ?></label>
														</th>
														<td>
															<!-- GDPR Rounded switch -->
															<label class="gdpr-checkbox-toggle">
																<input type="checkbox" name="moove_gdpr_close_button_enable" id="moove_gdpr_close_button_enable" <?php echo isset( $gdpr_options['moove_gdpr_close_button_enable'] ) ? ( intval( $gdpr_options['moove_gdpr_close_button_enable'] ) === 1 ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_close_button_enable'] ) ? 'checked' : '' ) ) : ''; ?> >
																<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Enabled', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Disabled', 'gdpr-cookie-compliance' ); ?>"></span>
															</label>
															
														</td>
													</tr>
													<tr class="gdpr-conditional-field" data-dependency="#moove_gdpr_close_button_enable">
														<td colspan="2">
															<hr>
															<h4><?php esc_html_e( 'Choose how the Close button should behave', 'gdpr-cookie-compliance' ); ?>:</h4>
															<table>
																<tr>
																	<td>
																		<fieldset class="gdpr-close-options">
																			<?php 
																			$gdpr_close_button_bhv = isset( $gdpr_options['gdpr_close_button_bhv'] ) && intval( $gdpr_options['gdpr_close_button_bhv'] ) ? intval( $gdpr_options['gdpr_close_button_bhv'] ) : 1;

																			$gdpr_close_button_bhv_redirect = isset( $gdpr_options['gdpr_close_button_bhv_redirect'] ) && sanitize_url( wp_unslash( $gdpr_options['gdpr_close_button_bhv_redirect'] ) ) ? sanitize_url( wp_unslash( $gdpr_options['gdpr_close_button_bhv_redirect'] ) ) : '';
																			?>
					
																			<label for="gdpr_close_button_bhv_1">
																				<input name="gdpr_close_button_bhv" type="radio" <?php echo $gdpr_close_button_bhv === 1 ? 'checked' : ''; ?> id="gdpr_close_button_bhv_1" value="1">
																				<?php esc_html_e( 'as a Close button', 'gdpr-cookie-compliance' ); ?>
																				<span class="gdpr_cb_bhv_desc"><?php esc_html_e( '(The Cookie Banner becomes hidden for the duration of the current browsing session, without accepting or rejecting cookies. The Cookie Banner will re-appear when the user next visits your site.)', 'gdpr-cookie-compliance' ); ?></span>
																			</label>
																		
																			<br /><br />

																			<label for="gdpr_close_button_bhv_2">
																				<input name="gdpr_close_button_bhv" type="radio" <?php echo $gdpr_close_button_bhv === 2 ? 'checked' : ''; ?> id="gdpr_close_button_bhv_2" value="2">
																				<?php esc_html_e( 'as a Reject button', 'gdpr-cookie-compliance' ); ?>
																				<span class="gdpr_cb_bhv_desc"><?php esc_html_e( '(The cookies are rejected and the cookie banner does not re-appear until the cookie consent expires.)', 'gdpr-cookie-compliance' ); ?></span>
																			</label>

																			<br /><br />

																			<label for="gdpr_close_button_bhv_3">
																				<input name="gdpr_close_button_bhv" type="radio" <?php echo $gdpr_close_button_bhv === 3 ? 'checked' : ''; ?> id="gdpr_close_button_bhv_3" value="3">
																				<?php esc_html_e( 'as an Accept button', 'gdpr-cookie-compliance' ); ?>
																				<span class="gdpr_cb_bhv_desc"><?php esc_html_e( '(The cookies are accepted and the cookie banner does not re-appear until the cookie consent expires.)', 'gdpr-cookie-compliance' ); ?></span>
																			</label>
																			
																			<br /><br />

																			<div class="gdpr-conditional-field-group">
																				<label for="gdpr_close_button_bhv_4">
																					<input name="gdpr_close_button_bhv" type="radio" <?php echo $gdpr_close_button_bhv === 4 ? 'checked' : ''; ?> id="gdpr_close_button_bhv_4" value="4">
																					<?php esc_html_e( 'as a Redirect', 'gdpr-cookie-compliance' ); ?>
																					<span class="gdpr_cb_bhv_desc"><?php esc_html_e( '(The cookies are rejected and the user will be redirected to the specified URL.)', 'gdpr-cookie-compliance' ); ?></span>
																				</label>
																				<br>
																				<input type="text" name="gdpr_close_button_bhv_redirect" id="gdpr_close_button_bhv_redirect" style="display: none;" class="regular-text" placeholder="<?php esc_html_e('Redirect location', 'gdpr-cookie-compliance') ?>" value="<?php echo esc_url( $gdpr_close_button_bhv_redirect ); ?>">
																			</div>
																			<!-- .gdpr-conditional-field-group -->

																			<br />

																		</fieldset>
																	</td>
																</tr>
															</table>
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
					<label for="moove_gdpr_infobar_position"><?php esc_html_e( 'Cookie Banner position', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<input name="moove_gdpr_infobar_position" type="radio" value="top" id="moove_gdpr_infobar_position_top" <?php echo isset( $gdpr_options['moove_gdpr_infobar_position'] ) ? ( 'top' === $gdpr_options['moove_gdpr_infobar_position'] ? 'checked' : '' ) : ''; ?> class="on-top"> <label for="moove_gdpr_infobar_position_top"><?php esc_html_e( 'Top', 'gdpr-cookie-compliance' ); ?></label> 
					<span class="separator"></span>

					<input name="moove_gdpr_infobar_position" type="radio" value="bottom" id="moove_gdpr_infobar_position_bottom" <?php echo isset( $gdpr_options['moove_gdpr_infobar_position'] ) ? ( 'bottom' === $gdpr_options['moove_gdpr_infobar_position'] ? 'checked' : '' ) : 'checked'; ?> class="on-off"> <label for="moove_gdpr_infobar_position_bottom"><?php esc_html_e( 'Bottom', 'gdpr-cookie-compliance' ); ?></label>

					<span class="separator"></span>

					<input name="moove_gdpr_infobar_position" type="radio" value="bottom_left" id="moove_gdpr_infobar_position_bottom_left" <?php echo isset( $gdpr_options['moove_gdpr_infobar_position'] ) ? ( 'bottom_left' === $gdpr_options['moove_gdpr_infobar_position'] ? 'checked' : '' ) : ''; ?> class="on-off"> <label for="moove_gdpr_infobar_position_bottom_left"><?php esc_html_e( 'Bottom Left', 'gdpr-cookie-compliance' ); ?></label>

					<span class="separator"></span>

					<input name="moove_gdpr_infobar_position" type="radio" value="bottom_right" id="moove_gdpr_infobar_position_bottom_right" <?php echo isset( $gdpr_options['moove_gdpr_infobar_position'] ) ? ( 'bottom_right' === $gdpr_options['moove_gdpr_infobar_position'] ? 'checked' : '' ) : ''; ?> class="on-off"> <label for="moove_gdpr_infobar_position_bottom_right"><?php esc_html_e( 'Bottom Right', 'gdpr-cookie-compliance' ); ?></label>

					<?php do_action( 'gdpr_cc_moove_gdpr_infobar_position_settings' ); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding: 0;">
					<hr />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="moove_gdpr_colour_scheme"><?php esc_html_e( 'Colour scheme', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<label class="gdpr-checkbox-toggle gdpr-color-scheme-toggle">
						<input type="checkbox" name="moove_gdpr_colour_scheme" <?php echo isset( $gdpr_options['moove_gdpr_colour_scheme'] ) ? ( 1 === intval( $gdpr_options['moove_gdpr_colour_scheme'] ) ? 'checked' : ( ! isset( $gdpr_options['moove_gdpr_colour_scheme'] ) ? 'checked' : '' ) ) : 'checked'; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Dark', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Light', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>                   
				</td>
			</tr>

			<tr>
				<td colspan="2" style="padding: 0;">
					<hr />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="gdpr_accesibility"><?php esc_html_e( 'Accessibility', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<label class="gdpr-checkbox-toggle gdpr-color-scheme-toggle">
						<input type="checkbox" name="gdpr_accesibility" <?php echo isset( $gdpr_options['gdpr_accesibility'] ) ? ( 1 === intval( $gdpr_options['gdpr_accesibility'] ) ? 'checked' : ( ! isset( $gdpr_options['gdpr_accesibility'] ) ? '' : '' ) ) : ''; ?> >
						<span class="gdpr-checkbox-slider" data-enable="<?php esc_html_e( 'Cookie Banner', 'gdpr-cookie-compliance' ); ?>" data-disable="<?php esc_html_e( 'Content', 'gdpr-cookie-compliance' ); ?>"></span>
					</label>
					<p class="description">
						<?php
							$content = __( 'Choose the right accessibility experience for your users. You can decide wether pressing tab key on your keyboard should first focus on the Cookie Banner or on your website\'s content.', 'gdpr-cookie-compliance' );			
							apply_filters( 'gdpr_cc_keephtml', $content, true );
						?>
					</p>            
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="gdpr_initialization_delay"><?php esc_html_e( 'Banner initialization delay', 'gdpr-cookie-compliance' ); ?></label>
				</th>
				<td>
					<span style="white-space: nowrap;">
						<input type="number" value="<?php echo $initalization_delay; ?>" min="0" step="1" name="gdpr_initialization_delay" id="gdpr_initialization_delay" style="width: 100px;">
						milliseconds
					</span>

					<p class="description">
						<?php
							$content = __( 'This feature can be used to improve Largest Contentful Paint (LCP) metric in PageSpeed Insights.', 'gdpr-cookie-compliance' );
							$content .= '<br />';
							$content .= __( 'Set 0 for the Cookie Banner to appear with no delay.', 'gdpr-cookie-compliance' );
							apply_filters( 'gdpr_cc_keephtml', $content, true );
						?>
					</p>            
				</td>
			</tr>

			<?php do_action( 'gdpr_cc_infobar_settings' ); ?>

		</tbody>
	</table>

	<hr />
	<br />
	<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'gdpr-cookie-compliance' ); ?></button>
	<?php do_action( 'gdpr_cc_banner_buttons_settings' ); ?>
</form>
