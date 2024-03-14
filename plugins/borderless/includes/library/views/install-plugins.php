<?php

namespace LIBRARY;

$plugin_installer = new PluginInstaller();
?>

<div class="library library--install-plugins">

	<?php echo wp_kses_post( ViewHelpers::plugin_header_output() ); ?>

	<div class="library__content-container">

		<div class="library__admin-notices js-library-admin-notices-container"></div>

		<div class="library__content-container-content">
			<div class="library__content-container-content--main">
				<div class="library-install-plugins-content">
					<div class="library-install-plugins-content-header">
						<h2><?php esc_html_e( 'Install Recommended Plugins', 'borderless' ); ?></h2>
						<p>
							<?php esc_html_e( 'Want to use the best plugins for the job? Here is the list of awesome plugins that will help you achieve your goals.', 'borderless' ); ?>
						</p>
					</div>
					<div class="library-install-plugins-content-content">
						<?php foreach ( $plugin_installer->get_partner_plugins() as $plugin ) : ?>
							<?php $is_plugin_active = $plugin_installer->is_plugin_active( $plugin['slug'] ); ?>
							<label class="plugin-item plugin-item-<?php echo esc_attr( $plugin['slug'] ); ?><?php echo $is_plugin_active ? ' plugin-item--active' : ''; ?>" for="library-<?php echo esc_attr( $plugin['slug'] ); ?>-plugin">
								<div class="plugin-item-content">
									<div class="plugin-item-content-title">
										<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
									</div>
									<?php if ( ! empty( $plugin['description'] ) ) : ?>
										<p>
											<?php echo wp_kses_post( $plugin['description'] ); ?>
										</p>
									<?php endif; ?>
									<div class="plugin-item-error js-library-plugin-item-error"></div>
									<div class="plugin-item-info js-library-plugin-item-info"></div>
								</div>
							</label>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="library__content-container-content--side">
				<?php echo wp_kses_post( ViewHelpers::small_theme_card() ); ?>
			</div>
		</div>

	</div>
</div>
