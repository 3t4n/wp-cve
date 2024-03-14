<?php
/**
 * Template Firing Order.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for firing order settings page.
function eos_dp_firing_order_callback() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		?>
		<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php
		return;
	}
	$untouchables = array( 'query-monitor/query-monitor.php' );
	$icons_url    = 'https://ps.w.org/';
	wp_nonce_field( 'eos_dp_firing_order_setts', 'eos_dp_firing_order_setts' );
	eos_dp_alert_plain_permalink();
	eos_dp_navigation();
	$plugins         = eos_dp_active_plugins();
	$plugins_by_dirs = eos_dp_get_plugins();
	?>
	<style id="fdp-firing-order-css">.eos-dp-firing-order.ui-sortable .eos-dp-plugin{margin:15px 0;padding:5px}</style>
	<section id="eos-dp-by-firing_order-section" class="eos-dp-section">
		<h2><?php esc_html_e( 'You can change the plugins firing order dragging and moving the plugins.', 'freesoul-deactivate-plugins' ); ?></h2>
		<p><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Plugins should use action hooks to run code in the desired order. Change the firing order if you really don\'t have other cleaner solutions.', 'freesoul-deactivate-plugins' ); ?></p>
		<p><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Remember that every time you activate a new plugin, you may need to change and save again the firing order according with your needs.', 'freesoul-deactivate-plugins' ); ?></p>
		<div class="eos-dp-firing-order" style="margin-top:32px">
			<?php
			foreach ( $plugins as $plugin ) {
				if ( EOS_DP_PLUGIN_BASE_NAME === $plugin || in_array( $plugin, array_keys( $plugins_by_dirs ) ) ) {
					$details_url = add_query_arg(
						array(
							'tab'         => 'plugin-information',
							'plugin'      => dirname( $plugin ),
							'TB_iframe'   => true,
							'eos_dp'      => $plugin,
							'eos_dp_info' => 'true',
						),
						admin_url( 'plugin-install.php' )
					);
					$plugin_name = strtoupper( eos_dp_get_plugin_name_by_slug( $plugin ) );
					?>
				<div class="eos-dp-plugin<?php echo in_array( $plugin, $untouchables ) ? ' eos-dp-not-touchable' : ''; ?>" data-path="<?php echo esc_attr( $plugin ); ?>">
					<span class="dashicons dashicons-move"></span>
					<span class="eos-dp-fo-plugin-wrp">
						<span class="dashicons dashicons-admin-plugins"></span>
						<span><a class="eos-dp-no-decoration" title="<?php printf( esc_attr__( 'View details of %s', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" href="<?php echo esc_url( $details_url ); ?>" target="_blank"><?php echo esc_html( $plugin_name ); ?></a></span>
					</span>
				</div>
					<?php
				}
			}
			?>
		</div>
		<?php eos_dp_save_button(); ?>
	</section>
	<?php
}
