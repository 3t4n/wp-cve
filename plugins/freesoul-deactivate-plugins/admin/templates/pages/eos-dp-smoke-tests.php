<?php
/**
 * Template Smoke Tests.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate by archive settings page.
function eos_dp_smoke_tests_callback() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		?>
		<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php
		return;
	}
	eos_dp_alert_plain_permalink();
	eos_dp_navigation();
	$active_plugins = eos_dp_active_plugins();
	$plugins        = eos_dp_get_plugins();
	?>
	<style id="fdp-tests-css">#eos-dp-plugins-comparison img{width:130px;height:20px;margin:0 10px}</style>
	<div id="eos-dp-plugin-info">
		<h2><?php esc_html_e( 'Smoke Tests on the last plugin versions', 'freesoul-deactivate-plugins' ); ?></h2>
		<div id="eos-dp-plugins-comparison" style="clear:both">
			<table class="table table-striped">
				<tbody>
					<?php
					$ap = 1;
					foreach ( $active_plugins as $plugin_slug ) {
						?>
					<tr>
						<td><?php echo esc_html( strtoupper( eos_dp_get_plugin_name_by_slug( $plugin_slug ) ) ); ?></td>
						<td>
							<a target="_blank" rel="noopener" href="https://plugintests.com/plugins/<?php echo esc_attr( dirname( $plugin_slug ) ); ?>/latest">
								<img loading="<?php $ap > 15 ? 'lazy' : 'eager'; ?>" alt="<?php esc_html_e( 'Test not found', 'freesoul-deactivate-plugins' ); ?>" onerror="this.src='<?php echo esc_url( EOS_DP_PLUGIN_URL . '/admin/assets/img/test-not-found.png' ); ?>';" src="https://plugintests.com/plugins/<?php echo esc_attr( dirname( $plugin_slug ) ); ?>/wp-badge.svg">
							</a>
							<a target="_blank" rel="noopener" href="https://plugintests.com/plugins/<?php echo esc_attr( dirname( $plugin_slug ) ); ?>/latest">
								<img loading="<?php $ap > 15 ? 'lazy' : 'eager'; ?>" alt="<?php esc_html_e( 'Test not found', 'freesoul-deactivate-plugins' ); ?>" onerror="this.src='<?php echo esc_url( EOS_DP_PLUGIN_URL . '/admin/assets/img/test-not-found.png' ); ?>';" src="https://plugintests.com/plugins/<?php echo esc_attr( dirname( $plugin_slug ) ); ?>/php-badge.svg">
							</a>
						</td>
					</tr>
						<?php
						++$ap;
					}
					?>
				</tbody>
			</table>
		</div>
		<p><?php echo wp_kses_post( sprintf( __( 'Tests performed by %1$s%2$s%3$s', 'freesoul-deactivate-plugins' ), '<a href="https://plugintests.com/" target="_blank" rel="noopener" >', 'https://plugintests.com/', '</a>' ) ); ?></p>
		<div id="eos-dp-go-to-top" class="hover right" style="margin:48px 6px 0 6px;z-index:999"><span title="<?php esc_attr_e( 'Go to top', 'freesoul-deactivate-plugins' ); ?>" style="background:#fff;padding:10px" class="dashicons dashicons-arrow-up-alt"></span></div>
	</div>
	<?php
}
