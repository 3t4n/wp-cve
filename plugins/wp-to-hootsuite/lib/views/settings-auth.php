<?php
/**
 * Outputs the Settings screen when the Plugin is authenticated with
 * the third party API service.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<div class="postbox wpzinc-vertical-tabbed-ui">
	<!-- Second level tabs -->
	<ul class="wpzinc-nav-tabs wpzinc-js-tabs" data-panels-container="#settings-container" data-panel=".panel" data-active="wpzinc-nav-tab-vertical-active">
		<li class="wpzinc-nav-tab lock">
			<a href="#authentication" class="wpzinc-nav-tab-vertical-active" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/authentication-settings/">
				<?php esc_html_e( 'Authentication', 'wp-to-hootsuite' ); ?>
			</a>
		</li>
		<li class="wpzinc-nav-tab default">
			<a href="#general-settings" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/general-settings/">
				<?php esc_html_e( 'General Settings', 'wp-to-hootsuite' ); ?>
			</a>
		</li>
		<li class="wpzinc-nav-tab image">
			<a href="#image-settings" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/text-to-image-settings/">
				<?php esc_html_e( 'Text to Image', 'wp-to-hootsuite' ); ?>
			</a>
		</li>
		<li class="wpzinc-nav-tab file-text">
			<a href="#log-settings" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/log-settings/">
				<?php esc_html_e( 'Log Settings', 'wp-to-hootsuite' ); ?>
			</a>
		</li>
		<li class="wpzinc-nav-tab arrow-right-circle">
			<a href="#repost-settings" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/repost-settings/">
				<?php esc_html_e( 'Repost Settings', 'wp-to-hootsuite' ); ?>
			</a>
		</li>
		<?php
		// Only display if we've auth'd and have profiles.
		if ( ! empty( $access_token ) ) {
			?>
			<li class="wpzinc-nav-tab users">
				<a href="#user-access" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/user-access-settings/">
					<?php esc_html_e( 'User Access', 'wp-to-hootsuite' ); ?>
				</a>
			</li>
			<?php
		}
		?>
		<li class="wpzinc-nav-tab tag">
			<a href="#custom-tags" data-documentation="<?php echo esc_attr( $this->base->plugin->documentation_url ); ?>/custom-tags-settings/">
				<?php esc_html_e( 'Custom Tags', 'wp-to-hootsuite' ); ?>
			</a>
		</li>
	</ul>

	<!-- Content -->
	<div id="settings-container" class="wpzinc-nav-tabs-content no-padding">
		<!-- Authentication -->
		<div id="authentication" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Authentication', 'wp-to-hootsuite' ); ?></h3>

					<p class="description">
						<?php
						echo esc_html(
							sprintf(
							/* translators: %1$s: Plugin Name, %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
								__( 'Authentication allows %1$s to post to %2$s', 'wp-to-hootsuite' ),
								$this->base->plugin->displayName,
								$this->base->plugin->account
							)
						);
						?>
					</p>
				</header>

				<div class="wpzinc-option">
					<div class="full">
						<?php
						echo esc_html(
							sprintf(
							/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
								__( 'Thanks - you\'ve authorized the plugin to post updates to your %s account.', 'wp-to-hootsuite' ),
								$this->base->plugin->account
							)
						);
						?>
					</div>
				</div>
				<div class="wpzinc-option">
					<div class="full">
						<a href="admin.php?page=<?php echo esc_attr( $this->base->plugin->name ); ?>-settings&amp;<?php echo esc_attr( $this->base->plugin->name ); ?>-disconnect=1" class="button wpzinc-button-red">
							<?php esc_html_e( 'Deauthorize Plugin', 'wp-to-hootsuite' ); ?>
						</a>
					</div>
				</div>
			</div>   
		</div>

		<!-- General Settings -->
		<div id="general-settings" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'General Settings', 'wp-to-hootsuite' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Provides options for logging, Post default level settings and whether to use WordPress Cron when publishing or updating Posts.', 'wp-to-hootsuite' ); ?>
					</p>
				</header>

				<div class="wpzinc-option">
					<div class="left">
						<label for="test_mode"><?php esc_html_e( 'Enable Test Mode', 'wp-to-hootsuite' ); ?></label>
					</div>
					<div class="right">
						<input type="checkbox" name="test_mode" id="test_mode" value="1" <?php checked( $this->get_setting( '', 'test_mode' ), 1 ); ?> />

						<p class="description">
							<?php
							echo esc_html(
								sprintf(
								/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
									__( 'If enabled, status(es) are not sent to %s, but will appear in the Log, if logging is enabled. This is useful to test status text, conditions etc.', 'wp-to-hootsuite' ),
									$this->base->plugin->account
								)
							);
							?>
						</p>
					</div>
				</div>

				<div class="wpzinc-option">
					<div class="left">
						<label for="force_trailing_forwardslash"><?php esc_html_e( 'Force Trailing Forwardslash?', 'wp-to-hootsuite' ); ?></label>
					</div>
					<div class="right">
						<input type="checkbox" name="force_trailing_forwardslash" id="force_trailing_forwardslash" value="1" <?php checked( $this->get_setting( '', 'force_trailing_forwardslash' ), 1 ); ?> />

						<p class="description">
							<?php
							esc_html_e( 'If enabled, any URLs in statuses will always end with a forwardslash. This might be required if the wrong image is shared with a status.', 'wp-to-hootsuite' );
							?>
							<br />
							<?php
							printf(
								'%1$s <a href="options-permalink.php">%2$s</a> %3$s',
								esc_html__( 'It\'s better to ensure your', 'wp-to-hootsuite' ),
								esc_html__( 'Permalink', 'wp-to-hootsuite' ),
								esc_html__( 'settings end with a forwardslash, but this option is a useful fallback if changing Permalink structure isn\'t possible.', 'wp-to-hootsuite' )
							);
							?>
						</p>
					</div>
				</div>

				<div class="wpzinc-option">
					<div class="left">
						<label for="proxy"><?php esc_html_e( 'Use Proxy?', 'wp-to-hootsuite' ); ?></label>
					</div>
					<div class="right">
						<input type="checkbox" name="proxy" id="proxy" value="1" <?php checked( $this->get_setting( '', 'proxy' ), 1 ); ?> />

						<p class="description">
							<?php
							echo esc_html(
								sprintf(
								/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot), %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
									__( 'If enabled, statuses sent to %1$s are performed through our proxy. This is useful if your ISP or host\'s country prevents access to %1$s.', 'wp-to-hootsuite' ),
									$this->base->plugin->account,
									$this->base->plugin->account
								)
							);
							?>
							<br />
							<?php esc_html_e( 'You may still need to use a VPN for initial Authentication when setting up the Plugin for the first time.', 'wp-to-hootsuite' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Image Settings -->
		<div id="image-settings" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Text to Image Settings', 'wp-to-hootsuite' ); ?></h3>
					<p class="description">
						<?php
						esc_html_e(
							'Provides options for automatically generating images from text, when a Status\' image option is set to Use Text to Image
                        and a status has Text to Image defined.',
							'wp-to-hootsuite'
						);
						?>
					</p>
				</header>

				<div class="wpzinc-option highlight">
					<div class="full">
						<h4>
							<?php
							esc_html_e( 'Need to automatically generate images?', 'wp-to-hootsuite' );
							?>
						</h4>

						<p>
							<?php
							printf(
								/* translators: Service name (Buffer, Hootsuite, SocialPilot) */
								esc_html__( '%s Pro provides options to generate images based on text, which are them submitted with your status message.', 'wp-to-hootsuite' ),
								esc_html( $this->base->plugin->displayName )
							);
							?>
						</p>

						<a href="<?php echo esc_url( $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Upgrade', 'wp-to-hootsuite' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<!-- Log Settings -->
		<div id="log-settings" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Log Settings', 'wp-to-hootsuite' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Provides options to enable logging, display logs on Posts and how long to keep logs for.', 'wp-to-hootsuite' ); ?>
					</p>
				</header>

				<div class="wpzinc-option">
					<div class="left">
						<label for="log_enabled"><?php esc_html_e( 'Enable Logging?', 'wp-to-hootsuite' ); ?></label>
					</div>
					<div class="right">
						<input type="checkbox" name="log[enabled]" id="log_enabled" value="1" <?php checked( $this->get_setting( 'log', '[enabled]' ), 1 ); ?> data-conditional="enable_logging" />
						<p class="description">
							<?php
							if ( $this->get_setting( 'log', '[enabled]' ) ) {
								printf(
									'%1$s <a href="%2$s">%3$s</a> %4$s',
									esc_html__( 'If enabled, the', 'wp-to-hootsuite' ),
									esc_html( admin_url( 'admin.php?page=' . $this->base->plugin->name . '-log' ) ),
									esc_html__( 'Plugin Logs', 'wp-to-hootsuite' ),
									esc_html(
										sprintf(
											/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
											__( 'will detail status(es) sent to %s, including any errors or reasons why no status(es) were sent.', 'wp-to-hootsuite' ),
											$this->base->plugin->account
										)
									)
								);
							} else {
								// Don't link "Plugin Log" text, as Logs are disabled so it won't show anything.
								echo esc_html(
									sprintf(
									/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
										__( 'If enabled, the Plugin Logs will detail status(es) sent to %1$s, including any errors or reasons why no status(es) were sent.', 'wp-to-hootsuite' ),
										$this->base->plugin->account
									)
								);
							}
							?>
						</p>
					</div>
				</div>

				<div id="enable_logging">
					<div class="wpzinc-option">
						<div class="left">
							<label for="log_display_on_posts"><?php esc_html_e( 'Display on Posts?', 'wp-to-hootsuite' ); ?></label>
						</div>
						<div class="right">
							<input type="checkbox" name="log[display_on_posts]" id="log_display_on_posts" value="1" <?php checked( $this->get_setting( 'log', '[display_on_posts]' ), 1 ); ?> />
			   
							<p class="description">
								<?php
								if ( $this->get_setting( 'log', '[enabled]' ) ) {
									printf(
										'%1$s <a href="%2$s">%3$s</a> %4$s',
										esc_html__( 'If enabled, a Log will be displayed when editing a Post.  Logs are always available through the', 'wp-to-hootsuite' ),
										esc_html( admin_url( 'admin.php?page=' . $this->base->plugin->name . '-log' ) ),
										esc_html__( 'Plugin Logs', 'wp-to-hootsuite' ),
										esc_html__( 'screen', 'wp-to-hootsuite' )
									);
								} else {
									// Don't link "Plugin Log" text, as Logs are disabled so it won't show anything.
									esc_html_e( 'If enabled, a Log will be displayed when editing a Post.  Logs are always available through the Plugin Logs screen.', 'wp-to-hootsuite' );
								}
								?>
							</p>
						</div>
					</div>

					<div class="wpzinc-option">
						<div class="left">
							<label for="log_level"><?php esc_html_e( 'Log Level', 'wp-to-hootsuite' ); ?></label>
						</div>
						<div class="right">
							<?php
							$log_levels_settings = $this->get_setting( 'log', 'log_level' );

							foreach ( $log_levels as $log_level => $label ) {
								?>
								<label for="log_level_<?php echo esc_attr( $log_level ); ?>">
									<input  type="checkbox" 
											name="log[log_level][]" 
											id="log_level_<?php echo esc_attr( $log_level ); ?>"
											value="<?php echo esc_attr( $log_level ); ?>"
											<?php echo ( in_array( $log_level, $log_levels_settings, true ) || $log_level === 'error' ? ' checked' : '' ); ?>
											<?php echo ( ( $log_level === 'error' ) ? ' disabled' : '' ); ?>
											/>

									<?php echo esc_html( $label ); ?>
								</label>
								<br />
								<?php
							}
							?>

							<p class="description">
								<?php esc_html_e( 'Defines which log results to save to the Log database. Errors will always be logged.', 'wp-to-hootsuite' ); ?>
							</p>
						</div>
					</div>

					<div class="wpzinc-option">
						<div class="left">
							<label for="log_preserve_days"><?php esc_html_e( 'Preserve Logs', 'wp-to-hootsuite' ); ?></strong>
						</div>
						<div class="right">
							<input type="number" name="log[preserve_days]" id="log_preserve_days" value="<?php echo esc_attr( $this->get_setting( 'log', '[preserve_days]' ) ); ?>" min="0" max="9999" step="1" />
							<?php esc_html_e( 'days', 'wp-to-hootsuite' ); ?>
					   
							<p class="description">
								<?php
								esc_html_e( 'The number of days to preserve logs for.  Zero means logs are kept indefinitely.', 'wp-to-hootsuite' );
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Repost Settings -->
		<div id="repost-settings" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Repost Settings', 'wp-to-hootsuite' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Provides options for when to run the WordPress Repost Cron Event on this WordPress installation.', 'wp-to-hootsuite' ); ?><br />
						<?php
						printf(
							/* translators: Service (Buffer, Hootsuite, SocialPilot) */
							esc_html__( 'When Post(s) are scheduled on %s will depend on the Repost Status Settings.', 'wp-to-hootsuite' ),
							esc_html( $this->base->plugin->displayName )
						);
						?>
					</p>
				</header>

				<div class="wpzinc-option highlight">
					<div class="full">
						<h4><?php esc_html_e( 'Revive Old Posts with Repost', 'wp-to-hootsuite' ); ?></h4>

						<p>
							<?php
							printf(
								/* translators: %1$s: Service (Buffer, Hootsuite, SocialPilot), %2$s: Service (Buffer, Hootsuite, SocialPilot) */
								esc_html__( 'Automatically schedule old Posts to %1$s with %2$s Pro.', 'wp-to-hootsuite' ),
								esc_html( $this->base->plugin->displayName ),
								esc_html( $this->base->plugin->displayName )
							);
							?>
						</p>

						<a href="<?php echo esc_url( $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Upgrade', 'wp-to-hootsuite' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<!-- User Access -->
		<div id="user-access" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'User Access', 'wp-to-hootsuite' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Optionally define which of your Post Types and connected social media account(s) should be available for configuration based on various WordPress User Roles.', 'wp-to-hootsuite' ); ?>
					</p>
				</header>

				<div class="wpzinc-option highlight">
					<div class="full">
						<h4><?php esc_html_e( 'Limit Post Types and Social Profiles by WordPress User Role', 'wp-to-hootsuite' ); ?></h4>

						<p>
							<?php
							printf(
								/* translators: %1$s: Service (Buffer, Hootsuite, SocialPilot) */
								esc_html__( '%s Pro provides options to limit which Post Types to show in the Settings screens, as well as prevent access to specific social media profiles linked to your Buffer account, on a per-WordPress Role basis.', 'wp-to-hootsuite' ),
								esc_html( $this->base->plugin->displayName )
							);
							?>
						</p>

						<a href="<?php echo esc_url( $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Upgrade', 'wp-to-hootsuite' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<!-- Custom Tags -->
		<div id="custom-tags" class="panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Custom Tags', 'wp-to-hootsuite' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'If your site uses Custom Fields, ACF or similar, you can specify additional tags to be added to the "Insert Tag" dropdown for each of your Post Types.  These can then be used by Users, instead of having to remember the template tag text to use.', 'wp-to-hootsuite' ); ?>
					</p>
				</header>

				<div class="wpzinc-option highlight">
					<div class="full">
						<h4><?php esc_html_e( 'Need to define your own Tags to use in status messages?', 'wp-to-hootsuite' ); ?></h4>

						<p>
							<?php
							printf(
								/* translators: %1$s: Service (Buffer, Hootsuite, SocialPilot) */
								esc_html__( '%s Pro provides options to define Custom Field / ACF Tags, which will then populate with Post data when used in status messages.  Tags also appear in the Insert Tags dropdown.', 'wp-to-hootsuite' ),
								esc_html( $this->base->plugin->displayName )
							);
							?>
						</p>

						<a href="<?php echo esc_url( $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Upgrade', 'wp-to-hootsuite' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
