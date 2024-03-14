<?php
// phpcs:ignoreFile

/**
 * Page view.
 *
 * @package GoogleAnalytics
 */

$optimize_code = get_option( 'googleanalytics_optimize_code' );
$universal          = get_option( 'googleanalytics_oauth_auth_token', '' );
$universal          = '' !== $universal;
$anonymization = get_option( 'googleanalytics_ip_anonymization', true );
$debug_mode    = get_option( 'googleanalytics_enable_debug_mode', 'off' );
$gdpr_config   = get_option( 'googleanalytics_gdpr_config' );
$sharethis_property = get_option( 'googleanalytics_sharethis_terms' );
$plugin_dir    = plugin_dir_path( __FILE__ );
$plugin_uri    = trailingslashit( get_home_url() ) . 'wp-content/plugins/googleanalytics/';
$has_code = filter_input(INPUT_GET, 'code');
$has_code = true === isset($has_code) ? $has_code : false;
$has_property = get_option('googleanalytics-ga4-property');
$has_property = true === isset($has_property) ? $has_property : false;
$ga4_optimize = get_option('googleanalytics-ga4-optimize');
$ga4_optimize = true === isset($ga4_optimize) ? $ga4_optimize : false;
$ga4_exclude_roles = get_option('googleanalytics-ga4-exclude-roles');
$ga4_exclude_roles = true === isset($ga4_exclude_roles) ? $ga4_exclude_roles : false;
$ga4_demo = get_option('googleanalytics-ga4-demo');
$ga4_demo = true === isset($ga4_demo) ? $ga4_demo : false;
$ga4_ip = get_option('googleanalytics-ga4-ip-anon');
$ga4_ip = true === isset($ga4_ip) ? $ga4_ip : false;
$ga4_gdpr = get_option('googleanalytics-ga4-gdpr');
$ga4_gdpr = true === isset($ga4_gdpr) ? $ga4_gdpr : false;
$ga_nonce = wp_create_nonce('ga4-setup');
$setup_done = false !== $has_property &&
			(
				false !== $ga4_gdpr ||
				false !== $ga4_demo ||
				false !== $ga4_exclude_roles ||
				false !== $ga4_optimize ||
				false !== $ga4_ip
			);
?>
	<div id="ga_access_code_modal" class="ga-modal" tabindex="-1">
		<div class="ga-modal-dialog">
			<div class="ga-modal-content">
				<div class="ga-modal-header">
					<span id="ga_close" class="ga-close">&times;</span>
					<h4 class="ga-modal-title">
						<?php esc_html_e( 'Please paste the access code obtained from Google below:' ); ?>
					</h4>
				</div>
				<div class="ga-modal-body">
					<div id="ga_code_error" class="ga-alert ga-alert-danger" style="display: none;"></div>
					<label for="ga_access_code"><strong><?php esc_html_e( 'Access Code' ); ?></strong>:</label>
					&nbsp;<input id="ga_access_code_tmp" type="text"
								 placeholder="<?php esc_html_e( 'Paste your access code here' ); ?>"/>
					<div class="ga-loader-wrapper">
						<div class="ga-loader"></div>
					</div>
				</div>
				<div class="ga-modal-footer">
					<button id="ga_btn_close" type="button" class="button">Close</button>
					<button type="button" class="button-primary"
							id="ga_save_access_code"
							onclick="ga_popup.saveAccessCode( event )"><?php esc_html_e( 'Save Changes' ); ?></button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php echo wp_kses_post( $data['debug_modal'] ); ?>
	<div class="wrap ga-wrap do-flex">
		<div class="setting-tabs">
			<div class="setting-tabs__tab ga4<?php echo false === $universal ? ' engage' : ''; ?>">
				<?php esc_html_e('Google Analytics 4', 'googleanalytics'); ?>
			</div>
			<?php if ( false !== $universal ) : ?>
				<div class="ua setting-tabs__tab engage">
					<?php esc_html_e( 'Universal Analytics Settings', 'googleanalytics' ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="ga4-settings-wrap setting-tab-content st-notice-there
		<?php echo true === $setup_done ? ' normal-settings' : '';
		echo false === $universal ? ' engage' : '';
		?>">
			<?php include 'ga-ga4-settings.php'; ?>
		</div>
		<div class="ua-settings-wrap setting-tab-content<?php echo false !== $universal ? ' engage' : ''; ?>">
			<h1>Universal Analytics - <?php esc_html_e( 'Settings', 'googleanalytics' ); ?></h1>
			<?php if (false === $setup_done) : ?>
				<button class="open-ga4">Setup Google Analytics 4</button>
			<?php endif; ?>
			<div style="margin-top: 0;" class="ga_container">
				<?php if ( false === empty( $data['error_message'] ) ) : ?>
					<?php echo wp_kses_post( $data['error_message'] ); ?>
				<?php endif; ?>
				<form id="ga_form" method="post" action="options.php">
					<?php settings_fields( 'googleanalytics' ); ?>
					<input id="ga_access_code" type="hidden"
						   name="<?php echo esc_attr( Ga_Admin::GA_OAUTH_AUTH_CODE_OPTION_NAME ); ?>" value=""/>
					<table class="form-table">
						<tr>
							<?php if ( false === empty( $data['popup_url'] ) ) : ?>
								<th scope="row">
									<label class="<?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip"' : '' ); ?>">
										<?php esc_html_e( 'Google Profile' ); ?>:
										<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
									</label>
								</th>
								<td <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'class="ga-tooltip"' : '' ); ?>>
									<?php
									echo wp_kses(
										$data['auth_button'],
										array(
											'button' => array(
												'class'   => array(),
												'id'      => array(),
												'onclick' => array(),
											),
										)
									);
									?>
									<span class="ga-tooltiptext"><?php echo esc_html( $tooltip ); ?></span>
									<?php if ( false === empty( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ] ) ) : ?>
										<div class="ga_warning">
											<strong><?php esc_html_e( 'Notice' ); ?></strong>:&nbsp
											<?php esc_html_e( 'Please uncheck the "Manually enter Tracking ID" option to authenticate and view statistics.' ); ?>
										</div>
									<?php endif; ?>
								</td>
							<?php endif; ?>

							<?php if ( false === empty( $data['ga_accounts_selector'] ) ) : ?>
								<th scope="row"><?php esc_html_e( 'Google Analytics Account' ); ?>:</th>
							<?php endif; ?>
						</tr>
						<?php if ( false === empty( $data['ga_accounts_selector'] ) ) : ?>
							<tr>
								<td>
									<?php
									echo wp_kses(
										$data['ga_accounts_selector'],
										array(
											'input'    => array(
												'name'  => array(),
												'type'  => array(),
												'value' => array(),
											),
											'select'   => array(
												'id'   => array(),
												'name' => array(),
											),
											'option'   => array(
												'value'    => array(),
												'selected' => array(),
											),
											'optgroup' => array(
												'label' => array(),
											),
										)
									);
									?>
								</td>
								<td>
									<button id="ga_sign_out" class="button-secondary" type="button">
										<?php esc_html_e( 'Sign out', 'googleanalytics' ); ?>
									</button>
								</td>
							</tr>
						<?php endif; ?>
						<tr id="ga_roles_wrapper">
							<th scope="row">
								<label class="<?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
									<?php esc_html_e( 'Exclude Tracking for Roles' ); ?>
									:
									<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
								</label>
							</th>
						</tr>
						<tr>
							<td>
								<?php
								if ( false === empty( $data['roles'] ) ) {
									$roles = $data['roles'];
									foreach ( $roles as $role_item ) {
										?>
										<div class="checkbox">
											<label class="ga_checkbox_label <?php echo esc_attr(false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : ''); ?>"
												   for="checkbox_<?php echo esc_attr( $role_item['id'] ); ?>">
												<input id="checkbox_<?php echo esc_attr( $role_item['id'] ); ?>" type="checkbox"
													<?php echo disabled( false === Ga_Helper::are_features_enabled() ); ?>
													   name="<?php echo esc_attr( Ga_Admin::GA_EXCLUDE_ROLES_OPTION_NAME . '[' . $role_item['id'] . ']' ); ?>"
													   id="<?php echo esc_attr( $role_item['id'] ); ?>"
													<?php echo esc_attr( ( $role_item['checked'] ? 'checked="checked"' : '' ) ); ?> />&nbsp;
												<?php echo esc_html( $role_item['name'] ); ?>
												<span class="ga-tooltiptext"><?php echo esc_html( $tooltip ); ?></span>
											</label>
										</div>
										<?php
									}
								}
								?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Enable IP Anonymization' ); ?>:</th>
						</tr>
						<tr>
							<td>
								<label class="ga-switch <?php echo esc_attr( ! Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
									<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
										<input id="ga-anonymization" name="googleanalytics_ip_anonymization"
											   type="checkbox" <?php echo checked( $anonymization, 'on' ); ?>>

										<div id="ga-slider" class="ga-slider round"></div>
									<?php else : ?>
										<input id="ga-anonymization" name="googleanalytics_ip_anonymization"
											   type="checkbox" disabled="disabled">

										<div id="ga-slider" class="ga-slider round"></div>
										<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
									<?php endif; ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'If using Google Optimize, enter optimize code here' ); ?>:</th>
						</tr>
						<tr>
							<td>
								<label class="ga-text <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
									<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
										<input id="ga-optimize" name="googleanalytics_optimize_code"
											   type="text" placeholder="GTM-XXXXXX"
											   value="<?php echo esc_attr( $optimize_code ); ?>">
									<?php else : ?>
										<input id="ga-optimize" name="googleanalytics_optimize_code"
											   type="text" placeholder="GTM-XXXXXX"
											   value="<?php echo esc_attr( $optimize_code ); ?>" readonly>
										<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
									<?php endif; ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Disable all features' ); ?>:</th>
						</tr>
						<tr>
							<td>
								<label class="ga-switch <?php echo esc_attr( ! Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
									<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
										<input id="ga-disable"
											   name="<?php echo esc_attr( Ga_Admin::GA_DISABLE_ALL_FEATURES ); ?>"
											   type="checkbox">
										<div id="ga-slider" class="ga-slider-disable ga-slider round"></div>
									<?php else : ?>
										<input id="ga-disable"
											   name="<?php echo esc_attr( Ga_Admin::GA_DISABLE_ALL_FEATURES ); ?>"
											   type="checkbox" disabled="disabled">
										<div id="ga-slider" class="ga-slider-disable ga-slider round"></div>
										<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
									<?php endif; ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Turn on GA Debugging' ); ?>:</th>
						</tr>
						<tr>
							<td>
								<label class="ga-switch <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
									<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
										<input id="ga-debugging" name="googleanalytics_enable_debug_mode"
											   type="checkbox" <?php echo checked( $debug_mode, 'on' ); ?>>
										<div id="ga-slider" class="ga-slider round"></div>
									<?php else : ?>
										<input id="ga-debugging" name="googleanalytics_enable_debug_mode"
											   type="checkbox" disabled="disabled">
										<div id="ga-slider" class="ga-slider round"></div>
									<?php endif; ?>
								</label>
								<div class="ga_warning">
									<strong><?php esc_html_e( 'WARNING' ); ?></strong>:&nbsp
									<?php
									esc_html_e(
										'For debugging purposes only! Should NOT be used on live sites!',
										'googleanalytics'
									);
									?>
								</div>
							</td>
						</tr>
						<?php require $plugin_dir . 'templates/gdpr.php'; ?>
					</table>

					<p class="submit">
						<input type="submit" class="button-primary"
							   value="<?php esc_html_e( 'Save Changes' ); ?>"/>
					</p>
				</form>
			</div>
		</div>
		<?php
		// If GDPR isn't enabled show ad otherwise show demo ad.
		if ( true === empty( $gdpr_config ) ) {
			include $plugin_dir . 'templates/sidebar/gdpr-ad.php';
		} else {
			// If Demo is not enabled show ad.
			if ( true === empty( get_option( 'googleanalytics_demographic' ) ) ) {
				include $plugin_dir . 'templates/sidebar/demo-ad.php';
			}
		}
		?>
		<?php if ( false === empty( $data['debug_info'] ) ) : ?>
			<tr>
				<td colspan="2">
					<p>If you are still experiencing an issue, we are here to help! We recommend clickingthe "Send
						Debugging Info" button below and pasting the information within an email to
						support@sharethis.com.</p>
					<p>
						<button id="ga_debug_button" class="button button-secondary"
								onclick="ga_debug.open_modal( event )">Send Debugging Info
						</button>
						<?php if ( false === empty( $data['ga_accounts_selector'] ) ) : ?>
							<?php echo wp_kses_post( $data['auth_button'] ); ?>
							<br>
							<small class="notice">
								*If you reset your google password you MUST re-authenticate to continue viewing your
								analytics dashboard.
							</small>
						<?php endif; ?>
					</p>
				</td>
			</tr>
		<?php endif; ?>

		<p class="ga-love-text"><?php esc_html_e( 'Love this plugin?' ); ?> <a
					href="https://wordpress.org/support/plugin/googleanalytics/reviews/#new-post"><?php esc_html_e( ' Please help spread the word by leaving a 5-star review!' ); ?> </a>
		</p>
	</div>
	<script type="text/javascript">
		const GA_DISABLE_FEATURE_URL = '<?php echo esc_url( Ga_Helper::create_url( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ), array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_disable_all_features' ) ) ); ?>';
		const GA_ENABLE_FEATURE_URL = '<?php echo esc_url( Ga_Helper::create_url( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ), array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_enable_all_features' ) ) ); ?>';
		jQuery( document ).ready( function() {
			ga_switcher.init( '<?php echo esc_js( $data[ Ga_Admin::GA_DISABLE_ALL_FEATURES ] ); ?>' );
		} );
	</script>
<?php
require 'templates/demo-popup.php';
