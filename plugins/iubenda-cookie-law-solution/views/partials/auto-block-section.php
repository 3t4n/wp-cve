<?php
/**
 * Auto-block section - partial page.
 *
 * @package  Iubenda
 */

$auto_block_section_data                = isset( $predefined_auto_block_section_data ) ? (array) $predefined_auto_block_section_data : array();
$frontend_auto_blocking_checkbox_status = iub_array_get( $auto_block_section_data, 'frontend-auto-blocking-checkbox-status' );
?>

<label class="checkbox-regular">
	<input type="checkbox" class="mr-2 blocking-method frontend-blocking-method" id="frontend_auto_blocking" disabled <?php checked( true, $frontend_auto_blocking_checkbox_status ); ?>>
	<span>
		<div><?php esc_html_e( 'iubenda Automatic Script Blocking', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['frontend_auto_blocking'] ); ?>" class="ml-1 tooltip-icon">?</a></div>

		<div id="auto-blocking-warning-message" class="notice notice--warning mt-2 p-3 align-items-center text-warning text-xs <?php echo ! $frontend_auto_blocking_checkbox_status ? 'd-flex' : ''; ?>">
			<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
			<p>
				<?php
				/* translators: 1:frontend_auto_blocking URL. */
				echo wp_kses_post( sprintf( __( 'To enable Automatic Blocking, please <a class="link-underline" target="_blank" href="%s">visit the guide</a> to make configuration changes.', 'iubenda' ), esc_url( iubenda()->settings->links['frontend_auto_blocking'] ) ) );
				?>
			</p>
		</div>
		<div id="auto-blocking-info-message" class="notice notice--info mt-2 p-3 align-items-center text-info text-xs <?php echo $frontend_auto_blocking_checkbox_status ? 'd-flex' : ''; ?>">
			<p>
				<?php
				/* translators: 1:frontend_auto_blocking URL. */
				echo wp_kses_post( sprintf( __( '<a class="link-underline" target="_blank" href="%s">Learn more</a> about our automatic blocking.', 'iubenda' ), esc_url( iubenda()->settings->links['frontend_auto_blocking'] ) ) );
				?>
			</p>
		</div>
	</span>
</label>
