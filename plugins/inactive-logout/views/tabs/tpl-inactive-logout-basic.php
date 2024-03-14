<?php
/**
 * Template for Basic settings page.
 *
 * @package inactive-logout
 */

// BASIC.
$override_network               = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_overrideby_multisite_setting' );
$time                           = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_logout_time' );
$countdown_enable               = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_disable_countdown' );
$countdown_timeout              = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_countdown_timeout' );
$ina_warn_message_enabled       = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_warn_message_enabled' );
$ina_concurrent                 = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_concurrent_login' );
$ina_enable_redirect            = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_enable_redirect' );
$ina_redirect_page_link         = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_redirect_page_link' );
$ina_enable_debugger            = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_enable_debugger' );
$ina_popup_modal                = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_logout_popup_localizations' );
$ina_close_without_reload       = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_disable_close_without_reload' );
$ina_disable_automatic_redirect = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_disable_automatic_redirect_on_logout' );

// IF redirect is custom page link.
if ( 'custom-page-redirect' === $ina_redirect_page_link ) {
	$custom_redirect_text_field = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_custom_redirect_text_field' );
}
?>
<div class="ina-settings-admin-wrap">
    <form method="post" class="ina-form" id="ina-settings-form" action="?page=inactive-logout&tab=ina-basic">
		<?php wp_nonce_field( '_nonce_action_save_timeout_settings', '_save_timeout_settings' ); ?>
        <div class="accordion-wrapper">
            <div class="panel">
                <table class="ina-form-tbl form-table">
                    <tbody>
					<?php if ( is_network_admin() ) { ?>
                        <tr>
                            <th scope="row"><label for="idle_overrideby_multisite_setting"><?php esc_html_e( 'Override for all sites', 'inactive-logout' ); ?></label></th>
                            <td>
                                <input class="regular-text" name="idle_overrideby_multisite_setting" type="checkbox" id="idle_overrideby_multisite_setting" <?php echo ! empty( $override_network ) ? 'checked' : false; ?> value="1">
                                <p class="description"><?php esc_html_e( 'When checked below settings will be effective and used for all sites in the network.', 'inactive-logout' ); ?></p>
                            </td>
                        </tr>
					<?php } ?>
                    <tr>
                        <th scope="row"><label for="idle_timeout"><?php esc_html_e( 'Idle Timeout', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input class="regular-text ina-idle-timeout-input" name="idle_timeout" min="1" max="1440" type="number" id="idle_timeout" value="<?php echo ! empty( $time ) ? esc_attr( $time / 60 ) : 15; ?>">
                            <i><?php esc_html_e( 'Minute(s)', 'inactive-logout' ); ?></i>
                            <p class="description"><?php esc_html_e( 'Note: When multi-role is activated, the modal will be displayed based on the idle timeout selected for each individual user role.', 'inactive-logout' ); ?></p>
                            <p class="description"><?php esc_html_e( 'Limited to 24 hours i.e 1440 minutes.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr class="ina-hide-section" <?php echo ! empty( $ina_warn_message_enabled ) ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                        <th scope="row"><label for="idle_timeout"><?php esc_html_e( 'Idle Message Content', 'inactive-logout' ); ?></label></th>
                        <td>
							<?php
							$settings        = array(
								'media_buttons' => false,
								'teeny'         => true,
								'textarea_rows' => 8,
							);
							$message_content = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_logout_message' );
							$content         = $message_content ? $message_content : '<p>You are being timed-out out due to inactivity. Please choose to stay signed in or to logoff.</p><p>Otherwise, you will be logged off automatically.</p>';
							wp_editor( $content, 'idle_message_text', $settings );
							?>
                            <p class="description"><?php esc_html_e( 'Message to be shown when idle timeout screen shows.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr class="ina-hide-section" <?php echo ! empty( $ina_warn_message_enabled ) ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                        <th scope="row"><label for="after_session_logout_message"><?php esc_html_e( 'Session Logout Message', 'inactive-logout' ); ?></label></th>
                        <td>
							<?php
							$settings        = array(
								'media_buttons' => false,
								'teeny'         => true,
								'textarea_rows' => 5,
							);
							$message_content = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_after_logout_message' );
							$content         = $message_content ? $message_content : '<p>You have been logged out because of inactivity.</p>';
							wp_editor( $content, 'after_session_logout_message', $settings );
							?>
                            <p class="description"><?php esc_html_e( 'Message to be shown when idle timeout screen shows.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr class="ina-hide-section" <?php echo ! empty( $ina_warn_message_enabled ) ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                        <th scope="row"><label for="idle_countdown_timeout"><?php esc_html_e( 'Timeout Countdown Period', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="idle_countdown_timeout" type="number" placeholder="10" id="idle_countdown_timeout" value="<?php echo ( ! empty( $countdown_timeout ) ) ? $countdown_timeout : ''; ?>">
                            <i><?php esc_html_e( 'Second(s)', 'inactive-logout' ); ?></i>
                            <p class="description"><?php esc_html_e( 'Countdown before the actual logout to the user in a pop-up. If you set this to 0, the countdown will be set to 10 seconds.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr class="ina-hide-section" <?php echo ! empty( $ina_warn_message_enabled ) ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                        <th scope="row"><label for="idle_disable_countdown"><?php esc_html_e( 'Disable Timeout Countdown', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="idle_disable_countdown" type="checkbox" id="idle_disable_countdown" <?php echo ! empty( $countdown_enable ) ? 'checked' : false; ?> value="1">
                            <p class="description"><?php esc_html_e( 'Check this box to immediately logout the user after the idle timeout minute without any timeout grace.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ina_show_warn_message_only"><?php esc_html_e( 'Show Warning Message Only', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="ina_show_warn_message_only" type="checkbox" id="ina_show_warn_message_only" <?php echo ! empty( $ina_warn_message_enabled ) ? 'checked' : false; ?> value="1">
                            <p class="description"><?php esc_html_e( 'This will show the warning message in a pop-up, but the user will not be logged out.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr id="show_on_warn_message_enabled" <?php echo ! empty( $ina_warn_message_enabled ) && (int) $ina_warn_message_enabled === 1 ? 'style="display:table-row;"' : 'style="display:none;"'; ?>>
                        <th scope="row"><label for="ina_show_warn_message"><?php esc_html_e( 'Warning Message Content', 'inactive-logout' ); ?></label></th>
                        <td>
							<?php
							$settings_warn        = array(
								'media_buttons' => false,
								'teeny'         => true,
								'textarea_rows' => 15,
							);
							$__ina_warn_message   = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_warn_message' );
							$content_warn_message = $__ina_warn_message ? $__ina_warn_message : '<h3>Wakeup !</h3><p>You have been inactive for {wakup_timout}. Press continue to continue browsing.</p>';
							wp_editor( $content_warn_message, 'ina_show_warn_message', $settings_warn );
							?>
                            <p class="description"><?php esc_html_e( 'Use {wakup_timout} to show minutes. This is message that will be shown when inactive.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ina_disable_multiple_login"><?php esc_html_e( 'Prevent Multiple Logins', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="ina_disable_multiple_login" type="checkbox" id="ina_disable_multiple_login" <?php echo ! empty( $ina_concurrent ) ? 'checked' : false; ?> value="1">
                            <p class="description"><?php esc_html_e( 'This will prevent the same user from logging in at multiple locations.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
					<?php do_action( 'ina__addon_form_elements' ); ?>

                    <tr class="ina-hide-section" <?php echo ! empty( $ina_warn_message_enabled ) ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                        <th scope="row"><label for="ina_enable_redirect_link"><?php esc_html_e( 'Enable Logout Redirect', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="ina_enable_redirect_link" type="checkbox" <?php echo ! empty( $ina_enable_redirect ) ? 'checked' : false; ?> id="ina_enable_redirect_link" value="1">
                            <p class="description"><?php esc_html_e( 'Redirect a user to specific page after session logout or on logout button click.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr class="ina-hide-section show_on_enable_redirect_link" <?php echo empty( $ina_warn_message_enabled ) && ! empty( $ina_enable_redirect ) ? 'style="display:table-row;"' : 'style="display:none;"'; ?>>
                        <th scope="row"><label for="ina_redirect_page"><?php esc_html_e( 'Redirect Page', 'inactive-logout' ); ?></label></th>
                        <td>
                            <select name="ina_redirect_page" class="ina_redirect_page regular-text">
                                <option value="custom-page-redirect"><?php esc_html_e( 'External Page Redirect', 'inactive-logout' ); ?></option>
								<?php
								$posts = \Codemanas\InactiveLogout\Helpers::getAllPostsPages();
								if ( ! empty( $posts ) ) {
									foreach ( $posts as $k => $post_types ) {
										?>
                                        <optgroup label="<?php echo ucfirst( $k ); ?>">
											<?php foreach ( $post_types as $post_type ) { ?>
                                                <option <?php echo ! empty( $ina_redirect_page_link ) && ( absint( $ina_redirect_page_link ) === $post_type['ID'] ) ? esc_attr( 'selected' ) : ''; ?>
                                                        value="<?php echo esc_attr( $post_type['ID'] ); ?>">
													<?php echo esc_html( $post_type['title'] ); ?>
                                                </option>
											<?php } ?>
                                        </optgroup>
										<?php
									}
								} else {
									?>
                                    <option value=""><?php esc_html_e( 'No Posts Found.', 'inactive-logout' ); ?></option>
									<?php
								}
								?>
                            </select>
                            <span id="show_if_custom_redirect" <?php echo ! empty( $ina_enable_redirect ) ? false : 'style=display:none;'; ?> <?php echo ! empty( $ina_redirect_page_link ) && 'custom-page-redirect' === $ina_redirect_page_link ? false : 'style="display:none;"'; ?>>
                    => <input name="custom_redirect_text_field" type="url" id="custom_redirect_text_field" class="regular-text code" placeholder="https://www.imdpen.com" value="<?php echo ( ! empty( $custom_redirect_text_field ) ) ? esc_attr( $custom_redirect_text_field ) : false; ?>">
                    </span>
                            <p class="description"><?php esc_html_e( 'Select a page to redirect to after session timeout and clicking OK.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr class="ina-hide-section show_on_enable_redirect_link" <?php echo empty( $ina_warn_message_enabled ) && ! empty( $ina_enable_redirect ) ? 'style="display:table-row;"' : 'style="display:none;"'; ?>>
                        <th scope="row"><label for="ina_disable_automatic_redirect"><?php esc_html_e( 'Turn off Automatic Redirect', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="ina_disable_automatic_redirect" type="checkbox" <?php echo ! empty( $ina_disable_automatic_redirect ) ? 'checked' : false; ?> id="ina_disable_automatic_redirect" value="1">
                            <p class="description"><?php esc_html_e( 'Check this option to disable automatic redirect after inactive popup; instead, the user will be redirected to a defined page upon clicking the "OK" button.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ina_enable_debugger"><?php esc_html_e( 'Enable Debugger?', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input name="ina_enable_debugger" type="checkbox" <?php echo ! empty( $ina_enable_debugger ) ? 'checked' : false; ?> id="ina_enable_debugger" value="1">
                            <p class="description"><?php esc_html_e( 'Enable debugger window for debugging logout issue.', 'inactive-logout' ); ?></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h4><?php esc_html_e( 'Modal (Pop-Up) Settings', 'inactive-logout' ); ?></h4>
            <div class="panel">
                <table class="ina-form-tbl form-table">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="popup_modal_text_popup_heading"><?php esc_html_e( 'Header Text', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input class="regular-text" name="popup_modal_text_popup_heading" type="text" id="popup_modal_text_popup_heading" value="<?php echo ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['popup_heading_text'] ) ? esc_html( $ina_popup_modal['popup_heading_text'] ) : 'Session Timeout'; ?>">
                            <span><a href="https://tinyurl.com/2n3zwpds" target="_blank"><i><?php _e( "Example", "inactive-logout" ); ?></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="popup_modal_text_close"><?php esc_html_e( 'Close Modal Button Text', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input class="regular-text" name="popup_modal_text_close" type="text" id="popup_modal_text_close" value="<?php echo ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['text_close'] ) ? esc_html( $ina_popup_modal['text_close'] ) : 'Close without Reloading'; ?>">
                            <span><a href="https://tinyurl.com/2erfvk4v" target="_blank"><i><?php _e( "Example", "inactive-logout" ); ?></i></a></span>
                            <span style="margin-left:20px;"><input type="checkbox" name="popup_modal_close_without_reload_hide" <?php echo ! empty( $ina_close_without_reload ) ? 'checked' : false; ?> value="1"><?php _e( "Check this to hide this button", "inactive-logout" ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="popup_modal_text_ok"><?php esc_html_e( 'After Logout Button Text', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input class="regular-text" name="popup_modal_text_ok" type="text" id="popup_modal_text_ok" value="<?php echo ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['text_ok'] ) ? esc_html( $ina_popup_modal['text_ok'] ) : 'OK'; ?>">
                            <span><a href="https://tinyurl.com/2pu9ghuf" target="_blank"><i><?php _e( "Example", "inactive-logout" ); ?></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="popup_modal_text_continue_browsing"><?php esc_html_e( 'Continue Button Text', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input class="regular-text" name="popup_modal_text_continue_browsing" type="text" id="popup_modal_text_continue_browsing" value="<?php echo ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['continue_browsing_text'] ) ? esc_html( $ina_popup_modal['continue_browsing_text'] ) : 'Continue Browsing'; ?>">
                            <span><a href="https://tinyurl.com/2gx4zvsd" target="_blank"><i><?php _e( "Example", "inactive-logout" ); ?></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="popup_modal_wakeup_continue_btn"><?php esc_html_e( 'Wakeup Button Text', 'inactive-logout' ); ?></label></th>
                        <td>
                            <input class="regular-text" name="popup_modal_wakeup_continue_btn" type="text" id="popup_modal_wakeup_continue_btn" value="<?php echo ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['wakeup_cta'] ) ? esc_html( $ina_popup_modal['wakeup_cta'] ) : 'Ok'; ?>">
                            <span><a href="https://tinyurl.com/2zw4edys" target="_blank"><i><?php _e( "Example", "inactive-logout" ); ?></i></a></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="submit submit-wrapper">
            <input type="submit" name="submit" id="ina-settings-save" class="ina-button ina-button-primary" data-loading="<?php esc_html_e( 'Saving.. Please wait', 'inactive-logout' ); ?>..." value="<?php esc_html_e( 'Save Changes', 'inactive-logout' ); ?>">
        </div>
    </form>
</div>
<script>
    jQuery(function ($) {
        var $redirectPage = $('.ina_redirect_page')
        $redirectPage.select2()
        $redirectPage.on('change', function (e) {
            if (e.target.value === 'custom-page-redirect') {
                document.getElementById('show_if_custom_redirect').style.display = 'inline'
            } else {
                document.getElementById('show_if_custom_redirect').style.display = 'none'
            }
        })
    })
</script>