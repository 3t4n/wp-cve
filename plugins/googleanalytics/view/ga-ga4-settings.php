<?php
$ga4_exclude_roles = true === is_array( $ga4_exclude_roles ) ? $ga4_exclude_roles : array();
$ga_admin          = new Ga_Admin();
$auth_info         = $ga_admin->getGa4AuthInfo();
?>
<script type="text/javascript">
	const GA_NONCE = '<?php echo esc_js( $ga_nonce ); ?>';
	const GA_NONCE_FIELD = 'ga4-setup';
</script>
<div class="settings-wrap setup-wrap<?php echo true === $setup_done ? ' hide' : ''; ?>">
	<div class="ga-step ga-step-1<?php echo false === $has_code && false === $has_property ? ' engage' : ''; ?>">
		<div class="ga-steps">
			1 of 3
			<div class="ga-dots"><span class="dark-green">•</span> • •</div>
		</div>
		<h2>Setup Google Authentication</h2>
		<p>Use our configuration wizard to properly setup Google Analytics with WordPress (with just a few clicks).</p>
		<a id="google-auth-link" href="<?php echo esc_url( $auth_info['auth_url'] ); ?>">
			<img src="<?php echo esc_url( plugins_url() ) . '/googleanalytics/assets/images/Google-logo.svg'; ?>" alt="Google Logo">Sign in with Google
		</a>
	</div>
	<div class="ga-step ga-step-2<?php echo false !== $has_code && false === $has_property ? ' engage' : ''; ?>">
		<div class="ga-steps">
			2 of 3
			<div class="ga-dots">• <span class="dark-green">•</span> •</div>
		</div>
		<h2>Select Account Property</h2>
		<p>Choose the view you want to use for your reports.</p>
		<label for="ga4-property">
			<select id="ga4-property">
				<option>Choose Property</option>
				<?php
				foreach ( $auth_info['properties'] as $account => $properties ) :
					if ( false === empty( $properties ) ) :
						?>
					<option disabled><?php echo esc_html( $account ); ?>:</option>
						<?php
						foreach ( $properties as $property ) :
							// Only add UA properties with default profile IDs.
							if ( true === isset( $property['id'] ) &&
								false !== strpos( $property['id'], 'UA-' ) &&
								false === isset( $property['defaultProfileId'] )
							) {
								continue;
							}
							?>
						<option data-view-id="<?php echo isset( $property['defaultProfileId'] ) ? esc_attr( $property['defaultProfileId'] ) : ''; ?>" value="<?php echo isset( $property['defaultProfileId'] ) ? 'properties/' . esc_attr( $property['internalWebPropertyId'] ) : esc_attr( $property['name'] ); ?>">
							<?php echo isset( $property['displayName'] ) ? esc_html( $property['displayName'] ) : esc_html( $property['name'] ); ?>
						</option>
							<?php
						endforeach;
					endif;
				endforeach;
				?>
			</select>
		</label>
		<a id="to-step-3">Next</a>
	</div>
	<div class="ga-step ga-step-3<?php echo false === $setup_done && false !== $has_code && false !== $has_property ? ' engage' : ''; ?>">
		<div class="ga-steps">
			3 of 3
			<div class="ga-dots">• • <span class="dark-green">•</span></div>
		</div>
		<div class="extra-settings">
			<h2>Settings</h2>
			<div class="ga-row">
				<div class="ua-dual-settings">
					<div class="ga-ua-col">
						<h2>If using Google Optimize, enter optimize code here</h2>
						<label>
							<input id="ga4-google-optimize" type="text" placeholder="GMT-XXXXXX" value="">
						</label>

						<h2>Exclude Tracking for Roles</h2>
						<div class="ga-exclude-roles">
							<div class="switch">
								<label class="item">
									<input type="checkbox" value="administrator">
									<span class="lever"></span>
									Administrator
								</label>
							</div>
							<div class="switch">
								<label class="item">
									<input type="checkbox" value="contributor">
									<span class="lever"></span>
									Contributor
								</label>
							</div>
							<div class="switch">
								<label class="item">
									<input type="checkbox" value="editor">
									<span class="lever"></span>
									Editor
								</label>
							</div>
							<div class="switch">
								<label class="item">
									<input type="checkbox" value="subscriber">
									<span class="lever"></span>
									Subscriber
								</label>
							</div>
							<div class="switch">
								<label class="item">
									<input type="checkbox" value="author">
									<span class="lever"></span>
									Author
								</label>
							</div>
						</div>
					</div>
					<div class="ga-ua-col">
						<div class="switch">
							<label class="item">
								Enable Demographics
								<input id="ga4-enable-demo" type="checkbox">
								<span class="lever"></span>
							</label>
						</div>
						<div class="switch">
							<label class="item">
								Enable IP Anonymization
								<input type="checkbox" id="ga4-enable-ip-anon">
								<span class="lever"></span>
							</label>
						</div>
						<div class="switch">
							<label class="item">
								Enable GDPR Consent Management Tool
								<input type="checkbox" id="ga4-enable-gdpr">
								<span class="lever"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<a id="complete-ga4-setup" class="green-button" href="<?php echo esc_url( get_admin_url() ); ?>admin.php?page=googleanalytics">Complete and go to dashboard</a>
		</div>
	</div>
</div>

<?php if (true === $setup_done) : ?>
<div style="margin-top: 0;<?php echo true === $setup_done ? '' : ' display: none;'; ?>" class="ga4_container ga_container">
	<?php if ( false === empty( $data['error_message'] ) ) : ?>
		<?php echo wp_kses_post( $data['error_message'] ); ?>
	<?php endif; ?>
	<h1>Settings</h1>
	<form id="ga4_form" method="post" action="options.php">
		<?php settings_fields( 'googleanalyticsga4' ); ?>
		<table class="form-table">
			<tr>
				<?php if ( false === empty( $data['popup_url'] ) ) : ?>
					<th scope="row">
						<label class="<?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip"' : '' ); ?>">
							<?php esc_html_e( 'Google Profile' ); ?>:
							<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
						</label>
					</th>
				<?php endif; ?>
			</tr>
			<?php if ( false === empty( $auth_info['properties'] ) ) : ?>
				<tr>
					<td>
						<select id="ga_account_selector" name="googleanalytics-ga4-property">
							<option>Please select your Google Analytics account:</option>
							<?php
							foreach ( $auth_info['properties'] as $account => $properties ) :
								if ( false === empty( $properties ) ) :
									?>
									<option disabled><?php echo esc_html( $account ); ?>:</option>
									<?php
									foreach ( $properties as $property ) :
										// Only add UA properties with default profile IDs.
										if ( true === isset( $property['id'] ) &&
											false !== strpos( $property['id'], 'UA-' ) &&
											false === isset( $property['defaultProfileId'] )
										) {
											continue;
										}

										$property_name = isset( $property['defaultProfileId'] ) ? 'properties/' . esc_attr( $property['internalWebPropertyId'] ) : esc_attr( $property['name'] );
										?>
										<option data-view-id="<?php echo isset( $property['defaultProfileId'] ) ? esc_attr( $property['defaultProfileId'] ) : ''; ?>" value="<?php echo esc_attr( $property_name ); ?>" <?php echo $property_name === $has_property ? 'selected' : ''; ?>>
											<?php echo isset( $property['displayName'] ) ? esc_html( $property['displayName'] ) : esc_html( $property['name'] ); ?>
										</option>
										<?php
									endforeach;
								endif;
							endforeach;
							?>
						</select>
					</td>
					<td>
						<button id="ga4_sign_out" class="button-secondary" type="button">
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
					if ( false === empty( $data['roles'] ) && true === is_array( $ga4_exclude_roles ) ) {
						$roles = $data['roles'];
						foreach ( $roles as $role_item ) {
							$role_id   = true === isset( $role_item['id'] ) ? $role_item['id'] : '';
							$role_name = true === isset( $role_item['name'] ) ? $role_item['name'] : '';
							?>
							<div class="checkbox">
								<label class="ga_checkbox_label <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>"
									for="checkbox_<?php echo esc_attr( $role_id ); ?>">
									<input id="checkbox_<?php echo esc_attr( $role_id ); ?>" type="checkbox"
										<?php echo disabled( false === Ga_Helper::are_features_enabled() ); ?>
										name="googleanalytics-ga4-exclude-roles[<?php echo esc_attr( str_replace( 'role-id-', '', $role_id ) ); ?>]"
										id="<?php echo esc_attr( $role_id ); ?>"
										<?php echo esc_attr( ( true === in_array( str_replace( 'role-id-', '', $role_id ), array_keys( $ga4_exclude_roles ), true ) ? 'checked="checked"' : '' ) ); ?> />&nbsp;
									<?php echo esc_html( $role_name ); ?>
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
					<label class="ga-switch <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
						<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
							<input id="ga-anonymization" name="googleanalytics-ga4-ip-anon"
								type="checkbox" <?php echo checked( $ga4_ip, 'on' ); ?>>

							<div id="ga-slider" class="ga-slider round"></div>
						<?php else : ?>
							<input id="ga-anonymization" name="googleanalytics-ga4-ip-anon"
								type="checkbox" disabled="disabled">

							<div id="ga-slider" class="ga-slider round"></div>
							<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
						<?php endif; ?>
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<input name="googleanalytics-ga4-demo" value="<?php echo esc_attr( $ga4_demo ); ?>" type="hidden">
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'If using Google Optimize, enter optimize code here', 'googleanalytics' ); ?>:</th>
			</tr>
			<tr>
				<td>
					<label class="ga-text <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
						<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
							<input id="ga-optimize" name="googleanalytics-ga4-optimize"
								type="text" placeholder="GTM-XXXXXX"
								value="<?php echo esc_attr( $ga4_optimize ); ?>">
						<?php else : ?>
							<input id="ga-optimize" name="googleanalytics-ga4-optimize"
								type="text" placeholder="GTM-XXXXXX"
								value="<?php echo esc_attr( $ga4_optimize ); ?>" readonly>
							<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
						<?php endif; ?>
					</label>
				</td>
			</tr>
			<?php require $plugin_dir . 'templates/gdpr.php'; ?>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>"/>
		</p>
	</form>
</div>
<?php endif; ?>
