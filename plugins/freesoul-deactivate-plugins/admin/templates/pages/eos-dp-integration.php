<?php
/**
 * Template Plugin Integration.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate in integration.
function eos_dp_integration_callback() {
	if ( ! current_user_can( 'activate_plugins' ) && function_exists( 'eos_dp_active_plugins' ) ) {
		?>
		<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php
		return;
	}
	$plugins_integration = eos_dp_plugins_integration();
	if ( ! isset( $_GET['int_plugin'] ) ) {
		$int_plugin = 'wordpress-core';
	} else {
		$int_plugin = sanitize_text_field( $_GET['int_plugin'] );
	}
	if ( ! isset( $plugins_integration[ $int_plugin ] ) ) {
		printf( esc_html__( 'No integration available for %s', 'freesoul-deactivate-plugins' ), esc_html( strtoupper( str_replace( '-', ' ', $int_plugin ) ) ) );
		return;
	}
	$plugin_info = $plugins_integration[ $int_plugin ];
	$is_active   = $plugin_info['is_active'];
	if ( ! $is_active ) {
		printf( esc_html__( '%s is not active', 'freesoul-deactivate-plugins' ), esc_html( strtoupper( str_replace( '-', ' ', $int_plugin ) ) ) );
		return;
	}
	$active_plugins            = eos_dp_active_plugins();
	$plugins_actions           = eos_dp_get_option( 'eos_dp_integration_actions' );
	$integration_actions_theme = eos_dp_get_option( 'eos_dp_integretion_actions_theme' );
	$n                         = count( $active_plugins );
	wp_nonce_field( 'eos_dp_integration_actions_setts', 'eos_dp_integration_actions_setts' );
	eos_dp_navigation();
	?>
	<section id="eos-dp-by-integration-section" class="eos-dp-section">
		<div class="eos-dp-margin-top-32">
			<h2><?php printf( esc_html__( 'Uncheck the plugins you want to deactivate on specific actions fired by %s.', 'freesoul-deactivate-plugins' ), esc_html( strtoupper( str_replace( '-', ' ', $int_plugin ) ) ) ); ?></h2>
			<?php do_action( 'eos_dp_before_wrapper' ); ?>
			<div id="eos-dp-wrp">
				<div>
					<input id="eos-dp-ajax-desc" type="radio" value="description" name="eos-dp-ajax-display" checked />
					<label style="display:inline-block" for="eos-dp-ajax-desc"><?php esc_html_e( 'Show action descriptions', 'freesoul-deactivate-plugins' ); ?></label>
					<span>&nbsp;&nbsp;</span><input id="eos-dp-ajax-slug" type="radio" value="slug" name="eos-dp-ajax-display" />
					<label style="display:inline-block" for="eos-dp-ajax-value"><?php esc_html_e( 'Show action slugs', 'freesoul-deactivate-plugins' ); ?></label>
				</div>
				<?php
				$row = 1;
				?>
				<table id="eos-dp-setts"  data-zoom="1">
				<?php
				eos_dp_table_head();
				eos_dp_plugins_slider_row();
				$plugins_by_dirs = isset( $GLOBALS['eos_dp_plugins_by_dirs'] ) ? $GLOBALS['eos_dp_plugins_by_dirs'] : eos_dp_get_plugins();

				foreach ( $plugin_info['ajax_actions'] as $action => $arr ) {
					$description    = isset( $arr['description'] ) ? $arr['description'] : '';
					$custom_default = isset( $arr['default'] ) && 'disabled' === $arr['default'] ? $active_plugins : array();
					$values         = isset( $plugins_actions[ $action ] ) ? explode( ',', $plugins_actions[ $action ] ) : $custom_default;
					?>
					<tr class="eos-dp-integration-row eos-dp-post-row" data-integration="<?php echo esc_attr( $action ); ?>">
						<td class="eos-dp-post-name-wrp">
							<span class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate all plugins for %s', 'freesoul-deactivate-plugins' ), esc_attr( $description ) ); ?>" data-row="<?php echo esc_attr( $row ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
							<span class="eos-dp-ajax-desc"><?php echo esc_html( $description ); ?></span>
							<span class="eos-dp-ajax-slug eos-hidden"><?php echo esc_html( $action ); ?></span>
							<?php
							if ( isset( $arr['notes'] ) && '' !== $arr['notes'] ) {
								?>
							<span class="dashicons dashicons-info-outline" title="<?php echo esc_attr( $arr['notes'] ); ?>"></span>
								<?php
							}
							?>
							<div class="eos-dp-actions">
								<a title="<?php esc_attr_e( 'Copy this row settings', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-copy" href="#"><span class="dashicons dashicons-admin-page"></span></a>
								<a title="<?php esc_attr_e( 'Paste last copied row settings', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-paste" href="#"><span class="dashicons dashicons-category"></span></a>
							</div>
						</td>
						<?php
						$k = 0;
						foreach ( $active_plugins as $plugin ) {
							if ( in_array( $plugin, array_keys( $plugins_by_dirs ) ) ) {
								$extra_class = $plugin === EOS_DP_PLUGIN_BASE_NAME ? ' eos-hidden' : '';
								?>
								<td<?php echo dirname( $plugin ) === $int_plugin ? ' style="display:none"' : ''; ?> class="center
											  <?php
												echo ! in_array( $plugin, $values ) ? ' eos-dp-active' : '';
												echo esc_attr( $extra_class );
												?>
												" data-path="<?php echo esc_attr( $plugin ); ?>">
									<div class="eos-dp-td-chk-wrp eos-dp-td-integration-chk-wrp">
										<input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ) . '-' . esc_attr( $action ); ?>" data-checked="<?php echo in_array( $plugin, $values ) ? 'checked' : 'not-checked'; ?>" type="checkbox"<?php echo in_array( $plugin, $values ) ? ' checked' : ''; ?> />
									</div>
								</td>
								<?php
								++$k;
							}
						}
						?>
						<td class="center<?php echo ! isset( $integration_actions_theme[ $action ] ) || $integration_actions_theme[ $action ] ? ' eos-dp-active' : ''; ?>" >
							<div class="eos-dp-td-chk-wrp eos-dp-td-integration-chk-wrp">
								<input class="eos-dp-row-theme eos-dp-col-<?php echo esc_attr( $k + 1 ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ) . '-' . esc_attr( $action ); ?>" data-checked="checked" type="checkbox" checked />
							</div>
						</td>
					</tr>
					<?php
				}
				?>
				</table>
			</div>
		</div>
		<div style="margin-top:64px">
			<h2><?php esc_html_e( 'For developers.', 'freesoul-deactivate-plugins' ); ?></h2>
			<p>Read <a href="https://freesoul-deactivate-plugins.com/how-deactivate-plugiins-on-specific-pages/for-developers#custom-ajax-action-in-fdp-settings" target="_blank">here</a> to learn how to add the ajax actions of a plugin or theme</p>
		</div>
		<?php eos_dp_save_button(); ?>
	</section>
	<?php
}
