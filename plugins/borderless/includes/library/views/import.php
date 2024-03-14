<?php

namespace LIBRARY;

$plugin_installer = new PluginInstaller();
$theme_plugins    = $plugin_installer->get_theme_plugins();
$theme            = wp_get_theme();
?>

<div class="borderless-library borderless-library--import">

	<?php echo wp_kses_post( ViewHelpers::plugin_header_output() ); ?>

	<div class="container">

		<div class="row justify-content-center">
			<div class="borderless-library-import__content col-9 my-5 p-3">
				<div class="borderless-library-import__required-plugins library-install-plugins-content js-library-install-plugins-content">
					<div class="library-install-plugins-content-content">
						<?php if ( empty( $theme_plugins ) ) { ?>
							<div class="library-content-notice">
								<p>
									<?php esc_html_e( 'All required/recommended plugins are already installed. You can import your demo content.' , 'borderless' ); ?>
								</p>
							</div>
						<?php } else { ?>

							<div class="borderless-library-import__plugins list-group list-group-radio d-grid gap-2 border-0 w-auto">
							<h3 class="text-center mb-5"><?php esc_html_e( 'Required Plugins', 'borderless' ); ?></h3>
							<?php foreach ( $theme_plugins as $plugin ) { ?>
								<?php $is_plugin_active = $plugin_installer->is_plugin_active( $plugin['slug'] ); ?>
								<div class="borderless-library-import__plugin position-relative plugin-item-<?php echo esc_attr( $plugin['slug'] ); ?><?php echo $is_plugin_active ? ' plugin-item--active' : ''; ?><?php echo ! empty( $plugin['required'] ) ? ' plugin-item--required' : ''; ?>" for="library-<?php echo esc_attr( $plugin['slug'] ); ?>-plugin">
									<div class="plugin-item-content list-group-item py-3 pe-5">

										<div class="plugin-item-content-title">
											<strong class="fw-semibold"><?php echo esc_html( $plugin['name'] ); ?></strong>
										</div>

										<?php if ( ! empty( $plugin['description'] ) ) { ?>
											<span class="d-block small opacity-75">
												<?php echo wp_kses_post( $plugin['description'] ); ?>
										</span>
										<?php } ?>

										<div class="plugin-item-error js-library-plugin-item-error"></div>
										<div class="plugin-item-info js-library-plugin-item-info"></div>

										<span class="borderless-library-import__plugin-item-checkbox">
											<input type="checkbox" class="borderless-library-import__plugin-item-checkbox-input form-check-input position-absolute top-50 end-0 me-3 fs-5" id="library-<?php echo esc_attr( $plugin['slug'] ); ?>-plugin" name="<?php echo esc_attr( $plugin['slug'] ); ?>" <?php checked( ! empty( $plugin['preselected'] ) || ! empty( $plugin['required'] ) || $is_plugin_active ); ?><?php disabled( $is_plugin_active ); ?>>
											<span class="borderless-library-import__plugin-checkbox position-absolute top-50 end-0">
												<?php if ( ! empty( $plugin['required'] ) ) { ?>
													<i class="bi bi-lock-fill me-5"></i>
												<?php } ?>
												<div class="borderless-library-import__plugin-loading spinner-border" role="status">
													<span class="visually-hidden">Loading...</span>
												</div>
											</span>
										</span>

									</div>

								</div>
							<?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="borderless-library-import__content-buttons d-grid gap-2 col-6 mx-auto mt-5">
						<a href="<?php echo esc_url( $this->get_plugin_settings_url() ); ?>" class="borderless-library-import__content-button-cancel btn btn-lg mx-2">
							<?php esc_html_e( 'Cancel' , 'borderless' ); ?>
						</a>
						<a href="#" class="borderless-library-import__content-button-import btn btn-lg btn-dark mx-2 js-library-install-plugins-before-import"><?php esc_html_e( 'Import' , 'borderless' ); ?></a>
					</div>
				</div>

				<div class="borderless-library-import__installing library-importing js-library-importing">
						<h3 class="text-center"><?php esc_html_e( 'We Are Building Your Website' , 'borderless' ); ?></h3>
						<div class="borderless-library-import__installing-icon-container">
							<a href="#" class="borderless-library-import__installing-icon" target="_blank">
								<i class="glyphicon glyphicon-play whiteText" aria-hidden="true"></i>
								<span class="ripple borderless-library-import__installing-icon"></span>
								<span class="ripple borderless-library-import__installing-icon"></span>
								<span class="ripple borderless-library-import__installing-icon"></span>
							</a>
						</div>
						<p class="text-center"><?php esc_html_e( 'Installing...' , 'borderless' ); ?></p>
				</div>

				<div class="borderless-library-import__imported text-center library-imported js-library-imported">

					<h3 class="text-center mb-5"><?php esc_html_e( 'Congratulations! &#127881;' , 'borderless' ); ?></h3>
					
					<div class="js-library-ajax-response-subtitle">
						<p>
							<?php esc_html_e( 'Your Website is ready.' , 'borderless' ); ?>
						</p>
					</div>

					<div class="library-imported-content">
						<div class="library__response  js-library-ajax-response"></div>
					</div>

					<div class="borderless-library-import__content-buttons d-grid gap-2 col-6 mx-auto mt-5">
						<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" target="_blank" class="borderless-library-import__content-button-cancel btn btn-lg mx-2"><?php esc_html_e( 'Customize' , 'borderless' ); ?></a>
						<a href="<?php echo esc_url( get_home_url() ); ?>" target="_blank" class="borderless-library-import__content-button-import btn btn-lg btn-dark mx-2"><?php esc_html_e( 'Visit Site' , 'borderless' ); ?></a>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
