<?php
/**
 * Debug Modal view.
 *
 * @package GoogleAnalytics
 */

$sdb = filter_input( INPUT_GET, 'sdb', FILTER_SANITIZE_STRING );

$debug_info = isset( $debug_info ) ? $debug_info : array();
$show_modal = false === empty( $sdb ) ? 'style=display:block;' : '';
?>

<div id="ga_debug_modal" class="ga-modal" tabindex="-1" <?php echo esc_attr( $show_modal ); ?>>
	<div class="ga-modal-dialog">
		<div id="ga_debug_modal_content" class="ga-modal-content">
			<div class="ga-modal-header">
				<h4 class="ga-modal-title"><?php echo esc_html( 'Debug helper:' ); ?></h4>
				<span id="ga_close" class="ga-close">&times;</span>
			</div>
			<div class="ga-modal-body">
				<div class="ga-loader-wrapper">
					<div class="ga-loader"></div>
				</div>
				<div class="ga-debug-form-div">
					<?php if ( is_array( $debug_info ) ) : ?>
						<?php echo wp_kses_post( $debug_info['message'] ); ?>
					<?php else : ?>
						<h4 class="ga-modal-title"><?php echo esc_html( 'Copy and paste this debug info into an email and send to support@sharethis.com' ); ?></h4>
						<label for="ga_debug_info"
							class="ga-debug-form-label"><strong><?php echo esc_html( 'Debug info' ); ?></strong>:</label>
						<textarea id="ga_debug_info" class="ga-debug-form-field" rows="8"
								cols="50"><?php echo esc_html( $debug_info ); ?></textarea>
						<div class="ga-modal-footer">
							<button type="button" id="copy-debug" class="button">
							<?php
							esc_html_e(
								'Copy',
								'google-analytics'
							);
							?>
									</button>
							<button id="ga_btn_close" type="button" class="button">Close</button>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
