<?php
/**
 * Template Table Head.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// It displays the table head for the plugin filters
function eos_dp_table_head( $reset = false ) {
	$plugins                           = eos_dp_get_plugins();
	$GLOBALS['eos_dp_plugins_by_dirs'] = $plugins;
	$active_plugins                    = eos_dp_active_plugins();
	$plugin_slug_names                 = eos_dp_get_option( 'fdp_plugin_slug_names' );
	?>
	<tr id="eos-dp-table-head">
		<th class="fdp-legend" style="vertical-align:bottom;border-style:none;text-align:initial;padding-left:20px;margin-left:-20px">
			<?php do_action( 'fdp_table_head_first_col' ); ?>
			<?php do_action( 'fdp_table_head_first_col_' . esc_attr( sanitize_text_field( $_GET['page'] ) ) ); ?>
			<?php if ( $reset ) : ?>
			<div style="margin-top:8px;margin-bottom:16px">
				<span style="margin:0;font-size:20px" title="<?php esc_html_e( 'Restore last saved options', 'freesoul-deactivate-plugins' ); ?>" class="dashicons dashicons-image-rotate"></span><span class="eos-dp-legend-txt"><?php esc_html_e( 'Back to last saved settings', 'freesoul-deactivate-plugins' ); ?></span>
			</div>
			<?php endif; ?>
		</th>
		<?php
		$n   = 0;
		$fdp = array();
		foreach ( $active_plugins as $p ) {
			if ( isset( $plugins[ $p ] ) ) {
				$plugin            = $plugins[ $p ];
				$plugin_name       = strtoupper( eos_dp_get_plugin_name_by_slug( $p ) );
				$plugin_name_short = substr( $plugin_name, 0, 28 );
				$plugin_name_short = $plugin_name === $plugin_name_short ? $plugin_name : $plugin_name_short . ' ...';
				$details_url       = add_query_arg(
					array(
						'tab'         => 'plugin-information',
						'plugin'      => dirname( $p ),
						'TB_iframe'   => true,
						'eos_dp'      => $p,
						'eos_dp_info' => 'true',
					),
					admin_url( 'plugin-install.php' )
				);
				?>
				<th class="eos-dp-name-th"<?php echo isset( $_GET['int_plugin'] ) && dirname( $p ) === $_GET['int_plugin'] ? ' style="display:none"' : ''; ?>>
					<div>
						<div id="eos-dp-plugin-name-<?php echo esc_attr( $n + 1 ); ?>" class="eos-dp-plugin-name" title="<?php echo esc_attr( $plugin_name ); ?>" data-path="<?php echo esc_attr( $p ); ?>">
							<span><a title="<?php printf( esc_attr__( 'View details of %s', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" href="<?php echo esc_url( $details_url ); ?>" target="_blank"><?php echo esc_html( $plugin_name_short ); ?></a></span>
						</div>
						<div class="eos-dp-global-chk-col-wrp">
							<div class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate %s everywhere', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" data-col="<?php echo esc_attr( $n + 1 ); ?>" class="eos-dp-global-chk-col" type="checkbox" /></div>
							<?php if ( $reset ) : ?>
							<div class="eos-dp-reset-col" data-col="<?php echo esc_attr( $n + 1 ); ?>"><span title="<?php printf( esc_attr__( 'Restore last saved options for %s everywhere', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" class="dashicons dashicons-image-rotate"></span></div>
							<?php endif; ?>
							<?php do_action( 'eos_dp_table_head_col_after' ); ?>
						</div>
						<div class="fdp-p-n"><?php echo esc_attr( $n + 1 ); ?></div>
					</div>
				</th>
				<?php
				++$n;
			}
		}
		do_action( 'eos_dp_after_table_head_columns' );
		?>
	</tr>
	<?php
}
