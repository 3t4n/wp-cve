<?php
/**
 * @package Admin
 * @sub-package Admin CatchIDs Display
 */
?>
<?php include( 'header.php' ); ?>

	<div id="catch-web-tools" aria-label="Main Content">
		<div class="content-wrapper" >
			<div class="header">
				<h3><?php _e( 'Dashboard', 'catch-web-tools' ); ?></h3>
			</div> <!-- .header -->
			<div class="content">

				<div class="module-container">
					<div class="module-wrap">
						<div id="module-webmaster-tools" class="catch-modules short-desc">


							<div class="module-header">
								<h3><?php _e( 'Webmaster Tools', 'catch-web-tools' ); ?></h3>
							</div> <!-- .module-header -->

							<div class="module-content">
								<p>
									<?php _e( 'Webmaster Tools gives you an option to add in the Site Verfication Code and Header and Footer Script required to manage your site.', 'catch-web-tools' ); ?>
								</p>
								<div class="catch-actions">
									<form method="post" action="options.php">
										<?php
										settings_fields( 'webmaster-tools-group' );

										$settings = catchwebtools_get_options( 'catchwebtools_webmaster' );

										if ( ! empty( $settings['status'] ) && $settings['status'] ) {
											echo '<input type="hidden" value="0"  name="catchwebtools_webmaster[status]"/>';

											submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
										} else {
											echo '<input type="hidden" value="1"  name="catchwebtools_webmaster[status]"/>';

											submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
										}
										?>

										<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-webmasters' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
									</form>
								</div>
							</div> <!-- .module-content -->
						</div><!-- #module-webmaster-tools -->
					</div><!-- .module-wrap -->

					<?php
					/**
					 * Do not show Custom CSS option from WordPress 4.7 onwards
					 */
					if ( ! function_exists( 'wp_update_custom_css_post' ) ) {
						?>

						<div class="module-wrap">

							<div id="module-customcss" class="catch-modules short-desc">

								<div class="module-header">
									<h3><?php _e( 'Custom CSS', 'catch-web-tools' ); ?></h3>
								</div>

								<div class="module-content">
									<p>
										<?php _e( 'Custom CSS gives you an option to add in your CSS to your WordPress site without building Child Theme. You can just add your Custom CSS and save, it will show up in the frontend head section. Leave it blank if it is not needed.', 'catch-web-tools' ); ?>
									</p>
									<div class="catch-actions">
										<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-custom-css' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
									</div>
								</div><!-- .module-content -->
							</div><!-- #module-customcss -->
						</div><!-- .module-wrap -->
					<?php } ?>

					<div class="module-wrap">

						<div id="module-catchids" class="catch-modules short-desc">

							<div class="module-header">
								<h3><?php _e( 'Catch IDs', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">

								<p>
									<?php _e( 'Catch IDs will show Post ID, Page ID, Media ID, Links ID, Category ID, Tag ID and UserID in the respective admin section tables.', 'catch-web-tools' ); ?>
								</p>

								<div class="catch-actions">
									<?php
										/* Disable Option if Catch IDs plugin is active */
									if ( is_plugin_active( 'catch-ids/catch-ids.php' ) ) :
										?>
										<p class="notice notice-warning">
											<?php _e( 'This module is currently disabled since Catch IDs standalone plugin is already active on your site.', 'catch-web-tools.' ); ?>
										</p>
									<?php else : ?>
										<form method="post" action="options.php">
											<?php
											settings_fields( 'catchids-settings-group' );

											$settings = catchwebtools_get_options( 'catchwebtools_catchids' );

											if ( ! empty( $settings['status'] ) && $settings['status'] ) {
												echo '<input type="hidden" value="0"  name="catchwebtools_catchids[status]"/>';

												submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
											} else {
												echo '<input type="hidden" value="1"  name="catchwebtools_catchids[status]"/>';

												submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
											}
											?>
											<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-catch-ids' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
										</form>
									<?php endif; ?>
								</div><!-- .catch-actions -->
							</div><!-- .catch-content -->
						</div><!-- #module-catchids -->
					</div><!-- .module-wrap -->


					<div class="module-wrap">
						<div id="module-socialicons" class="catch-modules long-desc">

							<div class="module-header">
								<h3><?php _e( 'Social Icons', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">
								<p>
									<?php _e( 'Social Icons gives you an option to add in your Social Profiles.', 'catch-web-tools' ); ?>
								</p>
								<p>
									<?php _e( 'You can add Social Icons by adding in Widgets in your Sidebar or by adding in Shortcode in your Page/Post Content or by adding the function in your template files.', 'catch-web-tools' ); ?>
								</p>
								<div class="catch-actions">
									<form method="post" action="options.php">
										<?php
										settings_fields( 'social-icons-group' );

										$settings = catchwebtools_get_options( 'catchwebtools_social' );

										if ( ! empty( $settings['status'] ) && $settings['status'] ) {
											echo '<input type="hidden" value="0"  name="catchwebtools_social[status]"/>';

											submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
										} else {
											echo '<input type="hidden" value="1"  name="catchwebtools_social[status]"/>';

											submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
										}
										?>

										<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-social-icons' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
									</form>
								</div>
							</div>
						</div><!-- #module-socialicons -->
					</div><!-- .module-wrap -->

					<div class="module-wrap">
						<div id="module-opengraph" class="catch-modules long-desc">
							<div class="module-header">
							<h3><?php _e( 'Open Graph', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">
							<p><?php _e( 'The Open Graph protocol enables your site to become a rich object in a social graph. For instance, this is used on Facebook to allow any web page to have the same functionality as any other object on Facebook.', 'catch-web-tools' ); ?>
							</p>
							<div class="catch-actions">
								<form method="post" action="options.php">
									<?php
									settings_fields( 'opengraph-settings-group' );

									$settings = catchwebtools_get_options( 'catchwebtools_opengraph' );

									if ( ! empty( $settings['status'] ) && $settings['status'] ) {
										echo '<input type="hidden" value="0"  name="catchwebtools_opengraph[status]"/>';

										submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
									} else {
										echo '<input type="hidden" value="1"  name="catchwebtools_opengraph[status]"/>';

										submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
									}
									?>

									<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-opengraph' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
								</form>
								</div>
							</div>
						</div><!-- #module-opengraph -->
					</div><!-- .module-wrap -->

					<div class="module-wrap">
						<div id="module-seo" class="catch-modules long-desc">
							<div class="module-header">
							<h3><?php _e( 'SEO ( BETA Version )', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">
								<p>
									<?php _e( 'SEO is in beta version. SEO can be used to add SEO meta tags to Homepage, specific Pages or Posts and Categories page. This section adds SEO meta data to site\'s section.', 'catch-web-tools' ); ?>
								</p>
								<div class="catch-actions">
									<form method="post" action="options.php">
										<?php
										settings_fields( 'seo-settings-group' );

										$settings = catchwebtools_get_options( 'catchwebtools_seo' );

										if ( ! empty( $settings['status'] ) && $settings['status'] ) {
											echo '<input type="hidden" value="0"  name="catchwebtools_seo[status]"/>';

											submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
										} else {
											echo '<input type="hidden" value="1"  name="catchwebtools_seo[status]"/>';

											submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
										}
										?>

										<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-seo' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
									</form>
								</div>
							</div><!-- #module-content -->
						</div><!-- #module-seo -->
					</div><!-- .module-wrap -->

					<div class="module-wrap">
						<div id="module-catchupdater" class="catch-modules short-desc">
							<div class="module-header">
								<h3><?php _e( 'Catch Updater', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">

								<p>
									<?php _e( 'Catch Updater is a simple and lightweight WordPress Theme Updater and Plugin Module, which enables you to update your themes and plugins easily using WordPress Admin Panel.', 'catch-web-tools' ); ?>
								</p>

								<div class="catch-actions">
									<form method="post" action="options.php">
										<?php
											settings_fields( 'catchupdater-settings-group' );

											$settings = catchwebtools_get_options( 'catchwebtools_catch_updater' );

											global $wp_version;
										if ( version_compare( $wp_version, '5.5', '<' ) ) :
											if ( ! empty( $settings['status'] ) && $settings['status'] ) {
												echo '<input type="hidden" value="0"  name="catchwebtools_catch_updater[status]"/>';

												submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
												echo '&nbsp;';

												echo '<a class="button button-secondary" href="' . esc_url( admin_url( 'theme-install.php?upload' ) ) . '">' . esc_html__( 'Upload Theme', 'catch-web-tools' ) . '</a>';
												echo '&nbsp;';
												echo '<a class="button button-secondary" href="' . esc_url( admin_url( 'plugin-install.php?upload' ) ) . '">' . esc_html__( 'Upload Plugin', 'catch-web-tools' ) . '</a>';
											} else {
												echo '<input type="hidden" value="1"  name="catchwebtools_catch_updater[status]"/>';

												submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
											}
											else :
												?>
												<p class="notice notice-warning">
													<?php
													printf( esc_html__( 'This module has been disabled by default since WordPress version 5.5 and above. For further detail, please visit this %1$slink%2$s', 'essential-content-types-pro' ), '<a target="_blank" href="' . esc_url( 'https://catchplugins.com/news/catch-updater-notice-wordpress-5-5/' ) . '">', '</a>' );
													?>
												</p>
												<?php
											endif;
											?>
									</form>
								</div><!-- .catch-actions -->
							</div><!-- .catch-content -->
						</div><!-- #module-catchids -->
					</div><!-- .module-wrap -->

					<div class="module-wrap">
						<!--#module-to-top-->
						<div id="module-to-top" class="catch-modules short-desc">
							<div class="module-header">
							<h3><?php _e( 'To Top', 'catch-web-tools' ); ?></h3>
						</div>

						<div class="module-content">
							<p>
								<?php _e( 'To Top plugin allows the visitor as well as admin to easily scroll back to the top of the page, with fully customizable options and ability to use image.', 'catch-web-tools' ); ?>
							</p>
							<div class="catch-actions">
								<?php
										/* Disable Option if Catch IDs plugin is active */
								if ( is_plugin_active( 'to-top/to-top.php' ) ) :
									?>
										<p class="notice notice-warning">
											<?php _e( 'This module is currently disabled since To Top standalone plugin is already active on your site.', 'catch-web-tools.' ); ?>
										</p>
									<?php else : ?>
										<form method="post" action="options.php">
											<?php
												settings_fields( 'to-top-settings-group' );

												$settings = catchwebtools_get_options( 'catchwebtools_to_top_options' );

											if ( ! empty( $settings['status'] ) && $settings['status'] ) {
												echo '<input type="hidden" value="0"  name="catchwebtools_to_top_options[status]"/>';

												submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
											} else {
												echo '<input type="hidden" value="1"  name="catchwebtools_to_top_options[status]"/>';

												submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
											}
											?>

											<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=catch-web-tools-to-top' ) ); ?>"><?php _e( 'Configure', 'catch-web-tools' ); ?></a>
										</form>
								<?php endif; ?>
							</div><!-- .module-actions -->
						</div><!-- .module-content -->
						</div><!-- #module-to-top -->
					</div><!-- .module-wrap -->

					<div class="module-wrap">
						<div id="module-catchupdater" class="catch-modules short-desc">
							<div class="module-header">
								<h3><?php _e( 'Big Image Size Threshold', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">

								<p>
									<?php _e( 'This allows you to disable the default Automatic Image Optimization and scaling feature from WordPress.', 'catch-web-tools' ); ?>
								</p>

								<div class="catch-actions">
									<form method="post" action="options.php">
										<?php
											settings_fields( 'big-image-size-threshold-settings-group' );

											$settings = catchwebtools_get_options( 'catchwebtools_big_image_size_threshold' );

										if ( ! empty( $settings['status'] ) && $settings['status'] ) {
											echo '<input type="hidden" value="0"  name="catchwebtools_big_image_size_threshold[status]"/>';

											submit_button( __( 'Deactivate', 'catch-web-tools' ), 'secondary', 'submit', false );
											echo '&nbsp;';
										} else {
											echo '<input type="hidden" value="1"  name="catchwebtools_big_image_size_threshold[status]"/>';

											submit_button( __( 'Activate', 'catch-web-tools' ), 'primary', 'submit', false );
										}
										?>
									</form>
								</div><!-- .catch-actions -->
							</div><!-- .catch-content -->
						</div><!-- #module-catchids -->
					</div><!-- .module-wrap -->

				</div><!-- .module-container -->
			</div><!-- .content -->
		</div><!-- .content-wrapper -->
		<div id="ctp-switch" class="content-wrapper col-3 catch-web-tools-main">
			<div class="header">
				<h2><?php esc_html_e( 'Catch Themes & Catch Plugins Tabs', 'catch-web-tools' ); ?></h2>
			</div> <!-- .Header -->
			<div class="content">
				<p><?php echo esc_html__( 'If you want to turn off Catch Themes & Catch Plugins tabs option in Add Themes and Add Plugins page, please uncheck the following option.', 'catch-web-tools' ); ?>
				</p>
				<table>
					<tr>
						<td>
						<?php echo esc_html__( 'Turn On Catch Themes & Catch Plugin tabs', 'catch-web-tools' ); ?>
						</td>
						<td>
						<?php $ctp_options = ctp_get_options(); ?>
							<div class="module-header <?php echo $ctp_options['theme_plugin_tabs'] ? 'active' : 'inactive'; ?>">
								<div class="switch">
									<input type="hidden" name="ctp_tabs_nonce" id="ctp_tabs_nonce" value="<?php echo esc_attr( wp_create_nonce( 'ctp_tabs_nonce' ) ); ?>" />
									<input type="checkbox" id="ctp_options[theme_plugin_tabs]" class="ctp-switch" rel="theme_plugin_tabs" <?php checked( true, $ctp_options['theme_plugin_tabs'] ); ?> >
									<label for="ctp_options[theme_plugin_tabs]"></label>
								</div>
								<div class="loader"></div>
							</div>
						</td>
					</tr>
				</table>
				</div>
			</div><!-- #ctp-switch -->

		<div class="content-wrapper">
			<div class="content">
				<div class="module-container">
					<div class="module-wrap catch-modules-long">
						<div id="module-securi-tips" class="catch-modules">
							<div class="module-header">
								<h3><?php _e( 'Security Tips', 'catch-web-tools' ); ?></h3>
							</div>

							<div class="module-content">
								<?php
								if ( username_exists( 'admin' ) ) {
									echo '<p>' . __( 'Caution!!! A user with username: admin exists, need to rename this username or remove it', 'catch-web-tools' ) . '</p>';
								} else {
									echo '<p>' . __( 'Congratulations!!! You do not have any users with admin as username', 'catch-web-tools' ) . '</p>';
								}
								?>

								<?php
									global $wpdb;

								if ( 'wp_' == $wpdb->prefix ) {
									echo '<p>' . __( 'Caution!!! WordPress Table Prefix is "wp_", need to change this prefix', 'catch-web-tools' ) . '</p>';
								} else {
									echo '<p>' . __( 'Congratulations!!! WordPress Table Prefix is not "wp_"', 'catch-web-tools' ) . '</p>';
								}

								?>

								<?php
									global $wp_version;

									//Get latest WordPress version. More info: wp-admin/includes/updates.php
									$update = get_core_updates();

								if ( version_compare( $update[0]->current, $wp_version, '<=' ) ) {
									echo '<p>' . __( 'Congratulations!!! Your WordPress version is the latest.', 'catch-web-tools' ) . '</p>';
								} else {
									echo '<p>' . sprintf( __( 'Caution!!! You do not have the current version of WordPress installed. The Current version is %1$s. Your installation version is %2$s Please update it %3$shere%4$s.', 'catch-web-tools' ), $wp_version, $update[0]->current, '<a href=' . esc_url( admin_url( 'update-core.php' ) ) . '>', '</a>' ) . '</p>';
								}

									//echo print_r( get_core_updates() ) ;
								?>
							</div><!-- .module-content -->
						</div><!-- #module-securi-tips -->
					</div><!-- .module-wrap -->
				</div><!-- .module-container -->
			</div><!-- .content -->
		</div><!-- .content-wrapper -->

	</div><!-- #customcss -->

<?php include( 'main-footer.php' ); ?>
